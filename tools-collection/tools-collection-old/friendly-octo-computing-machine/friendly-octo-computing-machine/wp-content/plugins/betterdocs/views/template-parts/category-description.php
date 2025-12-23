<?php
if ( ! $show_description || empty( $description ) ) {
	return;
}
?>
<p class="betterdocs-category-description">
	<?php
	if ( isset( $widget_type ) && $widget_type === 'category_box' ) {
		$allowed_tags = [ 'strong', 'em', 'b', 'code', 'i' ];
		echo wp_kses( $description, $allowed_tags );
	} else {
		echo wp_kses_post( $description );
	}
	?>
</p>
