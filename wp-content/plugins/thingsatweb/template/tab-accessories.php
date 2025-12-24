<?php
defined( 'ABSPATH' ) || exit;

global $product;
$category_id = $product->get_category_ids()[0];
$category_slug = get_term( $category_id )->slug;
$accessories_slug = 'accessories-to-'.$category_slug;

$products = wc_get_products(array(
    'category' => array($accessories_slug),
    'posts_per_page' => 12,
));
$favourite = THINGSATWEB_BASE . '/img/heart-o.svg';
?>

<div class="taw-tab-container">
    <div class="slider-accessories mt-3 mx-10 md:mx-0">
        <?php foreach ($products as $key => $value): ?>
            <div class="border p-3 rounded-md mx-2">
                <?php
                $image = get_the_post_thumbnail_url($value->get_id());
                $default = wc_placeholder_img_src(120);
                if(empty($image)) { $image = $default; }
                ?>
                <img class="!w-auto !h-24 sm:!h-24 md:!h-20 lg:!h-20 xl:!h-24 my-auto mx-auto" src=<?php  echo $image;?>
                onerror="this.onerror=null;this.src='<?php  echo $default;?>'">
                <h4 class="h-8 overflow-hidden text-xs text-center font-semibold mt-3"><?php echo $value->get_title(); ?></h4>
                <div class="my-4 flex flex-col items-center">
                    <a href="<?php echo $value->get_permalink();?>" > 
                        <button class="text-xs bg-red-600 w-16 h-6 text-white rounded-full hover:bg-black flex items-center justify-center">
                            <?php $price=$value->get_price_html();
                            echo product_buy_or_quote($price); ?>
                        </button>
                    </a>
                </div>
                <img class="w-3 mx-0" src=<?php echo $favourite; ?> alt="">
            </div>
        <?php endforeach; ?>                
    </div>
</div>

<script>
    jQuery( document ).ready( function( e ) {
        jQuery(".slider-accessories").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1536,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
	});
</script>

<?php
?>