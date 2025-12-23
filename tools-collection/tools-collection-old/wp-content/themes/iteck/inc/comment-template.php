<?php 
function iteck_comment($comment, $args, $depth) {

	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}?>

	<<?php echo esc_attr(  $tag ) ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) { ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
		<?php } ?>

		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>

		<div class="comment-inner clearfix">
			<?php if ($comment->comment_approved == '0') : ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.','iteck') ?></em>
				<br />
			<?php endif; ?>
			<p class="fn comment-author"> <?php comment_author_link(); ?></p>

			<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>">
				<?php
				/* translators: 1: date, 2: time */
				printf(  get_comment_date()) ?></a><?php edit_comment_link(); 
				?>
			</div>

			<?php comment_text(); ?>

			<?php 

				$comment_reply_link = get_comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<span class="comment-reply">',
							'after'     => '</span>',
						)
					)
				);

			if ( $comment_reply_link) {?>

				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
			<?php } ?>
		</div>

	<?php if ( 'div' != $args['style'] ) { ?>
	</div>
	<?php }
}