import { createElement, useEffect } from '@wordpress/element'

import { __ } from 'ct-i18n'
import { registerBlockType } from '@wordpress/blocks'

import Edit from './Edit'

registerBlockType('blocksy/woocommerce-filters', {
	apiVersion: 3,
	title: __('Shop Filters Controls', 'blocksy-companion'),
	description: __(
		'Widget for filtering the WooCommerce products loop by category, attribute or brand.',
		'blocksy-companion'
	),
	icon: 'filter',
	icon: {
		src: (
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				className="wc-block-editor-components-block-icon">
				<path d="M18.7,7.1c-0.4-1.5-1.7-2.6-3.3-2.6S12.4,5.6,12,7.1H4v1.8h8c0.4,1.5,1.7,2.5,3.3,2.5s2.9-1.1,3.3-2.5H20V7.1H18.7zM15.3,9.8c-1,0-1.8-0.8-1.8-1.8c0-1,0.8-1.8,1.8-1.8c1,0,1.8,0.8,1.8,1.8C17.1,8.9,16.3,9.8,15.3,9.8z M8.7,12.6c-1.6,0-2.9,1.1-3.3,2.6H4v1.8h1.3c0.4,1.5,1.7,2.5,3.3,2.5s2.9-1.1,3.3-2.5h8v-1.8h-8C11.6,13.7,10.3,12.6,8.7,12.6z M8.7,17.8c-1,0-1.8-0.8-1.8-1.8c0-1,0.8-1.8,1.8-1.8c1,0,1.8,0.8,1.8,1.8C10.5,17,9.7,17.8,8.7,17.8z" />
			</svg>
		),
	},
	category: 'widgets',
	supports: {
		html: false,
		inserter: false,
		lock: false,
	},
	attributes: {
		type: {
			type: 'string',
			default: 'categories',
		},
		viewType: {
			type: 'string',
			default: 'list',
		},
		showCounters: {
			type: 'boolean',
			default: false,
		},
		multipleFilters: {
			type: 'boolean',
			default: true,
		},
		attribute: {
			type: 'string',
			default: '',
		},
		taxonomy: {
			type: 'string',
			default: 'product_cat',
		},
		showLabel: {
			type: 'boolean',
			default: true,
		},
		showCheckbox: {
			type: 'boolean',
			default: true,
		},
		showAttributesCheckbox: {
			type: 'boolean',
			default: false,
		},
		showItemsRendered: {
			type: 'boolean',
			default: true,
		},
		showTaxonomyImages: {
			type: 'boolean',
			default: false,
		},
		showResetButton: {
			type: 'boolean',
			default: false,
		},
		hierarchical: {
			type: 'boolean',
			default: false,
		},
		expandable: {
			type: 'boolean',
			default: false,
		},
		defaultExpanded: {
			type: 'boolean',
			default: true,
		},
		useFrame: {
			type: 'boolean',
			default: false,
		},
		logoMaxW: {
			type: 'number',
			default: 40,
		},
		aspectRatio: {
			type: 'string',
			default: '16/9',
		},
		imageFit: {
			type: 'string',
			default: 'contain',
		},
		excludeTaxonomy: {
			type: 'boolean',
			default: false,
		},
		taxonomy_not_in: {
			type: 'array',
			default: [],
		},
		showSearch: {
			type: 'boolean',
			default: false,
		},
		searchPlaceholder: {
			type: 'string',
			default: '',
		},
		limitHeight: {
			type: 'boolean',
			default: false,
		},
		limitHeightValue: {
			type: 'number',
			default: 400,
		},
		showTooltips: {
			type: 'boolean',
			default: false,
		},
	},

	edit: (props) => {
		return <Edit {...props} />
	},

	save: () => (
		<div class="wp-block-blocksy-woocommerce-filters">
			Blocksy: Ajax Category Filter
		</div>
	),

	deprecated: [
		{
			isEligible: ({ taxonomy }) => taxonomy === 'product_brands',

			migrate: () => ({
				taxonomy: 'product_brand',
			}),
		},
	],
})

wp.blocks.registerBlockVariation('blocksy/widgets-wrapper', {
	name: 'blocksy-filters',
	title: __('Shop Filters', 'blocksy-companion'),
	attributes: {
		heading: __('Filter', 'blocksy-companion'),
		block: 'blocksy/woocommerce-filters',
	},
	isActive: (attributes) =>
		attributes.block === 'blocksy/woocommerce-filters',
	icon: {
		src: (
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				className="wc-block-editor-components-block-icon">
				<path d="M18.7,7.1c-0.4-1.5-1.7-2.6-3.3-2.6S12.4,5.6,12,7.1H4v1.8h8c0.4,1.5,1.7,2.5,3.3,2.5s2.9-1.1,3.3-2.5H20V7.1H18.7zM15.3,9.8c-1,0-1.8-0.8-1.8-1.8c0-1,0.8-1.8,1.8-1.8c1,0,1.8,0.8,1.8,1.8C17.1,8.9,16.3,9.8,15.3,9.8z M8.7,12.6c-1.6,0-2.9,1.1-3.3,2.6H4v1.8h1.3c0.4,1.5,1.7,2.5,3.3,2.5s2.9-1.1,3.3-2.5h8v-1.8h-8C11.6,13.7,10.3,12.6,8.7,12.6z M8.7,17.8c-1,0-1.8-0.8-1.8-1.8c0-1,0.8-1.8,1.8-1.8c1,0,1.8,0.8,1.8,1.8C10.5,17,9.7,17.8,8.7,17.8z" />
			</svg>
		),
	},
})
