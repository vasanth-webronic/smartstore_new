<?php 
/**
 * Plugin Name: Custom Users Access Management
 * Plugin URI: https://webronic.com/
 * Description: Manage customized users access and roles.
 * Version: 1.0.1
 * Author: Things at Web
 * Author URI: https://webronic.com
 * Text Domain: custom-uam
 * Domain Path: /languages
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 * 
 */

/**
 * Custom user access management
 *
 * Copyright (c) 2020 WEBRONIC
 *
 *
 * webronic is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     webronic
 * @version    1.0.1
 * @copyright  (c) 2020 webronic
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    custom-user-access-management
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}



// Define plugin paths and URLs
define( 'CUSTOME_UAM_URL', plugin_dir_url( __FILE__ ) );
define( 'CUSTOME_UAM_DIR', plugin_dir_path( __FILE__ ) );
define( 'CUSTOME_UAM_USER_TABLE', 'custom_uam_user_requests');



function uam_loadMyscriptAdmin()
{  
    wp_enqueue_script('custom_uam-script-js', CUSTOME_UAM_URL . '/js/script.js', ['jquery', 'jquery-migrate'], null, true);   
}


add_action('admin_enqueue_scripts', 'uam_loadMyscriptAdmin');

// Create Settings Fields
include( plugin_dir_path( __FILE__ ) . 'includes/custom-uam-setting-fields.php');

// Create Plugin Admin Menus and Setting Pages
include( plugin_dir_path( __FILE__ ) . 'includes/custom-uam-setting-menus.php');



//apply filters for product price
function custom_uam_check_product_price($return, $price, $args, $unformatted_price ){
 
  if(!current_user_can( 'c_uam_cap_price' )){
    return;
  }

  global $post;
  if(!empty($post)){
    $meta  = get_post_meta( $post->ID, 'induxter_product_meta' );
    $meta=isset($meta[0])?$meta[0]:array();

    //this visible_product_price is customized in /inc/metabox/product-metabox
    if(isset($meta['product_price_visible'])&&empty($meta['product_price_visible'])){
      return;
    }

    if(!empty($meta['product_custom_price_set'])){       
      if(isset($meta['product_custom_price_user_group'])&&!empty($meta['product_custom_price_user_group'])){
          foreach ($meta['product_custom_price_user_group'] as $value) {
            if(get_current_user_id()==$value['product_custom_price_user']){
              return get_woocommerce_currency_symbol().$value['product_custom_price'];
            }
          }
      }    
      
    } 
  }

  return $return; 
}

//add_filter( "wc_price", 'custom_uam_check_product_price',10,4);

function installTable(){
  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();
  $table_name=CUSTOME_UAM_USER_TABLE;
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    name varchar(55) NOT NULL,
    business varchar(55) NOT NULL,
    email varchar(55) NOT NULL,
    phone varchar(20) NOT NULL,
    role varchar(20) NOT NULL,    
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}

register_activation_hook( __FILE__, 'installTable' );





//add to cart button filter

function custom_uam_purchasable($purchasable) {
  if(current_user_can( 'c_uam_cap_shoping_cart' )){
      return $purchasable;  
  }
  return false;
}

//remove_role('custom_uam_Normal User');

//add_filter('woocommerce_is_purchasable', 'custom_uam_purchasable',10,1);

//hide cart icon
// Woo Sidecart - hide when empty
/*
add_action( 'wp_footer', function() {
    
    if (! current_user_can( 'c_uam_cap_shoping_cart' ) ) {        
        echo '<style type="text/css">.header-buttons-area .header-mini-cart{ display: none; }</style>';    
    }
});
*/
//to hide all payments
add_filter( 'woocommerce_cart_needs_payment', '__return_false' );

function saveDataToTable(){
  global $wpdb;
  $data = array_merge( (array) $_GET, (array) $_POST );

  
  $name=$data['f-name'];
  $surname=$data['f-surname'];

  $business=$data['f-business'];
  $phone=$data['f-phone'];
  $role=isset($data['f-role'])?$data['f-role']:"normal";
  $email=$data['f-email'];

  $wpdb->insert( 
    CUSTOME_UAM_USER_TABLE, 
    array( 
      'time' => current_time( 'mysql' ), 
      'name' => $name." ".$surname, 
      'business' => $business, 
      'phone' => $phone, 
      'role' => $role, 
      'email' => $email, 
    ) 
  );
}

add_action( 'wpcf7_mail_sent', 'saveDataToTable', 1 );



function createAcceptedUser($id,$password){
   global $wpdb;

  $res=$wpdb->get_row("select * from ".CUSTOME_UAM_USER_TABLE." where id=$id");

  $name=explode(" ",$res->name);
  $first_name=$name[0];
  $last_name=count($name)>1?$name[1]:"";

  if($res->role=="B2B Customer"){
    $res->role="custom_uam_B2B Customer";
  }else if($res->role=="Reseller"){
    $res->role="custom_uam_Reseller";
  }else{
    $res->role="custom_uam_Normal User";
  }

  $data=array(
    'user_login' => $res->email,
    'user_pass' => $password,
    'user_email' => $res->email,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'display_name' => $res->name,
    'role' => $res->role
  );
 
  $user_id = wp_insert_user($data);

  return !empty($user_id);
}

function removeUserRequest($id){
   global $wpdb;
   $wpdb->delete(CUSTOME_UAM_USER_TABLE, ['id' => $id]);
}

function custom_uam_accept_user(){
  
   $data = array_merge( (array) $_GET, (array) $_POST );
   $id=isset($data['id'])?$data['id']:"";
   $pwd=isset($data['pwd'])?$data['pwd']:"";
   if(!empty($id)||empty($pwd)){
      if(createAcceptedUser($id,$pwd)){
         removeUserRequest($id);
         return array("success"=>1);
      }else{
        return array("error"=>1);
      }     
   }
   return array("error"=>1);
}
add_action( "wp_ajax_custom_uam_accept_user",'custom_uam_accept_user');

function custom_uam_delete_user(){
  
   $data = array_merge( (array) $_GET, (array) $_POST );
  
   $id=isset($data['id'])?$data['id']:"";
   if(!empty($id)){
      removeUserRequest($id);
      return array("success"=>1);
   }
   return array("error"=>1);
}
add_action( "wp_ajax_custom_uam_delete_user",'custom_uam_delete_user');
