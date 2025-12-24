<div class="wrap" id="psp-main-container-admin">
    <h1 class="!text-2xl !font-bold text-black !mb-4">Stack Pricing</h1>

        <!-- <div class="wrap">
            <form method="post" style="display: inline; margin : 0px 10px" enctype="multipart/form-data">
                <input type="submit" name="export_file" value="Export" class="button button-outline-primary" />
            </form>
            <form method="post" style="display: inline;" enctype="multipart/form-data">
                <button type="button" id="toggle-button" class="button button-outline-primary ">import</button>

                <div class="hidden" id="import-file-input" style="margin-top: 10px;">
                    <input type="file" name="import_file" accept=".xlsx, .xls" required/>
                    <?php // submit_button('upload'); ?>
                </div>

            </form>

            <script type="text/javascript">
                document.getElementById('toggle-button').addEventListener('click', function() {
                    var fileInput = document.getElementById('import-file-input');
                    if (fileInput.classList.contains('hidden')) {
                        fileInput.classList.remove('hidden');
                    } else {
                        fileInput.classList.add('hidden');
                    }
                });
            </script>
        </div> 

       <?php
        if (isset($_FILES['import_file'])) {
            $file_path = $_FILES['import_file']['tmp_name'];
            $log = Product_Stack_Pricing_Import_Export::import_data($file_path);
        ?>
            <div id="event-log-container" style="margin-top: 20px;">
                <div id="event-log-header">

                    <span>Import Log</span>
                    <div id="event-log-notifications">
                        <?php echo count($log) ?>
                    </div>
                </div>
                <div id="event-log-content">
                    <?php foreach ($log as $log_entry) { ?>
                        <pre id="event-log-msg-container"><div id="event-log-msg-text"><?php echo $log_entry; ?> </div>
		</pre>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>-->
            <h2 class="text-xl font-semibold  pl-4">Products</h2>

    <div class="grid grid-cols-5 gap-1">
        <!-- Products Section -->
        <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-product-section-admin.php'; ?>
        
        <!-- Stack Rule Section -->
        <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-stack-rule-admin.php'; ?>

    </div>

    <!-- add product popup Section -->
    <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-add-product-popup.php'; ?>

    <!-- add rule popup Section -->
    <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-add-rule-popup.php'; ?>

    <!-- delete confirmation Section -->
    <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-delete-rule-popup.php'; ?>
</div>
<style>
    /* WebKit browsers (Chrome, Safari, Edge) */
input::-webkit-search-cancel-button {
    -webkit-appearance: none;
    appearance: none;
}

/* Firefox */
input::-moz-search-clear-button {
    display: none;
}
</style>