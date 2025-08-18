<?php
/**
 * Common functions for the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 */

/**
 * Define a constant if it is not already defined.
 *
 * @since  1.0.0
 * @param  string  $name   Constant name.
 * @param  mixed   $value  Constant value.
 * @return void
 */
function cpofw_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Get an array of data that relates enqueued assets to specific admin screens.
 *
 * @since  1.0.0
 * @return array<string, array<array<string, mixed>>>
 */
function cpofw_admin_screens_with_assets() {
	return array(
		'settings_page_copy-paste-order-for-woocommerce-settings' => array(
			array(
				'name'  => 'settings-page',
				'types' => array(
					'style',
					'script',
				),
			),
		),
		'edit-shop_order' => array(
			array(
				'name'      => 'orders-page',
				'localized' => true,
			),
		),
	);
}

/**
 * Get an array of localized data that relates enqueued assets to specific admin screens.
 *
 * @since  1.0.0
 * @return array<string, array<string, mixed>>
 */
function cpofw_get_admin_screens_localization_data() {
	return array(
		'edit-shop_order' => array(
			'NONCES' => array(
				'COPY_ORDER'  => wp_create_nonce( 'COPY_ORDER' ),
				'PASTE_ORDER' => wp_create_nonce( 'PASTE_ORDER' ),
				'REST'        => wp_create_nonce( 'wp_rest' ),
			),
		),
	);
}

/**
 * Get a list of WP style dependencies.
 *
 * @since  1.0.0
 * @return string[]
 */
function cpofw_get_wp_style_dependencies() {
	return array(
		'wp-components',
	);
}

/**
 * Get a list of WP style dependencies.
 *
 * @since  1.0.0
 * @param  string[]  $dependencies  Raw dependencies.
 * @return string[]
 */
function cpofw_get_style_asset_dependencies( $dependencies ) {
	$style_dependencies = cpofw_get_wp_style_dependencies();

	$new_dependencies = array();

	foreach ( $dependencies as $dependency ) {
		if ( in_array( $dependency, $style_dependencies, true ) ) {
			$new_dependencies[] = $dependency;
		}
	}

	return $new_dependencies;
}

/**
 * Check if the current admin screen has any enqueued assets.
 *
 * @since  1.0.0
 * @return int
 */
function cpofw_admin_current_screen_has_enqueued_assets() {
	return count( cpofw_admin_current_screen_enqueued_assets() );
}

/**
 * Get enqueued assets for the current admin screen.
 *
 * @since  1.0.0
 * @return array<mixed>
 */
function cpofw_admin_current_screen_enqueued_assets() {
	$current_screen = get_current_screen();

	if ( ! $current_screen instanceof WP_Screen ) {
		return array();
	}

	$assets = cpofw_admin_screens_with_assets();

	return ! empty( $assets[ $current_screen->id ] ) ? $assets[ $current_screen->id ] : array();
}

/**
 * Get an admin screen's localized data.
 *
 * @since  1.0.0
 * @param  WP_Screen  $screen  Screen to check.
 * @return mixed
 */
function cpofw_admin_screen_get_localized_data( $screen ) {
	$data = cpofw_get_admin_screens_localization_data();

	return ! empty( $data[ $screen->id ] ) ? $data[ $screen->id ] : array();
}

/**
 * Get a plugin setting by key.
 *
 * @since  1.0.0
 * @param  string  $key      Setting key.
 * @param  mixed   $default  Optional. Default value. Default false.
 * @return mixed
 */
function cpofw_get_plugin_setting( $key, $default = false ) {
	static $settings = false;

	if ( false === $settings ) {
		$settings = get_option( 'cpofw_settings', array() );
	}

	if ( isset( $settings[ $key ] ) ) {
		return $settings[ $key ];
	}

	return $default;
}

/**
 * Get a MYSQL DateTime from a timestamp.
 *
 * @since  1.0.0
 * @param  false|float|int  $time             Optional. Timestamp to convert.  Default false.
 * @param  string           $timezone_string  Optional. Timezone string. Default UTC.
 * @return string|false
 */
function cpofw_get_mysql_datetime( $time = false, $timezone_string = 'UTC' ) {
	if ( ! $time ) {
		$time = time();
	}

	if ( ! $timezone_string ) {
		$timezone_string = 'UTC';
	}

	$timezone = new DateTimeZone( $timezone_string );

	return wp_date( 'Y-m-d H:i:s', intval( $time ), $timezone );
}

/**
 * Get complete order data for copying.
 *
 * @since  1.0.0
 * @param  WC_Order  $order  Order to get data.
 * @return array
 */
function cpofw_get_complete_order_data( $order ) {
	$order_data = $order->get_data();

	$date_properties = array(
		'date_created',
		'date_modified',
		'date_completed',
		'date_paid',
	);

	foreach ( $date_properties as $date_property ) {
		if ( isset( $order_data[ $date_property ] ) && $order_data[ $date_property ] instanceof WC_DateTime ) {
			$order_data[ $date_property ] = $order_data[ $date_property ]->format( 'Y-m-d H:i:s' );
		}
	}

	$order_data['meta_data'] = $order->get_meta_data();

	$line_items_array = array();

	foreach ( $order_data['line_items'] as $item_id => $line_item ) {
		if ( $line_item instanceof WC_Order_Item_Product ) {
			$item_data = $line_item->get_data();

			$item_data['meta_data'] = $line_item->get_meta_data();

			$line_items_array[ $item_id ] = $item_data;
		}
	}

	$order_data['line_items'] = $line_items_array;

	$shipping_lines_array = array();

	foreach ( $order_data['shipping_lines'] as $item_id => $shipping_line ) {
		if ( $shipping_line instanceof WC_Order_Item_Shipping ) {
			$item_data = $shipping_line->get_data();

			$item_data['meta_data'] = $shipping_line->get_meta_data();

			$shipping_lines_array[ $item_id ] = $item_data;
		}
	}

	$order_data['shipping_lines'] = $shipping_lines_array;

	$fee_lines_array = array();

	foreach ( $order_data['fee_lines'] as $item_id => $fee_line ) {
		if ( $fee_line instanceof WC_Order_Item_Fee ) {
			$item_data = $fee_line->get_data();

			$item_data['meta_data'] = $fee_line->get_meta_data();

			$fee_lines_array[ $item_id ] = $item_data;
		}
	}

	$order_data['fee_lines'] = $fee_lines_array;

	$coupon_lines_array = array();

	foreach ( $order_data['coupon_lines'] as $item_id => $coupon_line ) {
		if ( $coupon_line instanceof WC_Order_Item_Coupon ) {
			$item_data = $coupon_line->get_data();

			$item_data['meta_data'] = $coupon_line->get_meta_data();

			$coupon_lines_array[ $item_id ] = $item_data;
		}
	}

	$order_data['coupon_lines'] = $coupon_lines_array;

	$tax_lines_array = array();

	foreach ( $order_data['tax_lines'] as $item_id => $tax_line ) {
		if ( $tax_line instanceof WC_Order_Item_Tax ) {
			$item_data = $tax_line->get_data();

			$item_data['meta_data'] = $tax_line->get_meta_data();

			$tax_lines_array[ $item_id ] = $item_data;
		}
	}

	$order_data['tax_lines'] = $tax_lines_array;

	$order_data['_cpofw_export'] = array(
		'version'         => COPY_PASTE_ORDER_FOR_WOOCOMMERCE_ORDER_JSON_VERSION,
		'exported_at'     => cpofw_get_mysql_datetime(),
		'source_site'     => get_site_url(),
		'source_order_id' => $order->get_id(),
	);

	/**
	 * Filter the complete order data before export.
	 *
	 * @since  1.0.0
	 * @param  array     $order_data  The order data array.
	 * @param  WC_Order  $order       The original order object.
	 */
	$order_data = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/export-order-data', $order_data, $order );

	return $order_data;
}

/**
 * Get encoded JSON data for an order.
 *
 * @since  1.0.0
 * @param  WC_Order  $order  Order to get data.
 * @return string
 */
function cpofw_get_json_order_data( $order ) {
	$order_data = cpofw_get_complete_order_data( $order );

	/**
	 * Filter the order data before JSON encoding.
	 *
	 * @since  1.0.0
	 * @param  array     $order_data  The order data array.
	 * @param  WC_Order  $order       The original order object.
	 */
	$order_data = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/before-json-encode', $order_data, $order );

	$json_data = wp_json_encode( $order_data, JSON_PRETTY_PRINT );

	/**
	 * Filter the JSON encoded order data.
	 *
	 * @since  1.0.0
	 * @param  string    $json_data   The JSON encoded order data.
	 * @param  array     $order_data  The order data array.
	 * @param  WC_Order  $order       The original order object.
	 */
	$json_data = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/json-encoded-data', (string) $json_data, $order_data, $order );

	return $json_data;
}

/**
 * Validate order data for pasting.
 *
 * @since  1.0.0
 * @param  array  $order_data  Order data to validate.
 * @return array{valid: bool, errors: string[]}
 */
function cpofw_validate_order_data( $order_data ) {
	$errors = array();

	if ( ! isset( $order_data['_cpofw_export'] ) ) {
		$errors[] = __( 'This does not appear to be a valid CPOFW order export.', 'copy-paste-order-for-woocommerce' );
	}

	$required_fields = array( 'status', 'total', 'line_items' );

	foreach ( $required_fields as $field ) {
		if ( ! isset( $order_data[ $field ] ) ) {
			$errors[] = sprintf(
				/* translators: %s: Missing field name. */
				__( 'Missing required field: %s', 'copy-paste-order-for-woocommerce' ),
				$field
			);
		}
	}

	if ( isset( $order_data['line_items'] ) && is_array( $order_data['line_items'] ) ) {
		if ( empty( $order_data['line_items'] ) ) {
			$errors[] = __( 'Order must have at least one line item.', 'copy-paste-order-for-woocommerce' );
		}
	}

	return array(
		'valid'  => empty( $errors ),
		'errors' => $errors,
	);
}

/**
 * Create a new order from pasted order data.
 *
 * @since  1.0.0
 * @param  array  $order_data  Order data to create from.
 * @return array{success: bool, order_id?: int, edit_url?: string, message: string}
 */
function cpofw_create_order_from_data( $order_data ) {
	try {
		/**
		 * Filter the order data before validation and import.
		 *
		 * @since  1.0.0
		 * @param  array  $order_data  The order data array from JSON.
		 */
		$order_data = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-order/before-validation', $order_data );

		$validation = cpofw_validate_order_data( $order_data );

		if ( ! $validation['valid'] ) {
			return array(
				'success' => false,
				'message' => implode( ' ', $validation['errors'] ),
			);
		}

		$order = wc_create_order();

		if ( is_wp_error( $order ) ) {
			return array(
				'success' => false,
				'message' => $order->get_error_message(),
			);
		}

		$properties_to_copy = array(
			'status',
			'currency',
			'customer_id',
			'customer_note',
			'billing',
			'shipping',
			'payment_method',
			'payment_method_title',
			'transaction_id',
			'customer_ip_address',
			'customer_user_agent',
		);

		$date_properties = array(
			'date_created',
			'date_modified',
			'date_completed',
			'date_paid',
		);

		foreach ( $date_properties as $date_property ) {
			if ( isset( $order_data[ $date_property ] ) && ! empty( $order_data[ $date_property ] ) ) {
				$setter = "set_{$date_property}";

				if ( method_exists( $order, $setter ) ) {
					$date_value = $order_data[ $date_property ];

					if ( is_string( $date_value ) ) {
						$date_value = cpofw_get_mysql_datetime( strtotime( $date_value ) );
					} elseif ( is_array( $date_value ) && isset( $date_value['date'] ) ) {
						$date_value = $date_value['date'];
					}

					$order->{ $setter }( $date_value );
				}
			}
		}

		foreach ( $properties_to_copy as $property ) {
			if ( isset( $order_data[ $property ] ) ) {
				$setter = "set_{$property}";

				if ( method_exists( $order, $setter ) ) {
					$order->{ $setter }( $order_data[ $property ] );
				}
			}
		}

		/**
		 * Filter the order after basic properties are set but before items are added.
		 *
		 * @since  1.0.0
		 * @param  WC_Order  $order       The order object being created.
		 * @param  array     $order_data  The order data array from JSON.
		 */
		$order = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-order/before-items', $order, $order_data );

		if ( isset( $order_data['line_items'] ) && is_array( $order_data['line_items'] ) ) {
			foreach ( $order_data['line_items'] as $line_item_data ) {
				$item = new WC_Order_Item_Product();

				$item_properties = array(
					'name',
					'product_id',
					'variation_id',
					'quantity',
					'subtotal',
					'subtotal_tax',
					'total',
					'total_tax',
					'tax_class',
				);

				foreach ( $item_properties as $property ) {
					if ( isset( $line_item_data[ $property ] ) ) {
						$setter = "set_{$property}";

						if ( method_exists( $item, $setter ) ) {
							$item->{ $setter }( $line_item_data[ $property ] );
						}
					}
				}

				if ( isset( $line_item_data['meta_data'] ) && is_array( $line_item_data['meta_data'] ) ) {
					foreach ( $line_item_data['meta_data'] as $meta ) {
						$key   = null;
						$value = null;

						if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
							$key   = $meta->key;
							$value = $meta->value;
						} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
							$key   = $meta['key'];
							$value = $meta['value'] ?? null;
						}

						if ( $key && ! is_null( $value ) ) {
							$item->add_meta_data( $key, $value );
						}
					}
				}

				/**
				 * Filter the line item before adding to order.
				 *
				 * @since  1.0.0
				 * @param  WC_Order_Item_Product  $item            The line item object.
				 * @param  array                  $line_item_data  The line item data from JSON.
				 * @param  WC_Order               $order           The order object.
				 */
				$item = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-line-item', $item, $line_item_data, $order );

				$order->add_item( $item );
			}
		}

		if ( isset( $order_data['shipping_lines'] ) && is_array( $order_data['shipping_lines'] ) ) {
			foreach ( $order_data['shipping_lines'] as $shipping_data ) {
				$item = new WC_Order_Item_Shipping();

				$item->set_method_title( $shipping_data['method_title'] ?? '' );
				$item->set_method_id( $shipping_data['method_id'] ?? '' );
				$item->set_total( $shipping_data['total'] ?? 0 );

				if ( isset( $shipping_data['meta_data'] ) && is_array( $shipping_data['meta_data'] ) ) {
					foreach ( $shipping_data['meta_data'] as $meta ) {
						$key   = null;
						$value = null;

						if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
							$key   = $meta->key;
							$value = $meta->value;
						} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
							$key   = $meta['key'];
							$value = $meta['value'] ?? null;
						}

						if ( $key && ! is_null( $value ) ) {
							$item->add_meta_data( $key, $value );
						}
					}
				}

				/**
				 * Filter the shipping item before adding to order.
				 *
				 * @since  1.0.0
				 * @param  WC_Order_Item_Shipping  $item           The shipping item object.
				 * @param  array                   $shipping_data  The shipping data from JSON.
				 * @param  WC_Order                $order          The order object.
				 */
				$item = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-shipping-item', $item, $shipping_data, $order );

				$order->add_item( $item );
			}
		}

		if ( isset( $order_data['fee_lines'] ) && is_array( $order_data['fee_lines'] ) ) {
			foreach ( $order_data['fee_lines'] as $fee_data ) {
				$item = new WC_Order_Item_Fee();

				$item->set_name( $fee_data['name'] ?? '' );
				$item->set_total( $fee_data['total'] ?? 0 );

				if ( isset( $fee_data['meta_data'] ) && is_array( $fee_data['meta_data'] ) ) {
					foreach ( $fee_data['meta_data'] as $meta ) {
						$key   = null;
						$value = null;

						if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
							$key   = $meta->key;
							$value = $meta->value;
						} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
							$key   = $meta['key'];
							$value = $meta['value'] ?? null;
						}

						if ( $key && ! is_null( $value ) ) {
							$item->add_meta_data( $key, $value );
						}
					}
				}

				/**
				 * Filter the fee item before adding to order.
				 *
				 * @since  1.0.0
				 * @param  WC_Order_Item_Fee  $item      The fee item object.
				 * @param  array              $fee_data  The fee data from JSON.
				 * @param  WC_Order           $order     The order object.
				 */
				$item = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-fee-item', $item, $fee_data, $order );

				$order->add_item( $item );
			}
		}

		if ( isset( $order_data['coupon_lines'] ) && is_array( $order_data['coupon_lines'] ) ) {
			foreach ( $order_data['coupon_lines'] as $coupon_data ) {
				$item = new WC_Order_Item_Coupon();

				$coupon_properties = array(
					'code',
					'discount',
					'discount_tax',
				);

				foreach ( $coupon_properties as $property ) {
					if ( isset( $coupon_data[ $property ] ) ) {
						$setter = "set_{$property}";

						if ( method_exists( $item, $setter ) ) {
							$item->{ $setter }( $coupon_data[ $property ] );
						}
					}
				}

				if ( isset( $coupon_data['meta_data'] ) && is_array( $coupon_data['meta_data'] ) ) {
					foreach ( $coupon_data['meta_data'] as $meta ) {
						$key   = null;
						$value = null;

						if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
							$key   = $meta->key;
							$value = $meta->value;
						} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
							$key   = $meta['key'];
							$value = $meta['value'] ?? null;
						}

						if ( $key && ! is_null( $value ) ) {
							$item->add_meta_data( $key, $value );
						}
					}
				}

				/**
				 * Filter the coupon item before adding to order.
				 *
				 * @since  1.0.0
				 * @param  WC_Order_Item_Coupon  $item         The coupon item object.
				 * @param  array                 $coupon_data  The coupon data from JSON.
				 * @param  WC_Order              $order        The order object.
				 */
				$item = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-coupon-item', $item, $coupon_data, $order );

				$order->add_item( $item );
			}
		}

		if ( isset( $order_data['tax_lines'] ) && is_array( $order_data['tax_lines'] ) ) {
			foreach ( $order_data['tax_lines'] as $tax_data ) {
				$item = new WC_Order_Item_Tax();

				$tax_properties = array(
					'rate_code',
					'label',
					'compound',
					'rate_id',
					'tax_total',
					'shipping_tax_total',
				);

				foreach ( $tax_properties as $property ) {
					if ( isset( $tax_data[ $property ] ) ) {
						$setter = "set_{$property}";

						if ( method_exists( $item, $setter ) ) {
							$item->{ $setter }( $tax_data[ $property ] );
						}
					}
				}

				if ( isset( $tax_data['meta_data'] ) && is_array( $tax_data['meta_data'] ) ) {
					foreach ( $tax_data['meta_data'] as $meta ) {
						$key   = null;
						$value = null;

						if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
							$key   = $meta->key;
							$value = $meta->value;
						} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
							$key   = $meta['key'];
							$value = $meta['value'] ?? null;
						}

						if ( $key && ! is_null( $value ) ) {
							$item->add_meta_data( $key, $value );
						}
					}
				}

				/**
				 * Filter the tax item before adding to order.
				 *
				 * @since  1.0.0
				 * @param  WC_Order_Item_Tax  $item      The tax item object.
				 * @param  array              $tax_data  The tax data from JSON.
				 * @param  WC_Order           $order     The order object.
				 */
				$item = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-tax-item', $item, $tax_data, $order );

				$order->add_item( $item );
			}
		}

		if ( isset( $order_data['meta_data'] ) && is_array( $order_data['meta_data'] ) ) {
			foreach ( $order_data['meta_data'] as $meta ) {
				$key   = null;
				$value = null;

				if ( is_object( $meta ) && property_exists( $meta, 'key' ) && property_exists( $meta, 'value' ) ) {
					$key   = $meta->key;
					$value = $meta->value;
				} elseif ( is_array( $meta ) && isset( $meta['key'] ) ) {
					$key   = $meta['key'];
					$value = $meta['value'] ?? null;
				}

				if ( $key && ! is_null( $value ) ) {
					if ( str_starts_with( $key, '_' ) && in_array( $key, array( '_order_key', '_order_stock_reduced' ), true ) ) {
						continue;
					}

					$order->add_meta_data( $key, $value );
				}
			}
		}

		if ( isset( $order_data['id'] ) ) {
			$order->add_meta_data( '_cpofw_copied_order_id', $order_data['id'] );
		}

		if ( isset( $order_data['_cpofw_export']['source_site'] ) ) {
			$order->add_meta_data( '_cpofw_source_site', $order_data['_cpofw_export']['source_site'] );
		}

		if ( isset( $order_data['_cpofw_export']['source_order_id'] ) ) {
			$order->add_meta_data( '_cpofw_source_order_id', $order_data['_cpofw_export']['source_order_id'] );
		}

		/**
		 * Filter the order after all items are added but before saving.
		 *
		 * @since  1.0.0
		 * @param  WC_Order  $order       The order object with all items added.
		 * @param  array     $order_data  The complete order data array from JSON.
		 */
		$order = apply_filters( 'dream-encode/copy-paste-order-for-woocommerce/import-order/before-save', $order, $order_data );

		$order->calculate_totals();
		$order->save();

		if ( isset( $order_data['_cpofw_export']['source_site'] ) ) {
			$source_site     = $order_data['_cpofw_export']['source_site'];
			$source_order_id = $order_data['_cpofw_export']['source_order_id'] ?? $order_data['id'] ?? 'Unknown';

			$note = sprintf(
				/* translators: 1. Source site URL, 2. Source order ID. */
				__( 'This order was copied from %1$s order ID %2$s.', 'copy-paste-order-for-woocommerce' ),
				$source_site,
				$source_order_id
			);

			$order->add_order_note( $note );
		}

		/**
		 * Action hook fired after order is completely created and saved.
		 *
		 * @since  1.0.0
		 * @param  WC_Order  $order      The created order object.
		 * @param  array     $order_data The complete order data array from JSON.
		 */
		do_action( 'cpofw_order_imported', $order, $order_data );

		return array(
			'success'  => true,
			'order_id' => $order->get_id(),
			'edit_url' => admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ),
			'message'  => sprintf(
				/* translators: %d: Order ID. */
				__( 'Order #%d created successfully.', 'copy-paste-order-for-woocommerce' ),
				$order->get_id()
			),
		);
	} catch ( Exception $e ) {
		return array(
			'success' => false,
			'message' => $e->getMessage(),
		);
	}
}
