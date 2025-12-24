<div class="wrap">
    <h2>Import Data</h2>


<a style="float: left;
    display: block;
    font-size: 12px;
    text-decoration: none;
    border: solid 1px;
    padding: 10px 20px;
    font-size: 15px;" class="thickbox" name="Import Product" href="#TB_inline?&width=400&height=300&inlineId=taw-prod-import">+ Import</a>
  
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';   
</script>

<div id="taw-prod-import" style="display:none;">
  <form action="<?php echo admin_url( 'admin-ajax.php' );?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="import_product">
      <input type="hidden" name="taw_nonce" value="<?php echo wp_create_nonce( 'taw_security' );?>">
    <div style="padding: 15px 0 5px 0;">
        <label style="padding: 5px 0;display:block">Import Type</label>
        <select name="import_type" id="taw-prod-form-import-type" style="width:100%;">
            <option>Price Update</option>
            <option value="Category Update">Category Update</option>
            <option>Attributes Update</option>  
            <option>Customer Update</option>
            <option>Customer Unique Price</option>   
            <option value="Picture Update">Picture Update</option>
            <option value="Title and Description">Title and Description</option>
            <option>Product Accessories</option>
        </select>
    </div>

    <div style="padding: 15px 0 5px 0;">
        <label style="padding: 5px 0;">Choose File (Spreedsheet)</label>
        <input style="margin:5px 0;width:100%;" name="import_file" type="file" id="taw-prod-form-file" />
    </div>
    <div style="padding: 15px 0 5px 0;margin-bottom:20px;">
    <label>All images are used in spreedsheet should be uploaded in <br/><a href="/wp-admin/upload.php">Media Library</a> before update here. </label>
    </div>
    <!-- <div style="padding: 15px 0 5px 0;margin-bottom:20px;">
        <label style="padding: 5px 0;">Select Image Folder</label>
        <input type="text" style="margin:5px 0;width:100%;" name="import_img_folder" id="taw-prod-form-img-folder"/> 
        <input type="file" style="margin:5px 0;width:100%;" name="import_img_folder[]" id="taw-prod-form-img-folder" webkitdirectory directory multiple/>
    </div> -->
    
    <input class="button-primary" type="submit" value="<?php _e('Save', TAW_TEXT_DOMAIN); ?>">
</div>  
</form>
<script>
    var taw_ajaxurl="<?php echo admin_url( 'admin-ajax.php' );?>";
    var taw_nonce="<?php echo wp_create_nonce( 'taw_security' );?>";
</script>