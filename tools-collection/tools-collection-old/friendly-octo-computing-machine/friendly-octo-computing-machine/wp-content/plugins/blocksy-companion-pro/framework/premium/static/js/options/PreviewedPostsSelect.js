import { createElement, useMemo } from '@wordpress/element'
import { addFilter } from '@wordpress/hooks'

import { __ } from 'ct-i18n'
import { BlockContextProvider } from '@wordpress/block-editor'

import { select } from '@wordpress/data'
import { useSelect } from '@wordpress/data'
import { EntityIdPicker } from 'blocksy-options'

if (wp.compose) {
	addFilter(
		'blockEditor.__unstableCanInsertBlockType',
		'blocksy.addPostContentFromInserter',
		(
			canInsert,
			blockType,
			rootClientId,
			{ getBlockParentsByBlockName }
		) => {
			if (blockType.name === 'core/post-content') {
				if (
					document.body.classList.contains(
						'post-type-ct_content_block'
					)
				) {
					return true
				}
			}

			return canInsert
		},
		500
	)

	addFilter(
		'editor.BlockEdit',
		'blocksy.WrapWithPostId',
		wp.compose.createHigherOrderComponent((C) => (props) => {
			if (!select('core/block-editor')) {
				return <C {...props} />
			}

			const selfBlock = select('core/block-editor').getBlock(
				props.clientId
			)

			const parentClientId = select(
				'core/block-editor'
			).getBlockHierarchyRootClientId(props.clientId)

			const parent = select('core/block-editor').getBlock(parentClientId)

			const parentAttributes =
				select('core/block-editor').getBlockAttributes(parentClientId)

			if (
				!selfBlock ||
				(selfBlock.name !== 'core/query' &&
					selfBlock.name !== 'blocksy/query' &&
					(parent.name === 'core/query' ||
						parent.name === 'blocksy/query'))
			) {
				return <C {...props} />
			}

			const data = useSelect((select) => {
				if (!select('core/editor')) {
					return {}
				}

				return select('core/editor').getEditedPostAttribute(
					'blocksy_meta'
				)
			}, [])

			if (
				!data ||
				!data.previewedPost ||
				!data.previewedPost.post_id ||
				(data.template_type === 'archive' &&
					data.template_subtype === 'canvas')
			) {
				return <C {...props} />
			}

			const previewedPost = data.previewedPost

			return (
				<BlockContextProvider
					value={{
						postId: previewedPost.post_id,
						postType: previewedPost.post_type || 'post',
					}}>
					<C
						{...{
							...props,
							context: {
								...(props.context || {}),
								postId: previewedPost.post_id,
								postType: previewedPost.post_type || 'post',
							},
						}}
					/>
				</BlockContextProvider>
			)
		})
	)
}

const PreviewedPostsSelect = ({ value, onChange }) => {
	const currentPostId = useMemo(() => value.post_id || '', [value.post_id])

	return (
		<div className="ct-previewed-post">
			<EntityIdPicker
				option={{
					placeholder: __('Select post', 'blocksy-companion'),
					entity: 'posts',
					post_type: 'ct_all_posts',
				}}
				return_type="entity"
				value={currentPostId}
				onChange={({ id, post_type }) => {
					onChange({
						post_id: id,
						post_type,
					})
				}}
			/>
		</div>
	)
}

export default PreviewedPostsSelect
