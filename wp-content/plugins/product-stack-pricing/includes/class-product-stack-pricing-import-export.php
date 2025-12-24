<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include_once(PRODUCT_STACK_PRICING_PATH . '/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Product_Stack_Pricing_Import_Export
{
    public static function export_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';

        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        if (empty($results)) {
            echo '<h1 style="color:red; margin-top:20px;">No data available for export.</h1>';
            return;
        }

        // Get all WordPress roles
        global $wp_roles;
        $roles = $wp_roles->roles;

        // Create the spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = array('art_no', 'qty', 'rule_price', 'role_name', 'users', 'enable_all_users', 'status');

        //$headers = array('id', 'art_no', 'qty', 'rule_price', 'role_name', 'users', 'enable_all_users', 'status');
        $sheet->fromArray($headers, NULL, 'A1');

        // Fill data and set formulas
        $row = 2;
        foreach ($results as $data) {
            // Check conditions for exporting row
            if ($data['rule_price'] != 0 && $data['qty'] != 0) {
                // Fetch user meta data
                $users = explode(',', $data['users']);
                $customer_numbers = [];
                foreach ($users as $user_id) {
                    $customer_no = get_user_meta($user_id, 'customer_no', true);

                    if (empty($customer_no)) {
                        $customer_no = get_user_meta($user_id, 'subcustomer_no', true);
                    }
                    if (empty($customer_no)) {
                        $customer_no = $user_id;
                    }
                    $customer_numbers[] = $customer_no;
                }
                $enable_all_users = $data['enable_all_users'] == 1 ? 'yes' : 'no';
                $status = $data['status'] == 1 ? 'active' : 'inactive';

                // Convert role names
                $role_name = $data['role'];
                switch ($role_name) {
                    case 'custom_uam_b2b':
                        $role_name = 'b2b';
                        break;
                    case 'custom_uam_reseller_sek':
                        $role_name = 'reseller sek';
                        break;
                    case 'custom_uam_reseller_eur':
                        $role_name = 'reseller eur';
                        break;
                    // Add more cases as needed
                }

                $data_to_export = array(
                    //$data['id'],
                    $data['art_no'],
                    $data['qty'],
                    $data['rule_price'],
                    $role_name,
                    implode(',', $customer_numbers), // Concatenate customer numbers
                    $enable_all_users,
                    $status,
                );

                $sheet->fromArray($data_to_export, NULL, 'A' . $row);

                $row++;
            }
        }

        $filename = 'product_stack_pricing_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Download the file
        self::downloadXlFile($filename, $spreadsheet);
    }

    public static function downloadXlFile($filename, $spreadsheet)
    {
        try {
            ob_clean();
            flush();
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Cache-Control: max-age=0");

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public static function import_data($file_path)
    {
        global $wpdb;
        $spreadsheet = IOFactory::load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        $filtered_rows = array_filter($rows, function($row) {
            // Remove rows that are entirely empty or contain only NULL values
            return array_filter($row, fn($cell) => !is_null($cell) && trim($cell) !== '') !== [];
        });
        
        $table_name = $wpdb->prefix . 'product_stack_pricing';

        if ($filtered_rows) {
            // Truncate the table before inserting new data
            $wpdb->query("TRUNCATE TABLE $table_name");
        }
    

        // Remove header row
        array_shift($filtered_rows);
        
        

        $log = [];
        foreach ($filtered_rows as $row) {
           
            $artNo = sanitize_text_field($row[0]);
            $qty = intval($row[1]);
            $price = floatval($row[2]);
            $role_name = sanitize_text_field($row[3]);

            // Convert role names back to their original values
            switch ($role_name) {
                case 'b2b':
                    $role_name = 'custom_uam_b2b';
                    break;
                case 'reseller sek':
                    $role_name = 'custom_uam_reseller_sek';
                    break;
                case 'reseller eur':
                    $role_name = 'custom_uam_reseller_eur';
                    break;
                // Add more cases as needed
            }

            $customer_numbers = explode(',', $row[4]);
            $users = [];
            foreach ($customer_numbers as $customer_no) {
                // Check if customer_no exists as user meta key 'customer_no'
                $user = get_users(array(
                    'meta_key' => 'customer_no',
                    'meta_value' => $customer_no,
                    'number' => 1,
                    'count_total' => false,
                    'fields' => 'ID',
                ));

                if (empty($user)) {
                    // If not found, check as 'subcustomer_no'
                    $user = get_users(array(
                        'meta_key' => 'subcustomer_no',
                        'meta_value' => $customer_no,
                        'number' => 1,
                        'count_total' => false,
                        'fields' => 'ID',
                    ));
                }

                if (empty($user)) {
                    // If still not found, assume $customer_no is actually the user ID
                    $user_id = intval($customer_no);
                } else {
                    $user_id = $user[0]; // Use the first user ID found
                }

                $users[] = $user_id;
            }

            $enable_all_users = strtolower($row[5]) === 'yes' ? 1 : 0;
            $status = strtolower($row[6]) === 'active' ? 1 : 0;
    

            // Prepare data for insertion
            $data = array(
               
                'artNo' => $artNo,
                'qty' => $qty,
                'price' => $price,
                'role' => $role_name,
                'users' => $users,
                'selectAllCheckboxval' => $enable_all_users,
                'status' => $status,
            );

            // Use the psp_save_stacking_rule function to insert the data
          
            $result = self::psp_save_stacking_rule($data);


            switch ($role_name) {
                case 'custom_uam_b2b':
                    $role_name = 'b2b';
                    break;
                case 'custom_uam_reseller_sek':
                    $role_name = 'reseller sek';
                    break;
                case 'custom_uam_reseller_eur':
                    $role_name = 'reseller eur';
                    break;
                // Add more cases as needed
            }

            if (is_wp_error($result)) {
                $log[] = "<span style='color:red;'>Error importing row with artNo {$artNo} for qty: {$qty}, price: {$price}, role: {$role_name} - " . $result->get_error_message() . "</span>";
            } else {
                $log[] = "<span style='color:green;'>{$result} {$artNo} for qty: {$qty}, price: {$price}, role: {$role_name}</span>";
            }
        }

        return $log;
    }

    public static function import_single_row($row){
        global $wpdb;
    
        $log = [];
     
        $had_errors  = false;
            $artNo = sanitize_text_field($row[0]);
            $qty = intval($row[1]);
            $price = floatval($row[2]);
            $role_name = strtolower( sanitize_text_field( $row[3] ) );

            // Convert role names back to their original values
            switch ($role_name) {
                case 'b2b':
                    $role_name = 'custom_uam_b2b';
                    break;
                case 'reseller sek':
                    $role_name = 'custom_uam_reseller_sek';
                    break;
                case 'reseller eur':
                    $role_name = 'custom_uam_reseller_eur';
                    break;
                // Add more cases as needed
            }

            $customer_numbers = explode(',', $row[4]);
            $users = [];
            foreach ($customer_numbers as $customer_no) {
                // Check if customer_no exists as user meta key 'customer_no'
                $user = get_users(array(
                    'meta_key' => 'customer_no',
                    'meta_value' => $customer_no,
                    'number' => 1,
                    'count_total' => false,
                    'fields' => 'ID',
                ));

                if (empty($user)) {
                    // If not found, check as 'subcustomer_no'
                    $user = get_users(array(
                        'meta_key' => 'subcustomer_no',
                        'meta_value' => $customer_no,
                        'number' => 1,
                        'count_total' => false,
                        'fields' => 'ID',
                    ));
                }

                if (empty($user)) {
                    // If still not found, assume $customer_no is actually the user ID
                    $user_id = intval($customer_no);
                } else {
                    $user_id = $user[0]; // Use the first user ID found
                }

                $users[] = $user_id;
            }

            $enable_all_users = strtolower($row[5]) === 'yes' ? 1 : 0;
            $status = strtolower($row[6]) === 'active' ? 1 : 0;
    

            // Prepare data for insertion
            $data = array(
               
                'artNo' => $artNo,
                'qty' => $qty,
                'price' => $price,
                'role' => $role_name,
                'users' => $users,
                'selectAllCheckboxval' => $enable_all_users,
                'status' => $status,
            );

            // Use the psp_save_stacking_rule function to insert the data
          
            $result = self::psp_save_stacking_rule($data);


            switch ($role_name) {
                case 'custom_uam_b2b':
                    $role_name = 'b2b';
                    break;
                case 'custom_uam_reseller_sek':
                    $role_name = 'reseller sek';
                    break;
                case 'custom_uam_reseller_eur':
                    $role_name = 'reseller eur';
                    break;
                // Add more cases as needed
            }

            if (is_wp_error($result)) {
                $log[] = "<span style='color:red;'>Error  with artNo {$artNo} : - " . $result->get_error_message() . "</span>";
                $had_errors = true;
            } 


        
        if($had_errors){
            return $log;
        }
        
    }
    

    public static function useRole($user_id){
      
        $user = new WP_User($user_id);
        $user_roles = $user->roles;
        $user_roles=$user_roles[0];
        return $user_roles;
    }

    public static function psp_save_stacking_rule($data)
    {

        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';
      
        // Retrieve data from the parameter
        $artNo = isset($data['artNo']) ? sanitize_text_field($data['artNo']) : '';
        $qty = isset($data['qty']) ? intval($data['qty']) : '';
        $price = isset($data['price']) ? floatval($data['price']) : '';
        $users = isset($data['users']) ? array_map('strval', $data['users']) : array();
        $id = isset($data['id']) ? intval($data['id']) : '';
        $role = isset($data['role']) ? sanitize_text_field($data['role']) : '';
        $enable_all_users = isset($data['selectAllCheckboxval']) && $data['selectAllCheckboxval'] === 1 ? 1 : 0;
        $status = isset($data['status']) ? intval($data['status']) : 0;

        // Validate input
        if (empty($artNo) || empty($qty) || empty($price) || empty($users)) {
            return new WP_Error('validation_error', 'Please provide all required fields.');
        }

        // Check if the rule already exists
        $existing_rule = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND rule_price = %f AND role = %s AND users = %s", $artNo, $qty, $price, $role, implode(',', $users)),
            ARRAY_A
        );
       
        // Check for existing rules with the same artNo, qty, and role, but not necessarily the same price
        if ($id) {
            $rules_with_same_qty = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND id != %d", $artNo, $qty, $role, $id),
                ARRAY_A
            );
        } else {
            $rules_with_same_qty = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s", $artNo, $qty, $role),
                ARRAY_A
            );
        }
        // Check for overlapping users
        $overlapping_users = false;
        foreach ($rules_with_same_qty as $rule) {
            $rule_users = explode(',', $rule['users']);
            if (array_intersect($users, $rule_users)) {
                $overlapping_users = true;
                break;
            }
        }

        // Determine whether to update or insert
        if ($existing_rule && $id == 0) {
          
            return new WP_Error('existing_rule', 'Rule already exists with the same details.');
        } elseif ($overlapping_users) {
            return new WP_Error('overlapping_users', 'A rule with the same quantity already exists for one or more users.');
        } else {
            // Check if a rule exists with the same artNo, qty, and role but different price
            if ($id) {
                $rule_with_same_qty_and_role = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND rule_price = %f AND id != %d",
                        $artNo, $qty, $role, $price, $id
                    ),
                    ARRAY_A
                );
            } else {
                $rule_with_same_qty_and_role = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND rule_price = %f",
                        $artNo, $qty, $role, $price
                    ),
                    ARRAY_A
                );
            }

            if ($rule_with_same_qty_and_role) {
                $existing_users = isset($rule_with_same_qty_and_role['users']) ? $rule_with_same_qty_and_role['users'] : '';
                $existing_users_array = explode(',', $existing_users);

                $RestrictProductDataUser = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT roleid FROM taw_restrict_product WHERE art_no = %s AND type = %s",
                        $artNo,
                        'user'  // Condition for type
                    ),
                    ARRAY_A
                );

                $restrictedUserRoleIds = array_map(function($item) {
                    return $item['roleid'];
                }, $RestrictProductDataUser);
                $commonRoleIds = array_intersect($restrictedUserRoleIds, $users);
                
                $all_users = array_unique(array_merge($existing_users_array, $commonRoleIds));
                $updated_users = implode(',', $all_users);
                
                $update_result = $wpdb->update(
                    $table_name,
                    array(
                        'users' => $updated_users,// New value for the users column
                    ),
                    array(
                        'art_no' => $artNo, // WHERE condition: match this value
                        'qty' => $qty, // WHERE condition: match this value
                        'role' => $role, // WHERE condition: match this value
                        'rule_price' =>$price,
                    ),
                    array(
                        '%s' // Format for the users column value (string)
                    ),
                    array(
                        '%s', // Format for art_no (string)
                        '%d', // Format for qty (integer)
                        '%s',  // Format for role (string)
                        '%f'
                    )
                );
            
                if (false === $update_result) {
                    return new WP_Error('update_failed', 'Failed to update the users column.');
                }
            
                return new WP_Error('rule_conflict', 'This rule already exists with other users of the same role. The user has been added to this rule.');
            }
        }

        $data = array(
            'art_no' => $artNo,
            'qty' => $qty,
            'rule_price' => $price,
            'role' => $role,
            'enable_all_users' => $enable_all_users,
            'users' => implode(',', $users),
            'status' => $status,
            'modified_by' => get_current_user_id(),
            'modified_date' => date('Y-m-d H:i:s'),
        );

        if ($id > 0) {
            if ($id) {
                $check_exists = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id),
                    ARRAY_A
                );

                if (empty($check_exists)) {
                    return new WP_Error('No data found for this ID: ' . $id);
                }
            }
            $flag=1;
            $flagforUsers=1;
            $Restrictedusers=[];
            $table_name1='taw_restrict_product';
            $returnString='Rule Updated Successfuly';
   
            $RestrictProductDataRole = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name1 WHERE art_no = %s AND type = %s",
                    $artNo,
                    'role'  // Condition for type
                ),
                ARRAY_A
            );

            $RestrictProductDataUser = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name1 WHERE art_no = %s AND type = %s",
                    $artNo,
                    'user'  // Condition for type
                ),
                ARRAY_A
            );
            if(!empty($RestrictProductDataRole)){
                foreach($RestrictProductDataRole as $Rule){
                    if($data['role']!=$Rule['roleid']){
                        $flag=0;
                        $returnString='Rule added but in inactive mode because this product is restricted for some other role';
                    }
                }
            }
            

            if(!empty($RestrictProductDataUser)){
                foreach($RestrictProductDataUser as $Rule){
                    $ActaluRole =Product_Stack_Pricing_Import_Export::useRole($Rule['roleid']);
                    if($ActaluRole!=$data['role']){
                        $flagforUsers=0;
                        $returnString='Rule added but in inactive mode because this product is restricted for some other User of other role';
                    }else{
                        array_push($Restrictedusers,$Rule['roleid']);
                    }
                }
            }

            
            if (!empty($Restrictedusers)) {
                $data['users'] = implode(',', $Restrictedusers);
                $returnString='Few users are removed as this product is restrict user wise for same role in restrict products';
            } else {
                if($flagforUsers == 0 && empty($Restrictedusers)){
                    return new WP_Error('rule_conflict',"This product is restricted for another role, so can't add this rule");
                }
                else if ($flagforUsers == 0) {
                    if ($status == 1) {
                        $data['enable_all_users'] = 0;
                    }
                } elseif ($flag == 0) {
                    return new WP_Error('rule_conflict',"This product is restricted for another role, so can't add this rule");
                }
            }
            
            $updated = $wpdb->update(
                $table_name,
                $data,
                array('id' => $id),
                array(
                    '%s', // art_no
                    '%d', // qty
                    '%f', // rule_price
                    '%s', // role
                    '%s', // enable_all_user
                    '%s', // users
                    '%d', // status
                    '%d', // modified_by
                    '%s'  // modified_date
                ),
                array('%d') // Where clause format
            );
            
            if ($updated !== false) {
                return $returnString;
            } else {
                $error_message = $wpdb->last_error;
                return new WP_Error('db_error', $error_message);
            }
     
          
            // $restrictedRoles=[];
            // $usersID=[];
            // $hasResticted=[];
            // $notRestricted=[];

            // foreach ($RestrictProductData as $resdata){
            //     if ($resdata['art_no'] == $data['art_no']) {
            //         if ($resdata['Type'] == 'role') {
            //             if($data['role']==$resdata['roleid']){
            //                 $flag=0;
            //             }
            //         }
            //         else{
            //          $ActaluRole =Product_Stack_Pricing_Import_Export::useRole($resdata['roleid']);
            //             if($ActaluRole!=$data['role']){
            //                 $flag=2;
            //                 array_push($usersID,$resdata['roleid']);
            //             }
            //             else{
            //                 $flag=0;
            //             }
            //         }
            //     }
            // }
            
            
            // $returnString='';
            // if($flag==0 && count($usersID)<=0){
            //     $returnString='Rule updated successfully.';
            // }
            // else if(count($usersID)>0){
            //     $data['status']=0;
            //     $data['users'] = implode(",", $usersID);

            //     $returnString='Rule updated successfully. But in deactivate mode';
            // }
            // else{
            //     $data['status']=0;
            //     $returnString='Rule updated successfully. But in deactivate mode';
            //     // Update existing rule
            // }
       

        } else {
            $flag=1;
            $flagforUsers=1;
            $Restrictedusers=[];
            $table_name1='taw_restrict_product';
            $returnString='Rule Updated Successfuly';

            $RestrictProductDataRole = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name1 WHERE art_no = %s AND type = %s",
                    $artNo,
                    'role'  // Condition for type
                ),
                ARRAY_A
            );

            $RestrictProductDataUser = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name1 WHERE art_no = %s AND type = %s",
                    $artNo,
                    'user'  // Condition for type
                ),
                ARRAY_A
            );
            if(!empty($RestrictProductDataRole)){
                foreach($RestrictProductDataRole as $Rule){
                    if($data['role']!=$Rule['roleid']){
                        $flag=0;
                        $returnString='Rule added but in inactive mode because this product is restricted for some other role';
                    }
                }
            }
          
            if(!empty($RestrictProductDataUser)){
                foreach($RestrictProductDataUser as $Rule){
                    $ActaluRole =Product_Stack_Pricing_Import_Export::useRole($Rule['roleid']);
                    if($ActaluRole!=$data['role']){
                        $flagforUsers=0;
                        $returnString='Rule added but in inactive mode because this product is restricted for some other User of other role';
                    }else{
                        array_push($Restrictedusers,$Rule['roleid']);
                    }
                }
            }

            if (!empty($Restrictedusers)) {
                $data['users'] = implode(',', $Restrictedusers);
                $returnString='Few users are removed as this product is restrict user wise for same role in restrict products';
            } else {
                if($flagforUsers == 0 && empty($Restrictedusers)){
                    return new WP_Error('rule_conflict',"This product is restricted for another role, so can't add this rule");
                }
                else if ($flagforUsers == 0) {
                    if ($status == 1) {
                        $data['enable_all_users'] = 0;
                    }
                } elseif ($flag == 0) {
                    return new WP_Error('rule_conflict',"This product is restricted for another role, so can't add this rule");
                }
            }

            // if(!empty($Restrictedusers)){
            //     $data['enable_all_users']= implode(',', $Restrictedusers);
            //     $returnString='Few users are removed as this product is restrict user wise for same role in restrict products';
            // }
            // else if($flagforUsers==0 ){
            //     if($status==1){
            //         $data['enable_all_users']=0;
            //     }
            // }else if($flag==0){
            //         return "This product is Restricted for other role so can't  add this rule";               
            // }
           $inserted = $wpdb->insert(
                $table_name,
                $data,
                array(
                    '%s', // art_no
                    '%d', // qty
                    '%f', // rule_price
                    '%s', // role
                    '%s', // enable_all_user
                    '%s', // users
                    '%d', // status
                    '%d', // modified_by
                    '%s'  // modified_date
                )
            );
            
            if ($inserted !== false) {
                return $returnString;
            } else {
                $error_message = $wpdb->last_error;
                return new WP_Error('db_error', $error_message);
            }
            // Insert new rule
            // $table_name1='taw_restrict_product';
            // $RestrictProductData = $wpdb->get_results(
            //     $wpdb->prepare("SELECT * FROM $table_name1 WHERE art_no = %s", $artNo),
            //     ARRAY_A
            // ); 
            
            // $flag=1;
            // $restrictedRoles=[];
            // $usersID=[];
            
            // foreach ($RestrictProductData as $resdata){
            //     if ($resdata['art_no'] == $data['art_no']) {
            //         if ($resdata['Type'] === 'role') {
            //                 array_push($restrictedRoles,$resdata['roleid']);
            //         }else{
            //          $ActaluRole =Product_Stack_Pricing_Import_Export::useRole($resdata['roleid']);
            //             if($ActaluRole!=$data['role']){
            //                 $flag=2;
            //                 array_push($usersID,$resdata['roleid']);
            //             }
            //             else{
            //                 $flag=0;
            //             }
            //         }
            //     }
            // }      
           
            // if((count($restrictedRoles))>0){
            //     if (in_array($data['role'], $restrictedRoles)){
            //         $flag=0;
            //     }
            // }
            // $returnString='';
           
            // if($flag==0 && count($usersID)<=0){
            //     $returnString='Rule updated successfully.';
            // }
            // else if($flag==0 && count($usersID)>=1){
            //     $data['users'] = implode(",", $usersID);
            //     $returnString='Rule updated successfully.';
            // }
            // else if(count($usersID)>0){
            //     $data['status']=0;
            //     $data['users'] = implode(",", $usersID);
            //     $returnString='Rule updated successfully. But in deactivate mode';
            // }
            // else{
  
            //     $returnString='Rule updated successfully.';
            //     // Update existing rule
            // }
                    
            // $temp=implode(",", $usersID);
            // $Temp1=implode(',', $users);
            // $lengthTemp = strlen($temp);
            // $lengthTemp1 = strlen($Temp1);

            // Compare the lengths
            // if ($lengthTemp != $lengthTemp1 &&  $data['enable_all_users']==1) {
            //     $data['enable_all_users']=0;
            // }       
            // $inserted = $wpdb->insert(
            //     $table_name,
            //     $data,
            //     array(
            //         '%s', // art_no
            //         '%d', // qty
            //         '%f', // rule_price
            //         '%s', // role
            //         '%s', // enable_all_user
            //         '%s', // users
            //         '%d', // status
            //         '%d', // modified_by
            //         '%s'  // modified_date
            //     )
            // );

            // if ($inserted !== false) {
            //     return $returnString;
            // } else {
            //     $error_message = $wpdb->last_error;
            //     return new WP_Error('db_error', 'Failed to save the rule. ' . $error_message);
            // }
        }
    }
}
