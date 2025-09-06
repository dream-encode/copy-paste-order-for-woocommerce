<?php
/**
 * Class Copy_Paste_Order_For_Woocommerce_REST_Order_Controller
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core\RestApi\V1\Frontend;

use Exception;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WC_Order;

use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\RestApi\Copy_Paste_Order_For_Woocommerce_REST_Response;
use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Abstracts\Copy_Paste_Order_For_Woocommerce_Abstract_REST_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Class Copy_Paste_Order_For_Woocommerce_REST_Order_Controller
 */
class Copy_Paste_Order_For_Woocommerce_REST_Order_Controller extends Copy_Paste_Order_For_Woocommerce_Abstract_REST_Controller {
	/**
	 * Copy_Paste_Order_For_Woocommerce_REST_Order_Controller constructor.
	 */
	public function __construct() {
		$this->rest_base = 'order';
	}

	/**
	 * Register routes API.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function register_routes() {
		$this->routes = array(
			'(?P<id>[\d]+)/copy' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'copy_order' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'copy-paste-order-for-woocommerce' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'paste'              => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'paste_order' ),
					'permission_callback' => array( $this, 'check_shop_manager_permission' ),
				),
			),
		);

		parent::register_routes();
	}

	/**
	 * Validate user permissions.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get prepared JSON data of an order.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function copy_order( $request ) {
		$response = new Copy_Paste_Order_For_Woocommerce_REST_Response();

		$success = false;

		try {
			$order_id = $request->get_param( 'id' );

			$order = wc_get_order( $order_id );

			if ( ! $order instanceof WC_Order ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_forbidden_context',
						__( 'Invalid order ID.', 'copy-paste-order-for-woocommerce' ),
						array( 'status' => '200' )
					)
				);
			}

			$success = true;

			$response->status = '200';
			$response->data   = copy_paste_order_for_woocommerce_get_json_order_data( $order );
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = $success;
		$response->status  = $success ? '200' : '401';

		return rest_ensure_response( $response );
	}

	/**
	 * Paste an order.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function paste_order( $request ) {
		$response = new Copy_Paste_Order_For_Woocommerce_REST_Response();

		$success = false;

		try {
			$order_data = $request->get_json_params();

			if ( empty( $order_data ) ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_invalid_data',
						__( 'No order data provided.', 'copy-paste-order-for-woocommerce' ),
						array( 'status' => 400 )
					)
				);
			}

			$result = copy_paste_order_for_woocommerce_create_order_from_data( $order_data );

			if ( $result['success'] ) {
				$success           = true;
				$response->data    = array();

				if ( isset( $result['order_id'] ) ) {
					$response->data['order_id'] = $result['order_id'];
				}

				if ( isset( $result['edit_url'] ) ) {
					$response->data['edit_url'] = $result['edit_url'];
				}

				$response->message = $result['message'];
			} else {
				$response->message = $result['message'];
			}

			$response->status = '200';
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = $success;
		$response->status  = $success ? '200' : '400';

		return rest_ensure_response( $response );
	}
}
