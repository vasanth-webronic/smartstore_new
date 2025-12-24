<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$lang=getSiteCurrentLang();
//echo $lang;
?>
<?php
icl_register_string('TAW_TEXT_DOMAIN','password and account details','password and account details');
$trans_hello=icl_t('TAW_TEXT_DOMAIN', 'Hello','Hello');
$trans_recent =icl_t('TAW_TEXT_DOMAIN', 'recent orders','recent orders');
$trans_ship = icl_t('TAW_TEXT_DOMAIN','shipping and billing addresses','shipping and billing addresses');
$trans_pass = icl_t('TAW_TEXT_DOMAIN','password and account details','password and account details');
?>
<div class="ml-0 lg:ml-8">
    <div class="flex">
        <h1 class="font-bold text-black text-2xl"><?php echo $trans_hello; ?></h1>
        <h1 class="font-bold text-red-600 text-2xl ml-2"><?php echo $current_user->display_name; ?></h1>
    </div>

    <p class="!mt-3"><?php if($lang=='en') { ?>From your account dashboard you can  <?php } ?></p>
	<?php $arrow = THINGSATWEB_BASE . '/img/ic_forward_arrow.svg'; ?>

    <div class="flex items-center ml-6">
        <div>
            <img class="!h-3 !w-auto" src=<?php echo $arrow; ?> alt="Arrow">
        </div>
        <p class="ml-2 text-start !mb-0"><?php if($lang=='en') { ?> see your <?php } ?> <a href=<?php echo wc_get_endpoint_url( 'orders' ); ?> class="text-red-600 !no-underline font-semibold"><?php echo $trans_recent; ?></a></p>
    </div>
	<div class="flex items-center mt-3  ml-6">
        <div>
            <img class="!h-3 !w-auto" src=<?php echo $arrow; ?> alt="Arrow">
        </div>
        <p class="ml-2 text-start !mb-0"><?php if($lang=='en') { ?>manage your <?php } ?> <a href=<?php echo wc_get_endpoint_url( 'edit-address' ); ?> class="text-red-600 !no-underline font-semibold"><?php echo $trans_ship; ?></a></p>
    </div>
	<div class="flex items-center mt-3  ml-6">
        <div>
            <img class="!h-3 !w-auto" src=<?php echo $arrow; ?> alt="Arrow">
        </div>
        <p class="ml-2 text-start !mb-0"><?php if($lang=='en') { ?> edit your  <?php } ?><a href=<?php echo wc_get_endpoint_url( 'edit-account' ); ?> class="text-red-600 !no-underline font-semibold"><?php echo $trans_pass; ?></a></p>
    </div>

	<?php /*
	<p class="!mb-0 mt-4">(not <span class="font-bold"> <?php echo $current_user->display_name;?>? </span> <a href=<?php echo esc_url( wc_logout_url() ); ?> class="text-red-600 !no-underline font-semibold">Log out</a>)</p>
*/ ?>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
