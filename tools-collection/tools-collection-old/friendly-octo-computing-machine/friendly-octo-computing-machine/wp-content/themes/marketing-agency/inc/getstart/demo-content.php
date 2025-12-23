<div class="theme-offer">
	<?php
        // Check if the demo import has been completed
        $marketing_agency_demo_import_completed = get_option('marketing_agency_demo_import_completed', false);

        // If the demo import is completed, display the "View Site" button
        if ($marketing_agency_demo_import_completed) {
        echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'marketing-agency') . '</p>';
        echo '<span><a href="' . esc_url(home_url()) . '" class="button button-primary site-btn" target="_blank">' . esc_html__('View Site', 'marketing-agency') . '</a></span>';
        }

		//POST and update the customizer and other related data of 
        if (isset($_POST['submit'])) {

        // Check if ibtana visual editor is installed and activated
        if (!is_plugin_active('ibtana-visual-editor/plugin.php')) {
          // Install the plugin if it doesn't exist
          $marketing_agency_plugin_slug = 'ibtana-visual-editor';
          $marketing_agency_plugin_file = 'ibtana-visual-editor/plugin.php';

          // Check if plugin is installed
          $marketing_agency_installed_plugins = get_plugins();
          if (!isset($marketing_agency_installed_plugins[$marketing_agency_plugin_file])) {
              include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
              include_once(ABSPATH . 'wp-admin/includes/file.php');
              include_once(ABSPATH . 'wp-admin/includes/misc.php');
              include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

              // Install the plugin
              $marketing_agency_upgrader = new Plugin_Upgrader();
              $marketing_agency_upgrader->install('https://downloads.wordpress.org/plugin/ibtana-visual-editor.latest-stable.zip');
          }
          // Activate the plugin
          activate_plugin($marketing_agency_plugin_file);
        }    

            // ------- Create Nav Menu --------
            $marketing_agency_menuname = 'Main Menus';
            $marketing_agency_bpmenulocation = 'primary';
            $marketing_agency_menu_exists = wp_get_nav_menu_object($marketing_agency_menuname);

            if (!$marketing_agency_menu_exists) {
                $marketing_agency_menu_id = wp_create_nav_menu($marketing_agency_menuname);

                // Create Home Page
                $marketing_agency_home_title = 'Home';
                $marketing_agency_home = array(
                    'post_type' => 'page',
                    'post_title' => $marketing_agency_home_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'home'
                );
                $marketing_agency_home_id = wp_insert_post($marketing_agency_home);
                // Assign Home Page Template
                add_post_meta($marketing_agency_home_id, '_wp_page_template', 'page-template/custom-home-page.php');
                // Update options to set Home Page as the front page
                update_option('page_on_front', $marketing_agency_home_id);
                update_option('show_on_front', 'page');
                // Add Home Page to Menu
                wp_update_nav_menu_item($marketing_agency_menu_id, 0, array(
                    'menu-item-title' => __('Home', 'marketing-agency'),
                    'menu-item-classes' => 'home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $marketing_agency_home_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create Pages Page with Dummy Content
                $marketing_agency_pages_title = 'Pages';
                $marketing_agency_pages_content = '
                Explore all the pages we have on our website. Find information about our services, company, and more.
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br>
                All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $marketing_agency_pages = array(
                    'post_type' => 'page',
                    'post_title' => $marketing_agency_pages_title,
                    'post_content' => $marketing_agency_pages_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'pages'
                );
                $marketing_agency_pages_id = wp_insert_post($marketing_agency_pages);
                // Add Pages Page to Menu
                wp_update_nav_menu_item($marketing_agency_menu_id, 0, array(
                    'menu-item-title' => __('Pages', 'marketing-agency'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => home_url('/pages/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $marketing_agency_pages_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create About Us Page with Dummy Content
                $marketing_agency_about_title = 'About Us';
                $marketing_agency_about_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...<br>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br>
                There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text.<br>
                All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $marketing_agency_about = array(
                    'post_type' => 'page',
                    'post_title' => $marketing_agency_about_title,
                    'post_content' => $marketing_agency_about_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'about-us'
                );
                $marketing_agency_about_id = wp_insert_post($marketing_agency_about);
                // Add About Us Page to Menu
                wp_update_nav_menu_item($marketing_agency_menu_id, 0, array(
                    'menu-item-title' => __('About Us', 'marketing-agency'),
                    'menu-item-classes' => 'about-us',
                    'menu-item-url' => home_url('/about-us/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $marketing_agency_about_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Set the menu location if it's not already set
                if (!has_nav_menu($marketing_agency_bpmenulocation)) {
                    $locations = get_theme_mod('nav_menu_locations'); // Use 'nav_menu_locations' to get locations array
                    if (empty($locations)) {
                        $locations = array();
                    }
                    $locations[$marketing_agency_bpmenulocation] = $marketing_agency_menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                }

        }
            // Set the demo import completion flag
    		update_option('marketing_agency_demo_import_completed', true);
    		// Display success message and "View Site" button
    		echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'marketing-agency') . '</p>';
    		echo '<span><a href="' . esc_url(home_url()) . '" class="button button-primary site-btn" target="_blank">' . esc_html__('View Site', 'marketing-agency') . '</a></span>';
            //end


            // Top Bar //

            set_theme_mod( 'marketing_agency_email_address_text', 'Mail' );
            set_theme_mod( 'marketing_agency_email_address', 'support@example.com' );
            set_theme_mod( 'marketing_agency_email_icon', 'far fa-envelope' );
            set_theme_mod( 'marketing_agency_phone_number_text', 'Phone' );
            set_theme_mod( 'marketing_agency_phone_number', '+00 123 456 7890' );
            set_theme_mod( 'marketing_agency_phone_icon', 'fas fa-phone' );
            set_theme_mod( 'marketing_agency_getstarted_text', 'GET STARTED' );
            set_theme_mod( 'marketing_agency_getstarted_link', '#' );
        
            // slider section start //
            set_theme_mod( 'marketing_agency_banner_button_label', 'GET STARTED' );
            set_theme_mod( 'marketing_agency_top_button_url', '#' );
            set_theme_mod( 'marketing_agency_slider_image', get_template_directory_uri().'/assets/images/slider-bg.png');

            for($marketing_agency_i=1;$marketing_agency_i<=3;$marketing_agency_i++){
                $marketing_agency_slider_title = 'Lorem ipsum dolor sit amet, consectetur.';
                $marketing_agency_slider_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.';
                   // Create post object
                $my_post = array(
                'post_title'    => wp_strip_all_tags( $marketing_agency_slider_title ),
                'post_content'  => $marketing_agency_slider_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                );

                // Insert the post into the database
                $marketing_agency_post_id = wp_insert_post( $my_post );

                if ($marketing_agency_post_id) {
                  // Set the theme mod for the slider page
                  set_theme_mod('marketing_agency_slider_page' . $marketing_agency_i, $marketing_agency_post_id);

                   $marketing_agency_image_url = get_template_directory_uri().'/assets/images/circle'.$marketing_agency_i.'.png';

                 $marketing_agency_image_id = media_sideload_image($marketing_agency_image_url, $marketing_agency_post_id, null, 'id');

                    if (!is_wp_error($marketing_agency_image_id)) {
                         // Set the downloaded image as the post's featured image
                        set_post_thumbnail($marketing_agency_post_id, $marketing_agency_image_id);
                    }
                }
            }

            // Services Section
 
            set_theme_mod('marketing_agency_section_title', 'Lorem ipsum dolor sit amet, consectetur adipiscing' );
            set_theme_mod('marketing_agency_section_title', 'Our Articles & News' );
            set_theme_mod('marketing_agency_services_category', 'postcategory1' );

            // Define post category names and post titles
            $marketing_agency_category_names = array('postcategory1', 'postcategory2');
            $marketing_agency_title_array = array(
                array("Graphic Design", "UI/UX Interaction","Animation & Motion"),
                array("Graphic Design", "UI/UX Interaction","Animation & Motion"),
            );

            foreach ($marketing_agency_category_names as $marketing_agency_index => $marketing_agency_category_name) {
                // Create or retrieve the post category term ID
                $marketing_agency_term = term_exists($marketing_agency_category_name, 'category');
                if ($marketing_agency_term === 0 || $marketing_agency_term === null) {
                    // If the term does not exist, create it
                    $marketing_agency_term = wp_insert_term($marketing_agency_category_name, 'category');
                }
                if (is_wp_error($marketing_agency_term)) {
                    error_log('Error creating category: ' . $marketing_agency_term->get_error_message());
                    continue; // Skip to the next iteration if category creation fails
                }

                for ($marketing_agency_i = 0; $marketing_agency_i < 3; $marketing_agency_i++) {
                    // Create post content
                    $marketing_agency_title = $marketing_agency_title_array[$marketing_agency_index][$marketing_agency_i];
                    $marketing_agency_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam';   
                    // Create post post object
                    $marketing_agency_my_post = array(
                        'post_title'    => wp_strip_all_tags($marketing_agency_title),
                        'post_content'  => $marketing_agency_slider_content,
                        'post_status'   => 'publish',
                        'post_type'     => 'post', // Post type set to 'post'
                    );

                    // Insert the post into the database
                    $marketing_agency_post_id = wp_insert_post($marketing_agency_my_post);
                    if (is_wp_error($marketing_agency_post_id)) {
                        error_log('Error creating post: ' . $marketing_agency_post_id->get_error_message());
                        continue; // Skip to the next post if creation fails
                    }

                    // Assign the category to the post
                    wp_set_post_categories($marketing_agency_post_id, array((int)$marketing_agency_term['term_id']));
                    // Handle the featured image using media_sideload_image
                    $marketing_agency_image_url = get_template_directory_uri() . '/assets/images/services' . ($marketing_agency_i + 1) . '.png';
                    $marketing_agency_image_id = media_sideload_image($marketing_agency_image_url, $marketing_agency_post_id, null, 'id');

                    if (is_wp_error($marketing_agency_image_id)) {
                        error_log('Error downloading image: ' . $marketing_agency_image_id->get_error_message());
                        continue; // Skip to the next post if image download fails
                    }
                    // Assign featured image to post
                    set_post_thumbnail($marketing_agency_post_id, $marketing_agency_image_id);
                }
            } 
            //Copyright Text
            set_theme_mod( 'marketing_agency_footer_text', 'By VWThemes' );
        }
    ?>

	
    <p><?php esc_html_e('Please back up your website if it’s already live with data. This importer will overwrite your existing settings with the new customizer values for Marketing Agency', 'marketing-agency'); ?></p>
    <form action="<?php echo esc_url(home_url()); ?>/wp-admin/themes.php?page=marketing_agency_guide" method="POST" onsubmit="return validate(this);">
        <?php if (!get_option('marketing_agency_demo_import_completed')) : ?>
            <input class="run-import" type="submit" name="submit" value="<?php esc_attr_e('Run Importer', 'marketing-agency'); ?>" class="button button-primary button-large">
        <?php endif; ?>
        <div id="spinner" style="display:none;">         
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/spinner.png" alt="" />
        </div>
    </form>
    <script type="text/javascript">
        function validate(form) {
            if (confirm("Do you really want to import the theme demo content?")) {
                // Show the spinner
                document.getElementById('spinner').style.display = 'block';
                // Allow the form to be submitted
                return true;
            } 
            else {
                return false;
            }
        }
    </script>
</div>
