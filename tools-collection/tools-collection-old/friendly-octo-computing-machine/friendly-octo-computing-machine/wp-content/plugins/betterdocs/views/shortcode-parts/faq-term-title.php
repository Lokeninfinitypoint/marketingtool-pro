<div class="betterdocs-faq-title">
	<?php
		$group_tag = betterdocs()->template_helper->is_valid_tag( $faq_group_title_tag );
		echo wp_kses_post( '<' . $group_tag . ' class="betterdocs-faq-title-tag">' . esc_html( $title ) . '</' . $group_tag . '>' );
	?>
</div>
