/* global CPOFW */

/**
 * Get localized data from the global CPOFW object
 */
const getLocalizedData = () => {
	if ( typeof CPOFW === 'undefined' ) {
		console.error( 'CPOFW localized data not found' )
		return {}
	}
	return CPOFW
}

/**
 * Get fetch options for GET requests
 */
export const fetchGetOptions = () => {
	const { NONCES } = getLocalizedData()
	
	return {
		headers: {
			'X-WP-Nonce': NONCES?.REST || ''
		}
	}
}

/**
 * Get fetch options for POST requests
 */
export const fetchPostOptions = ( postData ) => {
	const { NONCES } = getLocalizedData()
	
	return {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': NONCES?.REST || ''
		},
		body: JSON.stringify( postData )
	}
}

/**
 * API call to copy an order
 */
export const apiCopyOrder = async ( orderId ) => {
	try {
		const response = await fetch(
			`/wp-json/dream-encode/copy-paste-order-for-woocommerce/v1/order/${orderId}/copy`,
			fetchGetOptions()
		)
		
		if ( ! response.ok ) {
			throw new Error( `HTTP error! status: ${response.status}` )
		}
		
		return await response.json()
	} catch ( error ) {
		console.error( 'Error copying order:', error )
		throw error
	}
}

/**
 * API call to paste/create an order
 */
export const apiPasteOrder = async ( orderData ) => {
	try {
		const response = await fetch(
			'/wp-json/dream-encode/copy-paste-order-for-woocommerce/v1/order/paste',
			fetchPostOptions( orderData )
		)
		
		if ( ! response.ok ) {
			throw new Error( `HTTP error! status: ${response.status}` )
		}
		
		return await response.json()
	} catch ( error ) {
		console.error( 'Error pasting order:', error )
		throw error
	}
}
