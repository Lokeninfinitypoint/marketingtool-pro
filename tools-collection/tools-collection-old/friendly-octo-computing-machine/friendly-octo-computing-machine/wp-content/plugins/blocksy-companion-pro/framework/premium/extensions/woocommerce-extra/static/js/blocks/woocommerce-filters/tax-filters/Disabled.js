import { createElement } from '@wordpress/element'
import { useBlockProps } from '@wordpress/block-editor'

import { __ } from 'ct-i18n'

const errors = {
	categories: __('Please select a valid taxonomy.', 'blocksy-companion'),
	attributes: __('Please select a valid attribute.', 'blocksy-companion'),
}

const Disabled = ({ isError, type, children }) => {
	const blockProps = useBlockProps({
		className: 'ct-block-notice components-notice is-warning',
	})

	if (!isError) {
		return children
	}

	return (
		<div {...blockProps}>
			<div className="components-notice__content">
				<p>{errors?.[type]}</p>
			</div>
		</div>
	)
}

export default Disabled
