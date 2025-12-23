    
   <?php if ( iteck_option( 'iteck_mode_switcher' ) =="on") { ?>
                        
    <!-- Dark mode switcher -->
    <div class="iteck-mode-switcher cursor-as-pointer d-none d-md-flex"> <!-- hidden-xs hidden-sm -->
        <div class="iteck-mode-switcher-item dark"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Dark') ?></p></div>
        <div class="iteck-mode-switcher-item auto"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Auto') ?></p></div>
        <div class="iteck-mode-switcher-item light"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Light') ?></p></div>
        <div class="iteck-mode-switcher-toddler">
            <div class="iteck-mode-switcher-toddler-wrap">
                <div class="iteck-mode-switcher-toddler-item dark"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Dark') ?></p></div>
                <div class="iteck-mode-switcher-toddler-item auto"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Auto') ?></p></div>
                <div class="iteck-mode-switcher-toddler-item light"><p class="iteck-mode-switcher-item-state"><?php echo esc_html('Light') ?></p></div>
            </div>
        </div>
     </div>
     <?php }?>
 