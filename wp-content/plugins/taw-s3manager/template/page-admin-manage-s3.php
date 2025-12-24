<div class="wrap">
    <h2>Update Media to AWS S3</h2>
    <div>
        <button id="taw-s3-btn-sync" data-type="title_desc" style="margin:15px 0;">
        <span class="dashicons dashicons-update"></span> 
        <span class="load-text">Sync Media to S3</span>
        </button>  

        <div id="taw-s3-info-sync" >Information <div class="msg"></div></div> 
    </div>
</div>
<style>
#taw-s3-btn-sync{
  cursor: pointer;
    display: block;
    font-size: 12px;
    text-decoration: none;
    border: solid 1px;
    padding: 10px 20px;
    margin:10px;
    font-size: 15px;
}

@keyframes rotate-half {
  50% {transform: rotate(180deg);}
}

#taw-s3-btn-sync.loading span{
    animation: rotate-half 5s infinite;        
}
</style>
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

