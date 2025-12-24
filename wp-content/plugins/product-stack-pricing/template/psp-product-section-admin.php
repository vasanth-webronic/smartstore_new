<div class="p-4 col-span-2">

    <div class="flex mb-4  justify-between items-center">
        <div class="flex items-center">
            <button id="psp-product-add-btn" class="bg-psp-blue text-white px-4 py-1 rounded font-bold  hover:bg-blue-900">+ Add Products</button>
        </div>
        <div class="flex items-center gap-2">
            <div class="bg-psp-blue rounded-lg flex justify-center p-1 items-center text-white hover:bg-blue-900 cursor-pointer" id="psp_delete_button">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 12 12">
                    <path fill="currentColor" d="M5 3h2a1 1 0 0 0-2 0M4 3a2 2 0 1 1 4 0h2.5a.5.5 0 0 1 0 1h-.441l-.443 5.17A2 2 0 0 1 7.623 11H4.377a2 2 0 0 1-1.993-1.83L1.941 4H1.5a.5.5 0 0 1 0-1zm3.5 3a.5.5 0 0 0-1 0v2a.5.5 0 0 0 1 0zM5 5.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5M3.38 9.085a1 1 0 0 0 .997.915h3.246a1 1 0 0 0 .996-.915L9.055 4h-6.11z" />
                </svg>
            </div>
            <div class="bg-psp-blue relative rounded-lg flex justify-center p-1 items-center text-gray-400 hover:bg-blue-900 cursor-pointer" id="psp_edit_edit_button">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 576 512">
                    <path fill="currentColor" d="m402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6m156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8M460.1 174L402 115.9L216.2 301.8l-7.3 65.3l65.3-7.3zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1l30.9-30.9c4-4.2 4-10.8-.1-14.9" />
                </svg>
                <span class="psp-tooltip">
                <span class="psp-tooltip-arrow"></span>
                        Select product to go to edit page
                    </span>
            </div>
        </div>
    </div>

    <div class="p-6 bg-psp-grey rounded-md">
        <div class="relative w-full">
            <input type="search" id="psp-product-default-search" disabled class="block w-full focus:outline-none pl-4 pr-10 py-4 text-sm text-gray-900  !rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Article Number..." required />
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-4 h-4 text-psp-blue " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
        </div>

        <div class="space-y-2 my-4 h-[400px] overflow-y-scroll psp-scroll" id="psp-added-product-container">

            <!-- added product container -->
            <?php include PRODUCT_STACK_PRICING_PATH . 'template/psp-added-product-container.php'; ?>

        </div>

    </div>

</div>

<style>
    #psp-product-default-search[disabled] {
    cursor: not-allowed;
}
</style>