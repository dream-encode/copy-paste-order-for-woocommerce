<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dream-encode.com
 * @since             1.0.0
 * @package           Copy_Paste_Order_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Copy PLUGIN_NAME Paste Order for WooCommerce
 * Plugin URI:        https://maxmarineelectronics.com
 * Description:       A small plugin [D[D[D[D[D[D[Dutility plugin to help copy WooCommerce orders from one site to another with justa couple of clicks.
 * Version:           1.0.0
 * Author:            David Baumwald
 * Author URI:        https://dream-encode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       copy-paste-order-for-woocommerce
 * Domain Path:       /languages
 * GitHub Plugin URI: dream-encode/copy-paste-order-for-woocommerce
 * Release Asset:     true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants
 */
require_once 'includes/copy-paste-order-for-woocommerce-constants.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-copy-paste-order-for-woocommerce-activator.php
 *
 * @return void
 */
function copy_paste_order_for_woocommerce_activate() {
	require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/class-copy-paste-order-for-woocommerce-activator.php';
	Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Copy_Paste_Order_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-copy-paste-order-for-woocommerce-deactivator.php
 *
 * @return void
 */
function copy_paste_order_for_woocommerce_deactivate() {
	require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/class-copy-paste-order-for-woocommerce-deactivator.php';
	Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Copy_Paste_Order_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'copy_paste_order_for_woocommerce_activate' );
register_deactivation_hook( __FILE__, 'copy_paste_order_for_woocommerce_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 * @return void
 */
function copy_paste_order_for_woocommerce_init() {
	/**
	 * Import some common functions.
	 */
	require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/copy-paste-order-for-woocommerce-core-functions.php';

	/**
	 * Main plugin loader class.
	 */
	require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/class-copy-paste-order-for-woocommerce.php';

	$plugin = new Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Copy_Paste_Order_For_Woocommerce();
	$plugin->run();
}

copy_paste_order_for_woocommerce_init();
