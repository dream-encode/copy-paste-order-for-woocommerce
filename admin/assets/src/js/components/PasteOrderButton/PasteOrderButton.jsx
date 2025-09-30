import { __ } from '@wordpress/i18n'
import { useState, useEffect } from '@wordpress/element'
import { Button, Modal, Spinner, Notice, __experimentalVStack as VStack } from '@wordpress/components'

import OrderSummary from '@/components/OrderSummary/OrderSummary'
import { apiPasteOrder } from '@/utils/api'

const PasteOrderButton = () => {
	const [ isModalOpen, setIsModalOpen ] = useState( false )
	const [ isLoading, setIsLoading ]     = useState( false )
	const [ orderData, setOrderData ]     = useState( null )
	const [ error, setError ]             = useState( null )
	const [ isCreating, setIsCreating ]   = useState( false )
	const [ success, setSuccess ]         = useState( false )

	useEffect( () => {
		const addNewButton = document.querySelector( '.page-title-action:not(.cpofw-paste-order-button)' )

		if ( addNewButton && addNewButton.parentNode ) {
			const container = document.getElementById( 'cpofw-paste-order-container' )

			if ( container ) {
				addNewButton.parentNode.insertBefore( container, addNewButton.nextSibling )

				container.style.display = 'inline-block'
			}
		}
	}, [] )

	const openModal = async () => {
		setIsModalOpen( true )
		setIsLoading( true )
		setError( null )
		setOrderData( null )

		try {
			const clipboardText = await navigator.clipboard.readText()

			if ( ! clipboardText.trim() ) {
				setError( __( 'Clipboard is empty.', 'copy-paste-order-for-woocommerce' ) )

				setIsLoading( false )

				return
			}

			let parsedData

			try {
				parsedData = JSON.parse( clipboardText )
			} catch {
				setError( __( 'Clipboard content is not valid JSON.', 'copy-paste-order-for-woocommerce' ) )

				setIsLoading( false )

				return
			}

			if ( ! parsedData || 'object' !== typeof parsedData ) {
				setError( __( 'Invalid order data format.', 'copy-paste-order-for-woocommerce' ) )

				setIsLoading( false )

				return
			}

			const requiredFields = [
				'id',
				'status',
				'total',
				'line_items',
			]

			const missingFields = requiredFields.filter( ( field ) => ! ( field in parsedData ) )

			if ( missingFields.length > 0 ) {
				setError(
					__( 'Missing required order fields: ', 'copy-paste-order-for-woocommerce' ) +
					missingFields.join( ', ' )
				)

				setIsLoading( false )

				return
			}

			setOrderData( parsedData )
		} catch {
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
		if ( ! orderData ) {
			return
		}

		setIsCreating( true )
		setError( null )

		try {
			const response = await apiPasteOrder( orderData )

			if ( response.success ) {
				setSuccess( true )

				setTimeout( () => {
					if ( response.data && response.data.edit_url ) {
						window.location.href = response.data.edit_url
					} else {
						window.location.reload()
					}
				}, 2000 )
			} else {
				setError( response.message || __( 'Failed to create order.', 'copy-paste-order-for-woocommerce' ) )
			}
		} catch {
			setError( __( 'An error occurred while creating the order.', 'copy-paste-order-for-woocommerce' ) )
		} finally {
			setIsCreating( false )
		}
	}

	return (
		<>
			<a
				href="#"
				onClick={ ( e ) => {
					e.preventDefault()
					openModal()
				} }
				className="page-title-action cpofw-paste-order-button"
			>
				{ __( 'Paste Order(s)', 'copy-paste-order-for-woocommerce' ) }
			</a>

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
