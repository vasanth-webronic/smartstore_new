<?php

/**
 * Plugin Name: Woo Product Image Pointing Info
 * Plugin URI: https://webronic.com/
 * Description: Add pointing information to selected image that will be show on product page.
 * Version: 1.0.1
 * Author: Things at Web
 * Author URI: https://webronic.com
 * Text Domain: woo-proudct-img-info
 * Domain Path: /languages
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 * 
 */

/**
 * Woo Product Image Pointing Info
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
 * @package    woo-proudct-img-info
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


/**
 * Create the section beneath the products tab
 **/
if (!class_exists('myCustomFields')) {

    class myCustomFields
    {
        /**
         * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
         */
        var $prefix = '_wpii_';
        /**
         * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
         */
        var $postTypes = array("product");
        /**
         * @var  array  $customFields  Defines the custom fields available
         */

        /**
         * PHP 4 Compatible Constructor
         */
        function myCustomFields()
        {
            $this->__construct();
        }
        /**
         * PHP 5 Constructor
         */
        function __construct()
        {
            add_action('admin_menu', array($this, 'createCustomFields'));
            add_action('save_post', array($this, 'saveCustomFields'), 1, 2);

            add_action('admin_enqueue_scripts', array($this, 'wpii_load_wp_media_files'));
            add_action('woocommerce_product_after_tabs', array($this, 'loadProductSpareAccessories'));
        }

        function loadProductSpareAccessories()
        {
            $lang = getSiteCurrentLang();
            global $product;
            $your_img_src = "";
            //if(current_user_can('c_uam_cap_3d_picture')){
            $field = '_wpii_prod_img_pointer_info';
            $item = get_post_meta($product->get_id(), $field);

            if (!empty($item)) {
                $field = '_wpii_prod_img_pointer_info';
                $item = get_post_meta($product->get_id(), $field);
                if (!empty($item)) {
                    $item = json_decode($item[0], true);
                }

                $list = [];
                if (!empty($item['data'])) {
                    $list = $item['data'];
                }

                $your_img_src_id = "";
                if (!empty($item['imgId'])) {
                    $your_img_src_id = $item['imgId'];
                    $your_img_src = wp_get_attachment_image_src($your_img_src_id, 'full');
                    if (!empty($your_img_src)) {
                        $your_img_src = $your_img_src[0];
                    }
                }
            }
            

            if (!empty($your_img_src)) :
                if(current_user_can('c_uam_cap_spare_parts')):
            ?>

                <div class="product-img-info-hld hidden xl:block" style="margin-top:10px;">

                    <div class="flex bg-gray-100 p-3 items-center mt-3 lg:mt-0" id="tab-title-spareparts">
                    <div class="w-2.5 h-auto">
                        <img src="/wp-content/plugins/thingsatweb/img/ic_forward_arrow.svg" alt="Arrow">
                    </div>
                    <?php if($lang === 'en'){ ?>
                    <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Spare Parts","woo-proudct-img-info");?></h3>
                    <?php }elseif($lang === 'sv'){ ?>
                    <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Reservdelar","woo-proudct-img-info");?></h3>
                    <?php }?>
                    <div class="flex ml-auto items-center">
                    <div class="taw-tab-option w-3 h-auto mx-5" id="spareparts" onclick="togglespareparts()">
                        <!-- <div class="taw-tab-option w-3 h-auto mx-5 lg:hidden" id="spareparts" onclick="clickEvent(this)"> -->
                            <img src="/wp-content/plugins/thingsatweb/img/ic_minus.svg" alt="Arrow">
                        </div>
                    </div>
                </div>            

                    <div id="spare_img" style="border: solid transparent;margin:0 auto 0 auto;width: 600px;height: 400px;background-image: url('<?php  echo $your_img_src;
                                                                                                                                        ?>');background-repeat: no-repeat; background-position: center;background-size: 90% auto;position: relative;">
                    <?php foreach ($list as $key => $value):
                    ?>

                    <?php /*<img class="drag-point after_dragged_point" style="cursor:pointer; height:40px;width:40px;position:absolute;<?php echo 'top:'.$value['top'].'px;left:'.$value['left'].'px'; ?>" src="http://smartstoring.test/wp-content/uploads/2020/12/info_spot.gif" alt="<?php echo $value['txt'];?>" onclick="showProductImgInfoMsg(this)"> */ ?>

                    <div class="badgee" style="cursor:pointer; height:24px;width:24px;position:absolute;<?php  echo 'top:'.($value['top']).'px;left:'.($value['left']).'px'; 
                                                                                                                ?>"  data-key="< // ?=$key+1;?>" alt="<?php  echo $value['txt'];
                                                                                                                                                        ?>" ><?=$key+1;?> 
                        <span style="display: none;width: 300px;
                            color: white;
                            padding: 10px;
                            margin-left: -59px;
                            margin-top: 17px;
                            position: absolute;
                            text-align: center;
                            bottom: 45px;
                            background: rgb(204, 6, 30);
                            border: 2px solid;
	                        box-shadow: rgb(90, 90, 90) 1px 1px 10px;">
                        <?php echo $value['txt'];
                        ?> 
                        <span style="width: 0;
	                        height: 0;
	                        border-left: 25px solid transparent;
	                        border-right: 0 solid transparent;
	                        border-top: 20px solid #cc061e;
	                        position: absolute;
	                        bottom: -20px;
	                        left: 30px;">
                           
                        </span>
                        </span>

                    
                    </div>
                    <?php endforeach;
                    ?>
                    <div style="position: absolute;
	                    width: auto;
	                    display: none;
	                    height: auto;
	                    color: rgb(255, 255, 255);
	                    top: 255px;
	                    left: 23.1094px;
	                    background: rgb(204, 6, 30);
	                    box-shadow: rgb(90, 90, 90) 1px 1px 10px;
	                    padding: 10px;
	                    vertical-align: middle;
	                    border: 2px solid;
	                    text-align: center;" id="product-img-info-hld-msg">
    	                <div id="product-img-info-hld-msg-cont" style="
    	                    display: table-cell;
	                        height: auto;
	                        vertical-align: middle;
	                        text-align: center;
	                        width: 300px;">
                        </div>
                        <div style="width: 0;
	                        height: 0;
	                        border-left: 25px solid transparent;
	                        border-right: 0 solid transparent;
	                        border-top: 20px solid #cc061e;
	                        position: absolute;
	                        bottom: -20px;
	                        left: 30px;">
                        </div>
                    </div>
	            </div>	
                    <div class="td-section-title-two-line-wrapper" style="margin: 0px auto 20px;width: 400px">
					<div class="td-section-title-two-line" style="width: 150px;border-radius: 50% 0 0 50%;"></div>
					<div class="td-section-title-two-line td-middle-line"></div>
					<div class="td-section-title-two-line" style="width: 150px;border-radius: 0 50% 50% 0;"></div>
				</div>


                    <div id="spare_list" class="t_list_table">
                    <div class="t_heading">      
                        <div style="width:100px;border-right: solid 3px #fff;">S.No</div>
                        <div style="width:200px;border-right: solid 3px #fff;">Product</div>
                        <div style="width:200px;">Article Number</div> 
                        <div  style="width:calc(100% - 700px);border-right: solid 3px #fff;">Product Name</div>                      
                    </div>

                    <?php  foreach ($list as $key => $value):

                    $id = wc_get_product_id_by_sku($value['txt']);        
                    if(empty($id)){
                    continue;
                    } 
                    $p=wc_get_product( $id );
                    ?>
                    <div class="t_row" data-key="<?=$key+1;?>">
                        <div style="width:100px;"> <?php  echo $key+1;?> </div>
                        <div class="t_img" style="width:200px;"> <?php echo $p->get_image(apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' ), [], true );?> </div>
                        <div class="t_art_num" style="width:200px;"> <?php echo $value['txt'];?> </div>
                        <div class="t_title" style="width:calc(100% - 700px);"> <?php echo $p->get_title();?> </div>
                        <div style="color:#cc071c;width:160px;text-align:right">
                            <?php
                            $valueqty = isset($value['qty']) ? $value['qty'] : 0; // Check if 'qty' key exists
                            ?>
                            <span class="add_to_cart" data-qty="<?php echo  $valueqty ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo $id; ?>">
                                +
                            </span>
                        </div>
                    </div>
                    <?php  endforeach;
                    ?>
                    </div> 
                </div>
            <?php endif; endif; ?>
            <link rel='stylesheet' id='spare-css' href='/wp-content/plugins/woo-product-img-info/css/style.css' media='all' />
            <script src='/wp-content/plugins/woo-product-img-info/spare.js' id='spare-core-js'></script>
            <?php $this->loadProductAcs();
        }

        function loadProductAcs()
        {   $lang = getSiteCurrentLang();
            global $product;
            global $wpdb;
            //find accessories for product specific
            $parent_artno = $product->get_sku();
            $q = "SELECT acs_article, no_plates from taw_product_accessories WHERE parent_article='$parent_artno'";

            $results = $wpdb->get_results($q);
            $all_ids = [];
            $all_pnoplate = [];
            if (!empty($results)) {
                foreach ($results as $r) {
                    $pid = wc_get_product_id_by_sku($r->acs_article);
                    $pnoplate = $r->no_plates;
                    if (!empty($pid)) {
                        $all_ids[] = $pid;
                        $all_pnoplate[] = $pnoplate;
                    }
                }
            } else {
                $terms = get_the_terms($product->get_ID(), 'product_cat');
                foreach ($terms as $term) {
                    $product_cat_slug = $term->slug;
                    break;
                }

                // $acs_cat = "accessories-to-" . $product_cat_slug;

                $all_ids = get_posts(array(
                    'post_type' => 'product',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            // 'terms' => $acs_cat,
                            'operator' => 'IN',
                        )
                    ),
                ));
            }

            if (!empty($all_ids)) : 
            if(current_user_can('c_uam_cap_accessories')):
            ?>
                <div class="flex bg-gray-100 p-3 items-center " id="tab-title-accessories" style="margin-top:30px">
                    <div class="w-2.5 h-auto">
                        <img src="/wp-content/plugins/thingsatweb//img/ic_forward_arrow.svg" alt="Arrow">
                    </div>
                    <?php if ($lang === 'en') { ?>
                    <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Accessories", "woo-proudct-img-info"); ?></h3>
                    <?php }elseif ($lang === 'sv') { ?>
                    <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Tillbehör", "woo-proudct-img-info"); ?></h3>
                    <?php } ?>
                    <div class="flex ml-auto items-center">
                        <div class="taw-tab-option w-3 h-auto mx-5" id="accessories" onclick="toggleAccessories()">
                            <img src="/wp-content/plugins/thingsatweb/img/ic_minus.svg" alt="Arrow">
                        </div>
                    </div>
                </div>

                <div id="spare_list_table" class="t_list_table hidden md:block lg:block" style="margin-top:15px;">
                    <div class="t_heading">
                        <div style="width:200px;border-right: solid 3px #fff;">Product</div>
                        <div style="width:200px;border-right: solid 3px #fff;">Article Number</div>
                        <div style="width:calc(100% - 600px);">Specification</div>
                    </div>
                    <?php
                    foreach ($all_ids as $key => $id) :
                        $p = wc_get_product($id);
                        // $query="SELECT * FROM `taw_product_accessories` where ";
                        // $sub_items = $wpdb->get_results($query);
                        // print_r( $sub_items);
                        $product_id = $id; // Set the product ID
                        $product_qty = 1;
                    ?>
                        <div class="t_row" data-key="<?= $key + 1; ?>">
                            <div class="t_img" style="width:200px;">
                                <?php echo $p->get_image(apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail'), [], true); ?>
                            </div>
                            <div class="t_art_num" style="width:200px;">
                                <?php echo $p->get_sku(); ?>
                            </div>
                            <div class="t_title" style="width:calc(100% - 600px);height:100px;overflow:hidden;">
                                <?php echo $p->get_title(); ?>
                            </div>
                            <div style="color:#cc071c;width:160px;text-align:right">
                                <?php
                                $palletqty = isset($all_pnoplate[$key]) ? $all_pnoplate[$key] : 0;
                                ?>
                                <span class="add_to_cart" data-qty="<?php echo $palletqty; ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo $id; ?>">
                                +</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <head>
                    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.css" />
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.min.js"></script>
                </head>
                <div class="slick-carousel" id="slick-carouseltable">
                    <?php
                    foreach ($all_ids as $key => $id) :
                        $p = wc_get_product($id);
                    ?>
                        <div class="w-full md:hidden lg:hidden">
                            <div class="flex">
                                <div class=" mx-auto border mt-2 rounded-md" style="width:75%;">

                                    <div class="!w-auto flex items-center justify-center" style="height:120px; margin-top:10px;">
                                        <?php
                                        $image = get_the_post_thumbnail_url($p->get_id());
                                        $default = wc_placeholder_img_src(200);
                                        if (empty($image)) {
                                            $image = $default;
                                        }
                                        ?>
                                        <img class=" mx-auto" style="width: 120px; height: 100px;" src=<?php echo $image; ?> onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
                                    </div>
                                    <h3 class="w-full h-30 text-red-600 font-bold text-16 text-center mt-5" style="font-size:20px;"><?php echo $p->get_sku(); ?></h3>
                                    <h3 class="w-full h-30 text-sm font-semibold text-center mt-3"><?php echo $p->get_title() ?></h3>
                                    <div class="mt-2" style="margin-top:10px;padding:10px;color:#cc071c;text-align:center">
                                        <?php
                                        $palletqty = isset($all_pnoplate[$key]) ? $all_pnoplate[$key] : 0;
                                        ?>
                                        <span class="add_to_cart" style="background:#cc061c; height:30px; width:30px; display:inline-block; line-height:32px; cursor:pointer; color:#fff; font-size:30px; border-radius:50%" data-qty="<?php echo $palletqty; ?>" data-price="<?php echo $p->get_price(); ?>">+</span>
                                    </div>

                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; endif; ?>
                <div id="t_modal">
                    <div class="t_cont">
                        <div class="t_head">
                            <span class="t_close_btn">X</span>
                        </div>
                        <div class="t_body">
                            <div class="t_item">
                                <div class="t_item_img">
                                    <img src="" />
                                </div>
                                <div class="t_item_info">
                                    <div class="name"></div>
                                    <div class="artno"></div>
                                    <div class="price"><span></span> kr</div>
                                </div>
                                <div class="t_item_action">
                                    <div class="t_time_action_item">
                                        <span class="t_time_action_item_minus">-</span>
                                        <span class="t_time_action_item_val">1</span>
                                        <span class="t_time_action_item_plus">+</span>
                                    </div>
                                    <p class="min_req">Minimum Quantity Needed : <span></span></p>
                                </div>
                            </div>

                            <div style="float:left;width:100%;">
                                <div class="t_total_info">
                                    <span>Total</span>
                                    <span>-</span>
                                    <span class="t_total"><span></span> kr</span>
                                </div>
                                <div>
                                    <button id="productcartsubmit" class="t_spare_add_to_cart add-to-cart-button">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="path/to/jquery.min.js"></script>
                <script>
                     function toggleAccessories() {
                        var toggleElement = document.getElementById("accessories");
                        var accessoriesTable = document.getElementById("spare_list_table");
                        var accessoriesslickTable = document.getElementById("slick-carouseltable");

                        // Toggle the active class on the clicked element
                        toggleElement.classList.toggle("active");

                        var screenWidth = window.innerWidth;
                        if (screenWidth >= 768) { // Adjust the breakpoint as needed
                            if (accessoriesTable.style.display === "none") {
                                accessoriesTable.style.display = "block";
                            } else {
                                accessoriesTable.style.display = "none";
                            }
                        }
                        if (screenWidth <= 768) {
                            if (accessoriesslickTable.style.display === "none") {
                                accessoriesslickTable.style.display = "block";
                            } else {
                                accessoriesslickTable.style.display = "none";
                            }
                        }
                    }
                    function togglespareparts() {
                        var toggleElement = document.getElementById("spareparts");
                        var spareTable = document.getElementById("spare_list");
                        var spareimg = document.getElementById("spare_img");
                        // var accessoriesslickTable = document.getElementById("slick-carouseltable");

                        // Toggle the active class on the clicked element
                        toggleElement.classList.toggle("active");

                        var screenWidth = window.innerWidth;
                        if (screenWidth >= 768) { // Adjust the breakpoint as needed
                            if (spareTable.style.display === "none") {
                                spareTable.style.display = "block";
                            } else {
                                spareTable.style.display = "none";
                            }
                            if (spareimg.style.display === "none") {
                                spareimg.style.display = "block";
                            } else {
                                spareimg.style.display = "none";
                            }
                        }
                        // if (screenWidth <= 768) {
                        //     if (accessoriesslickTable.style.display === "none") {
                        //         accessoriesslickTable.style.display = "block";
                        //     } else {
                        //         accessoriesslickTable.style.display = "none";
                        //     }
                        // }
                    }
                    jQuery(function($) {
                        var selectedProductID; // Declare the global variable
                        var count = 1; // Initialize count to 1

                        $("#spare_list_table .add_to_cart").click(function(e) {
                            selectedProductID = $(this).attr('data-product-id'); // Update the global variable
                            //console.log(selectedProductID);
                            var title = $(this).closest(".t_row").find(".t_title").text();
                            var img = $(this).closest(".t_row").find(".t_img img").attr("src");
                            var art_no = $(this).closest(".t_row").find(".t_art_num").text();
                            var price = $(this).attr("data-price");
                            var qty = $(this).attr("data-qty");

                            $("#t_modal .t_item_img img").attr("src", img);
                            $("#t_modal .t_item_info .name").text(title);
                            $("#t_modal .t_item_info .artno").text(art_no);
                            $("#t_modal .t_item_info .price span").text(price);
                            $("#t_modal .t_item_action .min_req span").text(qty);

                            updaprice=price*count;
                            $("#t_modal .t_total_info .t_total span").text(updaprice);
                            $("#t_modal").css("display", "block");
                            $("body").attr("scroll", "no");
                            $("body").css("overflow", "hidden");
                        });
                        $("#spare_list .add_to_cart").click(function(e) {
                            selectedProductID = $(this).attr('data-product-id'); // Update the global variable
                            //console.log(selectedProductID);
                            var title = $(this).closest(".t_row").find(".t_title").text();
                            var img = $(this).closest(".t_row").find(".t_img img").attr("src");
                            var art_no = $(this).closest(".t_row").find(".t_art_num").text();
                            var price = $(this).attr("data-price");
                            var qty = $(this).attr("data-qty");

                            $("#t_modal .t_item_img img").attr("src", img);
                            $("#t_modal .t_item_info .name").text(title);
                            $("#t_modal .t_item_info .artno").text(art_no);
                            $("#t_modal .t_item_info .price span").text(price);
                            $("#t_modal .t_item_action .min_req span").text(qty);

                            updaprice=price*count;
                            $("#t_modal .t_total_info .t_total span").text(updaprice);
                            $("#t_modal").css("display", "block");
                            $("body").attr("scroll", "no");
                            $("body").css("overflow", "hidden");
                        });

                        $("#t_modal .t_time_action_item .t_time_action_item_plus").click(function(e) {
                            count++; // Increment the count
                            var cur = $("#t_modal .t_time_action_item .t_time_action_item_val");
                            cur.text(count);
                            var price=parseInt($("#t_modal .t_item_info .price span").text());                    
                            //console.log(price);
                            updaprice=price*count;
                            $("#t_modal .t_total_info .t_total span").text(updaprice);
                        });

                        $("#t_modal .t_time_action_item .t_time_action_item_minus").click(function(e) {
                            if (count > 1) {
                                count--; // Decrement the count only if it's greater than 1
                            }
                            var cur = $("#t_modal .t_time_action_item .t_time_action_item_val");
                            cur.text(count);
                            var price=parseInt($("#t_modal .t_item_info .price span").text());                    
                            //console.log(price);
                            updaprice=price*count;
                            $("#t_modal .t_total_info .t_total span").text(updaprice);
                        });

                        $('#productcartsubmit').on('click', function() {
                            var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                            let product_id = selectedProductID;
                            let product_qty = count;
                            let minimum_qty = parseInt($("#t_modal .t_item_action .min_req span").text()); 
                            // console.log(product_id);
                            // console.log(product_qty);
                            // console.log(minimum_qty);
                            if (!isNaN(minimum_qty) && minimum_qty >= 0) {
                                if (product_qty >= minimum_qty) {
                                    let data = {
                                        'action': 'ajaxcart',
                                        'product_id': product_id,
                                        'product_qty': product_qty,
                                    }

                                    jQuery.post(aurl, data, function(response) {
                                        console.log(response); // Check the response object in the console
                                        if (response.status === '1') {
                                            location.reload(true);
                                            // alert('Product added to cart successfully.');
                                        } else {
                                            alert('Could not add product to cart.');
                                        }
                                    });
                                } else {
                                    alert('Minimum quantity not met. Please add at least ' + minimum_qty + ' items to your cart.');
                                } 
                            } 
                            else {
                                let data = {
                                        'action': 'ajaxcart',
                                        'product_id': product_id,
                                        'product_qty': product_qty,
                                    }

                                    jQuery.post(aurl, data, function(response) {
                                        console.log(response); // Check the response object in the console
                                        if (response.status === '1') {
                                            location.reload(true);
                                            // alert('Product added to cart successfully.');
                                        } else {
                                            alert('Could not add product to cart.');
                                        }
                                    });
                            }
                        });
                    });
                    jQuery(document).ready(function($) {
                        $('.slick-carousel').slick({
                            dots: false, // If you want to show dots navigation
                            arrows: true, // If you want to show arrows navigation
                            slidesToShow: 1, // Number of slides to show at a time
                            slidesToScroll: 1, // Number of slides to scroll at a time
                        });

                    });
                </script>
       <?php }

        function wpii_load_wp_media_files($page)
        {

            // change to the $page where you want to enqueue the script

            //echo "raja ".$page;
            if ($page == 'post.php' || $page == 'post-new.php') {
                wp_enqueue_media();
                // Enqueue custom script that will interact with wp.media
                wp_enqueue_script('wpii_script', plugins_url('script.js', __FILE__), array('jquery', 'jquery-ui-draggable', 'jquery-ui-droppable'), time());
            }
        }

        /**
         * Remove the default Custom Fields meta box
         */
        function removeDefaultCustomFields($type, $context, $post)
        {
            foreach (array('normal', 'advanced', 'side') as $context) {
                foreach ($this->postTypes as $postType) {
                    remove_meta_box('postcustom', $postType, $context);
                }
            }
        }
        /**
         * Create the new Custom Fields meta box
         */
        function createCustomFields()
        {

            if (function_exists('add_meta_box')) {
                foreach ($this->postTypes as $postType) {
                    add_meta_box('my-custom-fields', 'Spare Parts Settings', array($this, 'showImagePointerInfoUI'), $postType, 'normal', 'high');
                }
            }
        }

        /**
         * Save the new Custom Fields values
         */
        function saveCustomFields($post_id, $post)
        {

            if (!isset($_POST['my-custom-fields_wpnonce']) || !wp_verify_nonce($_POST['my-custom-fields_wpnonce'], 'my-custom-fields'))
                return;
            if (!current_user_can('edit_post', $post_id))
                return;
            if (!in_array($post->post_type, $this->postTypes))
                return;


            if (isset($_POST['_wpii_prod_img_pointer_info']) && trim($_POST['_wpii_prod_img_pointer_info'])) {
                $value = $_POST['_wpii_prod_img_pointer_info'];
                // Auto-paragraphs for any WYSIWYG               
                update_post_meta($post_id, '_wpii_prod_img_pointer_info', $value);
            }
        }


        function showImagePointerInfoUI()
        {
            include(plugin_dir_path(__FILE__) . 'template/img-pointer-info.php');
        }

        function wpii_load_wp_custom_product_info($html, $post_id)
        {
            return $html;
        }
    } // End Class

} // End if class exists statement

// Instantiate the class
if (class_exists('myCustomFields')) {
    $myCustomFields_var = new myCustomFields();
}