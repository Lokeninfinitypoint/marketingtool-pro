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
import { Switch, Overlay } from 'blocksy-options'

import AllFonts from './AllFonts'
import Uploader, { getDefaultFutureFont } from './Uploader'

const CustomFonts = ({ extension, onExtsSync, setExtsStatus }) => {
	const [isDisplayed, setIsDisplayed] = useState(false)
	const [futureFont, setFutureFont] = useState({
		regular: null,
		variable: null,
	})

	const [pickedStackFonts, setPickedStackFonts] = useState('__DEFAULT__')

	const customFontsSettings = extension.data.settings

	const saveCustomFontsSettings = (s = null) => {
		const preloadUrls = Object.values(s.fonts)
			.reduce((acc, { variations, preloads }) => {
				if (!preloads) {
					return acc
				}

				return [
					...acc,
					...variations
						.filter(({ url }) => !!url)
						.map(
							({ variation, url }) =>
								preloads.variations.includes(variation) && url
						),
				]
			}, [])
			.filter((url) => !!url)

		onExtsSync({
			extAction: {
				type: 'update-settings',
				settings: {
					...s,
					...{
						urls: preloadUrls?.length > 0 ? preloadUrls : [],
					},
				},
			},
		})

		setExtsStatus((extStatus) => ({
			...extStatus,
			[extension.name]: {
				...extStatus[extension.name],
				data: {
					...extStatus[extension.name].data,
					settings: {
						...extStatus[extension.name].data.settings,
						...s,
					},
				},
			},
		}))
	}

	return (
		<div className="ct-extension-options ct-custom-fonts-options">
			<h4>{__('Custom Fonts Settings', 'blocksy-companion')}</h4>
			<p>
				{__(
					'Here you can see all your custom fonts that can be used in all typography options across the theme.',
					'blocksy-companion'
				)}
			</p>

			<AllFonts
				onChange={(e) => {
					saveCustomFontsSettings(e)
				}}
				customFontsSettings={customFontsSettings}
				saveCustomFontsSettings={saveCustomFontsSettings}
				editFont={(index) => {
					setFutureFont({
						regular: null,
						variable: null,
						[customFontsSettings.fonts[index].fontType]: {
							...customFontsSettings.fonts[index],
							editedIndex: index,
						},
					})

					setIsDisplayed(true)
				}}
			/>

			<Overlay
				items={isDisplayed}
				className="ct-custom-fonts-modal"
				onDismiss={() => {
					setIsDisplayed(false)
				}}
				render={() => {
					return (
						<Uploader
							extension={extension}
							futureFont={futureFont}
							setFutureFont={setFutureFont}
							onChange={(e) => {
								saveCustomFontsSettings(e)
							}}
							moveToAllFonts={() => {
								setIsDisplayed(false)
							}}
							customFontsSettings={customFontsSettings}
							pickedStackFonts={pickedStackFonts}
							setPickedStackFonts={setPickedStackFonts}
						/>
					)
				}}
			/>

			<button
				className="ct-button-primary"
				onClick={() => {
					setFutureFont({
						regular: getDefaultFutureFont('regular'),
						variable: getDefaultFutureFont('variable'),
					})

					setPickedStackFonts('__DEFAULT__')

					setIsDisplayed(true)
				}}>
				{__('Add Fonts', 'blocksy-companion')}
			</button>
		</div>
	)
}

export default CustomFonts
