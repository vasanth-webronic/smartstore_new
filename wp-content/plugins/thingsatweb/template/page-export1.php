<div class="wrap">
    <h2>Export Data</h2>


<a style="float: left;
    display: block;
    font-size: 12px;
    text-decoration: none;
    border: solid 1px;
    padding: 10px 20px;
    font-size: 15px;" class="thickbox" name="Export Product" href="#TB_inline?&width=400&height=300&inlineId=taw-prod-import"> Export</a>
  
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
  
</script>
<?php
  $lang=getSiteCurrentLang();
?>
<div id="taw-prod-import" style="display:none;">
  <form action="<?php echo admin_url( 'admin-ajax.php' );?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="export_product">
      <input type="hidden" name="taw_nonce" value="<?php echo wp_create_nonce( 'taw_security' );?>">
    <div style="padding: 15px 0 5px 0;">
        <label style="padding: 5px 0;display:block">Export File</label>
        <select name="export_type" id="taw-prod-form-import-type" style="width:100%;">
            <option>Price</option>
            <option value="Category">Category for <?php echo $lang;?></option>
            <option>Attributes</option>  
            <option>Customers</option>
            <option>Customers Unique Price</option>
            <option value="Pictures">Pictures for <?php echo $lang;?></option>
            <option value="Title and Description">Title and Description for <?php echo $lang;?></option>
        </select>
    </div> 
    
    <input class="button-primary" type="submit" value="<?php _e('Export', TAW_TEXT_DOMAIN); ?>">
</div>  
</form>
<script>
    var taw_ajaxurl="<?php echo admin_url( 'admin-ajax.php' );?>";
    var taw_nonce="<?php echo wp_create_nonce( 'taw_security' );?>";
</script>