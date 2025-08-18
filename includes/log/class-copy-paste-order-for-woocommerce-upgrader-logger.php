<?php
/**
 * Simple wrapper class for custom logs.
 *
 * @uses \WC_Logger();
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Log;

/**
 * Logger class.
 *
 * Log stuff to files.
 *
 * @since      1.0.0
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce_Upgrader_Logger {
	/**
	 * Log namespace.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string  $namespace  Log namespace.
	 */
	public static $namespace = 'copy-paste-order-for-woocommerce-upgrader';

	/**
	 * Log data.
	 *
	 * @since  1.0.0
	 * @param  mixed  $data  Data to log.
	 * @return void
	 */
	public static function log( $data ) {
		$logger = wc_get_logger();

		if ( is_object( $data ) || is_array( $data ) ) {
			$data = print_r( $data, true );
		}

		$logger->info( $data, array( 'source' => self::$namespace ) );
	}
}
