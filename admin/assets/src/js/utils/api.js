import apiFetch from '@wordpress/api-fetch'

/**
 * API call to copy an order.
 *
 * @since  1.0.0
 * @param  {int}  orderId  The ID of the order to copy.
 * @return {Promise}
 */
export const apiCopyOrder = async ( orderId ) => {
	const response = await apiFetch(
		{
			path: `/copy-paste-order-for-woocommerce/v1/order/${ orderId }/copy`,
		}
	)

	return response
}

/**
 * API call to paste/create an order.
 *
 * @since  1.0.0
 * @param  {Object}  orderData  The order data to paste.
 * @return {Promise}
 */
export const apiPasteOrder = async ( orderData ) => {
	const response = await apiFetch(
		{
			path: '/copy-paste-order-for-woocommerce/v1/order/paste',
			method: 'POST',
			data: orderData,
		}
	)

	return response
}
