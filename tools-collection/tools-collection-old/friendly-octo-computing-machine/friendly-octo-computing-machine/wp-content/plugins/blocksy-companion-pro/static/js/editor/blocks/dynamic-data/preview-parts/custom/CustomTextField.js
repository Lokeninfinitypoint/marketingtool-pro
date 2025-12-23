import { createElement, Fragment } from '@wordpress/element'

import {
	useBlockProps,
	__experimentalUseBorderProps as useBorderProps,
} from '@wordpress/block-editor'

import classnames from 'classnames'

import { useBlockSupportsCustom } from '../../hooks/use-block-supports-custom'

const CustomTextField = ({
	fieldDescriptor,
	attributes,
	attributes: { align, tagName: TagName, before, after, fallback },
	fieldData,
}) => {
	const blockProps = useBlockProps({
		className: classnames('ct-dynamic-data', {
			[`has-text-align-${align}`]: align,
		}),
	})

	const uniqueClass = blockProps.className
		.split(' ')
		.find((c) => c.startsWith('wp-elements-'))

	const previewData = useBlockSupportsCustom({
		fieldType: 'text',
		attributes,
		uniqueClass,
	})

	const borderProps = useBorderProps(attributes)

	let isFallback = false

	let valueToRender = fieldData || ''

	if (!valueToRender) {
		isFallback = true
		valueToRender = fallback || `Dynamic data: ${fieldDescriptor?.label}`
	}

	if (!isFallback && valueToRender && typeof valueToRender === 'string') {
		valueToRender = before + valueToRender + after
	}

	let css = ''

	if (previewData.css) {
		css += previewData.css
	}

	return (
		<Fragment>
			<TagName
				{...blockProps}
				{...borderProps}
				style={{
					...(blockProps.style || {}),
					...(borderProps.style || {}),
					...(previewData.style || {}),
				}}
				className={classnames(
					blockProps.className,
					borderProps.className,
					previewData.className
				)}
				dangerouslySetInnerHTML={{ __html: valueToRender }}
			/>

			{css && <style>{css}</style>}
		</Fragment>
	)
}

export default CustomTextField
