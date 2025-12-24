<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */


defined( 'ABSPATH' ) || exit;

// do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
  
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

<style type="text/css">
	#smartstore_order_table_wrapper .dt-input{
border: 1px solid #aaa !important;
border-radius: 3px !important;
padding: 5px !important;
background-color: transparent !important;
color: inherit !important;
}
.dt-search
{

display: flex;
justify-content: end;
align-items: center;

}
.dt-search input
{

width: 100px;

}
.dt-length{
display: flex;
justify-content: start;
align-items: center;
gap: 4px;
}
.dt-length select{

width: 60px;


}
@media screen and (max-width: 768px) {
#smartstore_order_table colgroup col {
	width: 100% !important;
}
.dt-empty{
	display: flex !important;
	justify-content: center;

}
.dt-empty::before{
	content: '' !important;
	
}
.dt-search{
	justify-content: flex-start;
}
.dt-length{
	flex-direction: row-reverse;
}


.dt-search input{
width: 100%;
}
.dt-search input :active{
outline: none;
}
.dt-search input :focus{
outline: none;
}
}

.alignleft {
    text-align: center !important;
}
.woocommerce-orders-table__cell-order-userid {
    text-align: left !important;
}
.woocommerce-orders-table__header-order-userid {
    text-align: left !important;
}
</style>
<?php
// Get the current user ID
$user_id = get_current_user_id();
$user_info = get_userdata($user_id);
$user_roles = $user_info->roles;
$user_role = $user_roles[0];

// Retrieve the sale_adminid for the current user
global $wpdb;

// if($user_role=='custom_uam_reseller_eur' || $user_role=='custom_uam_reseller_sek')
if(current_user_can('c_uam_cap_group_info'))
{  
	
    $status_sale = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM tsm_usermeta WHERE user_id = %d", $user_id ) );

        $cus_no= $wpdb->get_var( $wpdb->prepare( 
        "SELECT meta_value FROM tsm_usermeta WHERE user_id = %d AND meta_key like '%customer_no'", $user_id ) );
		
           
        $sale_admin_ids = $wpdb->get_col($wpdb->prepare("SELECT user_id FROM tsm_usermeta WHERE meta_value = %s AND meta_key like '%customer_no'",$cus_no));  
		//print_r($sale_admin_ids);
           
        // Fetch orders for all user IDs in $sale_admin_ids
        $orders = array();
        foreach ($sale_admin_ids as $admin_id) 
        {
            $admin_orders = wc_get_orders(array(
                'meta_key'    => '_customer_user',
                'meta_value'  => $admin_id,
                'numberposts' => -1,
            ));
            $orders = array_merge($orders, $admin_orders); 
        }
    
}else{
	
    $orders = wc_get_orders( array(
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'numberposts' => -1,
    ) );
}


$total_orders = count( $orders );
$orders_per_page = 10; 
$max_num_pages = ceil($total_orders / $orders_per_page);
$customer_orders = (object) array(
    'orders' => $orders,
    'total' => $total_orders,
    'max_num_pages' => $max_num_pages
);
?>

<?php if ($customer_orders->total >= '1') : ?>

	<table id="smartstore_order_table" class="!ml-0 lg:!ml-8 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead class="bg-gray-200">
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="!py-3 !border-none woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
		$user_id    = $order->get_customer_id(); // Get user ID associated with the order
        $user_name= $wpdb->get_var( $wpdb->prepare( 
            "SELECT user_login FROM tsm_users WHERE ID = %d", $user_id ) );
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="border-none !py-4 !bg-white woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a class="!no-underline font-semibold" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
								</a>

				<?php elseif ( 'order-userid' === $column_id ) : ?>
                    <span class="font-semibold"><?php echo esc_html( $user_name ); ?></span>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time class="font-semibold" datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<span class="font-semibold"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<span class="font-semibold">
								<?php
								/* translators: 1: formatted order total 2: total order items */
								echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
								?>
								</span>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button !no-underline px-4 py-1 bg-red-600 hover:bg-black text-white rounded-full font-semibold hover:text-white hover:bg-black' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php /* do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		 <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php  if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php  if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php  endif; ?>
		</div> 
	<?php  endif; */?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>


<?php //do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
<script type="text/javascript">
	let table = new DataTable('#smartstore_order_table',
		{
			    language: {
        searchPlaceholder: 'Search'
    }
		});
</script>

