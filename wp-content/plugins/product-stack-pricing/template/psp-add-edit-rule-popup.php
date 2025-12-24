<?php
        $users=[];
        global $wpdb;
        $rolesroleids=[];
        $nonRestrictedUsers=[];
        $table_name = 'taw_restrict_product';
        $query = $wpdb->prepare("SELECT Type, roleid FROM $table_name WHERE art_no = %s", $artNo);
        $RestrictedRules = $wpdb->get_results($query); 

        for($i=0;$i<count($RestrictedRules);$i++){
            if($RestrictedRules[$i]->Type==='user'){
                array_push($users,$RestrictedRules[$i]->roleid);
                array_push($nonRestrictedUsers,$RestrictedRules[$i]->roleid);
            }else{
                array_push($rolesroleids,$RestrictedRules[$i]->roleid);
            } 
        }   
   
        function useRole($user_id){
            $user = new WP_User($user_id);
            $user_roles = $user->roles;
            $user_roles=$user_roles[0];
            return $user_roles;
        }

        $userRoles = [];

        // Check if there are any users
        if (!empty($users)) {
            // Iterate over the users array

            foreach ($users as $user_id) {
                // Call the function and store the role in the array
                $role = useRole($user_id);
                if ($role !== null) {
                    array_push($userRoles,$role);
                }
            }
        }
?>
<div class="bg-white p-6 rounded-lg shadow-lg w-2/5 relative">
    <input type="text" id="edit-rule-verify" hidden value="<?php echo isset($rule['id']) ? esc_attr($rule['id']) : ''; ?>">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-black"><?php echo isset($rule['id']) ? 'Edit Rule' : 'Add Rule'; ?></h2>
        <button id="close-psp-add-rule-popup" class="text-psp-red"><img class="w-6 rounded-full p-1 bg-psp-red" src="<?php echo PRODUCT_STACK_PRICING_URL; ?>img/MaterialSymbolsCancelRounded.svg" alt=""></button>
    </div>
    <div class="flex gap-2 items-center  p-3 rounded-xl my-4 bg-[#F7F7F7]">
        <div class="w-full">
            <div class="font-semibold text-lg text-black">Minimum Quantity</div>
            <div class="relative w-full">
                <input type="number" min="0" id="psp-qty-default" class="block w-full focus:outline-none pl-4 pr-10 py-4 text-sm font-bold  !text-psp-blue  !rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500" value="<?php echo isset($rule['qty']) ? esc_attr($rule['qty']) : ''; ?>" required />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                    QTY
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="font-semibold text-lg text-black">Special Price per Unit</div>
            <div class="relative w-full">
                <input type="number" min="0" id="psp-price-default" class="block w-full focus:outline-none pl-4 pr-10 py-4 text-sm font-bold  !text-psp-blue !rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500" value="<?php echo isset($rule['rule_price']) ? esc_attr($rule['rule_price']) : ''; ?>" required />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-2 items-center justify-between item-center p-3 rounded-xl my-4 bg-[#F7F7F7]">
    
        <div class="font-bold text-black text-[17px] ">Select Role</div> 
       <select id="psp-role-default" class="bg-gray-50 border-none  text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 z-10">

        <option value="">--SELECT--</option>
            <?php
            // Get all roles

            $editable_roles = get_editable_roles();
            $selected_role = isset($rule['role']) ? $rule['role'] : '';
            $enable_all_users = isset($rule['enable_all_users']) && $rule['enable_all_users'] == 1 ? 1 : 0;

        
            // Loop through each role and create an option element
            foreach ($editable_roles as $role_key => $role) {
                $selected = ($role_key === $selected_role) ? 'selected' : '';
                if($role_key == "custom_uam_b2b" || $role_key == "custom_uam_reseller_sek" || $role_key == "custom_uam_reseller_eur"){
                    if(count($rolesroleids)>0|| count($userRoles)){
                        if (in_array($role_key, $rolesroleids)|| in_array($role_key, $userRoles)) {
                            // Output the option as selectable
                            echo '<option value="' . esc_attr($role_key) . '" ' . $selected . '>' . esc_html($role['name']) . '</option>';
                        } else {
                            // Output the option as disabled
                            echo '<option value="' . esc_attr($role_key) . '" ' . $selected . ' disabled>' . esc_html($role['name']) . ' (Unavailable)</option>';
                        }
                    }else{
                        echo '<option value="' . esc_attr($role_key) . '" ' . $selected . '>' . esc_html($role['name']) . '</option>';
                    }

            }}
            ?>

        </select>
        
    </div>
    <div class="flex gap-2 items-center justify-between item-center p-3 rounded-xl my-4 bg-[#F7F7F7]">
    <?php
    $enable_status = isset($rule['status']) && $rule['status'] == 1 ? 1 : 0;
   
     ?>
    <div class="font-bold text-black text-[17px] ">Active</div> 
    <div class="psp-toggle-container">
<input type="checkbox" <?php echo ($enable_status)  ? 'checked' : ''; ?> id="psp-toggle-btn">
<label for="psp-toggle-btn" class="psp-label"></label>
</div>
</div>
    <div class="p-3 rounded-xl mb-2  bg-[#F7F7F7]">


        <div class="flex gap-2 mb-2  items-center justify-between item-center">
            <div class="font-bold text-black text-[17px]">Users</div>
            <div class="flex items-center select-all-user-btn bg-psp-blue px-4 cursor-pointer rounded py-1 text-white select-none">
                <?php
                // Initialize variables
                $user_count = 0;
                $selected_user_count = 0;

                // Check if $rule['role'] and $rule['users'] are set
                if (isset($rule['role']) && isset($rule['users'])) {
                    $art_no=$rule['art_no'];
             
                global $wpdb;
                $table_name = $wpdb->prefix . 'product_stack_pricing';
                $query = $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s", $art_no);
                $results = $wpdb->get_results($query);
                //var_dump($results);
                
                    // Get all users from the specified role
                    $args = array(
                        'role' => $rule['role'],
                        'orderby' => 'user_nicename',
                        'order' => 'ASC',
                    );
                    $users_from_role = get_users($args);

                    // Count users from the role and user IDs stored in $rule['users']
                    $user_ids = explode(',', $rule['users']);
                    $user_count = count($users_from_role);
                    $selected_user_count = count(array_intersect($user_ids, wp_list_pluck($users_from_role, 'ID')));
                }
                ?>
                <div class="flex items-center justify-center">
                   
                   <div class=" border  mr-2  border-white rounded-full ">
                   <img class="start-0 !w-[16px] rounded-full checked-icon-psp-all  !text-white" <?php echo (($user_count > 0 && $user_count === $selected_user_count) || $enable_all_users)  ? '' : 'style="visibility: hidden;"'; ?>  src="<?php echo PRODUCT_STACK_PRICING_URL;
                                                                                                         ?>/img/checked-icon-white.svg" alt="">
                   </div>
                    <input type="checkbox" id="select-all-users"  class="mr-2 " style="border-radius: 100% !important; display:none;" <?php echo (($user_count > 0 && $user_count === $selected_user_count) || $enable_all_users) ? 'checked' : ''; ?>>
                </div>
                <span for="select-all-users" class="text-sm font-bold text-white" id="lab">All users</span>
            </div>

        </div>

        <div class="bg-[#F7F7F7]  rounded-xl " id="user_adding_container_psp">
            <div class="flex gap-4 ">
                <!-- <input type="text" id="add-user-search-input" class="border border-gray-300 px-4 py-3 ml-3 flex-1 !rounded-xl" placeholder="Add User">
                <button class="bg-psp-blue text-white px-4 py-3  rounded-xl font-bold" id="user_add_popup">Add</button> -->

                <div class="relative w-full">
                    <input type="search" id="psp-user-pop-default-search" class="block w-full focus:outline-none pl-4 pr-10 py-4 text-sm text-gray-900  !rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search User..." required />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-psp-blue " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div id="psp-user-search-results" class="relative w-full hidden shadow-md">
                  
                <div class="rounded-lg user-search-item  py-2 text-black bg-white mt-1 z-[999999999] absolute max-h-56 overflow-y-scroll psp-scroll border w-full !scrollbar-thin !scrollbar-thumb-rounded !scrollbar-thumb-gray-500 !scrollbar-track-gray-300">
                    <!-- Search results will be appended here -->
                </div>
            </div>
            <div class="space-y-2 p-1 rounded mt-2 !h-[150px] bg-white  overflow-y-scroll psp-scroll">
                <div class="grid grid-cols-3 gap-2" id="popup-user-container">
                    <?php
           
                    if (isset($rule['users'])) {
                        if ($selected_role) {
                            $args = array(
                                'role' => $selected_role,
                                'orderby' => 'user_nicename',
                                'order' => 'ASC',
                            );

                            $users_from_role = get_users($args);
                        }
                        $user_ids = explode(',', $rule['users']);
                        $matchingUserIDs=[];
                        $nonRestrictedUsers=$user_ids;
                        $user_ids = array_map(function($user) {
                            return (string) $user->ID;
                        }, $users_from_role);
                    
                        
                        foreach($users_from_role as $user){
                            $userID = (String)($user->ID);  
                            if (in_array($userID, $users)) {
                                // Add matching user to the results array
                                $matchingUserIDs[] = $user;
                            } 
                        }

                        foreach($users_from_role as $user){
                            $userID = (String)($user->ID);  
                            if (!in_array($userID, $users)) {
                                // Add matching user to the results array
                                $matchingUserIDs[] = $user;
                            } 
                        }

                
                        $users_from_role=$matchingUserIDs;
                        $noImage=false;
                        $ddd='';
                        foreach ($users_from_role as $user) {
                          
                            $user_info = get_userdata($user->ID);
                            if ($user_info) {
                                // Check if the user ID is in the array of user_ids
                                $is_selected_user = in_array($user_info->ID, $user_ids);

                                $customer_no = get_user_meta($user_info->ID, 'customer_no', true);
                                if (empty($customer_no)) {
                                    $customer_no = get_user_meta($user->ID, 'subcustomer_no', true);
                                }
                if (empty($customer_no)) {
                    $customer_no = "No Customer Number"; // Fallback to user ID if no customer number
                }

                 $isClickable=true;

                if (count($users) > 0) {
                    if(!in_array(strval($user->ID), $users)){
                            $isClickable = false;
                    }
                }
                if(count($rolesroleids) > 0){
                    $user_roles = $user_info->roles;
                    $role = $user_roles[0];
                    if (in_array($role, $rolesroleids)) {
                        $isClickable = true;
                    }
                }
                
                 $buttonClass = $isClickable ? '' : 'disabled'; // No class if clickable, 'disabled' if unclickable
                 $buttonStyle = $isClickable ? '' : 'pointer-events: none; opacity: 1;'; // Normal style if clickable, unclickable style if not


                 $imgSrc = $isClickable ? PRODUCT_STACK_PRICING_URL . '/img/MaterialSymbolsCheckCircleRounded.svg':
                  PRODUCT_STACK_PRICING_URL . '/img/solar--user-block-bold.svg'; // Use a different image for the non-clickable state


                  $visibility = $enable_all_users ? 'visible' : 'hidden';
            
                  if($enable_all_users){
                    $visibility ='visible';
                  }else{
                    for($i=0;$i<count($nonRestrictedUsers);$i++){
                        if(((int)$nonRestrictedUsers[$i])===$user_info->ID){
                            $visibility ='visible';
                        }
                    }
                  }
                  $shouldAddClass = $enable_all_users || ($isClickable && $visibility === 'visible');
                  if ($imgSrc === PRODUCT_STACK_PRICING_URL . '/img/solar--user-block-bold.svg'){
                    $visibility ='visible';
                  }
                  $checkedIconClass = ($imgSrc === PRODUCT_STACK_PRICING_URL . '/img/solar--user-block-bold.svg') ? '' : 'checked-icon-psp';
                    ?>
                               <div class="bg-white p-2 relative rounded border cursor-pointer all-users-by-role hover:bg-slate-50 flex gap-2 items-center justify-start <?php echo $shouldAddClass ? 'user-popup-card' : ''; ?>" data-userid="<?php echo esc_attr($user_info->ID); ?>">
                                    <div class="flex item-center !w-[20px]">
                                        <button  class="!text-psp-blue right-2 border border-psp-blue rounded-full <?php echo esc_attr($buttonClass); ?>" style="<?php echo esc_attr($buttonStyle); ?>">
                                        <img class="start-0 !w-[20px] rounded-full <?php echo $checkedIconClass; ?> !text-psp-blue"
                                        style="visibility: <?php echo $visibility; ?>;" 
                                        src="<?php echo $imgSrc; ?>" 
                                        alt="">
                                        </button>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold search-include-users" ><?php echo esc_html($user_info->display_name); ?></h4>
                                        <p class="search-include-users"><?php echo esc_html($customer_no); ?></p>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                    } else {
                        ?>
                        <div id="popup-user-card-nodatamsg" class="grid text-center p-2 col-span-3">Add some Users</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center mt-6 flex-col">
   
        <div id="error-message-psp-rule-popup" class="text-red" style="color: red;"></div>
        <button id="popipAddRuleSaveBTN" class="bg-psp-blue text-white py-2 px-6 max-w-[100px] rounded-lg m-auto">Save</button>
    </div>
</div>