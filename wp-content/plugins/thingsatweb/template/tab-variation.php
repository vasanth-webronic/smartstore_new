<?php
defined( 'ABSPATH' ) || exit;

global $product;
global $wpdb;
$sku = $product->get_sku();
if(empty($sku)) return;
$sku = substr($sku,0,5);
$q = "SELECT post_id FROM `tsm_postmeta` WHERE meta_key='_sku' and meta_value LIKE '%$sku%'";
$result = $wpdb->get_results($q);
$products = array();

foreach ($result as $key => $value) {
    if($product->get_id() != $value->post_id){
        $products[] = wc_get_product($value->post_id);
    }
}
?>

<?php if(count($products) == 0) return; ?>

<div class="taw-tab-container grid grid-cols-1 md:grid-cols-2 gap-3 lg:block lg:gap-0">
    <div class="hidden lg:flex mt-3 bg-[#374151] text-[14px] text-[#d7d7d7]">
        <span class="w-[70px] xl:w-[68px]"></span>
        <div class="w-full flex mx-4">
            <div class="w-1/5 flex items-center border-r-[3px] border-r-[#fff] !px-[10px] !py-[5px]">
                <span class="font-normal">Article number</span>
            </div>
            <div class="w-1/5 flex items-center border-r-[3px] border-r-[#fff] !px-[10px] !py-[5px]">
                <span class="font-normal">Farg</span>
            </div>
            <div class="w-1/5 flex items-center border-r-[3px] border-r-[#fff] !px-[10px] !py-[5px]">
                <span class="font-normal">Extension</span>
            </div>
            <div class="w-1/5 flex items-center border-r-[3px] border-r-[#fff] !px-[10px] !py-[5px]">
                <span class="font-normal">Load capacity</span>
            </div>
            <div class="w-1/5 flex items-center !px-[10px] !py-[5px]">
                <span class="font-normal">Price</span>
            </div>
        </div>
        <span class="w-[80px] xl:w-[78px]"></span>
    </div>
<?php foreach ($products as $key => $value):?>
    <?php 
        $attributes = $value->get_attributes();
        $frameColor = "-";
        $pa_frame_color = $attributes["pa_frame-color"] ?? 0;
        if(!empty($pa_frame_color)){
            $terms = $pa_frame_color->get_terms();
            $frame_color = wp_list_pluck($terms, 'name');
            $frameColor = $frame_color[0];
        }
        $loadCapacity = "-";
        $pa_load = $attributes["pa_load"] ?? 0;           
        if(!empty($pa_load)){
            $terms = $pa_load->get_terms();
            $load_capacity= wp_list_pluck($terms, 'name');
            $loadCapacity = $load_capacity[0];
        }
        $extension = "-";
        $pa_extension = $attributes["pa_extension"] ?? 0;           
        if(!empty($pa_extension)){
            $terms = $pa_extension->get_terms();
            $m_extension = wp_list_pluck($terms, 'name');
            $extension = $m_extension[0];
        }
    ?>
    <div class="border rounded-md lg:rounded-none p-3 lg:flex mt-2"> 
        <?php
          $image = get_the_post_thumbnail_url($value->get_id());
          $default = wc_placeholder_img_src(120);
          if(empty($image)) { $image = $default; }
        ?>
        <img class="!max-w-[120px] sm:!max-w-[130px] md:!max-w-[120px] lg:!max-w-[56px] !h-[120px] sm:!h-[130px] md:!h-[120px] lg:!h-[56px] my-auto mx-auto" src=<?php  echo $image;?>
        onerror="this.onerror=null;this.src='<?php echo $default;?>'">
        <!-- SMALL SCREEN-->
        <div class="sm:px-4 md:px-0 lg:hidden">
            <div class="flex mt-3">
                <div class="w-1/2 px-1">
                    <span class="text-[15px] md:text-[13px] font-normal text-center">Article number</span>
                </div>
                <div class="px-1"><span class="text-[15px] md:text-[13px]">:</span></div>
                <div class="w-1/2 flex px-1">
                    <a href="<?php echo $value->get_permalink();?>" >  
                        <span class="w-full text-[13px] md:text-[11px] font-bold text-red-600 break-all"><?php echo $value->get_sku(); ?></span>
                    </a>
                </div>
            </div>
            <div class="flex mt-1">
                <div class="w-1/2 px-1">
                    <span class="text-[15px] md:text-[13px] font-normal text-center">Farg</span>
                </div>
                <div class="px-1"><span class="text-[15px] md:text-[13px]">:</span></div>
                <div class="w-1/2 flex px-1">
                    <span class="font-bold text-[15px] md:text-[13px] break-all my-auto"><?php echo $frameColor; ?></span>
                </div>
            </div>
            <div class="flex mt-1">
                <div class="w-1/2 px-1">
                    <span class="text-[15px] md:text-[13px] font-normal text-center">Extension</span>
                </div>
                <div class="px-1"><span class="text-[15px] md:text-[13px]">:</span></div>
                <div class="w-1/2 flex px-1">
                    <span class="font-bold text-[15px] md:text-[13px] break-all"><?php echo $extension; ?></span>
                </div>
            </div>
            <div class="flex mt-1">
                <div class="w-1/2 px-1">
                    <span class="text-[15px] md:text-[13px] font-normal text-center">Load capacity</span>
                </div>
                <div class="px-1"><span class="text-[15px] md:text-[13px]">:</span></div>
                <div class="w-1/2 flex px-1">
                    <span class="font-semibold text-[15px] md:text-[13px] break-all"><?php echo $loadCapacity; ?></span>
                </div>
            </div>
            <div class="flex mt-1">
                <div class="w-1/2 px-1">
                    <span class="text-[15px] md:text-[13px] font-normal text-center">Price</span>
                </div>
                <div class="px-1"><span class="text-[15px] md:text-[13px]">:</span></div>
                <div class="w-1/2 flex px-1">
                    <a href="<?php echo $value->get_permalink();?>" >  
                        <span class="font-semibold text-red-600 text-[15px] md:text-[11px] break-all"><?php $price=$value->get_price_html();
                        echo product_price($price)?></span>
                    </a>
                </div>
            </div>
        </div>
        <!-- LARGE SCREEN-->
        <div class="w-full hidden lg:flex lg:mx-4">
            <div class="w-full lg:w-1/5 flex items-center justify-center lg:justify-start px-1">
                <a href="<?php echo $value->get_permalink();?>" >  
                    <span class="w-full text-[13px] font-semibold text-red-600 break-all"><?php echo $value->get_sku(); ?></span>
                </a>
            </div>
            <div class="w-full lg:w-1/5 flex items-center justify-center lg:justify-start px-1">
                <span class="font-semibold text-[15px] break-all"><?php echo $frameColor; ?></span>
            </div>
            <div class="w-full lg:w-1/5 flex items-center justify-center lg:justify-start px-1">
                <span class="font-semibold text-[15px] break-all"><?php echo $extension; ?></span>
            </div>
            <div class="w-full lg:w-1/5 flex items-center justify-center lg:justify-start px-1">
                <span class="font-semibold text-[15px] break-all"><?php echo $loadCapacity; ?></span>
            </div>
            <div class="w-full lg:w-1/5 flex items-center justify-center lg:justify-start px-1">
                <a href="<?php echo $value->get_permalink();?>" >  
                    <span class="font-semibold text-red-600 text-[15px] break-all"><?php $price=$value->get_price_html();
                        echo product_price($price);?></span>
                </a>
            </div>
        </div>
        <a class="my-auto" href="<?php echo $value->get_permalink();?>" >  
            <button class="mt-4 lg:mt-0 text-sm lg:text-xs bg-red-600 w-[88px] h-8 lg:w-16 lg:h-6 text-white rounded-full hover:bg-black mx-auto lg:ml-auto flex items-center justify-center"><?php echo product_buy_or_quote($price);?></button>
        </a>
    </div>
<?php endforeach;?>

</div>

<?php




?>

