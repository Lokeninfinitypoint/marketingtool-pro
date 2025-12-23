import { createElement, useMemo } from '@wordpress/element'
import { __ } from 'ct-i18n'

import { useBlockProps } from '@wordpress/block-editor'

import AttributePreview from './Preview/Attribute'
import HierarchicalItem from './Preview/HierarchicalItem'

const Preview = ({ attributes, setAttributes, blockData }) => {
	const blockProps = useBlockProps({
		className: 'ct-filter-widget-wrapper',
	})

	const placeholder = useMemo(() => {
		if (attributes.searchPlaceholder.length) {
			return attributes.searchPlaceholder
		}

		let entityPlaceholder = ''

		if (attributes.type === 'attributes') {
			const maybeAttribute = Object.values(blockData.attributes_tax).find(
				({ attribute_name }) => attribute_name === attributes.attribute
			)

			if (maybeAttribute) {
				entityPlaceholder = maybeAttribute.attribute_label.toLowerCase()
			}
		}

		if (attributes.type === 'categories') {
			if (blockData.product_taxonomies[attributes.taxonomy]) {
				entityPlaceholder =
					blockData.product_taxonomies[
						attributes.taxonomy
					].name.toLowerCase()
			}
		}

		return sprintf(__('Find by %s', 'blocksy-companion'), entityPlaceholder)
	}, [attributes, blockData])

	const shapes = {
		color: blockData.ct_color_swatch_shape,
		image: blockData.ct_image_swatch_shape,
		button: blockData.ct_button_swatch_shape,
		mixed: blockData.ct_mixed_swatch_shape,
	}

	const maybeAttribute = Object.values(blockData.attributes_tax).find(
		({ attribute_name }) => attribute_name === attributes.attribute
	)

	const additionalProps = useMemo(() => {
		if (attributes.type === 'categories') {
			return {
				style: {
					'--product-taxonomy-logo-size': `${attributes.logoMaxW}px`,
					'--product-taxonomy-logo-aspect-ratio':
						attributes.aspectRatio,
					'--theme-object-fit': attributes.imageFit,
					...(attributes.limitHeight
						? { maxHeight: `${attributes.limitHeightValue}px` }
						: {}),
				},
				'data-frame': attributes.useFrame ? 'yes' : 'no',
			}
		}

		if (
			attributes.type === 'attributes' &&
			maybeAttribute &&
			maybeAttribute.type &&
			maybeAttribute.type !== 'select'
		) {
			return {
				'data-swatches-shape': shapes[maybeAttribute.type],
				'data-swatches-type': maybeAttribute.type,
				...(attributes.limitHeight
					? {
							style: {
								maxHeight: `${attributes.limitHeightValue}px`,
							},
					  }
					: {}),
			}
		}

		return {
			...(attributes.limitHeight
				? {
						style: {
							maxHeight: `${attributes.limitHeightValue}px`,
						},
				  }
				: {}),
		}
	}, [attributes, maybeAttribute])

	if (attributes.type === 'categories') {
		if (!blockData.can_display_preview) {
			return (
				<div>
					{__(
						'Please wait until the lookup table is generated.',
						'blocksy-companion'
					)}
				</div>
			)
		}
	}

	return (
		<div {...blockProps}>
			{attributes.showSearch && (
				<div className="ct-filter-search">
					<input
						type="search"
						placeholder={placeholder}
						onChange={(e) =>
							setAttributes({ searchPlaceholder: e.target.value })
						}
					/>

					<span className="ct-filter-search-icon">
						<svg
							className="ct-filter-search-zoom-icon"
							width="13"
							height="13"
							fill="currentColor"
							aria-hidden="true"
							viewBox="0 0 15 15">
							<path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"></path>
						</svg>
					</span>
				</div>
			)}

			<ul
				className="ct-filter-widget"
				{...additionalProps}
				data-display-type={attributes.viewType}
				data-filter-criteria={
					attributes.type === 'categories'
						? `taxonomy:${attributes.taxonomy}`
						: attributes.type
				}>
				{blockData.terms.length > 0 &&
					(attributes.type === 'categories' &&
					attributes.hierarchical &&
					attributes.viewType === 'list'
						? blockData.terms
						: blockData.flatTerms
					).map((item) => {
						if (
							attributes.taxonomy_not_in.includes(item.term_id) &&
							attributes.excludeTaxonomy
						) {
							return null
						}

						if (item.count === 0) {
							return null
						}

						if (attributes.type === 'categories') {
							return (
								<HierarchicalItem
									key={item.term_id}
									taxItem={item}
									attributes={attributes}
								/>
							)
						}

						return (
							<AttributePreview
								key={item.term_id}
								blockData={blockData}
								maybeAttribute={maybeAttribute}
								item={item}
								attributes={attributes}
							/>
						)
					})}
			</ul>

			{attributes.showResetButton && (
				<div className="ct-filter-reset wp-block-button is-style-outline">
					<a
						href="#"
						className="ct-button-ghost wp-element-button wp-block-button__link">
						<svg
							width="12"
							height="12"
							viewBox="0 0 15 15"
							fill="currentColor">
							<path d="M8.5,7.5l4.5,4.5l-1,1L7.5,8.5L3,13l-1-1l4.5-4.5L2,3l1-1l4.5,4.5L12,2l1,1L8.5,7.5z"></path>
						</svg>
						{__('Reset Filter', 'blocksy-companion')}
					</a>
				</div>
			)}
		</div>
	)
}

export default Preview
