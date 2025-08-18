import {
	createRoot
} from '@wordpress/element'

import domReady from '@wordpress/dom-ready'

import AdminOrdersPage from '@/AdminOrdersPage/AdminOrdersPage'

domReady( () => {
	// Create a container for the app if it doesn't exist
	let container = document.getElementById( 'cpofw-admin-orders-page' )
	if ( ! container ) {
		container = document.createElement( 'div' )
		container.id = 'cpofw-admin-orders-page'
		document.body.appendChild( container )
	}

	const root = createRoot( container )
	root.render( <AdminOrdersPage /> )
} )
