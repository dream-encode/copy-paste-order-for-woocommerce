import { __ } from '@wordpress/i18n'
import { useState, useEffect } from '@wordpress/element'
import { Button, Modal, Spinner, Notice } from '@wordpress/components'
import { copy } from '@wordpress/icons'

import OrderSummary from '@/components/OrderSummary/OrderSummary'
import { apiCopyOrder } from '@/utils/api'

const CopyOrderButton = ( { orderId, autoOpen = false } ) => {
	const [ isModalOpen, setIsModalOpen ] = useState( false )
	const [ isLoading, setIsLoading ]     = useState( false )
	const [ orderData, setOrderData ]     = useState( null )
	const [ error, setError ]             = useState( null )
	const [ copied, setCopied ]           = useState( false )

	useEffect( () => {
		if ( autoOpen ) {
			openModal()
		}
	}, [ autoOpen ] )

	const openModal = async () => {
		setIsModalOpen( true )
		setIsLoading( true )
		setError( null )

		try {
			const response = await apiCopyOrder( orderId )

			if ( response.success && response.data ) {
				setOrderData( JSON.parse( response.data ) )
			} else {
				setError( response.message || __( 'Failed to load order data.', 'copy-paste-order-for-woocommerce' ) )
			}
		} catch {
			setError( __( 'An error occurred while loading the order.', 'copy-paste-order-for-woocommerce' ) )
		} finally {
			setIsLoading( false )
		}
	}

	const closeModal = () => {
		setIsModalOpen( false )
		setOrderData( null )
		setError( null )
		setCopied( false )
	}

	const copyToClipboard = async () => {
		if ( ! orderData ) {
			return
		}

		try {
			const orderJson = JSON.stringify( orderData, null, 2 )

			await navigator.clipboard.writeText( orderJson )

			setCopied( true )

			setTimeout( () => {
				closeModal()
			}, 1500 )
		} catch {
			setError( __( 'Failed to copy to clipboard.', 'copy-paste-order-for-woocommerce' ) )
		}
	}

	return (
		<>
			<Button
				variant="link"
				onClick={ openModal }
				icon={ copy }
				iconSize={ 16 }
				className="cpofw-copy-order-button"
				disabled={ isLoading }
			>
				{ isLoading ? __( 'Loading...', 'copy-paste-order-for-woocommerce' ) : __( 'Copy Order', 'copy-paste-order-for-woocommerce' ) }
			</Button>

			{ isModalOpen && (
				<Modal
					title={ __( 'Copy Order', 'copy-paste-order-for-woocommerce' ) }
					onRequestClose={ closeModal }
					className="cpofw-copy-order-modal"
					size="medium"
				>
					{ isLoading && (
						<div className="cpofw-modal-loading">
							<Spinner />
							<p>{ __( 'Loading order data...', 'copy-paste-order-for-woocommerce' ) }</p>
						</div>
					) }

					{ error && (
						<Notice status="error" isDismissible={ false }>
							{ error }
						</Notice>
					) }

					{ orderData && ! isLoading && (
						<>
							<OrderSummary orderData={ orderData } />

							<div className="cpofw-modal-actions">
								{ copied ? (
									<Notice status="success" isDismissible={ false }>
										{ __( 'Order data copied to clipboard!', 'copy-paste-order-for-woocommerce' ) }
									</Notice>
								) : (
									<Button
										variant="primary"
										onClick={ copyToClipboard }
										icon={ copy }
									>
										{ __( 'Copy to Clipboard', 'copy-paste-order-for-woocommerce' ) }
									</Button>
								) }

								<Button
									variant="secondary"
									onClick={ closeModal }
								>
									{ __( 'Close', 'copy-paste-order-for-woocommerce' ) }
								</Button>
							</div>
						</>
					) }
				</Modal>
			) }
		</>
	)
}

export default CopyOrderButton
