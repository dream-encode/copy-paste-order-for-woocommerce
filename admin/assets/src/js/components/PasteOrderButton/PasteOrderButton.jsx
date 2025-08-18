import { __ } from '@wordpress/i18n'
import { useState, useEffect } from '@wordpress/element'
import { Button, Modal, Spinner, Notice, __experimentalVStack as VStack } from '@wordpress/components'
import { upload } from '@wordpress/icons'

import OrderSummary from '@/components/OrderSummary/OrderSummary'
import { apiPasteOrder } from '@/utils/api'

const PasteOrderButton = () => {
	const [ isModalOpen, setIsModalOpen ] = useState( false )
	const [ isLoading, setIsLoading ] = useState( false )
	const [ orderData, setOrderData ] = useState( null )
	const [ error, setError ] = useState( null )
	const [ isCreating, setIsCreating ] = useState( false )
	const [ success, setSuccess ] = useState( false )

	// Position the button next to "Add New Order"
	useEffect( () => {
		const addNewButton = document.querySelector( '.page-title-action' )
		if ( addNewButton && addNewButton.parentNode ) {
			const container = document.getElementById( 'cpofw-paste-order-container' )
			if ( container ) {
				// Insert after the "Add New Order" button
				addNewButton.parentNode.insertBefore( container, addNewButton.nextSibling )
				container.style.display = 'inline-block'
				container.style.marginLeft = '10px'
			}
		}
	}, [] )

	const openModal = async () => {
		setIsModalOpen( true )
		setIsLoading( true )
		setError( null )
		setOrderData( null )

		try {
			// Read from clipboard
			const clipboardText = await navigator.clipboard.readText()
			
			if ( ! clipboardText.trim() ) {
				setError( __( 'Clipboard is empty.', 'copy-paste-order-for-woocommerce' ) )
				setIsLoading( false )
				return
			}

			// Try to parse JSON
			let parsedData
			try {
				parsedData = JSON.parse( clipboardText )
			} catch ( parseError ) {
				setError( __( 'Clipboard content is not valid JSON.', 'copy-paste-order-for-woocommerce' ) )
				setIsLoading( false )
				return
			}

			// Basic validation - check if it looks like order data
			if ( ! parsedData || typeof parsedData !== 'object' ) {
				setError( __( 'Invalid order data format.', 'copy-paste-order-for-woocommerce' ) )
				setIsLoading( false )
				return
			}

			// Check for required order fields
			const requiredFields = [ 'id', 'status', 'total', 'line_items' ]
			const missingFields = requiredFields.filter( field => ! ( field in parsedData ) )
			
			if ( missingFields.length > 0 ) {
				setError( 
					__( 'Missing required order fields: ', 'copy-paste-order-for-woocommerce' ) + 
					missingFields.join( ', ' ) 
				)
				setIsLoading( false )
				return
			}

			setOrderData( parsedData )
		} catch ( err ) {
			setError( __( 'Failed to read from clipboard.', 'copy-paste-order-for-woocommerce' ) )
		} finally {
			setIsLoading( false )
		}
	}

	const closeModal = () => {
		setIsModalOpen( false )
		setOrderData( null )
		setError( null )
		setIsCreating( false )
		setSuccess( false )
	}

	const createOrder = async () => {
		if ( ! orderData ) return

		setIsCreating( true )
		setError( null )

		try {
			const response = await apiPasteOrder( orderData )
			
			if ( response.success ) {
				setSuccess( true )
				
				// Redirect to edit the new order after a short delay
				setTimeout( () => {
					if ( response.data && response.data.edit_url ) {
						window.location.href = response.data.edit_url
					} else {
						// Fallback: reload the page to show the new order
						window.location.reload()
					}
				}, 2000 )
			} else {
				setError( response.message || __( 'Failed to create order.', 'copy-paste-order-for-woocommerce' ) )
			}
		} catch ( err ) {
			setError( __( 'An error occurred while creating the order.', 'copy-paste-order-for-woocommerce' ) )
		} finally {
			setIsCreating( false )
		}
	}

	return (
		<>
			<Button
				variant="secondary"
				onClick={ openModal }
				icon={ upload }
				className="cpofw-paste-order-button"
			>
				{ __( 'Paste Order(s)', 'copy-paste-order-for-woocommerce' ) }
			</Button>

			{ isModalOpen && (
				<Modal
					title={ __( 'Paste Order', 'copy-paste-order-for-woocommerce' ) }
					onRequestClose={ closeModal }
					className="cpofw-paste-order-modal"
					size="medium"
				>
					<VStack spacing={ 4 }>
						{ isLoading && (
							<div className="cpofw-modal-loading">
								<Spinner />
								<p>{ __( 'Reading clipboard data...', 'copy-paste-order-for-woocommerce' ) }</p>
							</div>
						) }

						{ error && (
							<Notice status="error" isDismissible={ false }>
								{ error }
							</Notice>
						) }

						{ success && (
							<Notice status="success" isDismissible={ false }>
								{ __( 'Order created successfully! Redirecting...', 'copy-paste-order-for-woocommerce' ) }
							</Notice>
						) }

						{ orderData && ! isLoading && ! success && (
							<>
								<p>{ __( 'The following order will be created:', 'copy-paste-order-for-woocommerce' ) }</p>
								<OrderSummary orderData={ orderData } />
								
								<div className="cpofw-modal-actions">
									{ isCreating ? (
										<Button variant="primary" disabled>
											<Spinner />
											{ __( 'Creating Order...', 'copy-paste-order-for-woocommerce' ) }
										</Button>
									) : (
										<Button
											variant="primary"
											onClick={ createOrder }
										>
											{ __( 'Create Order', 'copy-paste-order-for-woocommerce' ) }
										</Button>
									) }
									
									<Button
										variant="secondary"
										onClick={ closeModal }
										disabled={ isCreating }
									>
										{ __( 'Cancel', 'copy-paste-order-for-woocommerce' ) }
									</Button>
								</div>
							</>
						) }
					</VStack>
				</Modal>
			) }
		</>
	)
}

export default PasteOrderButton
