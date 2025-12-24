<?php 

global $product;
$meta = get_post_meta($product->get_ID(), 'taw_prod_opt');

if(!empty($meta)&&!empty($meta[0]['customised_product_setting'])){
    $meta=$meta[0];
    include_once(THINGSATWEB_DIR . '/template/page-customize-product.php');
}else{
    include_once(THINGSATWEB_DIR . '/template/page-single-product.php');
}