import { createElement } from '@wordpress/element'

const Label = ({ showLabel = true, label, count = 0, withCount = false }) => {
	if (!showLabel && !withCount) {
		return null
	}

	return (
		<span className="ct-filter-label">
			{showLabel ? `${label}` : ''}
			{withCount ? (
				<span className="ct-filter-count">({count})</span>
			) : null}
		</span>
	)
}

export default Label
