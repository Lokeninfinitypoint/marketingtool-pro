<div class="theme-offer">
	<?php
        // Check if the demo import has been completed
        $software_agency_demo_import_completed = get_option('software_agency_demo_import_completed', false);

        // If the demo import is completed, display the "View Site" button
        if ($software_agency_demo_import_completed) {
        echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'software-agency') . '</p>';
        echo '<span><a href="' . esc_url(home_url()) . '" class="button button-primary site-btn" target="_blank">' . esc_html__('View Site', 'software-agency') . '</a></span>';
        }

		//POST and update the customizer and other related data
        if (isset($_POST['submit'])) {

            // Check if ibtana visual editor is installed and activated
            if (!is_plugin_active('ibtana-visual-editor/plugin.php')) {
              // Install the plugin if it doesn't exist
              $software_agency_plugin_slug = 'ibtana-visual-editor';
              $software_agency_plugin_file = 'ibtana-visual-editor/plugin.php';

              // Check if plugin is installed
              $software_agency_installed_plugins = get_plugins();
              if (!isset($software_agency_installed_plugins[$software_agency_plugin_file])) {
                  include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
                  include_once(ABSPATH . 'wp-admin/includes/file.php');
                  include_once(ABSPATH . 'wp-admin/includes/misc.php');
                  include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

                  // Install the plugin
                  $software_agency_upgrader = new Plugin_Upgrader();
                  $software_agency_upgrader->install('https://downloads.wordpress.org/plugin/ibtana-visual-editor.latest-stable.zip');
              }
              // Activate the plugin
              activate_plugin($software_agency_plugin_file);
            }

            // Check if woocommerce is installed and activated
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
              // Install the plugin if it doesn't exist
              $software_agency_plugin_slug = 'woocommerce';
              $software_agency_plugin_file = 'woocommerce/woocommerce.php';

              // Check if plugin is installed
              $installed_plugins = get_plugins();
              if (!isset($installed_plugins[$software_agency_plugin_file])) {
                  include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
                  include_once(ABSPATH . 'wp-admin/includes/file.php');
                  include_once(ABSPATH . 'wp-admin/includes/misc.php');
                  include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

                  // Install the plugin
                  $software_agency_upgrader = new Plugin_Upgrader();
                  $software_agency_upgrader->install('https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip');
              }
              // Activate the plugin
              activate_plugin($software_agency_plugin_file);
            }

            // ------- Create Nav Menu --------
            $software_agency_menuname = 'Main Menus';
            $software_agency_bpmenulocation = 'primary';
            $software_agency_menu_exists = wp_get_nav_menu_object($software_agency_menuname);

            if (!$software_agency_menu_exists) {
                $software_agency_menu_id = wp_create_nav_menu($software_agency_menuname);

                // Create Home Page
                $software_agency_home_title = 'Home';
                $software_agency_home = array(
                    'post_type' => 'page',
                    'post_title' => $software_agency_home_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'home'
                );
                $software_agency_home_id = wp_insert_post($software_agency_home);
                // Assign Home Page Template
                add_post_meta($software_agency_home_id, '_wp_page_template', 'page-template/custom-home-page.php');
                // Update options to set Home Page as the front page
                update_option('page_on_front', $software_agency_home_id);
                update_option('show_on_front', 'page');
                // Add Home Page to Menu
                wp_update_nav_menu_item($software_agency_menu_id, 0, array(
                    'menu-item-title' => __('Home', 'software-agency'),
                    'menu-item-classes' => 'home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $software_agency_home_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create Pages Page with Dummy Content
                $software_agency_pages_title = 'Pages';
                $software_agency_pages_content = '
                <p>Explore all the pages we have on our website. Find information about our services, company, and more.</p>

                 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br>

                  All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $software_agency_pages = array(
                    'post_type' => 'page',
                    'post_title' => $software_agency_pages_title,
                    'post_content' => $software_agency_pages_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'pages'
                );
                $software_agency_pages_id = wp_insert_post($software_agency_pages);
                // Add Pages Page to Menu
                wp_update_nav_menu_item($software_agency_menu_id, 0, array(
                    'menu-item-title' => __('Pages', 'software-agency'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => home_url('/pages/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $software_agency_pages_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create About Us Page with Dummy Content
                $software_agency_about_title = 'About Us';
                $software_agency_about_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...<br>

                         Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br>

                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text.<br>

                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $software_agency_about = array(
                    'post_type' => 'page',
                    'post_title' => $software_agency_about_title,
                    'post_content' => $software_agency_about_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'about-us'
                );
                $software_agency_about_id = wp_insert_post($software_agency_about);
                // Add About Us Page to Menu
                wp_update_nav_menu_item($software_agency_menu_id, 0, array(
                    'menu-item-title' => __('About Us', 'software-agency'),
                    'menu-item-classes' => 'about-us',
                    'menu-item-url' => home_url('/about-us/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $software_agency_about_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Set the menu location if it's not already set
                if (!has_nav_menu($software_agency_bpmenulocation)) {
                    $locations = get_theme_mod('nav_menu_locations'); // Use 'nav_menu_locations' to get locations array
                    if (empty($locations)) {
                        $locations = array();
                    }
                    $locations[$software_agency_bpmenulocation] = $software_agency_menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                }

        }


            // Set the demo import completion flag
    		update_option('software_agency_demo_import_completed', true);
    		// Display success message and "View Site" button
    		echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'software-agency') . '</p>';
    		echo '<span><a href="' . esc_url(home_url()) . '" class="button button-primary site-btn" target="_blank">' . esc_html__('View Site', 'software-agency') . '</a></span>';
            //end


            // Top Bar //
            set_theme_mod( 'software_agency_top_bar_text', 'Lorem ipsum dolor sit amet ipsum dolor sit amet.' );
            set_theme_mod( 'software_agency_support_text', 'Support' );
            set_theme_mod( 'software_agency_support_link', '#' );
            set_theme_mod( 'software_agency_wishlist_text', 'Wishlist' );
            set_theme_mod( 'software_agency_wishlist_link', '#' );
            set_theme_mod( 'software_agency_location_text', 'Our Address' );
            set_theme_mod( 'software_agency_phone_number_text', 'Reach Us' );
            set_theme_mod( 'software_agency_phone_number', '+91 123 456 7890' );
            set_theme_mod( 'software_agency_email_address_text', 'Email Us At' );
            set_theme_mod( 'software_agency_email_address', 'example@support.com');
            set_theme_mod( 'software_agency_get_started_text', 'Get Started');
            set_theme_mod( 'software_agency_get_started_link', '#');


            // slider section start //          
            for($software_agency_i=1;$software_agency_i<=3;$software_agency_i++){
               $software_agency_slider_title = 'Lorem ipsum dolor sit amet,sed do eiusmod!';
               $software_agency_slider_content = 'Lorem ipsum is simply dummy text of the printing and typesetting industry.';
                  // Create post object
               $my_post = array(
               'post_title'    => wp_strip_all_tags( $software_agency_slider_title ),
               'post_content'  => $software_agency_slider_content,
               'post_status'   => 'publish',
               'post_type'     => 'page',
               );

               // Insert the post into the database
               $software_agency_post_id = wp_insert_post( $my_post );

               if ($software_agency_post_id) {
                 // Set the theme mod for the slider page
                 set_theme_mod('software_agency_slider_page' . $software_agency_i, $software_agency_post_id);

                  $software_agency_image_url = get_template_directory_uri().'/assets/images/slider'.$software_agency_i.'.png';

                $software_agency_image_id = media_sideload_image($software_agency_image_url, $software_agency_post_id, null, 'id');

                    if (!is_wp_error($software_agency_image_id)) {
                        // Set the downloaded image as the post's featured image
                        set_post_thumbnail($software_agency_post_id, $software_agency_image_id);
                    }
                }
            }


            // products

            $software_agency_title_array = array(
            array("Product Title #1",
                "Product Title #2",
                "Product Title #3",
                "Product Title #4")
            );

            foreach ($software_agency_title_array as $software_agency_titles) {
                // Loop to create only 4 products
                for ($software_agency_i = 0; $software_agency_i < 4; $software_agency_i++) {
                    // Create product content
                    $software_agency_title = $software_agency_titles[$software_agency_i];
                    $software_agency_content = 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';

                    // Create product post object
                    $software_agency_my_post = array(
                        'post_title'    => wp_strip_all_tags($software_agency_title),
                        'post_content'  => $software_agency_content,
                        'post_status'   => 'publish',
                        'post_type'     => 'product',
                    );
                    set_theme_mod('software_agency_product_settings', esc_url($software_agency_post_id));
                    // Insert the product into the database
                    $software_agency_post_id = wp_insert_post($software_agency_my_post);

                    if (is_wp_error($software_agency_post_id)) {
                        error_log('Error creating product: ' . $software_agency_post_id->get_error_message());
                        continue; // Skip to the next product if creation fails
                    }

                    // Add product meta (price, etc.)
                    update_post_meta($software_agency_post_id, '_sale_price', '99.00'); // Sale price
                    update_post_meta($software_agency_post_id, '_price', '99.00'); // Active price

                    // Handle the featured image using media_sideload_image
                    $software_agency_image_url = get_template_directory_uri() . '/assets/images/product' . ($software_agency_i + 1) . '.png';
                    $software_agency_image_id = media_sideload_image($software_agency_image_url, $software_agency_post_id, null, 'id');

                    if (is_wp_error($software_agency_image_id)) {
                        error_log('Error downloading image: ' . $software_agency_image_id->get_error_message());
                        continue; // Skip to the next product if image download fails
                    }

                    // Assign featured image to product
                    set_post_thumbnail($software_agency_post_id, $software_agency_image_id);
                }
            }

           // Check if the 'Products' page already exists
            $software_agency_page_query = new WP_Query(array(
                'post_type'      => 'page',
                'title'          => 'Products',
                'post_status'    => 'publish',
                'posts_per_page' => 1
            ));

            if (!$software_agency_page_query->have_posts()) {
                $software_agency_page_title = 'Explore Bestselling Softwares';
                $productpage = '[products limit="4" columns="4"]';

                // Append the WooCommerce products shortcode to the content
                $software_agency_content = '';
                $software_agency_content .= do_shortcode($productpage);

                // Create the new page
                $software_agency_page = array(
                    'post_type'    => 'page',
                    'post_title'   => $software_agency_page_title,
                    'post_content' => $software_agency_content,
                    'post_status'  => 'publish',
                    'post_author'  => 1,
                    'post_slug'    => 'products'
                );

                // Insert the page and get its ID
                $software_agency_page_id = wp_insert_post($software_agency_page);

                // Store the page ID in theme mod for future reference
                if (!is_wp_error($software_agency_page_id)) {
                    set_theme_mod('software_agency_product_settings', $software_agency_page_id);
                }
            }

            //Copyright Text
            set_theme_mod( 'software_agency_footer_text', 'By VWThemes' );

        }
    ?>

	<p><?php esc_html_e('Please back up your website if it’s already live with data. This importer will overwrite your existing settings with the new customizer values for Software Agency', 'software-agency'); ?></p>
    <form action="<?php echo esc_url(home_url()); ?>/wp-admin/themes.php?page=software_agency_guide" method="POST" onsubmit="return validate(this);">
        <?php if (!get_option('software_agency_demo_import_completed')) : ?>
            <input class="run-import" type="submit" name="submit" value="<?php esc_attr_e('Run Importer', 'software-agency'); ?>" class="button button-primary button-large">
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
