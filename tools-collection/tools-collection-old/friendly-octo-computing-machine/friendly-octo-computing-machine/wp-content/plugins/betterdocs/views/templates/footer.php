<div class="betterdocs-entry-footer">
	<?php
		/**
		 * Tags
		 */
		$view_object->get( 'templates/parts/tags' );

		do_action( 'betterdocs_docs_before_social' );
		/**
		 * Social Share
		 */
		$view_object->get( 'templates/parts/social' );


		/**
		 * Feedback Form
		 */
		$view_object->get( 'templates/feedback-form' );
	?>
</div> <!-- .entry-footer -->
