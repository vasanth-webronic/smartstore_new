<?php

function custom_uam_menu_content()
{
  // Double check user capabilities
  if ( !current_user_can('manage_options') ) {
      return;
  }
  include( CUSTOME_UAM_DIR . 'templates/admin/settings-page.php');
}

function custom_uam_menu_user_access()
{
  // Double check user capabilities
  if ( !current_user_can('manage_options') ) {
      return;
  }
  include( CUSTOME_UAM_DIR . 'templates/admin/custom-uam-role.php');
}

function custom_uam_menu_user_request()
{
  // Double check user capabilities
  if ( !current_user_can('manage_options') ) {
      return;
  }

  include( CUSTOME_UAM_DIR . 'templates/admin/custom-uam-user-requests.php');
  

}


function custom_uam_settings_pages()
{
  
add_submenu_page(
    'users.php',      
  __( 'Custome user access managent', 'custom-uam' ),    
  __( 'Manage Custom Roles', 'custom-uam' ),
    'manage_options', 
    'custom-user-access-management',    
    'custom_uam_menu_user_access'
  );

  add_submenu_page(
    'users.php',      
  __( 'Users Requests', 'custom-uam' ),    
  __( 'Manage Requested Users', 'custom-uam' ),
    'manage_options', 
    'custom-user-request-management',    
    'custom_uam_menu_user_request'
  );

}

add_action( 'admin_menu', 'custom_uam_settings_pages' );

// Add a link to your settings page in your plugin
function wpplugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=custom-user-access-management">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$filter_name = "plugin_action_links_" . plugin_basename( __FILE__ );
add_filter( $filter_name, 'wpplugin_add_settings_link' );

function custom_uam_save_role(){
    $response = array(
      'error' => false,
    );
    global $wpdb;
    $role_name=esc_html(trim($_POST['role_name']));
    $role_id=esc_html(trim($_POST['role_id']));
    $roleissubrole = isset($_POST['roleissubrole']) ? $_POST['roleissubrole'] : 0;
   
    if (empty($role_name) &&  $roleissubrole=='0') {
      $response['error'] = true;
      $response['message'] = 'Role title required';
   
      exit(json_encode($response));
    }
    if(empty($role_id)){
      $key="custom_uam_".trim(str_replace(" ","_",strtolower($role_name)));
      $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
      $option_results = $wpdb->get_results($option_query, ARRAY_A);
      $roles_data = unserialize($option_results[0]['option_value']);
      $roles_data[$key] = array(
        'name' => $role_name,
        'roleissubrole' => $roleissubrole,
        'capabilities' => array(
            // Add capabilities if needed
        )
      );
      $updated_serialized_data = serialize($roles_data);
      $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
      // add_role($key, $role_name, []); // Adding $roleissubrole as a parameter
      $response = array(
        'success' => true,
      );
    }else{
      if (!empty(get_role($role_id))) {
        $role = get_role($role_id);
        $role_capabilities = $role->capabilities;
        
        $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
        $option_results = $wpdb->get_results($option_query, ARRAY_A);
        $roles_data = unserialize($option_results[0]['option_value']);
        $clickedRoleSubroles = isset($roles_data[$role_id]['subroles']) ? $roles_data[$role_id]['subroles'] : array();

        remove_role($role_id);

        $roles_data[$role_id] = array(
          'name' => $role_name,
          'roleissubrole' => $roleissubrole,
          'capabilities' => $role_capabilities, // Fix applied here
          'subroles' => $clickedRoleSubroles
        );
        $updated_serialized_data = serialize($roles_data);
        //print_r($updated_serialized_data);
        $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
        // // add_role($role_id, $role_name, $role_capabilities);
      }
    }
    
    exit(json_encode($response));
}
add_action( "wp_ajax_custom_uam_save_role",'custom_uam_save_role');

function custom_uam_save_subrole() {
  $response = array(
      'error' => false,
  );
  global $wpdb;
  $role_name = esc_html(trim($_POST['role_name']));
  $role_id = esc_html(trim($_POST['roleID']));
  $clickedRoleData = esc_html(trim($_POST['clickedRoleData']));
  $clickedRolenameData = esc_html(trim($_POST['clickedRolenameData']));
  $roleissubrole = isset($_POST['roleissubrole']) ? $_POST['roleissubrole'] : 1;

  if (empty($role_name) && $roleissubrole == '1') {
      $response['error'] = true;
      $response['message'] = 'SubRole title required';

      exit(json_encode($response));
  }

  if (!empty(get_role($clickedRoleData))) {
      $subkey = "custom_uam_" . trim(str_replace(" ", "_", strtolower($role_name)));
      $role = get_role($clickedRoleData);
      $role_capabilities = $role->capabilities;
      $role_capabilities['c_uam_cap_group_info'] = true;
      // Get the existing subroles for the clicked role
      $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
      $option_results = $wpdb->get_results($option_query, ARRAY_A);
      $roles_data = unserialize($option_results[0]['option_value']);
      $clickedRoleSubroles = isset($roles_data[$clickedRoleData]['subroles']) ? $roles_data[$clickedRoleData]['subroles'] : array();

      // Append the new subrole to the existing subroles array
      $clickedRoleSubroles[] = $subkey;

      // Update the roles data with the new subroles array
      $roles_data[$clickedRoleData] = array(
          'name' => $clickedRolenameData,
          'roleissubrole' => 0,
          'capabilities' =>  $role_capabilities,
          'subroles' => $clickedRoleSubroles
      );

      // Serialize and update the roles data in the database
      $updated_serialized_data = serialize($roles_data);
      $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
  }
      if(empty($role_id)){
        //save role
        $key="custom_uam_".trim(str_replace(" ","_",strtolower($role_name)));
        $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";  
        $option_results = $wpdb->get_results($option_query, ARRAY_A);
        $roles_data = unserialize($option_results[0]['option_value']);
        $roles_data[$key] = array(
          'name' => $role_name,
          'roleissubrole' => $roleissubrole,
          'capabilities' => array(
              // Add capabilities if needed
          )
        );
        $updated_serialized_data = serialize($roles_data);
        $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
        // add_role($key, $role_name, []); // Adding $roleissubrole as a parameter
        $response = array(
          'success' => true,
        );
      }else{
        if (!empty(get_role($role_id))) {
          $role = get_role($role_id);
          $role_capabilities = $role->capabilities;
          
          remove_role($role_id);
          $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
          $option_results = $wpdb->get_results($option_query, ARRAY_A);
          $roles_data = unserialize($option_results[0]['option_value']);
          $roles_data[$role_id] = array(
            'name' => $role_name,
            'roleissubrole' => $roleissubrole,
            'capabilities' => $role_capabilities
          );
          $updated_serialized_data = serialize($roles_data);
          $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
          // add_role($role_id, $role_name, $role_capabilities);
      }
      }
  
  exit(json_encode($response));
}
add_action( "wp_ajax_custom_uam_save_subrole",'custom_uam_save_subrole');

//Delete Subrole
function custom_uam_removesubrole()
{
  $clickedSubrole = isset($_POST['clickedSubrole']) ? $_POST['clickedSubrole'] : '';
  $clickedRole = isset($_POST['clickedRole']) ? $_POST['clickedRole'] : '';

  global $wpdb;

  $removesubrole = esc_html(trim($clickedSubrole));
  if (!empty($removesubrole)) {
    remove_role($removesubrole);
  } else {
    $response['error'] = true;
  }

  // Check if the role exists in the database
  $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
  $option_results = $wpdb->get_results($option_query, ARRAY_A);
  $data = unserialize($option_results[0]['option_value']);

  // Check if the data is set and it contains subroles
  if (isset($data) && is_array($data) && !empty($data)) {
    foreach ($data as $role => &$role_data) {
      if ($role_data['name'] == $clickedRole && isset($role_data['subroles']) && is_array($role_data['subroles'])) {
        // Find and remove the clicked subrole
        $index = array_search($clickedSubrole, $role_data['subroles']);
        //print_r($index);
        if ($index !== false) {
          unset($role_data['subroles'][$index]);
        }
      }
    }
  }
  // Serialize the modified data back to the format for the database
  $serializedData = serialize($data);

  // Update the database with the modified data
  $wpdb->update(
    'tsm_options',
    array('option_value' => $serializedData),
    array('option_name' => 'tsm_user_roles')
  );

  // Return a response if needed
  echo json_encode(array('success' => true)); // Or any other response you may need
  exit;
}
add_action("wp_ajax_custom_uam_removesubrole", 'custom_uam_removesubrole');

function custom_uam_update_subrole(){
  $clickedSubrole = isset($_POST['clickedSubrole']) ? $_POST['clickedSubrole'] : '';
  $clickedroleid = isset($_POST['clickedroleid']) ? $_POST['clickedroleid'] : '';
  $newsubrole_name = isset($_POST['newsubrole_name']) ? $_POST['newsubrole_name'] : '';
  $roleissubrole = '1';

  global $wpdb;

  if (!empty(get_role($clickedSubrole))) {
    $subkey = "custom_uam_" . trim(str_replace(" ", "_", strtolower($newsubrole_name)));
    $role = get_role($clickedSubrole);
   
    $role_capabilities = $role->capabilities;
    remove_role( $clickedSubrole );
    // Get the existing subroles for the clicked role
    $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
    $option_results = $wpdb->get_results($option_query, ARRAY_A);
    $roles_data = unserialize($option_results[0]['option_value']);
    // $clickedRoleSubroles = isset($roles_data[$clickedSubrole]['subroles']) ? $roles_data[$clickedSubrole]['subroles'] : array();

    // // Append the new subrole to the existing subroles array
    // $clickedRoleSubroles[] = $subkey;
   

    // Update the roles data with the new subroles array
    $roles_data[$subkey] = array(
        'name' => $newsubrole_name,
        'roleissubrole' => $roleissubrole,
        'capabilities' => $role_capabilities
    );
   
    // // Serialize and update the roles data in the database
    $updated_serialized_data = serialize($roles_data);
    //print_r( $updated_serialized_data );
    $wpdb->update('tsm_options', array('option_value' => $updated_serialized_data), array('option_name' => 'tsm_user_roles'));
}

    // if(!empty(get_role( $clickedSubrole ))){
    //    $role=get_role($clickedSubrole );
    //    $newroleid="custom_uam_".trim(str_replace(" ","_",strtolower($newsubrole_name)));
    //    remove_role( $clickedSubrole );
    //    add_role($newroleid,$newsubrole_name,$role->capabilities);
    // }

  // Check if the role exists in the database
  $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
  $option_results = $wpdb->get_results($option_query, ARRAY_A);
  $data = unserialize($option_results[0]['option_value']);
  
  // Check if the data is set and it contains subroles
  if (isset($data) && is_array($data) && !empty($data)) {
      foreach ($data as $role => &$role_data) {

          if ($role == $clickedroleid && isset($role_data['subroles']) && is_array($role_data['subroles'])) {
              // Find and remove the clicked subrole
              
              $index = array_search($clickedSubrole, $role_data['subroles']);

              if ($index !== false) {
                $role_data['subroles'][$index] = "custom_uam_".trim(str_replace(" ","_",strtolower($newsubrole_name)));
              }
          }
      }
  }

  // Serialize the modified data back to the format for the database
  $serializedData = serialize($data);


  // Update the database with the modified data
  $wpdb->update(
      'tsm_options',
      array('option_value' => $serializedData),
      array('option_name' => 'tsm_user_roles')
  );

  // Return a response if needed
   echo json_encode(array('success' => true)); // Or any other response you may need
  exit;
}

add_action("wp_ajax_custom_uam_update_subrole", 'custom_uam_update_subrole');


function custom_uam_remove_role(){
    $response = array(
      'error' => false,
    );
   
    $role_id=esc_html(trim($_POST['id']));  
    if(!empty($role_id)){
      remove_role( $role_id );
    }else{
      $response['error'] = true;
    }    
  
    exit(json_encode($response)); 
}

add_action( "wp_ajax_custom_uam_remove_role",'custom_uam_remove_role');


function custom_uam_get_role_cap(){
   
    $response = array(
      'error' => false,
    );

    $role_id=esc_html(trim($_POST['id']));    
   
    if (empty($role_id)) {
      $response['error'] = true;
      $response['message'] = 'Something went wrong, please try again later';
   
      exit(json_encode($response));
    }

    $role=get_role( $role_id );
   // $response['data']="raa";
    $response['datas']=$role;
    exit(json_encode($response));
 
}

add_action( "wp_ajax_custom_uam_get_role_cap",'custom_uam_get_role_cap');

function custom_uam_get_subrole_cap(){
   
  $response = array(
    'error' => false,
  );

  $role_id=esc_html(trim($_POST['id']));
 
  if (empty($role_id)) {
    $response['error'] = true;
    $response['message'] = 'Something went wrong, please try again later';
 
    exit(json_encode($response));
  }

  $role=get_role( $role_id );
 // $response['data']="raa";
  $response['datas']=$role;
  exit(json_encode($response));

}

add_action( "wp_ajax_custom_uam_get_subrole_cap",'custom_uam_get_subrole_cap');


function custom_uam_save_cap(){
   
    $response = array(
      'error' => false,
    );

    $data=$_POST['data'];    
    $role_id=esc_html(trim($_POST['id']));     
   
    if (empty($role_id)||empty($data)) {
      $response['error'] = true;
      $response['message'] = 'Something went wrong, please try again later';
   
      exit(json_encode($response));
    }

    $role=get_role( $role_id );

    foreach ($role->capabilities as $key => $value) {
      $role->remove_cap($key);
    }
    
    foreach ($data as $value) {
      $role->add_cap(esc_html( $value ),true);
    }

   
    exit(json_encode($response));
 
}

//remove_role('custom_uam_Normal User');

add_action( "wp_ajax_custom_uam_save_cap",'custom_uam_save_cap');



