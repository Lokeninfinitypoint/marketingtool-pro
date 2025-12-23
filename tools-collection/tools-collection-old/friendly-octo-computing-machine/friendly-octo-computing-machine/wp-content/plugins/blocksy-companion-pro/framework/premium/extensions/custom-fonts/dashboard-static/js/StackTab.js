import { createElement } from '@wordpress/element'
import { humanizeStackFontName } from './helpers'

import { __, sprintf } from 'ct-i18n'

const StackTab = ({
	extension,
	customFontsSettings,
	onChange,
	pickedStackFonts,
	setPickedStackFonts,
}) => {
	const selectedStacks =
		pickedStackFonts === '__DEFAULT__'
			? customFontsSettings.stacks || []
			: pickedStackFonts

	return (
		<>
			<h4 className="ct-title">
				{__('Font Stacks', 'blocksy-companion')}
			</h4>

			<span
				className="ct-option-description"
				dangerouslySetInnerHTML={{
					__html: sprintf(
						__(
							'Collection of %s15 handpicked%s font families that ensure a similar look and feel of your text across all platforms, in the fastest and most robust way possible.',
							'blocksy-companion'
						),
						'<a href="https://modernfontstacks.com/" target="_blank">',
						'</a>'
					),
				}}
			/>

			<div
				className="ct-option-checkbox ct-font-stacks-list"
				data-columns="2">
				{Object.keys(extension.data.font_stacks)
					.sort()
					.map((stack, index) => {
						return (
							<label key={stack}>
								<input
									type="checkbox"
									checked={selectedStacks.includes(stack)}
									onChange={() => {
										setPickedStackFonts(
											selectedStacks.includes(stack)
												? selectedStacks.filter(
														(s) => s !== stack
												  )
												: [...selectedStacks, stack]
										)
									}}
								/>

								{humanizeStackFontName(stack)}
							</label>
						)
					})}
			</div>
		</>
	)
}

export default StackTab
