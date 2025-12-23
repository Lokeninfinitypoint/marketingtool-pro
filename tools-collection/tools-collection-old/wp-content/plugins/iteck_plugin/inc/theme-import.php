<?php
/**
 * IMPORT EXPORT THEME OPTIONS
 */
add_action( 'init', 'iteck_register_options_pages' );

/**
 * Registers all the required admin pages.
 */
function iteck_register_options_pages() {

  // Only execute in admin & if OT is instaliteck
  if ( is_admin() && function_exists( 'ot_register_settings' ) ) {
    // Register the pages
    ot_register_settings( 
      array(
        array( 
          'id'              => 'import_export',
          'pages'           => array(
            array(
              'id'              => 'import_export',
              'parent_slug'     => 'themes.php',
              'page_title'      => esc_html__( 'Theme Options Backup/Restore', 'iteck_plg' ),
              'menu_title'      => esc_html__( 'Options Backup', 'iteck_plg' ),
              'capability'      => 'edit_theme_options',
              'menu_slug'       => 'tmq-theme-backup',
              'icon_url'        => null,
              'position'        => null,
              'updated_message' => esc_html__( 'Options updated.', 'iteck_plg' ),
              'reset_message'   => esc_html__( 'Options reset.', 'iteck_plg' ),
              'button_text'     => esc_html__( 'Save Changes', 'iteck_plg' ),
              'show_buttons'    => false,
              'screen_icon'     => 'themes',
              'contextual_help' => null,
              'sections'        => array(
                array(
                  'id'          => 'tmq_import_export',
                  'title'       => esc_html__('Import/Export', 'iteck_plg' )
                )
              ),
              'settings'        => array(
                array(
                    'id'          => 'import_data_text',
                    'label'       => esc_html__( 'Import Theme Options', 'iteck_plg' ),
                    'desc'        => esc_html__( 'Theme Options', 'iteck_plg' ),
                    'std'         => '',
                    'type'        => 'import-data',
                    'section'     => 'tmq_import_export',
                    'rows'        => '',
                    'post_type'   => '',
                    'taxonomy'    => '',
                    'class'       => ''
                  ),
                  array(
                    'id'          => 'export_data_text',
                    'label'       => esc_html__( 'Export Theme Options', 'iteck_plg' ),
                    'desc'        => esc_html__( 'Theme Options', 'iteck_plg' ),
                    'std'         => '',
                    'type'        => 'export-data',
                    'section'     => 'tmq_import_export',
                    'rows'        => '',
                    'post_type'   => '',
                    'taxonomy'    => '',
                    'class'       => ''
                  )
              )
            )
          )
        )
      )
    );
  }
}

/**
 * Import Data option type.
 *
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_import_data' ) ) {

  function ot_type_import_data() {

    echo '<form method="post" id="import-data-form">';

      /* form nonce */
      wp_nonce_field( 'import_data_form', 'import_data_nonce' );

      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';

        /* description */
        echo '<div class="description">';

          if ( OT_SHOW_SETTINGS_IMPORT ) echo '<p>' . esc_html__( 'Only after you\'ve imported the Settings should you try and update your Theme Options.', 'iteck_plg' ) . '</p>';

          echo '<p>' . esc_html__( 'To import your Theme Options copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Theme Options" button.', 'iteck_plg' ) . '</p>';
          /* button */
          echo '<button class="option-tree-ui-button blue right hug-right">' . esc_html__( 'Import Theme Options', 'iteck_plg' ) . '</button>';
        echo '</div>';

        /* textarea */
        echo '<div class="format-setting-inner">';
          echo '<textarea rows="10" cols="40" name="import_data" id="import_data" class="textarea"></textarea>';
        echo '</div>';
      echo '</div>';
    echo '</form>';

  }

}

/**
 * Export Data option type.
 *
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_export_data' ) ) {

  function ot_type_export_data() {

    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';

      /* description */
      echo '<div class="description">';
        echo '<p>' . esc_html__( 'Export your Theme Options data by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later.', 'iteck_plg' ) . '</p>';
      echo '</div>';

      /* get theme options data */
      $data = get_option( 'option_tree' );
      $data = ! empty( $data ) ? ot_encode( serialize( $data ) ) : '';

      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_data" id="export_data" class="textarea">' . $data . '</textarea>';
      echo '</div>';
    echo '</div>';
  }
}