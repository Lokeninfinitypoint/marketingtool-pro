
<?php
// You can start editing here -- including this comment!
if ( have_comments() ) : ?>
	<h2 class="comments-title">
		<?php
		$comments_number = get_comments_number();
		if ( '1' === $comments_number ) {
			/* translators: %s: post title */
			printf( _x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'iteck' ), get_the_title() );
		} else {
			printf(
				/* translators: 1: number of comments, 2: post title */
				_nx(
					'%1$s Reply to &ldquo;%2$s&rdquo;',
					'%1$s Comments to &ldquo;%2$s&rdquo;',
					$comments_number,
					'comments title',
					'iteck'
				),
				number_format_i18n( $comments_number ),
				get_the_title()
			);
		}?>
	</h2>
	<ul id="comments" class="commentlist clearfix"><?php wp_list_comments('avatar_size=88&callback=iteck_comment'); ?></ul>
<?php endif; // have_comments() ?>

<div class="pagination-comment clearfix"><?php paginate_comments_links(); ?> </div>

<?php comment_form(array('title_reply'=> esc_html__('Leave A Comment','iteck'), 'comment_notes_before'=>'', 'comment_notes_after'=>'',
'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"'.' placeholder="'.esc_attr__('Type your comment here ...','iteck').'" cols="45" rows="8" ></textarea></p>',
)); ?> 