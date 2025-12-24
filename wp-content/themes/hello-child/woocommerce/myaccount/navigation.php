<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp;
$currentUrl = home_url($wp->request);
$parsed = explode('/', $currentUrl);
$currentPage = $parsed[count($parsed)-1];
$currentPage = $currentPage=='my-account'?'dashboard':$currentPage;
$currentPage = in_array("view-order", $parsed)?'orders':$currentPage;

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation pb-5 lg:pb-10 xl:pb-15">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
			if ($endpoint == 'downloads') {
				continue;
			} 
		?>
		<li class="bg-gray-200 p-3 mb-1  <?php echo $currentPage == $endpoint? 'active':''; ?>">   
			<a class="block !no-underline font-semibold text-black" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
			<?php echo esc_html( $label ); ?>	</a>
		</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
