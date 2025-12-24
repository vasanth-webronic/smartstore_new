<div class="p-4  col-span-3">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold mb-4">Stack Rule</h2>

        <div style="display: none;" class="border mb-4 psp-prod-status-selector-container flex rounded-lg bg-white  h-[34px] w-full md:w-64 lg:w-72">
        <div
            id="all"
            
            class="px-2 w-1/3 flex items-center cursor-pointer psp-prod-status-selector justify-center border-r text-[12px] font-bold rounded-l-lg">
            All
        </div>
        <div
            id="active"
            
            class="px-2 w-1/3 flex items-center cursor-pointer psp-prod-status-selector justify-center border-r  text-[12px] font-bold">
            Active
        </div>
        <div
            id="inactive"
            
            class="px-2 w-1/3 flex cursor-pointer items-center psp-prod-status-selector justify-center text-[12px] font-bold rounded-r-lg">
            Inactive
        </div>
    </div>

        <button class="bg-psp-blue text-white px-4 py-1 rounded mb-4 font-bold hover:bg-blue-900" style="display: none;" id="psp-rule-add-btn">+ Add Rule</button>
    </div>

    <div class="p-6 bg-psp-grey rounded-md  h-[512px] overflow-y-scroll psp-scroll" id="psp-added-rule-container">
        
        <!-- added product container -->
        <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-added-rule-container.php'; ?>

    </div>
</div>

