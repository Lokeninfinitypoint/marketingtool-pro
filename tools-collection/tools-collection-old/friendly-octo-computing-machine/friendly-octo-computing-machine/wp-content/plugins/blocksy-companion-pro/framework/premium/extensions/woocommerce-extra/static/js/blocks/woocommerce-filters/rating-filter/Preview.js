import { createElement } from '@wordpress/element'
import { useBlockProps } from '@wordpress/block-editor'
import { __ } from 'ct-i18n'
import cls from 'classnames'

const ratings = [
	{
		id: 1,
		label: __('1 star', 'blocksy-companion'),
	},
	{
		id: 2,
		label: __('2 stars', 'blocksy-companion'),
	},
	{
		id: 3,
		label: __('3 stars', 'blocksy-companion'),
	},
	{
		id: 4,
		label: __('4 stars', 'blocksy-companion'),
	},
	{
		id: 5,
		label: __('5 stars', 'blocksy-companion'),
	},
]

const Preview = ({ attributes }) => {
	const blockProps = useBlockProps({
		className: 'ct-filter-widget-wrapper',
	})

	const { showResetButton } = attributes

	return (
		<div {...blockProps}>
			<div className="ct-rating-filter">
				<ul className="ct-filter-widget" data-display-type="inline">
					{ratings.map(({ id, label }) => {
						return (
							<li
								className={cls('ct-filter-item', {
									active: id === 4,
								})}
								key={id}>
								<a
									href="#"
									rel="nofollow"
									ariaLabel={id}
									dataKey="filter_stock_rating"
									dataValue={id}>
										<svg
											width="22"
											height="22"
											viewBox="0 0 24 24"
											fill="currentColor"
											aria-hidden="true">
											<path
												d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
											/>
										</svg>
								</a>
							</li>
						)
					})}
				</ul>
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
