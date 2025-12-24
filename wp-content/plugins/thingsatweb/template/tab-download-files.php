<?php
defined( 'ABSPATH' ) || exit;

global $product;
$json = json_decode($product, true);
$metat_data = get_post_meta($json['id'], 'taw_prod_opt', true);
$article_price = $metat_data['article_price'];
if (array_key_exists("product_file_downloads", $article_price)) {

    $attachments = $article_price['product_file_downloads'];
    if(count($attachments) > 0):
        $download = THINGSATWEB_BASE . '/img/ic_download.svg'; ?>
        <div class="taw-tab-container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 mt-2 lg:mt-4">
                <?php foreach ($attachments as $key => $value): ?>
                    <div class="flex p-2 border items-center">
                        <div class="w-4 mx-0">
                                <img src=<?php echo $download; ?> alt="">
                        </div>
                        <a class="cursor-pointer text-black" href="/downloads/<?php echo $value['file']['id']; ?>/<?php echo $value['name']; ?>">
                            <span class="h-5 w-full overflow-hidden text-sm font-semibold text-center flex items-center ml-2"><?php echo $value['name']; ?></span>
                        </a>
                    </div>
                <?php endforeach;?> 
            </div>
        </div>
    <?php endif; ?>
    <?php
}