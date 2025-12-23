import {
	createElement,
	Component,
	useEffect,
	useState,
	Fragment,
} from '@wordpress/element'
import ctEvents from 'ct-events'

import classnames from 'classnames'
import { __, sprintf } from 'ct-i18n'
import { Switch } from 'blocksy-options'
import {
	humanizeStackFontName,
	humanizeVariations,
	getAllVariations,
} from './helpers'

const AllFonts = ({ customFontsSettings, onChange, editFont }) => {
	return (
		<Fragment>
			{[...customFontsSettings.fonts, ...customFontsSettings.stacks]
				.length > 0 && (
				<div className="ct-custom-fonts-list">
					<h4>{__('Available Fonts', 'blocksy-companion')}</h4>
					<ul>
						{customFontsSettings.fonts.map((font, index) => (
							<li key={index}>
								<div className="ct-custom-font-info">
									<span>
										{font.name}
										{font.__custom
											? ` (${__(
													'Dynamic Font',
													'blocksy-companion'
											  )})`
											: ''}
									</span>

									{font.fontType === 'variable' ? (
										<i>
											{__(
												'Variable font',
												'blocksy-companion'
											)}
											:&nbsp;
											{font.variations
												.filter(({ url }) => !!url)
												.map(({ variation }) =>
													humanizeVariations(
														variation,
														true
													)
												)
												.join(', ')}
										</i>
									) : (
										<i>
											{__(
												'Variations',
												'blocksy-companion'
											)}
											:&nbsp;
											{font.variations
												.map(({ variation }) =>
													humanizeVariations(
														variation
													)
												)
												.join(', ')}
										</i>
									)}
								</div>

								{!font.__custom && (
									<Fragment>
										<div className="ct-custom-font-actions">
											<button
												className="ct-edit-font"
												data-tooltip-reveal="top"
												onClick={() => editFont(index)}>
												<span className="ct-tooltip">
													{__(
														'Edit Font',
														'blocksy-companion'
													)}
												</span>
											</button>

											<button
												className="ct-remove-font"
												data-hover="red"
												data-tooltip-reveal="top"
												onClick={() => {
													onChange({
														...customFontsSettings,
														fonts: customFontsSettings.fonts.filter(
															({ name }) =>
																name !==
																font.name
														),
													})
												}}>
												<span className="ct-tooltip">
													{__(
														'Remove Font',
														'blocksy-companion'
													)}
												</span>
											</button>
										</div>
									</Fragment>
								)}
							</li>
						))}

						{customFontsSettings.stacks.map((stack) => (
							<li key={stack}>
								<div className="ct-custom-font-info">
									<span>{humanizeStackFontName(stack)}</span>

									<i>
										{__(
											'Modern Font Stack',
											'blocksy-companion'
										)}
									</i>
								</div>

								<div className="ct-custom-font-actions">
									<button
										className="ct-remove-font"
										data-hover="red"
										data-tooltip-reveal="top"
										onClick={() => {
											onChange({
												...customFontsSettings,
												stacks: customFontsSettings.stacks.filter(
													(s) => s !== stack
												),
											})
										}}>
										<span className="ct-tooltip">
											{__(
												'Remove Font',
												'blocksy-companion'
											)}
										</span>
									</button>
								</div>
							</li>
						))}
					</ul>
				</div>
			)}
		</Fragment>
	)
}

export default AllFonts
