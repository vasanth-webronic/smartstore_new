<div class="fixed z-[99999] flex justify-center items-center bg-black bg-opacity-45 h-screen w-screen top-0 bottom-0 right-0 left-0" style="display: none;" id="container-psp-add-product-popup">
    <div class="bg-white p-6 rounded-lg shadow-lg w-2/5 relative">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-black">Add Products</h2>
            <button id="close-psp-add-product-popup" class="text-psp-red"><img class="w-6 rounded-full p-1 bg-psp-red" src="<?php echo PRODUCT_STACK_PRICING_URL; ?>img/MaterialSymbolsCancelRounded.svg" alt=""></button>
        </div>
        <div class="mb-4 ">
            <div class="flex gap-4">
                <input type="text" id="add-product-search-input" class="border border-gray-300 px-4 py-3 ml-3 flex-1 !rounded-xl" placeholder="Enter product Art no ...">
                <button class="bg-psp-blue text-white px-4 py-3  rounded-xl font-bold" id="art_add_popup">Add</button>
            </div>
            <div id="psp-search-results" class="relative w-full hidden shadow-md">
                <div class="rounded-lg art-search-item px-2 py-2 text-black bg-white mt-1 z-[999999999] absolute max-h-56 overflow-y-scroll psp-scroll border w-full !scrollbar-thin !scrollbar-thumb-rounded !scrollbar-thumb-gray-500 !scrollbar-track-gray-300">
                    <!-- Search results will be appended here -->
                </div>
            </div>
        </div>
        <div class="space-y-2 bg-psp-grey p-5 rounded h-[220px] overflow-y-scroll psp-scroll  product-card-popup-container">
            <div id="popup-product-card-nodatamsg" class="flex justify-center p-2 "> Add some products </div>
        </div>
        <div class="flex justify-center mt-6">
            <button id="popipAddProdSaveBTN" class="bg-psp-blue text-white py-2 px-6 rounded-lg">Save</button>
        </div>
    </div>
</div>