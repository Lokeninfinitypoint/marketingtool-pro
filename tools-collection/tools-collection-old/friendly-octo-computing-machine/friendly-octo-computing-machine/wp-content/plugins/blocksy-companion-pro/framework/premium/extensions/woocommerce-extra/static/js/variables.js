import {
	withKeys,
	handleBackgroundOptionFor,
	typographyOption,
	maybePromoteScalarValueIntoResponsive,
} from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'
import {
	collectVariablesForCompare,
	collectVariablesForCompareLayers,
} from './sync/compare'
import { collectVariablesForFilters } from './sync/filters'
import { collectVariablesForSuggestedProducts } from './sync/suggested-products'
import { collectVariablesForQuickView } from './sync/quick-view'
import { collectVariablesForAddedToCartPopup } from './sync/added-to-cart-popup'
import { collectVariablesForSizeGuide } from './sync/size-guide'
import { collectVariablesForWaitlist } from './sync/waitlist'
import { collectVariablesForFloatingBar } from './sync/floating-bar'
import { collectVariablesForSwatches } from './sync/swatches'
import { collectVariablesForWStockScarcity } from './sync/stock-scarcity'
import { collectVariablesForWishlistShareBox } from './sync/wishlist'
import { collectVariablesForShareBox } from './sync/share-box'
import { collectVariablesForShippingProgressBar } from './sync/shipping-progress-bar'
import { collectVariablesForRelatedSlideshow } from './sync/related-slideshow'
import { collectVariablesForCartReservedTimer } from './sync/cart-reserved-timer'

ctEvents.on(
	'ct:customizer:sync:collect-variable-descriptors',
	(allVariables) => {
		allVariables.result = {
			product_compare_layout: collectVariablesForCompareLayers,
			...allVariables.result,

			...collectVariablesForFloatingBar(),
			...collectVariablesForFilters(),
			...collectVariablesForWishlistShareBox(),
			...collectVariablesForSwatches(),
			...collectVariablesForQuickView(),
			...collectVariablesForAddedToCartPopup(),
			...collectVariablesForSuggestedProducts(),
			...collectVariablesForCompare(),
			...collectVariablesForSizeGuide(),
			...collectVariablesForWaitlist(),
			...collectVariablesForWStockScarcity(),
			...collectVariablesForShareBox(),
			...collectVariablesForShippingProgressBar(),
			...collectVariablesForRelatedSlideshow(),
			...collectVariablesForCartReservedTimer(),


			// Single product type 2
			product_view_stacked_columns: {
				selector: '.ct-stacked-gallery .ct-stacked-gallery-container',
				variable: 'columns',
				responsive: true,
				unit: '',
			},


			// new badge
			newBadgeColor: [
				{
					selector: '.ct-woo-badge-new',
					variable: 'badge-text-color',
					type: 'color:text',
				},

				{
					selector: '.ct-woo-badge-new',
					variable: 'badge-background-color',
					type: 'color:background',
				},
			],

			// featured badge
			featuredBadgeColor: [
				{
					selector: '.ct-woo-badge-featured',
					variable: 'badge-text-color',
					type: 'color:text',
				},

				{
					selector: '.ct-woo-badge-featured',
					variable: 'badge-background-color',
					type: 'color:background',
				},
			],


			// product archive additional action buttons
			additional_actions_button_icon_color: [
				{
					selector: '.ct-woo-card-extra[data-type="type-1"]',
					variable: 'theme-button-text-initial-color',
					type: 'color:default',
					responsive: true,
				},
				{
					selector: '.ct-woo-card-extra[data-type="type-1"]',
					variable: 'theme-button-text-hover-color',
					type: 'color:hover',
					responsive: true,
				},

				{
					selector: '.ct-woo-card-extra[data-type="type-2"]',
					variable: 'theme-button-text-initial-color',
					type: 'color:default_2',
					responsive: true,
				},
				{
					selector: '.ct-woo-card-extra[data-type="type-2"]',
					variable: 'theme-button-text-hover-color',
					type: 'color:hover_2',
					responsive: true,
				},
			],

			additional_actions_button_background_color: [
				{
					selector: '.ct-woo-card-extra[data-type="type-1"]',
					variable: 'theme-button-background-initial-color',
					type: 'color:default',
					responsive: true,
				},
				{
					selector: '.ct-woo-card-extra[data-type="type-1"]',
					variable: 'theme-button-background-hover-color',
					type: 'color:hover',
					responsive: true,
				},

				{
					selector: '.ct-woo-card-extra[data-type="type-2"]',
					variable: 'theme-button-background-initial-color',
					type: 'color:default_2',
					responsive: true,
				},
				{
					selector: '.ct-woo-card-extra[data-type="type-2"]',
					variable: 'theme-button-background-hover-color',
					type: 'color:hover_2',
					responsive: true,
				},
			],
		}
	}
)
