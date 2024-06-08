<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode\Copy_Paste_Order_WooCommerce
 * @subpackage Dream_Encode\Copy_Paste_Order_WooCommerce/includes
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core;

use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Install\Copy_Paste_Order_For_Woocommerce_Install;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dream_Encode\Copy_Paste_Order_WooCommerce
 * @subpackage Dream_Encode\Copy_Paste_Order_WooCommerce/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce_Activator {
	/**
	 * Activator.
	 *
	 * Runs on plugin activation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function activate() {
		Copy_Paste_Order_For_Woocommerce_Install::install();
	}
}
