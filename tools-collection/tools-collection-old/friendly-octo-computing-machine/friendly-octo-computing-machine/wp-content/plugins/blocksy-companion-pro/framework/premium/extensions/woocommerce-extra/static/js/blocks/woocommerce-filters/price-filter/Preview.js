import { createElement } from '@wordpress/element'
import { useBlockProps } from '@wordpress/block-editor'
import { Spinner } from '@wordpress/components'

import { __ } from 'ct-i18n'

const Preview = ({ attributes, blockData }) => {
	const blockProps = useBlockProps({
		className: 'ct-filter-widget-wrapper',
	})
	const { showResetButton, showPrices, showInputs } = attributes

	if (!blockData) {
		return <Spinner />
	}

	return (
		<div {...blockProps}>
			<div className="ct-price-filter">
				<div className="ct-price-filter-slider">
					<div
						className="ct-price-filter-range-track"
						style={{
							'--start': `10%`,
							'--end': `70%`,
						}}></div>

					<span
						className="ct-price-filter-range-handle-min"
						style={{
							insetInlineStart: `10%`,
						}}
					/>

					<span
						className="ct-price-filter-range-handle-max"
						style={{
							insetInlineStart: `70%`,
						}}
					/>

					<input type="range" value={10} readOnly />
					<input type="range" value={100} readOnly />
				</div>

				{showPrices && !showInputs ? (
					<div className="ct-price-filter-values">
						<span>Price:&nbsp;</span>
						<span className="ct-price-filter-min">
							{blockData.currency_symbol}10
						</span>
						<span>&nbsp;-&nbsp;</span>
						<span className="ct-price-filter-max">
							{blockData.currency_symbol}70
						</span>
					</div>
				) : null}

				{showInputs ? (
					<div
						class="ct-price-filter-inputs"
						data-currency-position={blockData.currency_position}>
						<div class="ct-price-filter-input-min">
							Minimum:
							<div class="ct-price-filter-input ct-pseudo-input">
								<small>{blockData.currency_symbol}</small>
								<input
									type="number"
									value="10"
									min="0"
									max="70"
									step="1"
									name="min_price"
								/>
							</div>
						</div>
						<div class="ct-price-filter-input-max">
							Maximum:
							<div class="ct-price-filter-input ct-pseudo-input">
								<small>{blockData.currency_symbol}</small>
								<input
									type="number"
									value="70"
									min="0"
									max="70"
									step="1"
									name="max_price"
								/>
							</div>
						</div>
					</div>
				) : null}
			</div>

			{showResetButton ? (
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
			) : null}
		</div>
	)
}

export default Preview
