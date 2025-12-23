import { createElement } from '@wordpress/element'

const Counter = ({ showCounters, count }) => {
	if (!showCounters) {
		return null
	}

	return <span className="ct-filter-count">{count}</span>
}

export default Counter
