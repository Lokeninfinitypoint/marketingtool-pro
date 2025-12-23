import { createElement, useMemo, useCallback } from '@wordpress/element'

import { InspectorControls } from '@wordpress/block-editor'
import { Spinner } from '@wordpress/components'

import {
	PanelBody,
	ToggleControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	RangeControl,
} from '@wordpress/components'

import { sprintf, __ } from 'ct-i18n'
import { getTaxonomyLabel } from './TaxonomySelector'

const sizesConfig = ['1/1', '4/3', '16/9', '2/1']

const RenderOptions = ({ attributes, setAttributes, blockData }) => {
	const {
		type,
		taxonomy,
		showItemsRendered,
		showTaxonomyImages,
		aspectRatio,
		logoMaxW,
		useFrame,
	} = attributes

	const taxonomyLabel = getTaxonomyLabel(attributes, blockData)

	const optionLabel = useMemo(() => {
		if (type === 'categories') {
			return sprintf(
				__('Show %s Image', 'blocksy-companion'),
				taxonomyLabel
			)
		}

		return __('Show Swatches', 'blocksy-companion')
	}, [type, taxonomyLabel])

	const isChecked = useMemo(() => {
		if (type === 'categories' && taxonomy === 'product_brand') {
			return showItemsRendered
		}

		if (type === 'attributes') {
			return showItemsRendered
		}

		return showTaxonomyImages
	}, [type, taxonomy, showItemsRendered, showTaxonomyImages])

	const handleSwitch = useCallback(() => {
		if (type === 'categories' && taxonomy !== 'product_brand') {
			setAttributes({
				showTaxonomyImages: !showTaxonomyImages,
			})

			return
		}

		setAttributes({
			showItemsRendered: !showItemsRendered,
		})
	}, [type, taxonomy, showTaxonomyImages, showItemsRendered])

	const isShowRender = useMemo(() => {
		if (taxonomy === 'product_tag') {
			return false
		}

		if (type === 'categories' && taxonomy === 'product_brand') {
			return showItemsRendered
		}

		if (type === 'attributes') {
			return false
		}

		return showTaxonomyImages
	}, [type, taxonomy, showItemsRendered, showTaxonomyImages])

	if (!blockData || taxonomy === 'product_tag') {
		return null
	}

	return (
		<PanelBody>
			<ToggleControl
				label={optionLabel}
				checked={isChecked}
				onChange={handleSwitch}
			/>

			{isShowRender ? (
				<>
					<ToggleGroupControl
						label={__('Aspect Ratio', 'blocksy-companion')}
						value={aspectRatio}
						isBlock
						onChange={(newAspectRatio) =>
							setAttributes({
								aspectRatio: newAspectRatio,
							})
						}>
						{sizesConfig.map((ar) => (
							<ToggleGroupControlOption
								key={ar}
								value={ar}
								label={ar}
							/>
						))}
					</ToggleGroupControl>

					<ToggleGroupControl
						label={__('Scale', 'blocksy-companion')}
						value={attributes.imageFit}
						isBlock
						onChange={(nextImageFir) =>
							setAttributes({
								imageFit: nextImageFir,
							})
						}>
						<ToggleGroupControlOption
							key="cover"
							value="cover"
							label={__('Cover', 'blocksy-companion')}
						/>
						<ToggleGroupControlOption
							key="contain"
							value="contain"
							label={__('Contain', 'blocksy-companion')}
						/>
					</ToggleGroupControl>

					<RangeControl
						label={__('Max width', 'blocksy-companion')}
						value={logoMaxW}
						onChange={(val) =>
							setAttributes({
								logoMaxW: val,
							})
						}
						min={10}
						max={140}
					/>

					<ToggleControl
						label={__('Show Image Frame', 'blocksy-companion')}
						checked={useFrame}
						onChange={() =>
							setAttributes({
								useFrame: !useFrame,
							})
						}
					/>
				</>
			) : null}
		</PanelBody>
	)
}

export default RenderOptions
