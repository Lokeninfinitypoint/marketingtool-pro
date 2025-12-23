import { createElement, useState, useEffect } from '@wordpress/element'

import { __ } from 'ct-i18n'
import { registerBlockType } from '@wordpress/blocks'
import { InspectorControls } from '@wordpress/block-editor'
import {
	Panel,
	PanelBody,
	ToggleControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components'
import Preview from './Preview'
import {
	getAttributesFromOptions,
	getOptionsForBlock,
	OptionsPanel,
} from 'blocksy-options'
import cachedFetch from 'ct-wordpress-helpers/cached-fetch'

export const options = getOptionsForBlock('status_filter')
export const defaultAttributes = getAttributesFromOptions(options)

registerBlockType('blocksy/woocommerce-status-filter', {
	apiVersion: 3,
	title: __('Filter by Status Controls', 'blocksy-companion'),
	description: __('Filter by products status.', 'blocksy-companion'),
	icon: 'filter',
	icon: {
		src: (
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				className="wc-block-editor-components-block-icon">
				<path fill-rule="evenodd" d="M12 19.313a7.312 7.312 0 1 1 0-14.625 7.312 7.312 0 0 1 0 14.625ZM3 12a9 9 0 1 1 18 0 9 9 0 0 1-18 0Zm12.972-1.653-1.194-1.194-3.903 3.904-1.653-1.654-1.194 1.194 2.847 2.846 5.097-5.096Z"/>
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
		...defaultAttributes,

		layout: {
			type: 'string',
			default: 'list',
		},

		showCheckboxes: {
			type: 'boolean',
			default: true,
		},

		showCounters: {
			type: 'boolean',
			default: true,
		},

		showResetButton: {
			type: 'boolean',
			default: false,
		},
	},
	edit: ({ attributes, setAttributes }) => {
		const { layout, showResetButton, showCounters, showCheckboxes } =
			attributes

		const [blockData, setBlockData] = useState(null)

		useEffect(() => {
			cachedFetch(
				`${wp.ajax.settings.url}?action=blc_ext_filters_get_block_data`,
				{
					type: 'status',
				}
			)
				.then((response) => response.json())
				.then(({ success, data }) => {
					setBlockData(data)
				})
		}, [])

		return (
			<>
				<Preview attributes={attributes} blockData={blockData} />
				<InspectorControls>
					<Panel header="Filter Settings">
						<PanelBody>
							<ToggleGroupControl
								label={__('Display Type', 'blocksy-companion')}
								value={layout}
								isBlock
								onChange={(newLayout) =>
									setAttributes({ layout: newLayout })
								}>
								<ToggleGroupControlOption
									key="list"
									value="list"
									label={__('List', 'blocksy-companion')}
								/>
								<ToggleGroupControlOption
									key="inline"
									value="inline"
									label={__('Inline', 'blocksy-companion')}
								/>
							</ToggleGroupControl>

							<OptionsPanel
								purpose={'gutenberg'}
								onChange={(optionId, optionValue) => {
									setAttributes({
										[optionId]: optionValue,
									})
								}}
								options={options}
								value={attributes}
								hasRevertButton={false}
							/>
						</PanelBody>

						<PanelBody>
							<ToggleControl
								label={__(
									'Show Checkboxes',
									'blocksy-companion'
								)}
								checked={showCheckboxes}
								onChange={() =>
									setAttributes({
										showCheckboxes: !showCheckboxes,
									})
								}
							/>
						</PanelBody>

						<PanelBody>
							<ToggleControl
								label={__('Show Counters', 'blocksy-companion')}
								checked={showCounters}
								onChange={() =>
									setAttributes({
										showCounters: !showCounters,
									})
								}
							/>
						</PanelBody>

						<PanelBody>
							<ToggleControl
								label={__(
									'Show Reset Button',
									'blocksy-companion'
								)}
								checked={showResetButton}
								onChange={() =>
									setAttributes({
										showResetButton: !showResetButton,
									})
								}
							/>
						</PanelBody>
					</Panel>
				</InspectorControls>
			</>
		)
	},
	save: function () {
		return <div>Blocksy: Status Filter</div>
	},
})

wp.blocks.registerBlockVariation('blocksy/widgets-wrapper', {
	name: 'blocksy-status-filter',
	title: __('Filter by Status', 'blocksy-companion'),
	attributes: {
		heading: __('Filter', 'blocksy-companion'),
		block: 'blocksy/woocommerce-status-filter',
	},
	isActive: (attributes) =>
		attributes.block === 'blocksy/woocommerce-status-filter',
	icon: {
		src: (
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				className="wc-block-editor-components-block-icon">
				<path fill-rule="evenodd" d="M12 19.313a7.312 7.312 0 1 1 0-14.625 7.312 7.312 0 0 1 0 14.625ZM3 12a9 9 0 1 1 18 0 9 9 0 0 1-18 0Zm12.972-1.653-1.194-1.194-3.903 3.904-1.653-1.654-1.194 1.194 2.847 2.846 5.097-5.096Z"/>
			</svg>
		),
	},
})
