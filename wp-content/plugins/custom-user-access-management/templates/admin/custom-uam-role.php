<style type="text/css">
    ul#c_uam_cap_ul {
		padding: 10px;
	}

	ul#c_uam_cap_ul li {
    	padding: 10px;
	}
		
        /* For custom_role_ls */
    ul.custom_role_ls {
        padding: 10px;
	}

    ul.custom_role_ls li {
        padding: 15px 30px;
		cursor: pointer;
		position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
    }

    /* ul.custom_role_ls li:hover {
        background: #2271B1;
        color: black;
    } */

    ul.custom_role_ls li.active {
        
        color: white;
    }

    /* For subroles */
    ul.subroles li {
        padding: 15px 10px;
        cursor: pointer;
        position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
    }

    /* ul.subroles li:hover, */
    ul.subroles li.active {
        background: #2271B1;
        color: white;
	}

	span.active-arrow {
    	position: absolute;
        left: 0px;
        color: #FFFFFF;
        /* Adjusted to place arrow before the role name */
        /* top: 10px; Adjusted to vertically center the arrow */
        /* transform: translateY(-50%); Vertically center the arrow */
    	display: none;
	}

    span.custom-uam-overflow-action {
		margin-right: 50px;
		float: right;
		color: #318cbe;
	}

    ul.custom_role_ls li:hover span.active-arrow,
    ul.custom_role_ls li.active span.active-arrow,
    ul.subroles li:hover span.active-arrow,
    ul.subroles li.active span.active-arrow {
        display: none;
	}

    #custom-uam-overflow-action-dlg {
		width: 60px;
    	height: 50px;
		background: #fff;
		padding: 10px;
		position: absolute;
		display: none;
    	box-shadow: 2px 4px 5px #adadad;	
	}

    #custom-uam-overflow-action-dlg-close {
		position: absolute;
	    top: 2px;
	    right: 4px;
	    color: red;
	    cursor: pointer;

	}

    #custom-uam-overflow-action-dlg ul li {
		cursor: pointer;
		font-size: 14px;
	}

    #custom-uam-overflow-action-dlg ul li:hover {
		color: #318cbe;
	}

    .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 24px;
    }

    .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
    }

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: -4px;
    right: 0;
    bottom: 0;
    background-color: #F5F5F5;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 0px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #438830;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #438830;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }
</style>
<script>
jQuery(document).ready(function($) {
    // Variable to store the previously clicked role
    var previousCustomRole;
    var previousClickedSubroleImg;
    var previousClickeddeleteSubroleImg;
    var previousClickedSubrole;

    // Add event listener to the document body
    document.body.addEventListener('click', function(event) {
        // Get the clicked role
        var clickedCustomRole = event.target.closest('ul.custom_role_ls li');
        
        // Reset background color and border color of previously clicked role
        if (previousCustomRole) {
            previousCustomRole.style.background = 'white';
            previousCustomRole.style.border = '1px solid #E5E5E5'; // Reset border color
            
            // Reset addImg src of previously clicked role
            var previousAddImg = previousCustomRole.querySelector('.addImg');
            if (previousAddImg) {
                previousAddImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/add.png';
            }

            var previousEditImg = previousCustomRole.querySelector('.editImg');
            if (previousEditImg) {
                previousEditImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/edit.png';
            }

            var previousDeleteImg = previousCustomRole.querySelector('.deleteImg');
            if (previousDeleteImg) {
                previousDeleteImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/delete.png';
            }

            var previousActiveArrow = previousCustomRole.querySelector('span.active-arrow');
            if (previousActiveArrow) {
                previousActiveArrow.style.display = 'none';
            }

            // If the previous clicked role was a subrole, reset its image source
            if (previousClickedSubroleImg) {
                previousClickedSubroleImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/edit.png';
            }
            if (previousClickeddeleteSubroleImg) {
                previousClickeddeleteSubroleImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/delete.png';
            }

            // Reset font color of previous subrole to black
            if (previousClickedSubrole) {
                previousClickedSubrole.style.color = 'black';
            }

            // Reset font color of parent role to black
            var previousParentRole = previousCustomRole.closest('li.custom-uam-role-li');
            if (previousParentRole) {
                previousParentRole.style.color = 'black';
            }
        }
        
        // Change background color of clicked role
        if (clickedCustomRole) {
            clickedCustomRole.style.background = '#2271B1';
            // Check if the clicked role is a subrole
            var isSubRole = clickedCustomRole.classList.contains('custom-uam-subrole-li');
            if (isSubRole) {
                // Get the corresponding parent role
                var parentRole = clickedCustomRole.closest('li.custom-uam-role-li');
                // Set border color of corresponding parent role to blue
                if (parentRole) {
                    parentRole.style.border = '2px solid #2271B1';
                }

                var editsubImg = clickedCustomRole.querySelector('.editsubroletrigger');
                if (editsubImg) {
                    // Change the src attribute of the edit image
                    editsubImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/editWhite.png';
                }

                var deletesubImg = clickedCustomRole.querySelector('.deletesubroletrigger');
                if (deletesubImg) {
                    // Change the src attribute of the delete image
                    deletesubImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/deleteWhite.png';
                }

                // Store the reference to the clicked subrole's edit image
                previousClickedSubroleImg = editsubImg;
                previousClickeddeleteSubroleImg = deletesubImg;

                // Change font color of previous subrole to black
                if (previousClickedSubrole) {
                    previousClickedSubrole.style.color = 'black';
                }

                // Store the reference to the clicked subrole
                previousClickedSubrole = clickedCustomRole;
            }
            // Change addImg src of clicked role to addWhite.png
            var addImgs = clickedCustomRole.querySelectorAll('.addImg');
            if (addImgs) {
                addImgs.forEach(function(addImg) {
                    addImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/addWhite.png';
                });
            }

            var editImgs = clickedCustomRole.querySelectorAll('.editImg');
            if (editImgs) {
                editImgs.forEach(function(editImg) {
                    editImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/editWhite.png';
                });
            }
            
            var deleteImgs = clickedCustomRole.querySelectorAll('.deleteImg');
            if (deleteImgs) {
                deleteImgs.forEach(function(deleteImg) {
                    deleteImg.src = '<?php echo CUSTOME_UAM_URL; ?>/img/deleteWhite.png';
                });
            }

            var activeArrow = clickedCustomRole.querySelector('span.active-arrow');
            if (activeArrow) {
                activeArrow.style.display = 'block';
            }

            var parentRole = clickedCustomRole.closest('li.custom-uam-role-li');
            if (parentRole) {
                parentRole.style.border = '2px solid #2271B1';
                parentRole.style.color = '#00000'; // Change font color to blue
            }

            // Change font color of clicked role to white
            clickedCustomRole.style.color = 'white';
            
            // Update the previous clicked role
            previousCustomRole = clickedCustomRole;
        }
    });

});
</script>
<div class="wrap">
	<div class="width:100%;float:left;">
        <p style="margin:0px; width: 50%;color: #000000; font-weight: bold; font-size: 26px;float: left;"><?php esc_html_e('Roles & Permissions', 'custom-uam'); ?> </p>
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

            $roles_data = unserialize($option_results[0]['option_value']); ?>

            <h3 style="float:left; margin-top:10px; margin-left:20px; color: #000000; font-weight: bold; font-size: 20px;">Roles</h3>
            <div style=" float:right; margin-right:5px; text-align:right; border-radius: 8px;">
                <a href="#TB_inline?&width=250&height=150&inlineId=custom-uam-alert-add-edit-dlg" title="<?php esc_html_e('Add Role', 'custom-uam'); ?>" class="thickbox button button-primary" style="border-radius: 8px;">+ <?php esc_html_e('Add Role', 'custom-uam'); ?></a>
            </div>
            <?php
            echo '<ul class="custom_role_ls" style="margin-top:50px;">';
            foreach ($roles_data as $role_slug => $role_data) {
                // Check if the role is a custom user role
                if (strpos($role_slug, 'custom_uam') !== false && (!isset($role_data['roleissubrole']) || $role_data['roleissubrole'] != 1)) {
                    echo '<li style= "font-weight:bold;" data-role="' . $role_data['name'] . '" data-id="' . $role_slug . '" class="custom-uam-role-li">';
                    if (isset($role_data['subroles']) && is_array($role_data['subroles']) && !empty($role_data['subroles'])) {
                    echo '<span class="dashicons dashicons-arrow-down-alt2 pull-right active-arrow"></span>';
                    }
                    echo '<div class="custom-uam-role-li" data-role="' . $role_data['name'] . '" data-id="' . $role_slug . '"  style="display: flex; justify-content: space-between; align-items: center;">';
                        // Display the role name on the left corner
                        echo '<span id="clickedrole" class="custom-uam-role-li-title" style="margin-right: auto;">' . $role_data['name'] . '</span>';
                        // Display the icons on the right corner
                        echo '<span style="display: flex;">'; // Set span to flex for proper alignment
                    // Add icon
                            echo '<a href="#TB_inline?&width=250&height=150&inlineId=custom-uam-alert-subadd-subedit-dlg" data-role="' . urlencode($role_slug) . '" data-rolename="' . $role_data['name'] . '" class="thickbox add-sub-role-link" title="' . esc_html__('Add Sub Role', 'custom-uam') . '" style="border-radius: 8px; margin-left: 5px;"><img src="' . CUSTOME_UAM_URL . '/img/add.png" class="addImg" id="addImg" style="width:18px; height:18px;" alt="Add Sub Role"></a>';
                    // Edit icon
                    echo '<a href="#TB_inline?&width=250&height=150&inlineId=custom-uam-alert-add-edit-dlg" data-id="edit" class="thickbox  edit-rolename-link" title="' . esc_html__('Edit Role', 'custom-uam') . '" style="border-radius: 8px; margin-left: 10px;"><img src="' . CUSTOME_UAM_URL . '/img/edit.png" class="editImg" id="editImg" style="width:18px; height:18px;" alt="Edit Role"></a>';
                    // Delete icon
                            echo '<img src="' . CUSTOME_UAM_URL . '/img/delete.png" class="deleteRoleImg deleteImg" id="deleteImg" style="width:16px; height:18px; margin-left: 10px; cursor:pointer;" alt="Delete Role">';
                        echo '</span>';
                    echo '</div>';
                    // echo '<span class="dashicons dashicons-ellipsis pull-right custom-uam-overflow-action"></span>';
                    echo '<ul class="subroles" style="display:none; padding-top:20px;">'; // Start subroles list hidden by default
                    // Check if subroles exist and iterate over them
                    if (isset($role_data['subroles']) && is_array($role_data['subroles'])) {
                        foreach ($role_data['subroles'] as $subrole) {
                            $rolename = $roles_data[$subrole]['name'];
                            echo '<li data-id="' . $subrole . '" class="custom-uam-subrole-li" style="border-radius:6px;" >';
                                echo '<div data-id="' . $subrole . '" class="custom-uam-subrole-li"   style="display: flex; justify-content: space-between; align-items: center;">';
                                    // Display the role name on the left corner
                                    echo '<span  data-id="' . $subrole . '" id="clickedsubrole" style="margin-right: auto;">' . $roles_data[$subrole]['name'] . '</span>';
                                    // Display the icons on the right corner
                                    echo '<span style="display: flex;">'; // Set span to flex for proper alignment
                                        echo '<a href="#TB_inline?&width=250&height=150&inlineId=subrole-edit-dlg" data-role="' . urlencode($role_slug) . '" data-rolename="' . urlencode($role_data['name']) . '" class="thickbox" title="' . esc_html__('Sub Role Edit Form', 'custom-uam') . '" style="border-radius: 8px; margin-right: 5px;"><img src="' . CUSTOME_UAM_URL . '/img/edit.png" id="edit-subrole-trigger" class="editsubroletrigger" onclick="showEditForm(\'' . $role_slug . '\', \'' . $subrole . '\', \'' . $rolename . '\')" style="width:18px; height:18px;" alt="Edit Sub Role"></a>';
                                        echo '<img src="' . CUSTOME_UAM_URL . '/img/delete.png" id="deletesubrole" style="width:16px; padding-right:10px; left:0px; height:18px;"class="pull-right remove-subrole deletesubroletrigger" alt="Delete" />';
                                    echo '</span>';

                                echo '</div>';
                            echo '</li>';
		}
                    }
                    echo '</ul>'; // End subroles list 
                    echo '</li>';
                }
	}
	echo '</ul>';
?>
</div>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <div style="width: 58%; float: left; margin-left: 5px;">
            <h3 style="text-align: left; color: #000000; font-weight: bold; font-size: 20px; margin-left: 10px; margin-top: 5px; margin-bottom: 38px;"><?php echo __("Permissions", 'custom-uam'); ?></h3>
            <div style="background: #e6e5e5; height: 80vh; margin-left: 5px;">
                <ul id="c_uam_cap_ul" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
	<?php 
                    $cap = [
                        // ['k' => 'c_uam_cap_3d_picture', 't' => 'Image info'],
                        // ['k' => 'c_uam_cap_download_file', 't' => 'Download Files'],
                        // ['k' => 'c_uam_cap_newsletter', 't' => 'Newsletter'],
                        // ['k' => 'c_uam_cap_press_release', 't' => 'Press Release'],
                        ['k' => 'c_uam_cap_reseller_price', 't' => 'Reseller Price'],
                        ['k' => 'c_uam_cap_instruction_pdf', 't' => 'Instruction PDF'],
                        ['k' => 'c_uam_cap_data_sheet', 't' => 'Product Datasheet'],
                        ['k' => 'c_uam_cap_price', 't' => 'End User Price'],
                        ['k' => 'c_uam_cap_accessories', 't' => 'Accessories'],
                        ['k' => 'c_uam_cap_spare_parts', 't' => 'Spare Parts'],
                        ['k' => 'c_uam_cap_diagram', 't' => 'Diagram'],
                        ['k' => 'c_uam_cap_step_file', 't' => 'Step File'],
                        ['k' => 'c_uam_cap_group_info', 't' => 'Group Info'],
		]; 

                    foreach ($cap as $index => $v) :
                        $key = $v['k'];
                        $value = $v['t'];
                    ?>
                        <li style="display: flex; align-items: center; background-color:#FFFFFF;">
                            <!-- Value column -->
                            <div style="flex-grow: 1;">
                                <span style="color: #000000; font-weight: medium;"><?php echo $value; ?></span>
                            </div>
                            <!-- Switch column -->
                            <div style="text-align: right;">
                                <label class="switch">
                                    <input type="checkbox" class="c_uam_cap_cls" name="c_uam_cap" id="<?php echo $key; ?>" value='<?php echo $key; ?>'>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </li>
                    <?php endforeach; ?>
</ul>
            </div>
<div style="text-align: center;">
                <button class="button button-primary" style="display: none; margin: 5px auto;" id="custom_uam_cap_save_btn">Save</button>
</div>
</div>
</div>
<div id="custom-uam-alert-add-edit-dlg" style="display:none">	
        <div style="text-align: center;">
            <p id="custom-uam-alert-edit-txt" style="margin-left:-160px; font-weight:bold;"><?php echo esc_html_e('Role Name', 'custom-uam'); ?></p>
            <p id="custom-uam-alert-add-txt" style="display: none;"><?php echo esc_html_e('Edit Role', 'custom-uam'); ?></p>
		 <p style="color:red;display: none;" id="custom-uam-alert-error-txt"></p>
		  <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_role">
                <input style="width:90%; margin-bottom:30px;" type="text" placeholder="Enter Role Name" name="role_name" id="custom-uam-input-role-name">
		    <!-- Default Submit Button -->
		    <input type="hidden" name="action" value="custom_uam_save_role" />
		    <input type="hidden" name="id" value="0" id="custom-uam-input-role-id" />
                <input type="submit" name="submit" id="submit" class="button button-primary" style="padding-left:20px; padding-right:20px; font-size:14px;" value="<?php echo __('Add', 'custom_uam'); ?>" />
            </form>
        </div>
    </div>

    <div id="custom-uam-alert-subadd-subedit-dlg" style="display:none">
        <div style="text-align: center;">
            <p id="custom-uam-alert-subedit-txt" style="margin-left:-130px; font-weight:bold;"><?php echo esc_html_e('Sub Role Name', 'custom-uam'); ?></p>
            <p id="custom-uam-alert-subadd-txt" style="display: none;"><?php echo esc_html_e('Edit Role', 'custom-uam'); ?></p>
            <p style="color:red;display: none;" id="custom-uam-alert-suberror-txt"></p>
            <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_subrole">
                <input style="width:90%; margin-bottom:30px;" type="text" placeholder="Enter SubRole Name" name="subrole_name" id="custom-uam-input-subrole-name">
                <!-- Default Submit Button -->
                <input type="hidden" name="action" value="custom_uam_save_subrole" />
                <input type="hidden" name="id" value="0" id="custom-uam-input-subrole-id" />
                <input type="submit" name="submit" id="submit" class="button button-primary" style="padding-left:20px; padding-right:20px; font-size:14px;" value="<?php echo __('Add', 'custom_uam'); ?>" />
            </form>
        </div>
    </div>

    <div id="subrole-edit-dlg" style="display: none;">
        <div style="text-align: center;">
            <p style="color: red; display: none;" id="custom-uam-subrolealert-error-txt"></p>
            <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_update_subrole">
                <!-- Role selection fields -->
                <div id="updatesubrole-file" style="margin-top: 20px;">
                    <input style="margin-top: 10px; margin-bottom: 40px;" type="text" placeholder="SubRole title" name="subrole_name" id="customsubrole-value">
                </div>

                <!-- Default Submit Button -->
                <input type="hidden" name="clicked_subrole" id="clicked-subrole-value">
                <input type="hidden" name="clicked_roleid" id="clicked-roleid-value">
                <input type="hidden" name="clicked_subroleid" id="clicked-subroleid-value">
                <input type="submit" style="margin-bottom: 30px;" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save', 'custom_uam'); ?>" />
		  </form>
	</div>
</div>

<div id="custom-uam-overflow-action-dlg">
	<span style="position: absolute;
    top: -10px;
    left: 0px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;"></span>
	<ul>
            <li data-id="edit"><?php echo __('Edit', 'custom_uam'); ?></li>
            <li data-id="remove"><?php echo __('Remove', 'custom_uam'); ?></li>
	</ul>
	<span class="dashicons dashicons-no-alt" id="custom-uam-overflow-action-dlg-close"></span>
</div>
</div>

<script>
    var role_form_txt = "<?php esc_html_e('Add Role', 'custom-uam'); ?>";
    var role_form_edit_txt = "<?php esc_html_e('Role Edit Form', 'custom-uam'); ?>";
    var role_remove_txt = "<?php esc_html_e('Are you sure want to remove', 'custom-uam'); ?>";
    var custom_uam_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<script>
    document.addEventListener('click', function(event) {
        event.stopPropagation();
        var subroleDlg = document.getElementById('subrole-edit-dlg');
        var trigger = document.getElementById('edit-subrole-trigger');

        // Check if the clicked element is not a descendant of the dialog or the trigger
        if (event.target !== subroleDlg && !subroleDlg.contains(event.target) && event.target !== trigger) {
            // Close the dialog
            subroleDlg.style.display = 'none';
        }
    });

    function showEditForm(roleId, subrole, rolename) {
        var form = document.getElementById('subrole-edit-dlg');
        document.getElementById('customsubrole-value').value = rolename;
        jQuery('#clicked-subrole-value').val(rolename);
        jQuery('#clicked-subroleid-value').val(subrole);
        jQuery('#clicked-roleid-value').val(roleId);
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleLiElements = document.querySelectorAll('.custom-uam-role-li');

    roleLiElements.forEach(roleLi => {
        roleLi.addEventListener('click', function () {
            // Remove active class from all role elements
            roleLiElements.forEach(element => {
                element.classList.remove('active');
                element.style.border = 'none'
            });

            // Add active class to the clicked role element
            this.classList.add('active');

            const subroles = this.querySelector('.subroles'); // Access subroles relative to clicked roleLi

            const isActive = this.classList.contains('active');
            // Toggle subroles list display and arrow icons based on active state
            if (isActive && subroles && (subroles.style.display === 'block')) {
                subroles.style.display = 'block';
                const arrowIcons = document.querySelectorAll('.active-arrow');
                arrowIcons.forEach(arrowIcon => {
                    arrowIcon.classList.remove('dashicons-arrow-down-alt2');
                    arrowIcon.classList.add('dashicons-arrow-up-alt2');
                });
            } else {
                const subrolesLists = document.querySelectorAll('.subroles');
                subrolesLists.forEach(subrolesList => {
                    subrolesList.style.display = 'none';
                });
                const arrowIcons = document.querySelectorAll('.active-arrow');
                arrowIcons.forEach(arrowIcon => {
                    arrowIcon.classList.remove('dashicons-arrow-up-alt2');
                    arrowIcon.classList.add('dashicons-arrow-down-alt2');
                });
            }
        });
    });
});
    document.addEventListener("DOMContentLoaded", function() {
        
        var activeArrows = document.querySelectorAll('.active-arrow');
        activeArrows.forEach(function(arrow) {
            arrow.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent event from bubbling to parent li
                var subroles = this.parentNode.querySelector('.subroles');
                if (subroles.style.display === 'block') {
                    subroles.style.display = 'none';
                    this.classList.remove('dashicons-arrow-up-alt2');
                    this.classList.add('dashicons-arrow-down-alt2');
                } else {
                    subroles.style.display = 'block';
                    this.classList.remove('dashicons-arrow-down-alt2');
                    this.classList.add('dashicons-arrow-up-alt2');
                }
            });
        });

        // Add event listener to prevent subrole list closure when clicked
    var subroleListItems = document.querySelectorAll('.subroles li');

subroleListItems.forEach(function(item) {
    item.addEventListener('click', function(event) {
        var subroles = this.closest('.subroles');
        subroles.style.display = 'block';// event.stopPropagation(); // Prevent event from bubbling to parent elements
    });
});
        
    });
</script>

<script>
jQuery(document).ready(function($) {
    $('body').on('click', '#editImg', function(e) {
        e.preventDefault(); // Prevent default link behavior
        var title=$(this).closest('.custom-uam-role-li').find('.custom-uam-role-li-title').text();
        // Extract role name and ID
        var roleName = $(this).closest('.custom-uam-role-li').find('.custom-uam-role-li-title').text();
        var roleID = $(this).closest('.custom-uam-role-li').attr('data-id');
        console.log('roleID',roleID);
        console.log('roleName',roleName);
        // Set the role name and ID in the dialog form
        $("#custom-uam-input-role-name").val(roleName);
        $("#custom-uam-input-role-id").val(roleID);

        // Set the clicked role_data value in a data attribute of the form
        $('#custom_uam_save_role').data('clicked-role-data', roleID);
        $('#custom_uam_save_role').data('clicked-rolename-data', roleName);

        //Show the dialog
        tb_show('<?php echo esc_html__('Edit Role', 'custom-uam'); ?>', '#TB_inline?&width=300&height=200&inlineId=custom-uam-alert-add-edit-dlg');
    });

$('.add-sub-role-link').click(function(e) {
    e.preventDefault(); // Prevent default link behavior

    // Get the clicked role_data value
    var clickedRoleData = $(this).data('role');
    var clickedRolenameData = $(this).data('rolename');

    // Set the clicked role_data value in a data attribute of the form
    $('#custom_uam_save_subrole').data('clicked-role-data', clickedRoleData);
    $('#custom_uam_save_subrole').data('clicked-rolename-data', clickedRolenameData);

    // Show the dialog
    tb_show('<?php echo esc_html__('Add Sub Role', 'custom-uam'); ?>', '#TB_inline?&width=300&height=200&inlineId=custom-uam-alert-subadd-subedit-dlg');

});

$("#custom_uam_save_subrole").submit(function(e){
    e.preventDefault();
    var form = $(this);
    var roleissubrole = 1;
    var role = $("#custom-uam-input-subrole-name").val();
    var clickedRoleData = form.data('clicked-role-data'); // Get the clicked role_data value from the form's data attribute
    var clickedRolenameData = form.data('clicked-rolename-data');

    console.log('clickedRoleData', clickedRoleData);
    console.log('roleissubrole', roleissubrole); 
   
    if(role==''){
        $("#custom-uam-alert-suberror-txt").css("display","block").text("This field is required");
                return;
            }
            $("#custom-uam-alert-suberror-txt").css("display","none");
            var formData = {
            action: 'custom_uam_save_subrole',
            role_name: role,
            roleissubrole: roleissubrole,
            clickedRoleData:clickedRoleData,
            clickedRolenameData:clickedRolenameData
            };
                
            $.post(form.attr('action'), formData, function(data) {
                if(data.error){
                    $("#custom-uam-alert-suberror-txt").css("display","block").text(data.message);                    
                }else{
                    window.location.reload();
                }
        }, 'json');      
    });

});
</script>
