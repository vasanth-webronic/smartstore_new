<?php
/**
 * Plugin Name: Product Stack Pricing by webronics
 * Description: Maintaining a product price, We can able to offer a price individually for users.
 * Author: webronic.com
 * Version: 1.0.0
 * Author URI: webronic.com
 *
 * Text Domain: product-stack-pricing
 */

 /**
 * Product Stack Pricing by webronics
 *
 * Copyright (c) 2024 WEBRONIC
 *
 *
 * webronic is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     webronic
 * @version    1.0.0
 * @copyright  (c) 2024 webronic
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    Product Stack Pricing by webronics
 */


 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


define( 'PRODUCT_STACK_PRICING_VERSION', '1.0.0' );
define( 'PRODUCT_STACK_PRICING_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRODUCT_STACK_PRICING_URL', plugin_dir_url( __FILE__ ) );

// Include main plugin class if not already loaded
if ( ! class_exists( 'Product_Stack_Pricing' ) ) {
    require_once PRODUCT_STACK_PRICING_PATH . 'includes/class-product-stack-pricing.php';
}

register_activation_hook( __FILE__, array( 'Product_Stack_Pricing', 'create_table' ) );

