<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'copy-paste-order-for-woocommerce',
			false,
			COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'languages/'
		);
	}
}
