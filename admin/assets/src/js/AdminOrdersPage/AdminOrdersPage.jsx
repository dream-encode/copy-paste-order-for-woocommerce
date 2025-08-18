import { __ } from '@wordpress/i18n'
import { useEffect } from '@wordpress/element'
import domReady from '@wordpress/dom-ready'

import CopyOrderButton from '@/components/CopyOrderButton/CopyOrderButton'
import PasteOrderButton from '@/components/PasteOrderButton/PasteOrderButton'

const AdminOrdersPage = () => {
	useEffect( () => {
		const handleCopyOrderClick = ( event ) => {
			const icon = event.target.closest( '.cpofw-copy-icon' )

			if ( ! icon ) {
				return
			}

			event.preventDefault()
			event.stopPropagation()
			event.stopImmediatePropagation()

			let orderId = icon.dataset.orderId

			if ( ! orderId ) {
				const row = icon.closest( 'tr' )

				if ( row ) {
					if ( row.dataset && row.dataset.id ) {
						orderId = row.dataset.id
					} else if ( row.id ) {
						const orderIdMatch = row.id.match( /post-(\d+)/ )

						if ( orderIdMatch ) {
							orderId = orderIdMatch[1]
						}
					}
				}
			}

			if ( ! orderId ) {
				return
			}

			let container = document.querySelector( `.cpofw-copy-order-container[data-order-id="${ orderId }"]` )

			if ( ! container ) {
				const containersDiv = document.getElementById( 'cpofw-copy-order-containers' )

				if ( containersDiv ) {
					container = document.createElement( 'div' )

					container.className       = 'cpofw-copy-order-container'
					container.dataset.orderId = orderId
					container.style.display   = 'none'

					containersDiv.appendChild( container )
				}
			}

			if ( ! container.dataset.initialized ) {
				const root = wp.element.createRoot( container )

				const copyButton = wp.element.createElement( CopyOrderButton, {
					orderId: orderId,
					autoOpen: true
				} )

				root.render( copyButton )

				container.dataset.initialized = 'true'
			} else {
				const copyButton = container.querySelector( '.cpofw-copy-order-button' )

				if ( copyButton ) {
					copyButton.click()
				}
			}
		}

		const initializePasteButton = () => {
			const pasteContainer = document.getElementById( 'cpofw-paste-order-container' )

			if ( pasteContainer && ! pasteContainer.dataset.initialized ) {
				const root = wp.element.createRoot( pasteContainer )

				root.render( <PasteOrderButton /> )

				pasteContainer.dataset.initialized = 'true'
			}
		}

		const addCopyIcons = () => {
			const orderRows = document.querySelectorAll( 'tr[data-id], tr[id^="post-"]' )

			orderRows.forEach( ( row ) => {
				if ( row.querySelector( '.cpofw-copy-icon' ) ) {
					return
				}

				let orderId = null

				if ( row.dataset && row.dataset.id ) {
					orderId = row.dataset.id
				} else if ( row.id ) {
					const match = row.id.match( /post-(\d+)/ )

					if ( match ) {
						orderId = match[1]
					}
				}

				if ( ! orderId ) {
					return
				}

				let orderCell = row.querySelector( '.order_number' ) ||
								row.querySelector( '.column-order_number' ) ||
								row.querySelector( 'td:not(.hidden)' ) ||
								row.querySelector( 'td' )

				if ( ! orderCell ) {
					return
				}

				const copyIcon = document.createElement( 'span' )

				copyIcon.className       = 'cpofw-copy-icon'
				copyIcon.dataset.orderId = orderId
				copyIcon.title           = 'Copy Order'
				copyIcon.innerHTML       = '📋'
				copyIcon.style.marginLeft = '8px'
				copyIcon.style.cursor    = 'pointer'

				copyIcon.addEventListener( 'click', ( event ) => {
					event.preventDefault()
					event.stopPropagation()
					event.stopImmediatePropagation()

					handleCopyOrderClick( event )
				} )

				orderCell.appendChild( copyIcon )
			} )
		}

		initializePasteButton()

		setTimeout( addCopyIcons, 500 )

		const observer = new MutationObserver( ( mutations ) => {
			mutations.forEach( ( mutation ) => {
				if ( mutation.type === 'childList' && mutation.addedNodes.length > 0 ) {
					initializePasteButton()

					setTimeout( addCopyIcons, 100 )
				}
			} )
		} )

		observer.observe( document.body, {
			childList: true,
			subtree: true
		} )

		return () => {
			document.removeEventListener( 'click', handleCopyOrderClick )

			observer.disconnect()
		}
	}, [] )

	return null
}

export default AdminOrdersPage