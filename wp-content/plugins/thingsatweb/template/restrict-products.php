<style type="text/css">
    ul#c_uam_caprole_ul {
        padding: 10px;
    }

    ul#c_uam_caprole_ul li {
        padding: 10px;
    }

        /* For custom_restrict_ls */
    ul.custom_restrict_ls {
        padding: 10px;
    }

    ul.custom_restrict_ls li {
        padding: 15px 30px;
        cursor: pointer;
        position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
        font-weight: bold;
    }

    ul.custom_restrict_ls li.active{
        color: white;
        background: rgb(34, 113, 177);
        border: 2px solid #2271B1;
    }

    ul.custom_restrictuser_ls {
        padding: 4px;
    }

    ul.custom_restrictuser_ls li {
        padding: 15px 30px;
        cursor: pointer;
        position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
        width:375px;
        
    }

    ul.custom_restrictuser_ls li.active{
        color: white;
        background: rgb(34, 113, 177);
        border: 2px solid #2271B1;
    }

    .navdd-tab {
        padding: 5px 20px;
        text-decoration: none;
        color: black;
        border: 2px solid #E5E5E5;
        background: #cccccc;
        transition: background-color 0.3s, color 0.3s, border 0.3s;
        margin: 0;
    }

    .navdd-tab-active {
        background-color: #2271B1;
        border: 2px solid #2271B1;
        color: white !important;
    }

    nav {
        display: flex;
        gap: 0;
    }

    #entered-artnos li {
    display: flex;
    flex-direction: column;
    background-color: #FFFFFF;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    }

    #entered-artnos li div {
        display: flex;
        align-items: start;
    }

    #entered-artnos li div span {
        color: #000000;
    }

    #entered-artnos li div span:nth-child(1) {
        flex: 1;
        font-weight: bold;
    }

    #entered-artnos li div span:nth-child(2) {
        flex: 0 0 10px;
        text-align: start;
    }

    #entered-artnos li div span:nth-child(3) {
        flex: 2;
    }

    #entered-artnos li div img {
        cursor: pointer;
        width: 23px;
        height: 23px;
        margin-left: 10px;
    }
  
    #user-search {
    background: url('<?php echo THINGSATWEB_BASE; ?>/img/search.png') no-repeat;
    background-position: 10px center; /* Adjust the position as needed */
    background-size: 14px 14px; /* Adjust the size as needed */
    padding-left: 35px; /* Make room for the icon */
    }

    #custom-uam-alert-delete-dlg {
    display: none;
    position: fixed;
    z-index: 100051;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#deleteover {
    background: #000;
    opacity: 0.7;
    filter: alpha(opacity = 70);
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 100050;}

    
    .art-search-item:hover {
    background-color: #2271b1; /* Change background color on hover */
    color: #fff; /* Optional: Change text color on hover */
}

.scrollable-list-container {
    max-height: 600px; /* Adjust height as needed */
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top:28px;
    width: 455px;
}

/* Optional: Style the scrollbar */
.scrollable-list-container::-webkit-scrollbar {
    width: 8px;
}

.scrollable-list-container::-webkit-scrollbar-track {
    background: #ccc; 
}

.scrollable-list-container::-webkit-scrollbar-thumb {
    background: #2271B1;
    border-radius: 4px;
}

.scrollable-list-container::-webkit-scrollbar-thumb:hover {
    background: #2271B1;
}

.list-item.active {
    background-color: #2271B1; /* Blue background */
    color: white; /* White text */
}

.restrict-tooltip-arrow {
    position: absolute;
    bottom: -5px; /* Arrow height */
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid rgba(0, 0, 0, 0.7);
}

.restrict-tooltip {
    position: absolute;
    bottom: calc(100% + 5px); /* Position above the button */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    display: none; /* Initially hidden */
    z-index: 1;
    white-space: nowrap; /* Prevent line breaks */
}
.restrict-tooltipuser-arrow {
    position: absolute;
    bottom: -5px; /* Arrow height */
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid rgba(0, 0, 0, 0.7);
}

.restrict-tooltipuser {
    position: absolute;
    bottom: calc(100% + 5px); /* Position above the button */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    display: none; /* Initially hidden */
    z-index: 1;
    white-space: nowrap; /* Prevent line breaks */
}

.loader {
    border: 4px solid rgba(0, 0, 0, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin: 40px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#loaderhid {
    display: none;
    position: relative;
    min-height: 80vh;
}

.aloader {
    background-image:url('<?php echo THINGSATWEB_BASE; ?>/img/loading.gif');
    height: 50px;  
    width: 50px;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: absolute; /* Position relative to #loaderhid */
    top: 0; /* Align to the top */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    animation: mymove 5s infinite;
}

#loaderuser {
    display: none;
    position: relative;
    min-height: 80vh;
}

.aloaderuser {
    background-image:url('<?php echo THINGSATWEB_BASE; ?>/img/loading.gif');
    height: 50px;  
    width: 50px;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: absolute; /* Position relative to #loaderhid */
    top: 0; /* Align to the top */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    animation: mymove 5s infinite;
}

@keyframes mymove {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>

<div class="wrap">
    <div class="width:100%;float:left;">
        <p style="margin:0px; width: 50%;color: #000000; font-weight: bold; font-size: 26px;float: left;"><?php esc_html_e('Restrict Products', 'custom-uam'); ?> </p>
        <!-- <hr style="width: 100%;float: left;" /> -->
    </div>
    <div style="width: 95%; padding-top: 30px;float: left;">

        <div style="width: 40%; float: left; position: relative;">
            <?php
            $exclude_role = [""];
            global $wpdb;

            // Check if the role exists in the database
            $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
            $option_results = $wpdb->get_results($option_query, ARRAY_A);
            $resroles_data = unserialize($option_results[0]['option_value']); 

            // $user_query = "SELECT u.user_login, u.ID, m1.meta_value AS customerno FROM tsm_users u LEFT JOIN tsm_usermeta m1 ON u.ID = m1.user_id AND m1.meta_key LIKE '%customer_no'";
            // $user_results = $wpdb->get_results($user_query, ARRAY_A);

            global $wpdb;

            // Step 1: Retrieve and unserialize the roles data
            $option_name = 'tsm_user_roles';
            $roles_serialized = $wpdb->get_var($wpdb->prepare(
                "SELECT option_value FROM $wpdb->options WHERE option_name = %s",
                $option_name
            ));
            $roles_data = unserialize($roles_serialized);

            // Step 2: Filter roles where roleissubrole is 0
            $main_roles = array();
            foreach ($roles_data as $role_key => $role_value) {
                if (isset($role_value['roleissubrole']) && $role_value['roleissubrole'] == '0') {
                    $main_roles[] = $role_key;
                }
            }
            // print_r($main_roles);

            // Step 3: Prepare a REGEXP pattern to match any of the roles
            $role_regex = implode('|', array_map(function($role) {
                return preg_quote($role, '/');
            }, $main_roles));

            // Step 4: Query for users with the main roles
            $user_query = "
                SELECT u.user_login, u.ID, u.display_name, m1.meta_value AS customerno 
                FROM $wpdb->users u
                LEFT JOIN $wpdb->usermeta m1 
                ON u.ID = m1.user_id AND m1.meta_key = 'customer_no'
                WHERE u.ID IN (
                    SELECT user_id 
                    FROM $wpdb->usermeta 
                    WHERE meta_key = '{$wpdb->prefix}capabilities' 
                    AND meta_value REGEXP '\"(" . $role_regex . ")\";b:1;'
                )
            ";
            $user_results = $wpdb->get_results($user_query, ARRAY_A);
           
            ?>

            <nav aria-label="<?php esc_attr_e('Secondary menu'); ?>" style="display: flex; align-items: center; justify-content: space-between; gap: 0;">
                <div style="display: flex; gap: 0;">
                    <a href="#" id="roles-tab" class="navdd-tab navdd-tab-active" aria-current="page" style="border-radius: 6px 0 0 6px; font-size: 14px; font-weight: bold;"><?php esc_html_e('Roles'); ?></a>
                    <a href="#" id="users-tab" class="navdd-tab" style="border-radius: 0 6px 6px 0; font-size: 14px; font-weight: bold;"><?php esc_html_e('Users'); ?></a>
                </div>
                <input type="text" id="user-search" placeholder="Search User" style="display: none; width: 50%; background-color: white; border-radius: 8px; border: 1px solid #ccc;">
            </nav>

            <div id="roles-content">
                <?php
                echo '<ul class="custom_restrict_ls" style="margin-top:18px;">';
                // Flag to check the first item
                $is_first = true;

                foreach ($resroles_data as $resrole_slug => $resrole_data) {
                    // Check if the role is a custom user role
                    if (strpos($resrole_slug, 'custom_uam') !== false && (!isset($resrole_data['roleissubrole']) || $resrole_data['roleissubrole'] != 1)) {
                        // Add active class to the first item
                        $active_class = $is_first ? 'active' : '';
                        
                        echo '<li class="custom-uam-restrictproduct-li ' . $active_class . '" data-role="' . $resrole_data['name'] . '" data-id="' . $resrole_slug . '">';
                        echo '<span id="clickedrole" class="custom-uam-restrictproduct-li-title" style="margin-right: auto;">' . $resrole_data['name'] . '</span>';
                        echo '</li>';
                        
                        // Set the flag to false after the first iteration
                        $is_first = false;
                    }
                }
                echo '</ul>';
                
                ?>
            </div>
            

            <div id="users-content" style="display:none;">
                <div class="scrollable-list-container" style="width: 100%;">
                    <ul class="custom_restrictuser_ls" style="margin-top:0px;">
                        <?php
                        // Flag to check the first item
                        $is_first = true;
                        foreach ($user_results as $userres) {
                            // Determine active class
                            $active_class = $is_first ? ' active' : '';
                            echo '<li style="width: 85%;" class="custom-uam-restrictproductuser-li' . $active_class . '" data-role="' . $userres['user_login'] . '" data-id="' . $userres['ID'] . '">';
                            echo '<span id="clickedroleuser" class="custom-uam-restrictproductuser-li-title" style="margin-right: auto;">';
                            echo '<span style="font-weight: bold;">' . $userres['user_login'] . '</span><br>';
                            echo '<span style="font-weight: bold;">' . $userres['display_name'] . '</span><br>';
                            if (!empty($userres['customerno'])) {
                                echo '<span style="font-weight: lighter;">' . esc_html($userres['customerno']) . '</span>';
                            } else {
                                echo '<span style="font-weight: lighter;">No Customer Number</span>';
                            }
                            echo '</span>';
                            echo '</li>';
                            // Set the flag to false after the first iteration
                            $is_first = false;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

        <!-- Roles Tab -->
        <div style="width: 58%; float: left; margin-left: 5px;" id="right-roles-content">
            <h3 style="float:left; text-align: left; color: #000000; font-weight: bold; font-size: 20px; margin-left: 10px; margin-top: 5px;">
                <?php echo __("Restricted Products", 'custom-uam'); ?>
            </h3>
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-right: 5px; border-radius: 8px;">
                <div id="deleterestrictproduct" style="background-color: #2271B1; position: relative; border-radius: 8px; display: flex; justify-content: center; padding: 0.25rem; align-items: center; color: #fff; cursor: pointer; margin-right: 7px;" onmouseover="this.style.backgroundColor='#135e96'" onmouseout="this.style.backgroundColor='#2271B1'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 12 12">
                        <path fill="currentColor" d="M5 3h2a1 1 0 0 0-2 0M4 3a2 2 0 1 1 4 0h2.5a.5.5 0 0 1 0 1h-.441l-.443 5.17A2 2 0 0 1 7.623 11H4.377a2 2 0 0 1-1.993-1.83L1.941 4H1.5a.5.5 0 0 1 0-1zm3.5 3a.5.5 0 0 0-1 0v2a.5.5 0 0 0 1 0zM5 5.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5M3.38 9.085a1 1 0 0 0 .997.915h3.246a1 1 0 0 0 .996-.915L9.055 4h-6.11z" />
                    </svg>
                </div>
                <div id="editrestrictproduct" style="background-color: #2271B1; position: relative; border-radius: 8px; display: flex; justify-content: center; padding: 0.25rem; align-items: center; color: #9CA3AF; cursor: pointer; margin-right: 7px;" onmouseover="this.style.backgroundColor='#135e96'" onmouseout="this.style.backgroundColor='#2271B1'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 576 512">
                        <path fill="currentColor" d="m402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6m156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8M460.1 174L402 115.9L216.2 301.8l-7.3 65.3l65.3-7.3zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1l30.9-30.9c4-4.2 4-10.8-.1-14.9" />
                    </svg>
                    <span class="restrict-tooltip" style="display: none;">
                        <span class="restrict-tooltip-arrow"></span>
                        Select product to go to edit page
                    </span>
                </div>
                <a href="#TB_inline?&width=500&height=300&inlineId=custom-uam-alert-add-edit-dlg" title="<?php esc_html_e('Add Products', 'custom-uam'); ?>" class="thickbox button button-primary" style="border-radius: 8px;">+ <?php esc_html_e('Add', 'custom-uam'); ?></a>
            </div>
            <div style="display: flex; justify-content: flex-start; align-items: center; margin-top: 20px;">
                 <input type="text" id="roleproduct-search" placeholder="Search Article Number" style="width: 100%; max-width: 250px; background-color: white; border-radius: 4px; border: 1px solid #ccc;">
            </div>            
            <div style="background: #e6e5e5; border-radius: 4px;min-height: 80vh; max-height: auto;">
                <div class="restrict-prod-checkbox-item-container" style="display:none; padding-left: 10px; margin-top: 15px; padding-top: 20px;">
                    <input type="checkbox" id="restrict_select_all"> Select All
                </div>
                <ul id="c_uam_caprole_ul" style="display: block;">
                    <!-- Roles-specific restricted products will be listed here -->
                </ul>
                <div id="loaderhid">
                <div class="aloader"></div>
                </div>
                <div id="no-results" style="display: none; background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px;">
                    <span style="color: red; font-weight: medium;">No results found</span>
                </div>
            </div>
            <div style="text-align: center;">
                <button class="button button-primary" style="display: none; margin: 5px auto;" id="custom_uam_cap_save_btn">Save</button>
            </div>
        </div>
        <!-- Users Tab -->

        <div style="width: 58%; float: left; margin-left: 5px; display:none;" id="right-users-content" >
            <h3 style="float:left; text-align: left; color: #000000; font-weight: bold; font-size: 20px; margin-left: 10px; margin-top: 5px;">
                <?php echo __("Restricted Products", 'custom-uam'); ?>
            </h3>
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-right: 5px; border-radius: 8px;">
                <div id="deleterestrictuserproduct" style="background-color: #2271B1; position: relative; border-radius: 8px; display: flex; justify-content: center;padding: 0.25rem; align-items: center; color: #fff; cursor: pointer; margin-right: 7px;" onmouseover="this.style.backgroundColor='#135e96'" onmouseout="this.style.backgroundColor='#2271B1'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 12 12">
                        <path fill="currentColor" d="M5 3h2a1 1 0 0 0-2 0M4 3a2 2 0 1 1 4 0h2.5a.5.5 0 0 1 0 1h-.441l-.443 5.17A2 2 0 0 1 7.623 11H4.377a2 2 0 0 1-1.993-1.83L1.941 4H1.5a.5.5 0 0 1 0-1zm3.5 3a.5.5 0 0 0-1 0v2a.5.5 0 0 0 1 0zM5 5.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5M3.38 9.085a1 1 0 0 0 .997.915h3.246a1 1 0 0 0 .996-.915L9.055 4h-6.11z" />
                    </svg>        
                </div>
                <div id="editrestrictuserproduct" style="background-color: #2271B1; position: relative; border-radius: 8px; display: flex; justify-content: center; padding: 0.25rem; align-items: center; color: #9CA3AF; cursor: pointer; margin-right: 7px;" onmouseover="this.style.backgroundColor='#135e96'" onmouseout="this.style.backgroundColor='#2271B1'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 576 512">
                        <path fill="currentColor" d="m402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6m156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8M460.1 174L402 115.9L216.2 301.8l-7.3 65.3l65.3-7.3zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1l30.9-30.9c4-4.2 4-10.8-.1-14.9" />
                    </svg>
                    <span class="restrict-tooltipuser" style="display: none;">
                        <span class="restrict-tooltipuser-arrow"></span>
                        Select product to go to edit page
                    </span>
                </div>
                <a href="#TB_inline?&width=500&height=300&inlineId=custom-uam-alert-add-edit-dlg" title="<?php esc_html_e('Add Products', 'custom-uam'); ?>" class="thickbox button button-primary" style="border-radius: 8px;">+ <?php esc_html_e('Add', 'custom-uam'); ?></a>
            </div>
            <div style="display: flex; justify-content: flex-start; align-items: center; margin-top: 20px;">
                <input type="text" id="userproduct-search" placeholder="Search Article Number" style="width: 100%;margin-left: 10px; max-width: 250px; background-color: white; border-radius: 4px; border: 1px solid #ccc;">
            </div>
            <div  style="background: #e6e5e5;border-radius: 4px;margin-left: 10px;min-height: 80vh; max-height: auto;padding-top: 5px;margin-top: 13px;" >
                <div class="restrictuser-prod-checkbox-item-container" style="display:none; padding-left: 10px; margin-top: 15px; padding-top: 20px;">
                    <input type="checkbox" id="restrictuser_select_all"> Select All
                </div>
                <ul id="c_uam_capuser_ul" style="display: block;">
                    <!-- User-specific restricted products will be listed here -->
                </ul>
                <div id="loaderuser">
                <div class="aloaderuser"></div>
                </div>
                <div id="nouser-results" style=" display: none;  background-color: rgb(255, 255, 255);padding: 10px;margin: 10px 15px;border-radius: 4px;">
                    <span style="color: red; font-weight: medium;">No results found</span>
                </div>
            </div>
            <!-- <div id="no-results" style="display: none; color: red; text-align: center;">No results found</div> -->
            <div style="text-align: center;">
                <button class="button button-primary" style="display: none; margin: 5px auto;" id="custom_uam_capuser_save_btn">Save</button>
            </div>
        </div>
        
    </div>
    <div id="custom-uam-alert-add-edit-dlg" style="display:none">
        <div style="text-align: center;">
            <p style="color:red; display: none;" id="custom-uam-alert-error-txt"></p>
            <div style="margin-bottom: 10px;">
                <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_restrict_artno">
                    <input style="width: 84%; margin-bottom: 10px; margin-top: 30px; margin-left: 0px; border-radius: 4px;" type="text" placeholder="Enter Article Number" name="restrict_artno" id="custom-uam-input-restrict-artno">
                    <button type="button" id="add-artno-btn" class="button button-primary" style="padding-left: 20px; padding-right: 20px; font-size: 14px; width: 15%; margin-bottom: 10px; border-radius: 6px; margin-top: 30px;"><?php echo __('Add', 'custom_uam'); ?></button>
                    <div id="restrict-search-results" style="width: 100%; display: none; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); cursor: pointer;">
                        <div class="art-search-results" style="text-align:start; padding: 0.5rem; color: #000; background-color: #f8fafc; margin-top: -10px; z-index: 999999999; position: absolute; max-height: 14rem; overflow-y: auto; border: 1px solid #e2e8f0; width: 76%; scrollbar-width: thin; scrollbar-thumb-radius: 4px; scrollbar-color: gray; scrollbar-track-color: #ccc;">
                            <!-- Search results will be appended here -->
                        </div>
                    </div>
                    <ul id="entered-artnos" style="list-style-type: none; padding: 0;"></ul>
                    <input type="hidden" name="all_artnos" id="all-artnos" value="">
                    <input type="hidden" name="action" value="custom_uam_save_restrict_artno" />
                    <input type="hidden" name="id" value="0" id="custom-uam-input-restrict-id" />
                    <div class="wpml-dialog-footer">
                        <input type="submit" name="submit" id="submit" class="button button-primary" style="padding-left: 20px; padding-right: 20px; font-size: 14px;" value="<?php echo __('Save', 'custom_uam'); ?>" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <div id="custom-uam-alert-delete-dlg" style="display:none;">
        <div style="text-align: center;">
            <p id="custom-uam-alert-msg" style="font-weight:bold; width:330px;"></p>
            <div style="margin-bottom: 10px;">
                <button type="button" id="confirm-remove-btn" class="button button-primary" style="border:#D5352C;background: #D5352C 0% 0% no-repeat padding-box; padding-left: 20px; padding-right: 20px; font-size: 14px; width: 35%; margin-bottom: 10px; border-radius: 6px;"><?php echo __('Remove', 'custom_uam'); ?></button>
                <button type="button" id="cancel-remove-btn" class="button" style="color:black; border:#F0F0F1; background: #F0F0F1 0% 0% no-repeat padding-box; padding-left: 20px; padding-right: 20px; font-size: 14px; width: 35%; margin-bottom: 10px; border-radius: 6px;"><?php echo __('Cancel', 'custom_uam'); ?></button>
            </div>
        </div>
    </div>
    <div id="deleteover"  style="display:none;"></div>
    <!-- <div id="custom-uam-alert-add-edit-dlg" style="display:none">
        <div style="text-align: center;">
            <p style="color:red;display: none;" id="custom-uam-alert-error-txt"></p>
            <form method="post" action="<?php //echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_restrict_artno">
                <?php
                    // global $wpdb;
                    // $all_skus = $wpdb->get_col("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sku'");

                    // $all_skus = array_filter($all_skus);
                ?>

                <select name="restrict_artno" id="custom-uam-input-restrict-artno" style="width: 400px;">
                    <option value="" disabled selected>Select SKU</option>
                    <?php //foreach ($all_skus as $sku) : ?>
                        <option value="<?php //echo esc_attr($sku); ?>"><?php //echo esc_html($sku); ?></option>
                    <?php //endforeach; ?>
                </select>

                <input type="hidden" name="action" value="custom_uam_save_restrict_artno" />
                <input type="hidden" name="id" value="0" id="custom-uam-input-restrict-id" />
                <input type="submit" name="submit" id="submit" class="button button-primary" style="padding-left:20px; padding-right:20px; font-size:14px;" value="<?php //echo __('Save', 'custom_uam'); ?>" />
            </form>
        </div>
    </div> -->
</div>

<script>
    var role_form_txt = "<?php esc_html_e('Add Role', 'custom-uam'); ?>";
    var role_form_edit_txt = "<?php esc_html_e('Role Edit Form', 'custom-uam'); ?>";
    var role_remove_txt = "<?php esc_html_e('Are you sure want to remove', 'custom-uam'); ?>";
    var custom_uam_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>