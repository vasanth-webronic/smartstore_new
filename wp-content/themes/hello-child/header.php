<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
$enable_skip_link = apply_filters( 'hello_elementor_enable_skip_link', true );
$skip_link_url = apply_filters( 'hello_elementor_skip_link_url', '#content' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

 


	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$delay = 1; // seconds
$loader = get_stylesheet_directory_uri().'/smartstoring-loader-logo.png';
$overlayColor = '#575656';
?>
<div id="smart-preloader" class="preloader-overlay" style="background-color: #575656">
    <img class="preloader-icon" src="<?php echo $loader; ?>"/>
</div>
<style>
    body.preloader-active {
        overflow: hidden;
    }
    .preloader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000000000; /* Adjust z-index as needed */
    }
    .preloader-icon {
        
        height: auto;
                max-height: 100%;
    }
@media (min-width: 992px) {
        .preloader-icon {
            max-width: 320px;
        }
    }
    @media (max-width: 991px) {
        .preloader-icon {
            max-width: 220px;
        }
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            var preloader = document.getElementById("smart-preloader");
            if (preloader) {
                preloader.remove();
                document.body.classList.remove("preloader-active");
            }
        }, 1 * 1000);
        document.body.classList.add("preloader-active");
    });
</script>



<?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<a class="skip-link screen-reader-text" href="<?php echo esc_url( $skip_link_url ); ?>"><?php echo esc_html__( 'Skip to content', 'hello-elementor' ); ?></a>
<?php } ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	if ( hello_elementor_display_header_footer() ) {
		if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
			get_template_part( 'template-parts/dynamic-header' );
		} else {
			get_template_part( 'template-parts/header' );
		}
	}
}

