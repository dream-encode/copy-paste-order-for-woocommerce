<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/public
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Frontend;

use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\RestApi\Copy_Paste_Order_For_Woocommerce_Core_API;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/public
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce_Public {

	/**
	 * Initialize rest api instances.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_init() {
		$api = new Copy_Paste_Order_For_Woocommerce_Core_API();
	}
}
