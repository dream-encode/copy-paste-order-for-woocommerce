<?php
/**
 * PHPStan type definitions for Copy Paste Order for WooCommerce
 *
 * @package Copy_Paste_Order_For_WooCommerce
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Type definition for order data structure used in copy/paste operations.
 *
 * This class serves as a PHPStan type definition and should not be instantiated.
 * It defines the exact shape of the order data array that is serialized to JSON
 * during copy operations and deserialized during paste operations.
 *
 * @phpstan-type OrderItemMetaData array{
 *     id?: int,
 *     key: string,
 *     value: mixed,
 *     display_key?: string,
 *     display_value?: string
 * }
 *
 * @phpstan-type OrderLineItemData array{
 *     id?: int,
 *     name: string,
 *     product_id: int,
 *     variation_id: int,
 *     quantity: int,
 *     subtotal: string,
 *     subtotal_tax: string,
 *     total: string,
 *     total_tax: string,
 *     tax_class: string,
 *     taxes?: array,
 *     meta_data?: OrderItemMetaData[]
 * }
 *
 * @phpstan-type OrderShippingItemData array{
 *     id?: int,
 *     method_title: string,
 *     method_id: string,
 *     instance_id: string,
 *     total: string,
 *     total_tax: string,
 *     taxes?: array,
 *     meta_data?: OrderItemMetaData[]
 * }
 *
 * @phpstan-type OrderFeeItemData array{
 *     id?: int,
 *     name: string,
 *     tax_class: string,
 *     tax_status: string,
 *     total: string,
 *     total_tax: string,
 *     taxes?: array,
 *     meta_data?: OrderItemMetaData[]
 * }
 *
 * @phpstan-type OrderCouponItemData array{
 *     id?: int,
 *     code: string,
 *     discount: string,
 *     discount_tax: string,
 *     meta_data?: OrderItemMetaData[]
 * }
 *
 * @phpstan-type OrderTaxItemData array{
 *     id?: int,
 *     rate_code: string,
 *     rate_id: int,
 *     label: string,
 *     compound: bool,
 *     tax_total: string,
 *     shipping_tax_total: string,
 *     rate_percent?: float,
 *     meta_data?: OrderItemMetaData[]
 * }
 *
 * @phpstan-type OrderAddressData array{
 *     first_name: string,
 *     last_name: string,
 *     company: string,
 *     address_1: string,
 *     address_2: string,
 *     city: string,
 *     state: string,
 *     postcode: string,
 *     country: string,
 *     email?: string,
 *     phone?: string
 * }
 *
 * @phpstan-type OrderMetaData array{
 *     id?: int,
 *     key: string,
 *     value: mixed
 * }
 *
 * @phpstan-type OrderExportData array{
 *     version: string,
 *     exported_at: string,
 *     source_site: string,
 *     source_order_id: int
 * }
 *
 * @phpstan-type OrderData array{
 *     id?: int,
 *     parent_id: int,
 *     status: string,
 *     currency: string,
 *     version: string,
 *     prices_include_tax: bool,
 *     date_created?: string,
 *     date_modified?: string,
 *     date_completed?: string,
 *     date_paid?: string,
 *     discount_total: string,
 *     discount_tax: string,
 *     shipping_total: string,
 *     shipping_tax: string,
 *     cart_tax: string,
 *     total: string,
 *     total_tax: string,
 *     customer_id: int,
 *     order_key: string,
 *     billing: OrderAddressData,
 *     shipping: OrderAddressData,
 *     payment_method: string,
 *     payment_method_title: string,
 *     transaction_id: string,
 *     customer_ip_address: string,
 *     customer_user_agent: string,
 *     created_via: string,
 *     customer_note: string,
 *     line_items: OrderLineItemData[],
 *     tax_lines: OrderTaxItemData[],
 *     shipping_lines: OrderShippingItemData[],
 *     fee_lines: OrderFeeItemData[],
 *     coupon_lines: OrderCouponItemData[],
 *     meta_data: OrderMetaData[],
 *     _cpofw_export: OrderExportData
 * }
 */
final class OrderDataType {
    /**
     * Private constructor to prevent instantiation.
     * This class is only for PHPStan type definitions.
     */
    private function __construct() {}
}
