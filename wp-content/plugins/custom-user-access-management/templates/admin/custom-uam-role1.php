<style type="text/css">
	ul#c_uam_cap_ul{
		padding: 10px;
	}
	ul#c_uam_cap_ul li {
    	padding: 10px;
	}
	ul.custom_role_ls{
		padding:10px;
		
	}
	ul.custom_role_ls li{
		padding:10px;
		cursor: pointer;
		position: relative;
		border-bottom: solid 1px #e6e5e5;		
	}

	ul.custom_role_ls li:hover,ul.custom_role_ls li.active{
		background:#e6e5e5;
	}

	span.active-arrow {
    	position: absolute;
    	right: 10px;
    	top: 10px;
    	display: none;
	}

	span.custom-uam-overflow-action{
		margin-right: 50px;
		float: right;
		color: #318cbe;
	}

	ul.custom_role_ls li:hover span.active-arrow,ul.custom_role_ls li.active span.active-arrow{
		display: block;
	}

	#custom-uam-overflow-action-dlg{
		width: 60px;
    	height: 50px;
		background: #fff;
		padding: 10px;
		position: absolute;
		display: none;
    	box-shadow: 2px 4px 5px #adadad;	
	}
	#custom-uam-overflow-action-dlg-close{
		position: absolute;
	    top: 2px;
	    right: 4px;
	    color: red;
	    cursor: pointer;

	}
	#custom-uam-overflow-action-dlg ul li{
		cursor: pointer;
		font-size: 14px;
	}

	#custom-uam-overflow-action-dlg ul li:hover{
		color: #318cbe;
	}

</style>
<div class="wrap">
	<div class="width:100%;float:left;">
	  	<p style="margin:0px; width: 50%;font-size:30px;float: left;"><?php esc_html_e( 'Manage Custom Roles','custom-uam' ); ?> </p>

		<div style="width:50%;float: left; text-align:right;height: 50px;"> 
			
			<a href="#TB_inline?&width=300&height=200&inlineId=custom-uam-alert-add-edit-dlg" title="<?php esc_html_e( 'Role Form','custom-uam' ); ?>" class="thickbox button button-primary">+ <?php esc_html_e( 'Add New Role','custom-uam' ); ?></a>	
		</div>

	 	<hr style="width: 100%;float: left;" />
	</div>
<div style="width: 80%;margin: 0px auto;padding-top: 30px;float: left;">
	
<div style="width: 45%;float:left;position: relative;">
	<?php 

	$exclude_role=[""];

	$roles_obj = new WP_Roles();

	$roles_names_array = $roles_obj->get_names();

	echo '<h3 style="text-align:center;">Custom User Roles</h3>';
	echo '<ul class="custom_role_ls" >';
	foreach ($roles_names_array as $key=>$role_name) {
		
		if(false !==strrpos($key,'custom_uam')){
			echo '<li data-id="'.$key.'" class="custom-uam-role-li"><span class="custom-uam-role-li-title">'.$role_name.'</span><span class="dashicons dashicons-arrow-right-alt2 pull-right active-arrow"></span>  <span class="dashicons dashicons-ellipsis pull-right custom-uam-overflow-action"></span></li>';
		}
	    
	}
	echo '</ul>';
?>

</div>

<div style="width: 45%;float:left;background:#e6e5e5;height: 100vh;">
	<h3 style="text-align: center;"><?php echo __("Role's Permission",'custom-uam');?></h3>
	<ul id="c_uam_cap_ul">

	<?php 
		$cap=[
			['k'=>'c_uam_cap_3d_picture','t'=>'Image info'],
			['k'=>'c_uam_cap_download_file','t'=>'Download Files'],
			//['k'=>'c_uam_cap_picture','t'=>'Picture Bank'],
			//['k'=>'c_uam_cap_reseller_page','t'=>'Reseller Page'],
			['k'=>'c_uam_cap_newsletter','t'=>'Newsletter'],
			['k'=>'c_uam_cap_press_release','t'=>'Press Release'],
			['k'=>'c_uam_cap_reseller_price','t'=>'Reseller Price'],
			['k'=>'c_uam_cap_instruction_pdf','t'=>'Instruction PDF'],
			['k'=>'c_uam_cap_data_sheet','t'=>'Product Datasheet'],
			['k'=>'c_uam_cap_price','t'=>'End User Price'],
			['k'=>'c_uam_cap_accessories','t'=>'Accessories'],
			['k'=>'c_uam_cap_spare_parts','t'=>'Spare Parts'],
			['k'=>'c_uam_cap_diagram','t'=>'Diagram'],
			//['k'=>'c_uam_cap_shoping_cart','t'=>'Shopping Cart']				
		]; 

		foreach ($cap as  $v):
			$key=$v['k'];
			$value=$v['t'];

	?>

		<li><input type="checkbox" class="c_uam_cap_cls" name="c_uam_cap" id="<?php echo $key; ?>" value='<?php echo $key; ?>'> <?php echo $value; ?> </li> 

	<?php endforeach;?>
</ul>
<div style="text-align: center;">
<button class="button button-primary" style="display: none;margin: 0px auto;" id="custom_uam_cap_save_btn">Save</button>
</div>
</div>
</div>

<div id="custom-uam-alert-add-edit-dlg" style="display:none">	
	<div style="padding:30px 10px;text-align: center;">
		 <p id="custom-uam-alert-edit-txt"><?php echo esc_html_e('Add Role','custom-uam');?></p>
		 <p id="custom-uam-alert-add-txt" style="display: none;"><?php echo esc_html_e('Edit Role','custom-uam');?></p>		
		 <p style="color:red;display: none;" id="custom-uam-alert-error-txt"></p>
		  <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_role">
		  	<input type="text" placeholder="Role title" name="role_name" id="custom-uam-input-role-name">
		    <!-- Default Submit Button -->
		    <input type="hidden" name="action" value="custom_uam_save_role" />
		    <input type="hidden" name="id" value="0" id="custom-uam-input-role-id" />
		    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save','custom_uam');?>" />	    
		   
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
		<li data-id="edit"><?php echo __('Edit','custom_uam');?></li>
		<li data-id="remove"><?php echo __('Remove','custom_uam');?></li>		
	</ul>
	<span class="dashicons dashicons-no-alt" id="custom-uam-overflow-action-dlg-close"></span>
</div>
</div>

<script>
	var role_form_txt="<?php esc_html_e( 'Role Form','custom-uam' ); ?>";
	var role_form_edit_txt="<?php esc_html_e( 'Role Edit Form','custom-uam' ); ?>";
	var role_remove_txt="<?php esc_html_e( 'Are you sure want to remove','custom-uam' ); ?>";
	var custom_uam_ajax_url='<?php echo admin_url('admin-ajax.php'); ?>';	
</script>
