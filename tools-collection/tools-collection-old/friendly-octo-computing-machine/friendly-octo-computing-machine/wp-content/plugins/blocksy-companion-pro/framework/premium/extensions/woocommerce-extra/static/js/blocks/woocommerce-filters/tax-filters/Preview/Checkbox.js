import { createElement } from '@wordpress/element'

const Checkbox = ({ showCheckbox }) => {
	if (showCheckbox) {
		return <span className="ct-filter-checkbox" />
	}

	return null
}

export default Checkbox
