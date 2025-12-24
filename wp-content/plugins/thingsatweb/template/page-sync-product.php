<div class="wrap">
    <h2>Sync to Woocommerce Products</h2>

    <p>It will sync data to <b><?php echo getSiteCurrentLang();?></b> language products</p>
    <button class="btn-sync" data-type="title_desc" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">1. Sync Title Desc </span>
    </button>

    <button class="btn-sync" data-type="price" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">2. Sync Price</span>
    </button>

    <button class="btn-sync" data-type="category" >
       <span class="dashicons dashicons-update"></span> <span class="load-text">3. Sync Category</span>
    </button>

    <button class="btn-sync" data-type="attributes" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">4. Sync Attributes</span>
    </button>

    <button class="btn-sync" data-type="image" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">5. Sync Images</span>
    </button>

    <button class="btn-sync" data-type="accessories" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">6. Sync Accessories</span>
    </button>

    <button class="btn-sync" data-type="customeruniqueprice" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">7. Sync Customeruniqueprice</span>
    </button>

    <button class="btn-sync" data-type="diagram" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">8. Sync Diagram</span>
    </button>

    <button class="btn-sync" data-type="spareparts" >
        <span class="dashicons dashicons-update"></span> <span class="load-text">9. Sync Spareparts</span>
    </button>
  
</div>
<style>
.btn-sync{
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

.btn-sync.loading span{
    animation: rotate-half 5s infinite;        
}
</style>
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

