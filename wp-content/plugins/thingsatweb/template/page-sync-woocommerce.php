<div class="wrap">
    <h2>Sync to Woocommerce</h2>

    <p>It will sync data to <b><?php echo getSiteCurrentLang();?></b> language products</p>
    <button class="btn-syncri" data-type="woocomm_data" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">1. Sync Woocommercedata </span>
    </button>
    <div id="response-message" style="display: none;"></div>
</div>
<style>
.btn-syncri{
    float: left;
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

.btn-syncri.loading span{
    animation: rotate-half 5s infinite;        
}
</style>
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

