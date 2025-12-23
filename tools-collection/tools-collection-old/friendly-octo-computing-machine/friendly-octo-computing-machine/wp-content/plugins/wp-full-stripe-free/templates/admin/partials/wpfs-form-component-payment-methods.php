<?php
/** @var $option MM_WPFS_Control */

?>
<div class="wpfs-form-check wpfs-form-check--block">
	<input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>[]" <?php if ( isset( $form->paymentMethods ) && ! empty( $form->paymentMethods ) && in_array( $option->metadata()['id'], json_decode( $form->paymentMethods ) ) ) : ?> checked<?php endif; ?> value="<?php $option->value(); ?>" <?php $option->attributes(); ?> />
	<label class="wpfs-form-check-label__payment_method wpfs-form-check-label" for="<?php $option->id(); ?>">
		<span class="wpfs-form-check-label__title"><?php $option->label(); ?> (<a href="<?php echo $option->metadata()['external_docs'] ?>" target="_blank">Stripe docs</a>)</span>
		<?php if ( isset( $option->metadata()['currencies'] ) and count( $option->metadata()['currencies'] ) > 0 ) : ?>
			<div class="wpfs-form-check-label__desc">
				Currencies:
				<code style="line-height: 26px;">
							<?php
							if ( is_array( $option->metadata()['currencies'] ) && count( $option->metadata()['currencies'] ) > 1 ) {
								echo strtoupper( implode( ', ', $option->metadata()['currencies'] ) );
							} else {
								echo strtoupper( $option->metadata()['currencies'][0] );
							}
							?>
						</code>
			</div>
		<?php endif; ?>
		<?php if ( isset( $option->metadata()['countries'] ) and count( $option->metadata()['countries'] ) > 0 ) : ?>
			<div class="wpfs-form-check-label__desc">
				Countries:
				<code style="line-height: 26px;">
							<?php
							if ( is_array( $option->metadata()['countries'] ) && count( $option->metadata()['countries'] ) > 1 ) {
								echo strtoupper( implode( ', ', $option->metadata()['countries'] ) );
							} else {
								echo strtoupper( $option->metadata()['countries'][0] );
							}
							?>
						</code>
			</div>
		<?php endif; ?>
		<?php if ( isset( $option->metadata()['bnpl'] ) and $option->metadata()['bnpl'] ) : ?>
			<div class="wpfs-form-check-label__desc info">
				Payment type: Buy now, pay later
			</div>
		<?php elseif ( isset( $option->metadata()['recurring'] ) and $option->metadata()['recurring'] ) : ?>
			<div class="wpfs-form-check-label__desc info">
				Payment type: Recurring
			</div>
		<?php elseif ( isset( $option->metadata()['recurring'] ) and $option->metadata()['recurring'] === false ) : ?>
			<div class="wpfs-form-check-label__desc info">
				Payment type: One-off only
			</div>
		<?php endif; ?>
		<span class="wpfs-form-check-label__illu">
			<img src="<?php echo MM_WPFS_Assets::images( 'payment-methods/' . $option->metadata()['icon'] ); ?>" />
		</span>
	</label>
</div>