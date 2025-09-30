import { __ } from '@wordpress/i18n'
import {
	Card,
	CardBody,
	CardHeader,
	__experimentalText as Text,
	__experimentalVStack as VStack,
	__experimentalHStack as HStack,
	__experimentalDivider as Divider,
	Icon
} from '@wordpress/components'

import {
	receipt,
	people,
	tag
} from '@wordpress/icons'

const formatPrice = ( price ) => {
	return `$${ parseFloat( price ).toFixed( 2 ) }`
}

const OrderSummary = ( { orderData } ) => {
	if ( ! orderData ) {
		return null
	}

	const formatDate = ( dateString ) => {
		try {
			return new Date( dateString ).toLocaleDateString()
		} catch {
			return dateString
		}
	}

	const getStatusLabel = ( status ) => {
		const statusLabels = {
			'pending': __( 'Pending Payment', 'copy-paste-order-for-woocommerce' ),
			'processing': __( 'Processing', 'copy-paste-order-for-woocommerce' ),
			'on-hold': __( 'On Hold', 'copy-paste-order-for-woocommerce' ),
			'completed': __( 'Completed', 'copy-paste-order-for-woocommerce' ),
			'cancelled': __( 'Cancelled', 'copy-paste-order-for-woocommerce' ),
			'refunded': __( 'Refunded', 'copy-paste-order-for-woocommerce' ),
			'failed': __( 'Failed', 'copy-paste-order-for-woocommerce' )
		}

		return statusLabels[ status ] || status
	}

	return (
		<Card className="cpofw-order-summary">
			<CardHeader>
				<HStack>
					<Icon icon={ receipt } size={ 20 } />
					<Text variant="title.small">
						{ __( 'Order Summary', 'copy-paste-order-for-woocommerce' ) }
					</Text>
				</HStack>
			</CardHeader>
			<CardBody>
				<VStack spacing={ 3 }>
					<div className="cpofw-order-details">
						<HStack justify="space-between">
							<Text weight="600">
								{ __( 'Order ID:', 'copy-paste-order-for-woocommerce' ) }
							</Text>
							<Text>{ orderData.id }</Text>
						</HStack>

						<HStack justify="space-between">
							<Text weight="600">
								{ __( 'Status:', 'copy-paste-order-for-woocommerce' ) }
							</Text>
							<Text>{ getStatusLabel( orderData.status ) }</Text>
						</HStack>

						<HStack justify="space-between">
							<Text weight="600">
								{ __( 'Total:', 'copy-paste-order-for-woocommerce' ) }
							</Text>
							<Text>{ formatPrice( orderData.total ) }</Text>
						</HStack>

						{ orderData.date_created && (
							<HStack justify="space-between">
								<Text weight="600">
									{ __( 'Date Created:', 'copy-paste-order-for-woocommerce' ) }
								</Text>
								<Text>{ formatDate( orderData.date_created ) }</Text>
							</HStack>
						) }
					</div>

					<Divider />

					{ ( orderData.billing || orderData.shipping ) && (
						<>
							<HStack>
								<Icon icon={ people } size={ 18 } />
								<Text variant="title.small">
									{ __( 'Customer Information', 'copy-paste-order-for-woocommerce' ) }
								</Text>
							</HStack>

							{ orderData.billing && (
								<div className="cpofw-billing-info">
									<Text weight="600">
										{ __( 'Billing:', 'copy-paste-order-for-woocommerce' ) }
									</Text>
									<Text>
										{ [
											orderData.billing.first_name,
											orderData.billing.last_name
										].filter( Boolean ).join( ' ' ) }
									</Text>
									{ orderData.billing.email && (
										<Text>{ orderData.billing.email }</Text>
									) }
								</div>
							) }

							<Divider />
						</>
					) }

					{ orderData.line_items && orderData.line_items.length > 0 && (
						<>
							<HStack>
								<Icon icon={ tag } size={ 18 } />
								<Text variant="title.small">
									{ __( 'Items', 'copy-paste-order-for-woocommerce' ) } ({ orderData.line_items.length })
								</Text>
							</HStack>

							<div className="cpofw-line-items">
								{ orderData.line_items.slice( 0, 5 ).map( ( item, index ) => (
									<HStack key={ index } justify="space-between">
										<VStack spacing={ 1 }>
											<Text>{ item.name }</Text>
											{ item.quantity > 1 && (
												<Text variant="muted">
													{ __( 'Qty:', 'copy-paste-order-for-woocommerce' ) } { item.quantity }
												</Text>
											) }
										</VStack>
										<Text>{ formatPrice( item.total ) }</Text>
									</HStack>
								) ) }

								{ orderData.line_items.length > 5 && (
									<Text variant="muted">
										{ __( '... and %d more items', 'copy-paste-order-for-woocommerce' ).replace( '%d', orderData.line_items.length - 5 ) }
									</Text>
								) }
							</div>
						</>
					) }

					{ orderData.meta_data && orderData.meta_data.length > 0 && (
						<>
							<Divider />
							<Text variant="muted">
								{ __( 'Includes %d metadata entries', 'copy-paste-order-for-woocommerce' ).replace( '%d', orderData.meta_data.length ) }
							</Text>
						</>
					) }
				</VStack>
			</CardBody>
		</Card>
	)
}

export default OrderSummary
