
<?php
/**
 * Taw Shipping Method.
 *
 * @version 1.0.1
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Taw_Shipping_Rate class.
 */
class Taw_Shipping_Rate extends WC_Shipping_Method {

	/**
	 * Cost passed to [fee] shortcode.
	 *
	 * @var string Cost.
	 */
	protected $fee_cost = '';

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'taw_shipping_rate';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'TAW rate', 'woocommerce' );
		$this->method_description = __( 'Custom shipping method form thingsatweb', 'woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Init user set variables.
	 */
	public function init() {
		$this->instance_form_fields = include THINGSATWEB_DIR.'/inc/shipping/settings-flat-rate.php';
		$this->title                = $this->get_option( 'title' );
		$this->tax_status           = $this->get_option( 'tax_status' );
		$this->cost                 = $this->get_option( 'cost' );
		$this->type                 = $this->get_option( 'type', 'class' );
	}

	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param  string $sum Sum of shipping.
	 * @param  array  $args Args, must contain `cost` and `qty` keys. Having `array()` as default is for back compat reasons.
	 * @return string
	 */
	protected function evaluate_cost( $sum, $args = array() ) {
		// Add warning for subclasses.
		if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
			wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
		}

		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
		$this->fee_cost = $args['cost'];

		// Expand shortcodes.
		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			)
		);

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string.
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string.
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters.
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math.
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
	}

	/**
	 * Work out fee (shortcode).
	 *
	 * @param  array $atts Attributes.
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts(
			array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			),
			$atts,
			'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}

	/**
	 * Calculate the shipping costs.
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ) {
		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => 0,
			'package' => $package,
		);
		
		$products=$package['contents'] ?? [];		

	
		$settings=get_option('taw_prod_shipping_settings');
		$lang=getSiteCurrentLang();
		$shipment_simple_cate_key="category";
		if($lang!="sv"){
			$shipment_simple_cate_key="category_".$lang;
		}
		$simple_prod=$settings['shipment_simple'] ?? [];

		

		$address=$package['destination'] ?? [];
		$country=$address['country'];
		$postcode=$address['postcode'];
		
		//calculate postal code cost
		$postal_code_cost_ar=$settings['postal_code_'.$country] ?? [];
		$postal_code_cost=0;
		foreach($postal_code_cost_ar as $c){
			if($c['zip']==($postcode[0] ?? 0)){
				$postal_code_cost=$c['cost'];
				break;
			};
		}	

		$simpleProdRate=0;
		$customProdRate=0;
		foreach($products as $p){
			$product_id=$p['product_id'] ?? 0;
			$qty=$p['quantity'] ?? 0;

			//check product type
			if(!empty($p["taw_config_obj"])){
				//for config products
				$conf=json_decode($p["taw_config_obj"],true);
				$size=explode("x",$conf['Size']['title'] ?? "0x0");
				$door_w=$size[0];
				$door_h=$size[1];
				$side_w = $conf['extra']['sidelight_size'] ?? 0;
				$family_name =$conf['extra']['family_name'] ?? "";

				$cost=calculate_shipment_cost($door_w,$door_h,$side_w,$qty,$family_name,$country,$settings,$postal_code_cost);
				$customProdRate+=$cost;
				
			}else{
				//for simple products
				$prod_cates=[];	
				$parentCate=[];
				/** collect product categories and those parent categories */
				if(!empty($product_id)){
					$terms = get_the_terms ( $product_id, 'product_cat' );
					foreach ( $terms as $term ) {						
						$prod_cates[$term->slug]=$term->slug;
						$parentcats = get_ancestors($term->term_id, 'product_cat');						
						foreach($parentcats as $p){
							$t=get_term( $p );
							$parentCate[$t->slug]=$t->slug;						
						}						
					}					
				}
				$prod_cates=array_merge($prod_cates,$parentCate);

				$match="";
				foreach($prod_cates as $slug){
					/*** check slug exist in  */
					foreach($simple_prod as $s){
						$cates=$s[$shipment_simple_cate_key] ?? [];
						foreach($cates as $c){
							$cSlug=explode("::",$c)[1] ?? "";
							if($cSlug==$slug){
								$match=$s;
								break;
							}
						}
						if(!empty($match)){
							break;
						}
					}

					if(!empty($match)){
						$art_nos=$match['art_nos'] ?? [];
						
						//check condition match
						foreach($art_nos as $a){
							if(!empty($a['count'])){
								if($qty>$a['count']){
									$pricing_art=$a['art_no'];
									break;
								}
							}else{
								if($qty>=$a['min']&&$qty<=$a['max']){
									$pricing_art=$a['art_no'];
									break;
								}
							}							
						}

						if(!empty($pricing_art)){
							$priecArtAr=explode(",",$pricing_art);
							$price=0;
							foreach($priecArtAr as $art_no){
								$price+=getPriceByArtNo($art_no);
							}
							$cost=ceil(($price+$postal_code_cost)/10)*10;
							if($cost>$simpleProdRate){
								$simpleProdRate=$cost;
							}
						}
						break;
					}
				}
			}
			
		}

		$rate['cost']=$simpleProdRate+$customProdRate;

		$this->add_rate( $rate );
		do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );

	}

	/**
	 * Get items in package.
	 *
	 * @param  array $package Package of items from cart.
	 * @return int
	 */
	public function get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}

	/**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param mixed $package Package of items from cart.
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

	/**
	 * Sanitize the cost field.
	 *
	 * @since 3.4.0
	 * @param string $value Unsanitized value.
	 * @throws Exception Last error triggered.
	 * @return string
	 */
	public function sanitize_cost( $value ) {
		$value = is_null( $value ) ? '' : $value;
		$value = wp_kses_post( trim( wp_unslash( $value ) ) );
		$value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ) ), '', $value );
		// Thrown an error on the front end if the evaluate_cost will fail.
		$dummy_cost = $this->evaluate_cost(
			$value,
			array(
				'cost' => 1,
				'qty'  => 1,
			)
		);
		if ( false === $dummy_cost ) {
			throw new Exception( WC_Eval_Math::$last_error );
		}
		return $value;
	}
}