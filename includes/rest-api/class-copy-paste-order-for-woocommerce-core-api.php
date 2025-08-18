<?php
/**
 * Class Copy_Paste_Order_For_Woocommerce_Core_API
 *
 * @since 1.0.0
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core\RestApi;

use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Abstracts\Copy_Paste_Order_For_Woocommerce_Abstract_API;

defined( 'ABSPATH' ) || exit;

/**
 * Class Copy_Paste_Order_For_Woocommerce_Core_API
 *
 * @since 1.0.0
 */
class Copy_Paste_Order_For_Woocommerce_Core_API extends Copy_Paste_Order_For_Woocommerce_Abstract_API {
	/**
	 * Includes files
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_includes() {
		parent::rest_api_includes();

		$path_version = 'includes/rest-api' . DIRECTORY_SEPARATOR . $this->version . DIRECTORY_SEPARATOR . 'frontend';

		include_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . $path_version . '/class-copy-paste-order-for-woocommerce-rest-order-controller.php';
	}

	/**
	 * Register all routes.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_register_routes() {
		$controllers = array(
			'Copy_Paste_Order_For_Woocommerce_REST_Order_Controller',
		);

		$this->controllers = $controllers;

		parent::rest_api_register_routes();
	}
}
