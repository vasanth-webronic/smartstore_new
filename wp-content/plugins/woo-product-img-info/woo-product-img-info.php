<?php

/**
 * Plugin Name: Woo Product Image Pointing Info
 * Plugin URI: https://webronic.com/
 * Description: Add pointing information to selected image that will be show on product page.
 * Version: 1.0.1
 * Author: Things at Web
 * Author URI: https://webronic.com
 * Text Domain: woo-proudct-img-info
 * Domain Path: /languages
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 * 
 */

/**
 * Woo Product Image Pointing Info
 *
 * Copyright (c) 2020 WEBRONIC
 *
 *
 * webronic is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     webronic
 * @version    1.0.1
 * @copyright  (c) 2020 webronic
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    woo-proudct-img-info
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


/**
 * Create the section beneath the products tab
 **/
if (!class_exists('myCustomFields')) {

    class myCustomFields
    {
        /**
         * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
         */
        var $prefix = '_wpii_';
        /**
         * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
         */
        var $postTypes = array("product");
        /**
         * @var  array  $customFields  Defines the custom fields available
         */

        /**
         * PHP 4 Compatible Constructor
         */
        function myCustomFields()
        {
            $this->__construct();
        }
        /**
         * PHP 5 Constructor
         */
        function __construct()
        {
            add_action('admin_menu', array($this, 'createCustomFields'));
            add_action('save_post', array($this, 'saveCustomFields'), 1, 2);

            add_action('admin_enqueue_scripts', array($this, 'wpii_load_wp_media_files'));
            add_action('woocommerce_product_after_tabs', array($this, 'loadProductSpareAccessories'));
        }

        function loadProductSpareAccessories()
        {
            $lang = getSiteCurrentLang();
            global $product;
            global $wpdb;
            //find accessories for product specific
            $parent_artno = $product->get_sku();
            $q = "SELECT acs_article, no_plates from taw_product_accessories WHERE parent_article='$parent_artno'";

            $results = $wpdb->get_results($q);
            $all_ids = [];
            $all_pnoplate = [];
            if (!empty($results)) {
                foreach ($results as $r) {
                    $pid = wc_get_product_id_by_sku($r->acs_article);
                    $pnoplate = $r->no_plates;
                    if (!empty($pid)) {
                        $all_ids[] = $pid;
                        $all_pnoplate[] = $pnoplate;
                    }
                }
            } else {
                $terms = get_the_terms($product->get_ID(), 'product_cat');
                foreach ($terms as $term) {
                    $product_cat_slug = $term->slug;
                    break;
                }

                // $acs_cat = "accessories-to-" . $product_cat_slug;

                $all_ids = get_posts(array(
                    'post_type' => 'product',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            // 'terms' => $acs_cat,
                            'operator' => 'IN',
                        )
                    ),
                ));
            }

            if (!empty($all_ids)) :
                if(current_user_can('c_uam_cap_accessories')):
?>

                <div class="flex bg-gray-100 p-3 items-center " id="tab-title-accessories" style="margin-top:30px">
                    <div class="w-2.5 h-auto">
                        <img src="/wp-content/plugins/thingsatweb//img/ic_forward_arrow.svg" alt="Arrow">
                    </div>
                    <?php if ($lang === 'en') { ?>
                        <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Accessories", "woo-proudct-img-info"); ?></h3>
                    <?php } elseif ($lang === 'sv') { ?>
                        <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Tillbehör", "woo-proudct-img-info"); ?></h3>
                    <?php } ?>
                    <div class="flex ml-auto items-center">
                        <div class="taw-tab-option w-3 h-auto mx-5" id="accessories" onclick="toggleAccessories()">
                            <img src="/wp-content/plugins/thingsatweb/img/ic_minus.svg" alt="Arrow">
                        </div>
                    </div>
                </div>

                <div id="spare_list" class="acc_list_table hidden md:block lg:block" style="margin-top:15px;">
                    <div class="t_heading">
                        <div style="width:200px;border-right: solid 3px #fff;"><?php if ($lang === 'en') { ?>Product<?php } elseif ($lang === 'sv') { ?>Produkt<?php } ?></div>
                        <div style="width:200px;border-right: solid 3px #fff;"><?php if ($lang === 'en') { ?>Article Number<?php } elseif ($lang === 'sv') { ?>Artikelnummer<?php } ?></div>
                        <div style="width:calc(100% - 600px);"><?php if ($lang === 'en') { ?>Product Name<?php } elseif ($lang === 'sv') { ?>Produktnamn<?php } ?></div>
                    </div>
                    <?php
                    foreach ($all_ids as $key => $id) :
                        $acc = wc_get_product($id);
                        $acp = getCurrentLangProductBySku($acc->get_sku(), $lang);
                        if (empty($acp)) {
                            continue;
                        }
                        $p = wc_get_product($acp->get_id());
                        $product_id = $id; // Set the product ID
                        $product_qty = 1;
                    ?>
                        <div class="t_row" data-key="<?= $key + 1; ?>">
                            <div class="t_img" style="width:200px; margin-top: 20px ; margin-left:10px;">
                                <?php echo $p->get_image(apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail'), [], true); ?>
                            </div>
                            <div class="t_art_num" style="width:200px; margin-top: 20px ;">
                                <?php echo $p->get_sku(); ?>
                            </div>
                            <div class="t_title" style="width:calc(100% - 600px);height:100px;overflow:hidden;margin-top: 20px ;">
                                <?php echo $p->get_title(); ?>
                            </div>
                            <div style="color:#cc071c;width:160px;text-align:right; margin-top: 20px ;">
                                <?php
                                $palletqty = isset($all_pnoplate[$key]) ? $all_pnoplate[$key] : 0;
                                ?>
                                <span class="add_to_cart" data-qty="<?php echo $palletqty; ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo $acp->get_id(); ?>">
                                    +</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- <head>
                    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.css" />
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.min.js"></script>
                </head> -->
                <div class="slick-carousel" style="margin: 0px 20px;" id="slick-carouseltable">
                    <?php
                    foreach ($all_ids as $key => $id) :
                        $acc = wc_get_product($id);
                        $acp = getCurrentLangProductBySku($acc->get_sku(), $lang);
                        if (empty($acp)) {
                            continue;
                        }
                        $p = wc_get_product($acp->get_id());
                        $product_id = $id; // Set the product ID
                        $product_qty = 1;
                    ?>
                        <div class="w-full md:hidden lg:hidden">
                            <div class="flex">
                                <div class=" mx-auto border mt-2 rounded-md cardCarousel">

                                    <div class="!w-auto flex items-center justify-center c_img" style="height:120px; margin-top:10px;">
                                        <?php
                                        $image = get_the_post_thumbnail_url($p->get_id());
                                        $default = wc_placeholder_img_src(200);
                                        if (empty($image)) {
                                            $image = $default;
                                        }
                                        ?>
                                        <img class=" mx-auto" style="width: 120px; height: 100px;" src=<?php echo $image; ?> onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
                                    </div>
                                    <h3 class="w-full h-30 text-red-600 font-bold text-16 text-center mt-5 c_art_num" style="font-size:20px;"><?php echo $p->get_sku(); ?></h3>
                                    <h3 class="w-full h-30 text-sm font-semibold text-center mt-3 c_title"><?php echo $p->get_title() ?></h3>
                                    <div class="mt-2" style="margin-top:10px;padding:10px;color:#cc071c;text-align:center">
                                        <?php
                                        $palletqty = isset($all_pnoplate[$key]) ? $all_pnoplate[$key] : 0;
                                        ?>
                                        <span class="add_to_cart_mob" style="background:#cc061c; height:30px; width:30px; display:inline-block; line-height:32px; cursor:pointer; color:#fff; font-size:30px; border-radius:50%" data-qty="<?php echo $palletqty; ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo $acp->get_id(); ?>">+</span>
                                    </div>

                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; endif; 
            ?>
            <link rel='stylesheet' id='spare-css' href='/wp-content/plugins/woo-product-img-info/css/style.css' media='all' />
            <script src='/wp-content/plugins/woo-product-img-info/spare.js' id='spare-core-js'></script>
            <?php $this->loadProductAcs();
        }

        function loadProductAcs()
        {
            $lang = getSiteCurrentLang();
            global $product;
            $your_img_src = "";
            //if(current_user_can('c_uam_cap_3d_picture')){
            $field = '_wpii_prod_img_pointer_info';
            $item = get_post_meta($product->get_id(), $field);

            if (!empty($item)) {
                $field = '_wpii_prod_img_pointer_info';
                $item = get_post_meta($product->get_id(), $field);
                if (!empty($item)) {
                    $item = json_decode($item[0], true);
                }

                $list = [];
                if (!empty($item['data'])) {
                    $list = $item['data'];
                }

                $your_img_src_id = "";
                if (!empty($item['imgId'])) {
                    $your_img_src_id = $item['imgId'];
                    $your_img_src = wp_get_attachment_image_src($your_img_src_id, 'full');
                    if (!empty($your_img_src)) {
                        $your_img_src = $your_img_src[0];
                    }
                }
            }

            if (!empty($your_img_src)) :
                if(current_user_can('c_uam_cap_spare_parts')):
            ?>
                <div class="product-img-info-hld " style="margin-top:10px;">

                    <div class="flex bg-gray-100 p-3 items-center mt-3 lg:mt-0" id="tab-title-spareparts">
                        <div class="w-2.5 h-auto">
                            <img src="/wp-content/plugins/thingsatweb/img/ic_forward_arrow.svg" alt="Arrow">
                        </div>
                        <?php if ($lang === 'en') { ?>
                            <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Spare Parts", "woo-proudct-img-info"); ?></h3>
                        <?php } elseif ($lang === 'sv') { ?>
                            <h3 class="font-semibold ml-2 text-lg my-auto"><?php echo __("Reservdelar", "woo-proudct-img-info"); ?></h3>
                        <?php } ?>
                        <div class="flex ml-auto items-center">
                            <div class="taw-tab-option w-3 h-auto mx-5" id="spareparts" onclick="togglespareparts()">
                                <!-- <div class="taw-tab-option w-3 h-auto mx-5 lg:hidden" id="spareparts" onclick="clickEvent(this)"> -->
                                <img src="/wp-content/plugins/thingsatweb/img/ic_minus.svg" alt="Arrow">
                            </div>
                        </div>
                    </div>
<div id="spare_img" class="hidden xl:block" style="border: solid transparent; margin: 0 auto 0 auto; width:<?php echo 840 * 1.4; ?>px; height: <?php echo 600 * 1.4; ?>px; background-image: url('<?php echo $your_img_src; ?>'); background-repeat: no-repeat; background-position: center; background-size: 90% auto; position: relative;">
                        <?php
                        $groupedData = [];
                        $indexByKey = [];
                        foreach ($list as $item) {
                            $txt = $item['txt'];
                            if (!isset($indexByKey[$txt])) {
                                $indexByKey[$txt] = count($indexByKey) + 1;
                            }
                            $key = $indexByKey[$txt];
                            $groupedData[$key][] = $item;
                        }
                        foreach ($groupedData as $key => $group) :
        foreach ($group as $item) :
            if ($item['left'] != 0) :
                ?>
                            <div class="badgee" style="cursor:pointer; padding-top:3px; height:30px; width:30px; position:absolute;<?php echo 'top:' . ($item['top'] * 1.4) . 'px;left:' . ($item['left'] * 1.4) . 'px'; ?>" data-key="<?= $key; ?>" alt="<?php echo $item['txt']; ?>" onclick="scrollToSection(<?php echo $key; ?>)">
                    <?= $key; ?>
                                <span style="display: none; width: 300px; color: white; padding: 10px; margin-left: -59px; margin-top: 17px; position: absolute; text-align: center; bottom: 45px; background: rgb(204, 6, 30); border: 2px solid; box-shadow: rgb(90, 90, 90) 1px 1px 10px;"> 
                                    <?php echo $item['txt']; ?>
                                    <span style="width: 0; height: 0; border-left: 25px solid transparent; border-right: 0 solid transparent; border-top: 20px solid #cc061e; position: absolute; bottom: -20px; left: 30px;"></span>
                                    </span>
</div>
            <?php
            endif;
        endforeach;
    endforeach;
    ?>
</div>



<div id="spare_img_mob" class="md:h-[400px] xl:hidden" style="border: solid transparent;margin:0 auto 0 auto;width: 100%;max-width: 600px;height: 100%;position: relative;">
    <img class="w-full h-auto " src="<?php echo $your_img_src; ?>">
    <?php   

      foreach ($groupedData as $key => $group) :
        foreach ($group as $value) :
            if ($value['left'] != 0) : ?>
        <?php 
            // Convert original pixel position values to percentages
            $left_percentage = ($value['left'] / 840) * 100;
            $top_percentage = ($value['top'] / 600) * 100;
        ?>
        <div class="badgee" style="cursor:pointer; height:24px;width:24px;position:absolute;<?php echo 'top:'.$top_percentage.'%;left:'.$left_percentage.'%'; ?>"  data-key="<?=$key;?>" alt="<?php echo $value['txt'];?>" >
            <?=$key;?> 
            <span style="display: none;width: 300px;color: white;padding: 10px;margin-left: -59px;margin-top: 17px;position: absolute;text-align: center;bottom: 45px;background: rgb(204, 6, 30);border: 2px solid;box-shadow: rgb(90, 90, 90) 1px 1px 10px;">
                <?php echo $value['txt']; ?> 
                <span style="width: 0;height: 0;border-left: 25px solid transparent;border-right: 0 solid transparent;border-top: 20px solid #cc061e;position: absolute;bottom: -20px;left: 30px;"></span>
                                </span>
                            </div>
<?php 
     endif;
endforeach;
                        endforeach; ?>


                        <div style="position: absolute; width: auto;display: none;height: auto;color: rgb(255, 255, 255);top: 255px;left: 23.1094px;background: rgb(204, 6, 30);box-shadow: rgb(90, 90, 90) 1px 1px 10px;padding: 10px;vertical-align: middle;border: 2px solid;text-align: center;" id="product-img-info-hld-msg">
                            <div id="product-img-info-hld-msg-cont" style="display: table-cell;height: auto;vertical-align: middle;text-align: center;width: 300px;">
                            </div>
                            <div style="width: 0; height: 0;border-left: 25px solid transparent;border-right: 0 solid transparent;border-top: 20px solid #cc061e;position: absolute;bottom: -20px;left: 30px;">
                            </div>
                        </div>
                    </div>
                    <div class="td-section-title-two-line-wrapper" style="margin: 0px auto 20px;width: 400px">
                        <div class="td-section-title-two-line" style="width: 150px;border-radius: 50% 0 0 50%;"></div>
                        <div class="td-section-title-two-line td-middle-line"></div>
                        <div class="td-section-title-two-line" style="width: 150px;border-radius: 0 50% 50% 0;"></div>
                    </div>

<!-- Display Spare Parts as Carousel -->
                    <div class="slick-carousel slick-carousel-spare spareparts-carousel xl:hidden" style="margin: 0px 20px;" id="slick-carousel-spare">
                <?php 
                        $groupedData = [];
                        $indexByKey = [];
                        $displayedKeys = [];

                        foreach ($list as $item) {
                            $txt = $item['txt'];
                            if (!isset($indexByKey[$txt])) {
                                $indexByKey[$txt] = count($indexByKey) + 1;
                            }
                            $key = $indexByKey[$txt];
                            if (!in_array($key, $displayedKeys)) {
                                $groupedData[$key][] = $item;
                                $displayedKeys[] = $key;
                            }
                        }
                        foreach ($groupedData as $key => $group) : ?>
                            <?php foreach ($group as $value) :
                    $sp = getCurrentLangProductBySku($value['txt'], $lang);        
                    if (empty($sp)) {
                        continue;
                    } 
                    $p = wc_get_product($sp->get_id());
                ?>
                    <div class="flex spare-card" data-key="<?=$key;?>">
                        <div class=" mx-auto border mt-2 rounded-md cardCarousel" >
                            <div style="margin-top: 10px ;margin-left:10px; display: inline-block; background-color:#374151; color: white;" class=" px-3 py-1 rounded-md font-semibold"> <?php echo $key; ?> </div>
                            <div class="!w-auto flex items-center justify-center c_img" style="height:120px; margin-top:10px;">
                                <?php
                                $image = get_the_post_thumbnail_url($p->get_id());
                                $default = wc_placeholder_img_src(200);
                                if (empty($image)) {
                                    $image = $default;
                                }
                                ?>
                                <img class=" mx-auto" style="width: 120px; height: 100px;" src=<?php echo $image; ?> onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
                            </div>
                            <h3 class="w-full h-30 text-red-600 font-bold text-16 text-center mt-5 c_art_num" style="font-size:20px;"><?php echo $value['txt']; ?> </h3>
                            <h3 class="w-full h-30 text-sm font-semibold text-center mt-3 c_title"><?php echo $p->get_title(); ?> </h3>
                            <div class="mt-2" style="margin-top:10px;padding:10px;color:#cc071c;text-align:center">
                                <?php
                                $palletqty = isset($value['qty']) ? $value['qty'] : 0;
                                ?>
                                <span class="add_to_cart_mob" style="background:#cc061c; height:30px; width:30px; display:inline-block; line-height:32px; cursor:pointer; color:#fff; font-size:30px; border-radius:50%" data-qty="<?php echo $palletqty; ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo $sp->get_id(); ?>">+</span>
                            </div>
                        </div>
                  </div>
                <?php endforeach;
                    endforeach;
                ?>
            </div>

                    <div id="spare_list_table" class="t_list_table hidden lg:block">
                        <div class="t_heading">
                            <div style="width:100px;border-right: solid 3px #fff;">S.No</div>
                            <div style="width:200px;border-right: solid 3px #fff;"><?php if ($lang === 'en') { ?>Product<?php } elseif ($lang === 'sv') { ?>Produkt<?php } ?></div>
                            <div style="width:200px;border-right: solid 3px #fff; "><?php if ($lang === 'en') { ?>Article Number<?php } elseif ($lang === 'sv') { ?>Artikelnummer<?php } ?></div>
                            <div style="width:calc(100% - 700px);"><?php if ($lang === 'en') { ?>Product Name<?php } elseif ($lang === 'sv') { ?>Produktnamn<?php } ?></div>
                        </div>

                        <?php $groupedData = [];
                        $indexByKey = [];
                        $displayedKeys = [];

                        foreach ($list as $item) {
                            $txt = $item['txt'];
                            if (!isset($indexByKey[$txt])) {
                                $indexByKey[$txt] = count($indexByKey) + 1;
                            }
                            $key = $indexByKey[$txt];
                            if (!in_array($key, $displayedKeys)) {
                                $groupedData[$key][] = $item;
                                $displayedKeys[] = $key;
                            }
                        }
                        foreach ($groupedData as $key => $group) : ?>
                            <?php foreach ($group as $item) :
                                $sp = getCurrentLangProductBySku($item['txt'], $lang);
                            if (empty($sp)) {
                                continue;
                            }
                            $p = wc_get_product($sp->get_id());
                        ?>
                            <div id="section_<?php echo $key; ?>" class="t_row" data-key="<?= $key; ?>">
                                <div style="width:100px; margin-top: 20px ;margin-left:10px;"> <?php echo $key; ?> </div>
                                <div class="t_img" style="width:200px; margin-top: 20px ;"> <?php echo $p->get_image(apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail'), [], true); ?> </div>
                                <div class="t_art_num" style="width:200px; margin-top: 20px ;"> <?php echo $item['txt']; ?> </div>
                                <div class="t_title" style="width:calc(100% - 700px); margin-top: 20px ;"> <?php echo $p->get_title(); ?> </div>
                                <div style="color:#cc071c;width:160px;text-align:right ; margin-top: 20px ;">
                                    <?php
                                    $valueqty = isset($item['qty']) ? $item['qty'] : 0; // Check if 'qty' key exists
                                    ?>
                                    <span class="add_to_cart" data-qty="<?php echo  $valueqty ?>" data-price="<?php echo $p->get_price(); ?>" data-product-id="<?php echo  $sp->get_id(); ?>">
                                        +
                                    </span>
                                </div>
                            </div>
                        <?php endforeach;
endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; endif; 
            ?>
            <div id="t_modal">
                <div class="t_cont">
                    <div class="t_head">
                        <span class="t_close_btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M18.3 5.71a.996.996 0 0 0-1.41 0L12 10.59L7.11 5.7A.996.996 0 1 0 5.7 7.11L10.59 12L5.7 16.89a.996.996 0 1 0 1.41 1.41L12 13.41l4.89 4.89a.996.996 0 1 0 1.41-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4" />
                            </svg></span>
                    </div>
                    <div class="t_body">
                        <div class="t_item">
                            <div class="t_item_img">
                                <img src="" id="prod_img" />
                            </div>
                            <div class="t_item_info">
                                <div class="name" id="prod_name"></div>
                                <div class="artno"></div>
                                <div class="price"><span></span> <?php echo get_woocommerce_currency_symbol(); ?></div>
                            </div>
                            <div class="t_item_action">
                                <div class="t_time_action_item">
                                    <span class="t_time_action_item_minus">-</span>
                                    <span class="t_time_action_item_val">1</span>
                                    <span class="t_time_action_item_plus">+</span>
                                </div>
                                <p class="min_req">                                
                                    <?php if ($lang === 'en') { ?>Minimum Quantity Needed :<?php } elseif ($lang === 'sv') { ?>Antal :<?php } ?> 
                                <span></span></p>
                            </div>
                        </div>

                        <div>
                            <div class="t_total_info">
                                <span><?php if ($lang === 'en') { ?>Total<?php } elseif ($lang === 'sv') { ?>Totalt<?php } ?></span>
                                <span>-</span>
                                <span class="t_total"><span></span> <?php echo get_woocommerce_currency_symbol(); ?></span>
                            </div>
                            <div>
                                <button id="productcartsubmit" class="t_spare_add_to_cart add-to-cart-button"><?php esc_html_e('Add to Cart', 'TAW_TEXT_DOMAIN'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="notify-of-add-to-cart" id="notify-of-add-to-cart">
                <div class="productAddedToCart">
                    <div class="notifyHeader">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m4 8l2.05 1.64a.48.48 0 0 0 .4.1a.5.5 0 0 0 .34-.24L10 4" />
                                <circle cx="7" cy="7" r="6.5" />
                            </g>
                        </svg>
                        <div class="AddedToCart">
                            <?php $lang = getSiteCurrentLang();
                            if ($lang == 'en') { ?> Product added to cart <?php } elseif ($lang == 'sv') { ?> Lägger till produkt <?php } ?>
                        </div>
                    </div>
                    <div id="productContent">
                        <!-- Product items will be dynamically added here -->
                    </div>
                </div>
            </div>
            <style type="text/css">
                .productAddedToCart {
                    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                    background: white;
                    border-radius: 20px 20px 20px 20px;
                }

                #productContent {
                    padding: 10px 20px;
                }

                .notifyProductContent {
                    background: white;
                    padding: 10px 20px;
                    border-radius: 0px 0px 20px 20px;
                    display: flex;
                    justify-content: space-around;
                    gap: 20px;
                    align-items: center;



                }

                .AddedToCart {
                    font-size: 16px;
                }

                .notifyHeader {

                    color: white;
                    display: flex;

                    gap: 20px;
                    align-items: center;
                    background: #cc071d;
                    padding: 10px 10px;
                    border-radius: 20px 20px 0px 0px;
                }

                .notify-of-add-to-cart {
                    display: none;
                    position: fixed;
                    max-width: 400px;
                    top: 20%;
                    right: 5%;
                    z-index: 19000 !important;
                    ;
                }

                @media only screen and (max-width: 500px) {
                    .notify-of-add-to-cart {
                        top: 15%;
                        right: 2%;
                    }

                    .AddedToCart {
                        font-size: 12px;
                    }
                }

                @media screen and (min-width: 1900px) {
                    .notify-of-add-to-cart {
                        top: 15%;

                    }
                }

                .loader {
                    border: 8px solid #f3f3f3;
                    border-top: 8px solid #cc071d;
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 1s linear infinite;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
@media (min-width: 1200px) {
                  #spare_img_mob {
                    display: none !important;
                  }
                }
                @media (max-width: 1200px) {
                    .badgee{
                        width: 20px !important;
                        height: 20px !important;
                        font-size: 13px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        
                    }
                }



                .overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    /* Adjust the opacity as needed */
                    z-index: 9999;
                    /* Set a higher z-index than the popup */
                }
            </style>
            <script>
function scrollToSection(sectionKey) {
                    var sectionId = 'section_' + sectionKey;
                    var sectionElement = document.getElementById(sectionId);

                    if (sectionElement) {
                        var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                        var sectionHeight = sectionElement.offsetHeight;
                        var offsetTop = sectionElement.offsetTop;

                        var scrollTo = offsetTop - (windowHeight - sectionHeight) / 2;

                        window.scrollTo({
                            top: scrollTo,
                            behavior: 'smooth'
                        });
                    }
                }

                function toggleAccessories() {
                    var toggleElement = document.getElementById("accessories");
                    var accessoriesTable = document.getElementById("spare_list");
                    var accessoriesslickTable = document.getElementById("slick-carouseltable");

                    // Toggle the active class on the clicked element
                    toggleElement.classList.toggle("active");

                    var screenWidth = window.innerWidth;
                    if (screenWidth >= 768) { // Adjust the breakpoint as needed
                        if (accessoriesTable.style.display === "none") {
                            accessoriesTable.style.display = "block";
                        } else {
                            accessoriesTable.style.display = "none";
                        }
                    }
                    if (screenWidth <= 768) {
                        if (accessoriesslickTable.style.display === "none") {
                            accessoriesslickTable.style.display = "block";
                        } else {
                            accessoriesslickTable.style.display = "none";
                        }
                    }
                }

                function togglespareparts() {
                    var toggleElement = document.getElementById("spareparts");
                    var spareTable = document.getElementById("spare_list_table");
                    var spareimg = document.getElementById("spare_img");
var spareimgMob = document.getElementById("spare_img_mob");
                    var sparepartslickTable = document.getElementById("slick-carousel-spare");

                    // Toggle the active class on the clicked element
                    toggleElement.classList.toggle("active");

                   //console.log('clicked')

                    var screenWidth = window.innerWidth;
                    if (screenWidth >= 768) { // Adjust the breakpoint as needed
                        if (spareTable.style.display === "none") {
                            spareTable.style.display = "block";
                        } else {
                            spareTable.style.display = "none";
                        }
                        if (spareimg.style.display === "none") {
                            spareimg.style.display = "block";
                        } else {
                            spareimg.style.display = "none";
                        }
                    }
if (screenWidth <= 768) {
                        if (sparepartslickTable.style.display === "none") {
                            sparepartslickTable.style.display = "block";
                        } else {
                            sparepartslickTable.style.display = "none";
                        }
                        if (spareimgMob.style.display === "none") {
                            spareimgMob.style.display = "block";
                        } else {
                            spareimgMob.style.display = "none";
                        }
                    }
                }

                function showLoader() {
                    var loader = document.createElement('div');
                    loader.className = 'loader';
                    loader.style.zIndex = '10000000000000000';
                    document.body.appendChild(loader);
                    var overlay = document.createElement('div');

                    overlay.className = 'overlay';
                    document.body.appendChild(overlay);
                }

                function hideLoader() {
                    var overlay = document.getElementsByClassName('overlay')[0]
                    document.body.removeChild(overlay);
                    var loader = document.querySelector('.loader');
                    if (loader) {
                        loader.parentNode.removeChild(loader);
                    }
                }

                function addItemToCartACC(imgSrc, title) {
                    // Check if the product with the same title is already in the cart
                    var existingProduct = document.querySelector('.AddedToCart .notifyProductContent [data-title="' + CSS.escape(title) + '"]');
                    if (existingProduct) {
                        // If the product already exists, you can update the quantity or take other actions
                        // console.log('Product with title ' + title + ' is already in the cart.');
                    } else {
                        var notifyProductContent = document.getElementById("productContent");
                        notifyProductContent.innerHTML = '';

                        // Create a new product item div
                        var productItem = document.createElement("div");
                        productItem.classList.add("productCartItem");
                        productItem.classList.add("notifyProductContent");
                        productItem.setAttribute("data-title", CSS.escape(title)); // Set a data attribute to identify the product

                        // Add image element to the product item
                        var imgElement = document.createElement("img");
                        imgElement.src = imgSrc;
                        imgElement.style.width = "50px";
                        imgElement.style.height = "50px";
                        productItem.appendChild(imgElement);

                        // Add title element to the product item
                        var titleElement = document.createElement("div");
                        titleElement.textContent = title;
                        productItem.appendChild(titleElement);

                        // Append the product item to the notifyProductContent div
                        notifyProductContent.appendChild(productItem);
                    }

                    // Show the cart notification
                    var popmessage = document.getElementById('notify-of-add-to-cart');
                    console.log(popmessage)

                    popmessage.style.display = "block";
                }

                function updateCartContent() {
                    // Make an additional AJAX request to get the updated cart count and content
                    var cartUpdateXhr = new XMLHttpRequest();
                    cartUpdateXhr.open('GET', '<?php echo admin_url('admin-ajax.php?action=woocommerce_get_refreshed_fragments'); ?>', true);
                    cartUpdateXhr.onreadystatechange = function() {
                        if (cartUpdateXhr.readyState === 4 && cartUpdateXhr.status === 200) {
                            var cartUpdateResponse = JSON.parse(cartUpdateXhr.responseText);

                            //  console.log(cartUpdateResponse.fragments)

                            // Update the cart content on the page
                            if (cartUpdateResponse.fragments) {
                                var specificSelector = '.elementor-menu-cart__toggle_button span.elementor-button-icon-qty';
                                var elementToUpdate = document.querySelectorAll('.fkcart-item-count');


                                //   console.log("before",elementToUpdate)

                                if (elementToUpdate && cartUpdateResponse.fragments[specificSelector]) {
                                    // Parse the HTML and update the content
                                    var parsedHtml = new DOMParser().parseFromString(cartUpdateResponse.fragments[specificSelector], 'text/html');
                                    var qtyElem = parsedHtml.querySelector('.elementor-button-icon-qty')
                                    elementToUpdate.forEach(function(element) {
                                        element.innerHTML = qtyElem.innerHTML;
                                        element.setAttribute('data-item-count', qtyElem.innerHTML);
                                    });
                                }

                                //  console.log(elementToUpdate)
                                //  console.log(cartUpdateResponse.fragments[specificSelector])

                                // If you have other elements to update, you can continue the loop for other selectors
                                for (var selector in cartUpdateResponse.fragments) {
                                    if (cartUpdateResponse.fragments.hasOwnProperty(selector) && selector !== specificSelector) {
                                        var otherElementToUpdate = document.querySelector(selector);
                                        if (otherElementToUpdate) {
                                            otherElementToUpdate.innerHTML = cartUpdateResponse.fragments[selector];
                                        }
                                    }
                                }
                            }
                        }
                    };

                    // Send the request to update the cart fragments
                    cartUpdateXhr.send();
                }
                jQuery(function($) {
                    var selectedProductID; // Declare the global variable
                    var count = 1; // Initialize count to 1

                    $(".add_to_cart_mob").click(function(e) {
                        selectedProductID = $(this).attr('data-product-id'); // Update the global variable
                        console.log(selectedProductID);
                        var title = $(this).closest(".cardCarousel").find(".c_title").text();
                        var img = $(this).closest(".cardCarousel").find(".c_img img").attr("src");
                        var art_no = $(this).closest(".cardCarousel").find(".c_art_num").text();
                        var price = $(this).attr("data-price");
                        var qty = $(this).attr("data-qty");
                        var t_qty = $(".t_time_action_item_val").text(1)
                        count = 1


                        $("#t_modal .t_item_img img").attr("src", img);
                        $("#t_modal .t_item_info .name").text(title);
                        $("#t_modal .t_item_info .artno").text(art_no);
                        $("#t_modal .t_item_info .price span").text(price);
                        $("#t_modal .t_item_action .min_req span").text(qty);

                        updaprice = price * count;
                        $("#t_modal .t_total_info .t_total span").text(updaprice);
                        $("#t_modal").css("display", "block");
                        
                    });
                    $("#spare_list .add_to_cart").click(function(e) {
                        selectedProductID = $(this).attr('data-product-id'); // Update the global variable
                        //console.log(selectedProductID);
                        var title = $(this).closest(".t_row").find(".t_title").text();
                        var img = $(this).closest(".t_row").find(".t_img img").attr("src");
                        var art_no = $(this).closest(".t_row").find(".t_art_num").text();
                        var price = $(this).attr("data-price");
                        var qty = $(this).attr("data-qty");
                        var t_qty = $(".t_time_action_item_val").text(1)
                        count = 1

                        $("#t_modal .t_item_img img").attr("src", img);
                        $("#t_modal .t_item_info .name").text(title);
                        $("#t_modal .t_item_info .artno").text(art_no);
                        $("#t_modal .t_item_info .price span").text(price);
                        $("#t_modal .t_item_action .min_req span").text(qty);

                        updaprice = price * count;
                        $("#t_modal .t_total_info .t_total span").text(updaprice);
                        $("#t_modal").css("display", "block");
                        
                    });
                    $("#spare_list_table .add_to_cart").click(function(e) {
                        selectedProductID = $(this).attr('data-product-id'); // Update the global variable
                        //console.log(selectedProductID);
                        var title = $(this).closest(".t_row").find(".t_title").text();
                        var img = $(this).closest(".t_row").find(".t_img img").attr("src");
                        var art_no = $(this).closest(".t_row").find(".t_art_num").text();
                        var price = $(this).attr("data-price");
                        var qty = $(this).attr("data-qty");
                        var t_qty = $(".t_time_action_item_val").text(1)
                        count = 1

                        $("#t_modal .t_item_img img").attr("src", img);
                        $("#t_modal .t_item_info .name").text(title);
                        $("#t_modal .t_item_info .artno").text(art_no);
                        $("#t_modal .t_item_info .price span").text(price);
                        $("#t_modal .t_item_action .min_req span").text(qty);

                        updaprice = price * count;
                        $("#t_modal .t_total_info .t_total span").text(updaprice);
                        $("#t_modal").css("display", "block");
                        
                    });

                    $("#t_modal .t_time_action_item .t_time_action_item_plus").click(function(e) {

                        count++; // Increment the count
                        var cur = $("#t_modal .t_time_action_item .t_time_action_item_val");
                        cur.text(count);
                        var price = parseInt($("#t_modal .t_item_info .price span").text());
                        //console.log(price);
                        updaprice = price * count;
                        $("#t_modal .t_total_info .t_total span").text(updaprice);
                    });

                    $("#t_modal .t_time_action_item .t_time_action_item_minus").click(function(e) {
                        if (count > 1) {
                            count--; // Decrement the count only if it's greater than 1
                        }
                        var cur = $("#t_modal .t_time_action_item .t_time_action_item_val");
                        cur.text(count);
                        var price = parseInt($("#t_modal .t_item_info .price span").text());
                        //console.log(price);
                        updaprice = price * count;
                        $("#t_modal .t_total_info .t_total span").text(updaprice);
                    });

                    $('#productcartsubmit').on('click', function(e) {
                        showLoader()

                        var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                        let product_id = selectedProductID;
                        let product_qty = count;
                        let minimum_qty = parseInt($("#t_modal .t_item_action .min_req span").text());
                        // console.log('aurl: ' + aurl);
                        // console.log('product_id: ' +product_id);
                        // console.log('product_qty: ' +product_qty);
                        var productImage = e.target.closest('#t_modal').querySelector('#prod_img').src;
                        var productTitle = e.target.closest('#t_modal').querySelector('#prod_name').innerHTML;

                        let data = {
                            'action': 'ajaxcart',
                            'product_id': product_id,
                            'product_qty': product_qty,
                        }

                        jQuery.post(aurl, data, function(response) {
                            console.log('data:', data); // Log the entire response object
                            if (response.status === '1') {
                                hideLoader()
                                addItemToCartACC(productImage, productTitle);
                                updateCartContent();
                                var popmessage = document.getElementById('notify-of-add-to-cart');

                                setTimeout(function() {
                                    popmessage.style.display = 'none';
                                }, 4000);
$("#t_modal").hide()
                            } else {
                                hideLoader()
$("#t_modal").hide()
                                alert('Could not add product to cart.');
                            }
                        });
                    });
                });
                jQuery(document).ready(function($) {
                    $('.slick-carousel').slick({
                        dots: false, // If you want to show dots navigation
                        arrows: true, // If you want to show arrows navigation
                        slidesToShow: 1, // Number of slides to show at a time
                        slidesToScroll: 1, // Number of slides to scroll at a time
                    });

                });
            </script>
<?php }

        function wpii_load_wp_media_files($page)
        {

            // change to the $page where you want to enqueue the script

            //echo "raja ".$page;
            if ($page == 'post.php' || $page == 'post-new.php') {
                wp_enqueue_media();
                // Enqueue custom script that will interact with wp.media
                wp_enqueue_script('wpii_script', plugins_url('script.js', __FILE__), array('jquery', 'jquery-ui-draggable', 'jquery-ui-droppable'), time());
            }
        }

        /**
         * Remove the default Custom Fields meta box
         */
        function removeDefaultCustomFields($type, $context, $post)
        {
            foreach (array('normal', 'advanced', 'side') as $context) {
                foreach ($this->postTypes as $postType) {
                    remove_meta_box('postcustom', $postType, $context);
                }
            }
        }
        /**
         * Create the new Custom Fields meta box
         */
        function createCustomFields()
        {

            if (function_exists('add_meta_box')) {
                foreach ($this->postTypes as $postType) {
                    add_meta_box('my-custom-fields', 'Spare Parts Settings', array($this, 'showImagePointerInfoUI'), $postType, 'normal', 'high');
                }
            }
        }

        /**
         * Save the new Custom Fields values
         */
        function saveCustomFields($post_id, $post)
        {

            if (!isset($_POST['my-custom-fields_wpnonce']) || !wp_verify_nonce($_POST['my-custom-fields_wpnonce'], 'my-custom-fields'))
                return;
            if (!current_user_can('edit_post', $post_id))
                return;
            if (!in_array($post->post_type, $this->postTypes))
                return;


            if (isset($_POST['_wpii_prod_img_pointer_info']) && trim($_POST['_wpii_prod_img_pointer_info'])) {
                $value = $_POST['_wpii_prod_img_pointer_info'];
                // Auto-paragraphs for any WYSIWYG               
                update_post_meta($post_id, '_wpii_prod_img_pointer_info', $value);
            }
        }


        function showImagePointerInfoUI()
        {
            include(plugin_dir_path(__FILE__) . 'template/img-pointer-info.php');
        }

        function wpii_load_wp_custom_product_info($html, $post_id)
        {
            return $html;
        }
    } // End Class

} // End if class exists statement

// Instantiate the class
if (class_exists('myCustomFields')) {
    $myCustomFields_var = new myCustomFields();
}
