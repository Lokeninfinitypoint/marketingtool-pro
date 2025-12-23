import {
	createElement,
	Component,
	useEffect,
	useState,
} from '@wordpress/element'
import { __ } from 'ct-i18n'
import { Switch, Select } from 'blocksy-options'
import cls from 'classnames'

import { OptionsPanel } from 'blocksy-options'

import nanoid from 'nanoid'

const MultipleLocationsSelect = ({ value, onChange, option: { choices } }) => {
	return (
		<div className="ct-controls-group">
			{value.map((l, index) => (
				<section key={l.__id}>
					<button
						type="button"
						className="ct-remove-condition-location"
						onClick={(e) => {
							e.preventDefault()
							onChange(
								value.filter(({ __id }) => l.__id !== __id)
							)
						}}>
						×
					</button>
					<OptionsPanel
						onChange={(optionId, optionValue) => {
							onChange(
								value.map((localL) =>
									localL.__id === l.__id
										? {
												...localL,
												[optionId]: optionValue,
										  }
										: localL
								)
							)
						}}
						options={{
							location: {
								label: false,
								type: 'blocksy-hooks-select',
								search: true,
								value: '',
								design: 'none',
								defaultToFirstItem: false,
								choices,
								placeholder: __(
									'Select location',
									'blocksy-companion'
								),
							},

							priority: {
								label: false,
								type: 'ct-number',
								value: 10,
								min: 1,
								max: 100,
								design: 'none',
								attr: { 'data-width': 'full' },
							},

							condition: {
								type: 'ct-condition',
								condition: {
									location: 'custom_hook',
								},
								options: {
									custom_location: {
										label: __(
											'Custom Hook',
											'blocksy-companion'
										),
										type: 'text',
										value: '',
									},
								},
							},

							other_condition: {
								type: 'ct-condition',
								condition: {
									location:
										'blocksy:single:content:paragraphs-number',
								},
								options: {
									paragraphs_count: {
										label: __(
											'After Block Number',
											'blocksy-companion'
										),
										type: 'ct-number',
										value: '3',
										design: 'inline',
										attr: {
											'data-width': 'full'
										},
									},
								},
							},

							other_condition_wh: {
								type: 'ct-condition',
								condition: {
									location:
										'blocksy:single:content:headings-number',
								},
								options: {
									headings_count: {
										label: __(
											'Before Heading Number',
											'blocksy-companion'
										),
										type: 'ct-number',
										value: '3',
										design: 'inline',
										attr: {
											'data-width': 'full'
										},
									},
								},
							},

							other_condition_cards: {
								type: 'ct-condition',
								condition: {
									location: 'blocksy:loop:card:cards-number',
								},
								options: {
									cards_count: {
										label: __(
											'After Card Number',
											'blocksy-companion'
										),
										type: 'ct-number',
										value: '3',
										design: 'inline',
										attr: {
											'data-width': 'full'
										},
									},

									repeat_for_every_card: {
										label: __(
											'Repeat',
											'blocksy-companion'
										),
										type: 'ct-switch',
										value: 'no',
										design: 'inline',
										wrapperAttr: {
											'data-location': 'block',
										},
									},
								},
							},
						}}
						value={l}
						hasRevertButton={false}
					/>
				</section>
			))}
			<button
				className="button"
				onClick={(e) => {
					e.preventDefault()

					onChange([
						...value,
						{
							__id: nanoid(),
							location: '',
							priority: 10,
							custom_location: '',
							paragraphs_count: '5',
						},
					])
				}}>
				{__('Add New Location', 'blocksy-companion')}
			</button>
		</div>
	)

	return (
		<Select
			onChangeFor={onChangeFor}
			onChange={(value) => {
				onChange(value)

				/*
				setTimeout(() => {
					onChangeFor(
						'priority',
						blocksy_premium_admin.all_hooks.find(
							({ hook }) => hook === value
						).priority || 10
					)
				}, 1000)
                */
			}}
			{...props}
		/>
	)
}

export default MultipleLocationsSelect
