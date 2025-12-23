<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
?>
<div class="wpfs-form-group">
    <div class="wpfs-form-check">
        <input id="<?php $view->inheritLocale()->id(); ?>" name="<?php $view->inheritLocale()->name(); ?>" <?php $view->inheritLocale()->attributes(); ?> value="<?php $view->inheritLocale()->value(); ?>" <?php echo $form->inheritLocale === $view->inheritLocale()->value(false) ? 'checked' : ''; ?>>
        <label class="wpfs-form-check-label" for="<?php $view->inheritLocale()->id(); ?>"><?php $view->inheritLocale()->label(); ?></label>
    </div>
    <div class="wpfs-form-help">
        <?php
            echo sprintf(
                __( 'Inherit locale settings from %1$sglobal configurations%2$s.', 'wp-full-stripe-free' ),
                '<a target="_blank" href="' . MM_WPFS_Admin_Menu::getAdminUrlBySlug( MM_WPFS_Admin_Menu::SLUG_SETTINGS_WORDPRESS_DASHBOARD ) . '">',
                '</a>'
            ); 
        ?>
    </div>
</div>
<div class="wpfs-form-group wpfs-locale--can-be-disabled">
    <label class="wpfs-form-label"><?php $view->localeDecimalSeparator()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeDecimalSeparator()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->decimalSeparator == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->decimalSeparator == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group wpfs-locale--can-be-disabled">
    <label class="wpfs-form-label"><?php $view->localeUseSymbolNotCode()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeUseSymbolNotCode()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->showCurrencySymbolInsteadOfCode == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->showCurrencySymbolInsteadOfCode == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group wpfs-locale--can-be-disabled">
    <label class="wpfs-form-label"><?php $view->localeCurrencySymbolAtFirstPosition()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeCurrencySymbolAtFirstPosition()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->showCurrencySignAtFirstPosition == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->showCurrencySignAtFirstPosition == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group wpfs-locale--can-be-disabled">
    <label class="wpfs-form-label"><?php $view->localePutSpaceBetweenSymbolAndAmount()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localePutSpaceBetweenSymbolAndAmount()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->putWhitespaceBetweenCurrencyAndAmount == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->putWhitespaceBetweenCurrencyAndAmount == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
