
<style type="text/css">
	.dataTables_wrapper .dataTables_length select{
		width: 50px;
}
	.custom_user_request_table {
        border-collapse: collapse;
        width: 100%;
    }

    .custom_user_request_table th, .custom_user_request_table td {
        border: 1px solid #ddd; /* Border color */
        padding: 8px; /* Padding between content and border */
        text-align: left;
    }

    .custom_user_request_table th {
        background-color: #f2f2f2; /* Header background color */
	}
</style>
<div class="wrap">
	<h3>Manage user requests</h3>
	<p style="color:red;display: none;" id="custom-uam-alert-error-txt"></p>
<table id="custom_user_request_table" class="custom_user_request_table">
	<thead style="text-align: left;">
		<tr>
			<th>Date</th>
			<th>Business</th>
			<th>Name</th>
			<th>Role</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		global $wpdb;

		$q = "SELECT * FROM  custom_uam_user_requests";

		$data=$wpdb->get_results( $q ); 
		
		foreach ($data as $v):
		?>
		<tr data-id="<?php echo $v->id;?>">
			<td><?php echo $v->time;?></td>
			<td>
				<?php echo $v->business;?>
			</td>
			<td>
				<?php echo $v->name;?>
			</td>
			<td>
				<?php echo $v->role;?>
			</td>
			<td>
				<?php echo $v->email;?>
			</td>
			<td>
				<?php echo $v->phone;?>
			</td>
			<td>
				<button class="custom_user_request_accept">Accept</button>
				<!-- <button class="custom_user_request_delete">Delete</button> -->
				<button class="custom_user_request_delete" data-id="<?php echo $v->id; ?>">Delete</button>
			</td>
			
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
</div>
<div id="custom-uam-alert-add-edit-dlg" style="display:none">	
	<div style="padding:30px 10px;text-align: center;">			
			<p style="color:red;display: none;" id="custom-uam-alert-error-txt-pwd"></p>
		  <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="custom_uam_save_user">
		  	<input type="password" placeholder="Enter user password" name="pwd" id="custom-uam-input-pwd">
		  	
		    <!-- Default Submit Button -->
		    <input type="hidden" name="action" value="custom_uam_accept_user" />
		    <input type="hidden" name="id" value="0" id="custom-uam-user-id" />
		    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save','custom_uam');?>" />	    
		   
		  </form>
	</div>
</div>
<script type="text/javascript">
	var cajax_url='<?php echo admin_url('admin-ajax.php'); ?>';

</script>