import { createElement } from '@wordpress/element'

const ItemImage = ({ taxItem, attributes }) => {
	const { showItemsRendered, showTaxonomyImages, type, taxonomy } = attributes

	if (!showItemsRendered && !showTaxonomyImages) {
		return null
	}

	if (!taxItem?.tax_image?.url) {
		return null
	}

	if (
		type === 'categories' &&
		taxonomy === 'product_brand' &&
		!showItemsRendered
	) {
		return null
	}

	if (
		type === 'categories' &&
		taxonomy !== 'product_brand' &&
		!showTaxonomyImages
	) {
		return null
	}

	return (
		<div className="ct-media-container">
			<img
				src={taxItem.tax_image.url}
				alt={taxItem.name}
				style={{
					aspectRatio: 'var(--product-taxonomy-logo-aspect-ratio)',
				}}
			/>
		</div>
	)
}

export default ItemImage
