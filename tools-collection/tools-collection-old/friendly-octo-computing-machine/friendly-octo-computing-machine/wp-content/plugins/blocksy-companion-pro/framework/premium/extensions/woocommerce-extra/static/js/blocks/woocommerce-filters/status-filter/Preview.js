import { createElement } from '@wordpress/element'
import { useBlockProps } from '@wordpress/block-editor'
import { __ } from 'ct-i18n'
import { Spinner } from '@wordpress/components'

const Preview = ({ attributes, blockData }) => {
	const blockProps = useBlockProps({
		className: 'ct-filter-widget-wrapper',
	})

	const { showCounters, showResetButton, showCheckboxes, layout, statuses } =
		attributes

	const { status_counts } = blockData || {}

	if (!blockData) {
		return <Spinner />
	}

	return (
		<div {...blockProps}>
			<div className="ct-status-filter">
				<ul className="ct-filter-widget" data-display-type={layout}>
					{statuses.map(({ id, label, enabled }) => {
						if (!enabled || !(+status_counts[id] || 0)) {
							return null
						}

						return (
							<li className="ct-filter-item" key={id}>
								<div className="ct-filter-item-inner">
									<a
										href="#"
										rel="nofollow"
										aria-label={id}
										data-key="filter_stock_status"
										data-value={id}>
										{showCheckboxes ? (
											<span className="ct-filter-checkbox"></span>
										) : null}
										<span className="ct-filter-label">
											{label}
										</span>
										{showCounters ? (
											<span className="ct-filter-count">
												{status_counts[id] || 0}
											</span>
										) : null}
									</a>
								</div>
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
