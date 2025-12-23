<?php

global $post;
$current_product_id = $post->ID;

$pages = get_posts([
	'numberposts' => -1,
	'post_type'   => 'ct_thank_you_page',
]);

$selected = (int) get_post_meta($current_product_id, '_ct_thank_you_page_id', true);

?>
<div id="ct_custom_thank_you" class="panel woocommerce_options_panel hidden">
	<div class="options_group">
		<p class="form-field">
			<label for="ct_thank_you_page_id">
				<?php esc_html_e( 'Choose page', 'blocksy-companion' ); ?>
			</label>
			
			<select name="ct_thank_you_page_id" id="ct_thank_you_page_id" class="select short">
				<option value="0">
					<?php esc_html_e( 'None', 'blocksy-companion' ); ?>
				</option>
					
				<?php foreach ( $pages as $page ) { ?>
					<option <?php echo $page->ID === $selected ? 'selected' : ''; ?> value="<?php echo esc_attr( $page->ID ); ?>">
						<?php echo esc_html( $page->post_title ); ?>
					</option>
				<?php } ?>
			</select>

			<span class="woocommerce-help-tip" tabindex="0" aria-label="<?php esc_attr_e( 'Choose a custom thank you page for this product.', 'blocksy-companion' ); ?>"></span>
		</p>
	</div>
</div>