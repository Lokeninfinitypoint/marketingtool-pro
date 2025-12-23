import { createElement, useMemo } from '@wordpress/element'
import { __ } from 'ct-i18n'

import Checkbox from './Checkbox'
import Label from './Label'
import Counter from './Counter'

const AttributePreview = ({ blockData, maybeAttribute, attributes, item }) => {
	const maybeColor = item?.meta?.accent_color?.default?.color
	const maybeImage = item?.meta?.image?.url

	const colorStyles = useMemo(() => {
		if (
			item?.meta?.color_type === 'dual' &&
			item?.meta?.accent_color?.secondary?.color &&
			item?.meta?.accent_color?.secondary?.color !== 'CT_CSS_SKIP_RULE' &&
			item?.meta?.accent_color?.default?.color &&
			item?.meta?.accent_color?.default?.color !== 'CT_CSS_SKIP_RULE'
		) {
			const { accent_color } = item.meta
			const {
				default: { color: defaultColor },
				secondary: { color: secondaryColor },
			} = accent_color

			return {
				backgroundImage: `linear-gradient(-45deg, ${defaultColor} 0%, ${defaultColor} 50%, ${secondaryColor} 50%, ${secondaryColor} 100%)`,
			}
		}

		return {
			backgroundColor: maybeColor,
		}
	}, [item?.meta])

	return (
		<li className="ct-filter-item">
			<div className="ct-filter-item-inner">
				<a href="#">
					<Checkbox
						showCheckbox={attributes.showAttributesCheckbox}
					/>

					{blockData.has_swatches && attributes.showItemsRendered ? (
						<>
							{(maybeAttribute?.type === 'color' &&
								maybeColor !== 'CT_CSS_SKIP_RULE') ||
							(maybeAttribute?.type === 'image' && maybeImage) ||
							maybeAttribute?.type === 'button' ? (
								<span className="ct-swatch-container">
									{maybeAttribute?.type === 'color' ? (
										<span
											className="ct-swatch"
											style={colorStyles}
										/>
									) : null}
									{maybeAttribute?.type === 'image' ? (
										<span className="ct-media-container ct-swatch">
											<img
												src={maybeImage}
												alt={item.name}
												style={{
													aspectRatio: '1/1',
													height: 'auto',
													maxWidth: '100%'
												}}
											/>
										</span>
									) : null}
									{maybeAttribute?.type === 'button' ? (
										<span className="ct-swatch">
											{item?.meta?.short_name ||
												item?.name}
										</span>
									) : null}
								</span>
							) : null}
						</>
					) : null}

					<Label label={item.name} showLabel={attributes.showLabel} />

					<Counter
						count={item.count}
						showCounters={attributes.showCounters}
					/>
				</a>
			</div>
		</li>
	)
}

export default AttributePreview
