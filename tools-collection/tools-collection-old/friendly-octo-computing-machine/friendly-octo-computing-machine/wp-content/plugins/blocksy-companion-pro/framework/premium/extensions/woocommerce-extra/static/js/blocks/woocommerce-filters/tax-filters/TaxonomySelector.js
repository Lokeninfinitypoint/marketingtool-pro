import { createElement } from '@wordpress/element'
import { sprintf, __ } from 'ct-i18n'
import { ToggleControl, FormTokenField } from '@wordpress/components'

export const getTaxonomyLabel = (attributes, blockData) => {
	if (attributes.type === 'categories') {
		if (
			blockData &&
			blockData.product_taxonomies &&
			blockData.product_taxonomies[attributes.taxonomy] &&
			blockData.product_taxonomies[attributes.taxonomy].name
		) {
			return blockData.product_taxonomies[attributes.taxonomy].name
		}

		return __('Product Categories', 'blocksy-companion')
	}

	if (attributes.type === 'attributes') {
		return __('Product Attributes', 'blocksy-companion')
	}

	return __('Taxonomy', 'blocksy-companion')
}

const TaxonomySelector = ({ blockData, attributes, setAttributes }) => {
	const { excludeTaxonomy, taxonomy_not_in } = attributes

	const taxonomyLabel = getTaxonomyLabel(attributes, blockData)

	return (
		<>
			<ToggleControl
				label={sprintf(
					__('Exclude %s', 'blocksy-companion'),
					taxonomyLabel
				)}
				help={false}
				checked={excludeTaxonomy}
				onChange={() =>
					setAttributes({
						excludeTaxonomy: !excludeTaxonomy,
					})
				}
			/>

			{excludeTaxonomy && (
				<FormTokenField
					label={__('Exclude Speciffic Items', 'blocksy-companion')}
					__experimentalShowHowTo={false}
					value={
						blockData
							? blockData.flatTerms
									.filter(({ term_id }) =>
										taxonomy_not_in.includes(term_id)
									)
									.map(
										({ term_id, name }) =>
											`${name}---${term_id}`
									)
							: []
					}
					suggestions={
						blockData
							? blockData.flatTerms.map(
									({ name, term_id }) =>
										`${name}---${term_id}`
							  )
							: []
					}
					displayTransform={(v) => v.split('---')[0]}
					__experimentalExpandOnFocus
					onChange={(tokens) => {
						setAttributes({
							taxonomy_not_in: tokens.map((token) =>
								parseInt(token.split('---')[1])
							),
						})
					}}
				/>
			)}
		</>
	)
}

export default TaxonomySelector
