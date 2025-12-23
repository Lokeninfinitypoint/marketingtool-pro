import { createElement, useState, useEffect } from '@wordpress/element'

import { InspectorControls } from '@wordpress/block-editor'
import { Spinner } from '@wordpress/components'

import {
	Panel,
	PanelBody,
	SelectControl,
	ToggleControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	RangeControl,
} from '@wordpress/components'

import { __ } from 'ct-i18n'
import cachedFetch from 'ct-wordpress-helpers/cached-fetch'

import Preview from './Preview'
import Disabled from './Disabled'
import TaxonomySelector from './TaxonomySelector'
import RenderOptions from './RenderOptions'

const flattenTerms = (terms) => {
	return terms.reduce((acc, term) => {
		return [
			...acc,
			{
				...term,
				children: [],
			},

			...(term.children ? flattenTerms(term.children) : []),
		]
	}, [])
}

const typesConfig = {
	// Maybe migrate type to taxonomies
	categories: __('Taxonomy', 'blocksy-companion'),
	attributes: __('Attribute', 'blocksy-companion'),
}

const Edit = ({ attributes, setAttributes }) => {
	const [blockData, setBlockData] = useState(null)

	useEffect(() => {
		const fetchData = async () => {
			const body = {
				type: attributes.type,
			}

			if (attributes.type === 'categories') {
				body.taxonomy = attributes.taxonomy
			}

			if (attributes.type === 'attributes') {
				body.attribute = attributes.attribute
			}

			cachedFetch(
				`${wp.ajax.settings.url}?action=blc_ext_filters_get_block_data`,
				body
			)
				.then((response) => response.json())
				.then(({ success, data }) => {
					if (data.terms.length > 0) {
						data.flatTerms = flattenTerms(data.terms)
					}
					setBlockData(data)
				})
		}

		fetchData()
	}, [attributes.type, attributes.taxonomy, attributes.attribute])

	useEffect(() => {
		if (attributes.type === 'brands') {
			setAttributes({
				type: 'categories',
				taxonomy: 'product_brand',
			})
		}

		// Handle deprecated taxonomy value for backward compatibility
		if (attributes.taxonomy === 'product_brands') {
			setAttributes({
				taxonomy: 'product_brand',
			})
		}

		if (!attributes.taxonomy) {
			setAttributes({
				taxonomy: 'product_cat',
			})
		}
	}, [attributes.type, attributes.taxonomy])

	const attributeTaxonomiesOpts = [
		{
			label: __('Select attribute', 'blocksy-companion'),
			value: '',
		},

		...(blockData
			? Object.values(blockData.attributes_tax).map(
					({ attribute_name, attribute_label }) => ({
						label: attribute_label,
						value: attribute_name,
					})
			  )
			: []),
	]

	const taxonomiesOpts = blockData
		? Object.keys(blockData.product_taxonomies).map((key) => ({
				label: blockData.product_taxonomies[key].name,
				value: key,
		  }))
		: []

	let isInvalidType = false

	if (blockData && attributes.type === 'attributes') {
		if (!attributes.attribute) {
			isInvalidType = true
		}

		if (
			!attributeTaxonomiesOpts
				.map(({ value }) => value)
				.includes(attributes.attribute)
		) {
			isInvalidType = true
		}
	}

	if (
		blockData &&
		attributes.type === 'categories' &&
		!taxonomiesOpts.map(({ value }) => value).includes(attributes.taxonomy)
	) {
		isInvalidType = true
	}

	return (
		<>
			{!blockData && <Spinner />}

			{blockData && (
				<Disabled isError={isInvalidType} type={attributes.type}>
					<Preview
						blockData={blockData}
						attributes={attributes}
						setAttributes={setAttributes}
					/>
				</Disabled>
			)}

			<InspectorControls>
				<Panel header={__('Filter Settings', 'blocksy-companion')}>
					<PanelBody>
						<ToggleGroupControl
							label={__('Filter By', 'blocksy-companion')}
							value={attributes.type}
							isBlock
							onChange={(type) => setAttributes({ type })}>
							{Object.keys(typesConfig).map((buttonType) => (
								<ToggleGroupControlOption
									key={buttonType}
									value={buttonType}
									label={typesConfig[buttonType]}
									disable={true}
								/>
							))}
						</ToggleGroupControl>

						<ToggleGroupControl
							label={__('Display Type', 'blocksy-companion')}
							value={attributes.viewType}
							isBlock
							onChange={(newViewType) =>
								setAttributes({ viewType: newViewType })
							}>
							<ToggleGroupControlOption
								value="list"
								label={__('List', 'blocksy-companion')}
							/>
							<ToggleGroupControlOption
								value="inline"
								label={__('Inline', 'blocksy-companion')}
							/>
						</ToggleGroupControl>

						{attributes.type === 'categories' && (
							<SelectControl
								label={__('Taxonomy', 'blocksy-companion')}
								options={taxonomiesOpts}
								value={attributes.taxonomy}
								onChange={(taxonomy) =>
									setAttributes({ taxonomy })
								}
							/>
						)}

						{attributes.type === 'attributes' && (
							<SelectControl
								label={__('Attribute', 'blocksy-companion')}
								options={attributeTaxonomiesOpts}
								value={attributes.attribute}
								onChange={(attribute) =>
									setAttributes({ attribute })
								}
							/>
						)}
					</PanelBody>

					<PanelBody>
						<ToggleControl
							label={__(
								'Multiple Selections',
								'blocksy-companion'
							)}
							help={__(
								'Allow selecting multiple items in a filter.',
								'blocksy-companion'
							)}
							checked={attributes.multipleFilters}
							onChange={() =>
								setAttributes({
									multipleFilters:
										!attributes.multipleFilters,
								})
							}
						/>
					</PanelBody>

					<PanelBody>
						<ToggleControl
							label={__('Show Search Box', 'blocksy-companion')}
							checked={attributes.showSearch}
							onChange={() =>
								setAttributes({
									showSearch: !attributes.showSearch,
								})
							}
						/>
					</PanelBody>

					{attributes.type === 'categories' && (
						<PanelBody>
							<ToggleControl
								label={__(
									'Show Checkboxes',
									'blocksy-companion'
								)}
								checked={attributes.showCheckbox}
								onChange={() =>
									setAttributes({
										showCheckbox: !attributes.showCheckbox,
									})
								}
							/>
						</PanelBody>
					)}

					{attributes.type === 'attributes' && (
						<PanelBody>
							<ToggleControl
								label={__(
									'Show Checkboxes',
									'blocksy-companion'
								)}
								checked={attributes.showAttributesCheckbox}
								onChange={() =>
									setAttributes({
										showAttributesCheckbox:
											!attributes.showAttributesCheckbox,
									})
								}
							/>
						</PanelBody>
					)}

					{blockData &&
						attributes.type === 'categories' &&
						attributes.viewType === 'list' &&
						blockData.product_taxonomies[attributes.taxonomy]
							?.is_taxonomy_hierarchical && (
							<PanelBody>
								<ToggleControl
									label={__(
										'Show Hierarchy',
										'blocksy-companion'
									)}
									checked={attributes.hierarchical}
									onChange={() =>
										setAttributes({
											hierarchical:
												!attributes.hierarchical,
										})
									}
								/>

								{attributes.hierarchical ? (
									<>
										<ToggleControl
											label={__(
												'Expandable',
												'blocksy-companion'
											)}
											checked={attributes.expandable}
											onChange={() =>
												setAttributes({
													expandable:
														!attributes.expandable,
												})
											}
										/>

										{attributes.expandable ? (
											<ToggleControl
												label={__(
													'Expanded by Default',
													'blocksy-companion'
												)}
												checked={
													attributes.defaultExpanded
												}
												onChange={() =>
													setAttributes({
														defaultExpanded:
															!attributes.defaultExpanded,
													})
												}
											/>
										) : null}
									</>
								) : null}
							</PanelBody>
						)}

					<RenderOptions
						{...{ attributes, setAttributes, blockData }}
					/>

					<PanelBody>
						<ToggleControl
							label={__('Show Label', 'blocksy-companion')}
							checked={attributes.showLabel}
							onChange={() =>
								setAttributes({
									showLabel: !attributes.showLabel,
								})
							}
						/>
					</PanelBody>

					<PanelBody>
						<ToggleControl
							label={__('Show Counter', 'blocksy-companion')}
							checked={attributes.showCounters}
							onChange={() =>
								setAttributes({
									showCounters: !attributes.showCounters,
								})
							}
						/>
					</PanelBody>

					{(attributes.type === 'attributes' &&
						attributes.showItemsRendered) ||
					(attributes.type === 'categories' &&
						attributes.showTaxonomyImages) ||
					(attributes.showItemsRendered &&
						attributes.type === 'categories' &&
						attributes.taxonomy === 'product_brand') ? (
						<PanelBody>
							<ToggleControl
								label={__('Show Tooltip', 'blocksy-companion')}
								checked={attributes.showTooltips}
								onChange={() =>
									setAttributes({
										showTooltips: !attributes.showTooltips,
									})
								}
							/>
						</PanelBody>
					) : null}

					{blockData && blockData.terms.length > 0 && (
						<PanelBody>
							<TaxonomySelector
								attributes={attributes}
								setAttributes={setAttributes}
								blockData={blockData}
							/>
						</PanelBody>
					)}

					<PanelBody>
						<ToggleControl
							label={__(
								'Container Maximum Height',
								'blocksy-companion'
							)}
							checked={attributes.limitHeight}
							onChange={() =>
								setAttributes({
									limitHeight: !attributes.limitHeight,
								})
							}
						/>

						{attributes.limitHeight ? (
							<RangeControl
								label={__('Max Height', 'blocksy-companion')}
								value={attributes.limitHeightValue}
								onChange={(val) =>
									setAttributes({
										limitHeightValue: val,
									})
								}
								min={10}
								max={1000}
								step={10}
							/>
						) : null}
					</PanelBody>

					<PanelBody>
						<ToggleControl
							label={__('Show Reset Button', 'blocksy-companion')}
							checked={attributes.showResetButton}
							onChange={() =>
								setAttributes({
									showResetButton:
										!attributes.showResetButton,
								})
							}
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>
		</>
	)
}

export default Edit
