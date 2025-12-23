<?php
define("ITEM_THEME_NAME","Iteck");
define("ITEM_PLUGIN_DOMAIN","iteck_plg_load");
define("item_theme_version","1.1.3");
define("item_id","38682639"); 

/* Add the registration custom page */
add_action('admin_menu','iteck_registration_menu',9);
function iteck_registration_menu() {

    global $license_status;
    $license_status = get_option('license_status', '');

    if ($license_status == 'Active') {
        add_menu_page('Register '.ITEM_THEME_NAME,ITEM_THEME_NAME,'manage_options','registration','iteck_registration_page','dashicons-align-center',4);
        add_submenu_page('registration',ITEM_THEME_NAME.' Settings', 'Theme Options','manage_options','iteck_theme_settings','iteck_theme_settings');
        add_submenu_page('registration','Import demo','Import demo','manage_options','one-click-demo-import','iteck_theme_settings');
        
    }else {
        add_menu_page('Register '.ITEM_THEME_NAME,'Register '.ITEM_THEME_NAME,'manage_options','registration','iteck_registration_page','dashicons-align-center',4);
    }
    //add_submenu_page(null, ITEM_THEME_NAME.' Settings', 'Theme Options', 'manage_options', 'iteck_theme_settings_hidden', 'iteck_theme_settings');

}


class Iteck_Register_Page {
    public function deactivate_license_link() {
        if ( isset($_GET['deactivate_license']) && $_GET['deactivate_license'] == '1') {
            update_option('license_status', 'Inactive');
            update_option('saved_license_key', '');
        }
        $saved_license_key = get_option('saved_license_key', ''); // Default to empty string

        $revoke_url = "https://doc.themescamp.com/support/?license_key={".$saved_license_key."}&blog=" . urlencode(site_url());

        return apply_filters("deactivate_license_link", add_query_arg(
            array(
                'license_key' => $saved_license_key,
                'deactivate-license' => true,
            ),
            $revoke_url
        ));
    }
}


function iteck_registration_page() {



    $is_successful = isset($_GET['success']) && $_GET['success'] == '1';
    $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';

    if (!empty($token) & $is_successful) {
        update_option('saved_license_key', $token);
        update_option('license_status', 'Active');  // Set the license status to 'Active'
    }
    global $license_status;
    $saved_license_key = get_option('saved_license_key', '');
    $license_status = get_option('license_status', '');  // Get the license status from the database

    if ($license_status == 'Active') {
        $intro = esc_html__('Thank you for choosing '.ITEM_THEME_NAME.'! Your product is already registered, so you have access to:',ITEM_THEME_NAME);
        $title = esc_html__('Congratulations! Your product is registered now.',ITEM_PLUGIN_DOMAIN);
        $icon  = 'yes';
        $class = 'is-registered';
    }else {
        $intro = esc_html__('Thank you for choosing '.ITEM_THEME_NAME.'! Your product must be registered to:',ITEM_PLUGIN_DOMAIN);
        $title = esc_html__('Click on the button below to begin the registration process.',ITEM_PLUGIN_DOMAIN);
        $icon  = 'no';
        $class = 'not-registered';
    }
    $foreach = array(
        "admin-site"       => esc_html__('See the theme options',ITEM_PLUGIN_DOMAIN),
        "admin-appearance" => esc_html__('Import our awesome demos',ITEM_PLUGIN_DOMAIN),
        "admin-plugins"    => esc_html__('Install the included plugins',ITEM_PLUGIN_DOMAIN),
        "update"           => esc_html__('Receive automatic updates',ITEM_PLUGIN_DOMAIN),
        "businessman"      => esc_html__('Access to support',ITEM_PLUGIN_DOMAIN)
    );?>


    <div id="tcg-registration-wrap" class="tcg-demos-container  <?php echo esc_attr($class)?>">
        <div class="tcg-dash-container tcg-dash-container-medium">
            <div class="postbox">
                <h2><span><?php echo esc_html__('Welcome to',ITEM_THEME_NAME).' '.ITEM_THEME_NAME.'!';?></span></h2>


                <div class="inside">
                    <div class="main">
                        <h3 class="tcg-dash-margin-remove"><span class="dashicons dashicons-<?php echo esc_attr($icon);?> library-icon-key"></span> <?php echo ($title);?></h3>


                                <?php
                                    if ($is_successful) {
                                        echo '<div class="notice notice-success">Registration Successful!</div>';
                                        //update_option('license_status', 'Active');
                                    }

                                    //$license_status = get_option('license_status', 'Inactive');

                                    if ($license_status == 'Active') {
                                        echo '<p>Your License Key <small> ( purchase code )</small>: <strong>' . esc_html($saved_license_key) . '</strong></p>';
                                    }

                                ?>

                        <p class="tcg-dash-text-lead"><?php echo ($intro);?></p>
                        <ul>
                            <?php foreach ($foreach as $icon => $item) {
                                if ($icon == "admin-site") {
                                    $link = '';
                                }else if ($icon == "admin-appearance") {
                                }else if ($icon == "businessman") {
                                    $link = 'https://themescamp.ticksy.com/';
                                }else {
                                    $link = "";
                                }?>
                                <li><i class="dashicons dashicons-<?php echo esc_attr($icon)?>"></i><?php echo ($link != ""?"<a target='_blank' href='".$link."'>":"").($item).($link != ""?"</a>":"")?></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <div class="community-events-footer">

                    <?php if ($license_status == 'Active') {   global $item_updater; $item_updater = new Iteck_Register_Page(); ?>
                        <div class="tcg-support-status tcg-support-status-active">
                            <?php esc_html_e('License Status:', ITEM_PLUGIN_DOMAIN); ?> <span><?php echo esc_html($license_status); ?></span>
                            <a class="button" href="<?php echo $item_updater->deactivate_license_link(); ?>"><?php esc_html_e('Revoke License', ITEM_PLUGIN_DOMAIN); ?></a>
                        </div>

                    <?php } else { ?>
                        <div class="tcg-registration-wrap">
                            <a href="<?php echo iteck_activate_link();?>" class="button button-primary"><?php esc_html_e('Register Now!', ITEM_PLUGIN_DOMAIN); ?></a>
                            <a href="<?php echo 'https://themeforest.net/item/i/'.item_id; ?>" class="button" target="_blank"><?php esc_html_e('Purchase a License', ITEM_PLUGIN_DOMAIN); ?></a>
                        </div>
                        <?php 
                    }?>
                </div>
            </div>
        </div>
    </div>
    <?php


}

function iteck_activate_link(){

    $redirect_url = urlencode(admin_url('admin.php?page=registration&envato_verify_purchase=1'));
    $initiate_url = "https://doc.themescamp.com/support/?item={".item_id."}&blog=" . urlencode(site_url()) . "&redirect_url={$redirect_url}";

    return apply_filters( "activate_license_link", add_query_arg(
        array(
            'item'         => item_id,
        ),
        $initiate_url
    ));
}






