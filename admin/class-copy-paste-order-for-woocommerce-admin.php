<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/admin
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Admin;

use WP_Screen;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/admin
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! copy_paste_order_for_woocommerce_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = copy_paste_order_for_woocommerce_admin_screens_with_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			if ( ! empty( $asset['types'] ) && ! in_array( 'style', $asset['types'], true ) ) {
				continue;
			}

			if ( isset( $asset['conditions'] ) && false === $asset['conditions'] ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				$asset_base_url = COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_URL . 'admin/';

				$asset_file = include COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php";

				wp_enqueue_style(
					"copy-paste-order-for-woocommerce-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/css/admin-{$asset['name']}.min.css",
					copy_paste_order_for_woocommerce_get_style_asset_dependencies( $asset_file['dependencies'] ),
					$asset_file['version'],
					'all'
				);
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! copy_paste_order_for_woocommerce_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = copy_paste_order_for_woocommerce_admin_screens_with_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				if ( ! empty( $asset['types'] ) && ! in_array( 'script', $asset['types'], true ) ) {
					continue;
				}

				if ( isset( $asset['conditions'] ) && false === $asset['conditions'] ) {
					continue;
				}

				$asset_base_url = COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_URL . 'admin/';

				$asset_file = include COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php";

				wp_register_script(
					"copy-paste-order-for-woocommerce-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/js/admin-{$asset['name']}.min.js",
					$asset_file['dependencies'],
					$asset_file['version'],
					array(
						'in_footer' => true,
					)
				);

				if ( ! empty( $asset['localized'] ) ) {
					wp_localize_script( "copy-paste-order-for-woocommerce-admin-{$asset['name']}", 'CPOFW', copy_paste_order_for_woocommerce_admin_screen_get_localized_data( $current_screen ) );
				}

				wp_enqueue_script( "copy-paste-order-for-woocommerce-admin-{$asset['name']}" );

				wp_set_script_translations( "copy-paste-order-for-woocommerce-admin-{$asset['name']}", 'copy-paste-order-for-woocommerce' );
			}
		}
	}

	/**
	 * Add copy order icon to billing address column.
	 *
	 * @param  string   $column    Column name.
	 * @param  int      $order_id  Order ID.
	 * @return void
	 */
	public function add_copy_icon_to_billing_column( $column, $order_id ) {
		if ( 'billing_address' !== $column ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		printf(
			'<span class="cpofw-copy-icon" data-order-id="%d" title="%s">📋</span>',
			absint( $order_id ),
			esc_attr__( 'Copy Order', 'copy-paste-order-for-woocommerce' )
		);
	}

	/**
	 * Add copy order containers for React mounting.
	 *
	 * @return void
	 */
	public function add_copy_order_container() {
		$current_screen = get_current_screen();

		if ( ! $current_screen || ! in_array( $current_screen->id, array( 'edit-shop_order', 'woocommerce_page_wc-orders' ), true ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		echo '<div id="cpofw-copy-order-containers" style="display: none;"></div>';
	}

	/**
	 * Add paste order button to orders page.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function add_paste_order_button() {
		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen || 'edit-shop_order' !== $current_screen->id ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		echo '<div id="cpofw-paste-order-container"></div>';
	}
}
