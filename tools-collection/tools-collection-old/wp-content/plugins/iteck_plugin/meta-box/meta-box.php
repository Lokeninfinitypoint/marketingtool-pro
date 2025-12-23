<?php
/**
 * Metabox Class Fill.
 *
 * @package Iteck
 */
?>
<?php
/**
 * Calls the class on the post edit screen.
 */
defined('ITECK_ADDONS_ROOT') or define('ITECK_ADDONS_ROOT', dirname(__FILE__));
defined('ITECK_ADDONS_CUSTOM_POST_TYPE') or define('ITECK_ADDONS_CUSTOM_POST_TYPE', dirname(__FILE__).'/custom-post-type');
defined('ITECK_ADDONS_ROOT_DIR') or define('ITECK_ADDONS_ROOT_DIR', plugins_url().'/iteck_plugin');


function Iteck_Meta_Boxes() 
{
    new iteckMetaboxes();
}

	if ( is_admin() ) {
	    add_action( 'load-post.php', 'Iteck_Meta_Boxes' );
	    add_action( 'load-post-new.php', 'Iteck_Meta_Boxes' );
	}


/** 
 * The iteckMetaboxes Class.
 */
class iteckMetaboxes {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		$this->iteck_metabox_addons();
		add_action( 'add_meta_boxes', array( $this, 'iteck_add_meta_boxs' ) );
		add_action( 'save_post', array( $this, 'iteck_save_meta_box' ) );
		add_action('admin_enqueue_scripts', array($this, 'admin_script_loader'));

		/* Portfolio */
		add_action( 'add_meta_boxes', array( $this, 'iteck_add_meta_boxs_portfolios' ) );
	}

	/**
	 * Adds the meta box functions container.
	 */
	public function iteck_metabox_addons(){
		include('meta-box-maps.php'); 
	}

	/**
	 * Adds the meta box container.
	 */
	public function iteck_add_meta_boxs( $iteck_post_type ) {
		$iteck_post_types = array('post', 'page', 'portfolio','product');    //limit meta box to certain post types
		$flag = false;
        if ( in_array( $iteck_post_type, $iteck_post_types )){
           	$flag = true;
        }
        if($flag == true){
	        $this->iteck_add_meta_box('iteck_admin_options', 'Iteck '.ucfirst($iteck_post_type).' Settings', $iteck_post_type);
	    }

	}

	public function iteck_add_meta_box($iteck_id, $iteck_label_name, $iteck_post_type)
	{
		add_meta_box(
			$iteck_id
			,$iteck_label_name
			,array( $this, $iteck_id )
			,$iteck_post_type
			
		);
	}

	public function iteck_admin_options()
	{
		global $post;
		if($post->post_type == 'page' || $post->post_type == 'portfolio' || $post->post_type == 'product'){
			$iteck_tabs_title = array('General Settings', 'Header Settings', 'Footer','Sidepanel');
			$iteck_tabs_sub_title = array('General configuration settings', 'Header section configuration settings', 'Enable/Disable comments in '.$post->post_type, 'Footer section configuration settings', 'Sidepanel section configuration settings');
			$iteck_page_tabs = array('General Settings', 'Header Settings', 'Footer','Sidepanel');
			$iteck_page_tab_content = array('general','header', 'footer','sidepanel');
		}elseif($post->post_type == 'post'){
			$iteck_tabs_title = array('General Settings', 'Header Settings', 'Footer','Sidepanel','Post');
			$iteck_tabs_sub_title = array('General configuration settings', 'Header section configuration settings', 'Enable/Disable comments in '.$post->post_type, 'Footer section configuration settings', 'Sidepanel section configuration settings', 'Post section configuration settings');
			$iteck_page_tabs = array('General Settings', 'Header Settings', 'Footer','Sidepanel','Post');
			$iteck_page_tab_content = array('general','header', 'footer','sidepanel','post');
		}else{
			$iteck_tabs_title = array('General Settings','Header Settings', 'Footer Settings','Sidepanel Settings');
			$iteck_tabs_sub_title = array( 'General configuration settings','Header section configuration settings', 'Enable/Disable comments in page', 'Footer section configuration settings', 'Sidepanel section configuration settings');
			$iteck_page_tabs = array( 'General Settings','Header Settings', 'Footer Settings','Sidepanel Settings');
			$iteck_page_tab_content = array('general','header','footer','sidepanel');
		}

		$iteck_icon_class = array('icon-gears','fa fa-header', 'el-icon-website', 'fa fa-align-left', 'fa fa-server', 'el-icon-website icon-rotate', 'fa fa-list-alt');
		echo '<ul class="iteck_meta_box_tabs">';
		$iteck_icon = 0;
		$iteck_showicon = '';
			foreach( $iteck_page_tabs as $tab_key => $tab_name ) {
				if($iteck_icon_class){
					$iteck_showicon = '<i class="'.esc_attr($iteck_icon_class[$iteck_icon]).'"></i>';
				}
				echo '<li class="iteck_tab_'.$iteck_page_tab_content[$tab_key].'"><a href="'.esc_url($tab_name).'">'.$iteck_showicon.'<span class="group_title">'.esc_attr($tab_name).'</span></a></li>';
				$iteck_icon++;
			}
		echo '</ul>';

		echo '<div class="iteck_meta_box_tab_content">';
		foreach( $iteck_page_tab_content as $tab_content_key => $tab_content_name ) {
			echo '<div class="iteck_meta_box_tab" id="iteck_tab_'.esc_attr($tab_content_name).'">';
				echo "<div class='main_tab_title'>";
					echo "<h3>".$iteck_tabs_title[$tab_content_key]."</h3>";
					echo "<span class='description'>".$iteck_tabs_sub_title[$tab_content_key]."</span>";
				echo "</div>";
				include('metabox-tabs/metabox_'.$tab_content_name.'.php'); 
			echo '</div>';
		}
		echo '</div>';
		echo '<div class="clear"></div>';
	}


	/**
	 * Adds the meta box for Portfolio. 
	 */
	public function iteck_add_meta_boxs_portfolios( $iteck_post_type ) 
	{
		$iteck_post_types = array('portfolio','post');     //limit meta box to certain post types
		$flag = false;
        if ( in_array( $iteck_post_type, $iteck_post_types )){
           	$flag = true;
        }
        if($flag == true){
	        $this->iteck_add_meta_box('iteck_admin_options_single', 'Iteck '.ucfirst($iteck_post_type).' Format Settings', $iteck_post_type);
	    }

	}

	public function iteck_add_meta_boxs_portfolio($iteck_id, $iteck_label_name, $iteck_post_type)
	{
		add_meta_box(
			$iteck_id
			,$iteck_label_name
			,array( $this, $iteck_id )
			,$iteck_post_type
			,'advanced'
			,'high'
		);
	}

	public function iteck_admin_options_single()
	{
        global $post;
		echo '<div class="iteck_meta_box_tab_content_single">';
			echo '<div class="iteck_meta_box_tab" id="iteck_tab_single">';
		
		echo '</div>';
		if($post->post_type == 'portfolio'):
                include('metabox-tabs/metabox_portfolio_setting.php' );
                endif;
		echo '</div>';
		echo '<div class="clear"></div>';
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function iteck_save_meta_box( $iteck_post_id ) {
	
		// If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $iteck_post_id;

		/* OK, its safe for us to save the data now. */
		$iteck_data = array();
		foreach ( $_POST as $key => $value ) {
			if ( strstr( $key, 'iteck_') ) {
				// Sanitize the user input.
				$iteck_data = sanitize_text_field( $_POST[$key] );
				// Update the meta field.
				update_post_meta( $iteck_post_id, $key, $iteck_data );
			}
		}
	}

	function admin_script_loader() {
		
		global $pagenow;
		if( is_admin() && ( $pagenow=='post-new.php' || $pagenow=='post.php' ) ) {
			wp_enqueue_script('media-upload'); 
			wp_enqueue_script('thickbox');
	   		wp_enqueue_style('thickbox');
	   		wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( 'wp-color-picker');
    		wp_register_script('alpha-color-picker', ITECK_ADDONS_ROOT_DIR.'/meta-box/js/alpha-color-picker.js', array('jquery', 'wp-color-picker'), '1.0' );
		   	wp_enqueue_script('alpha-color-picker');
		   	wp_register_style('alpha-color-picker', ITECK_ADDONS_ROOT_DIR.'/meta-box/css/alpha-color-picker.css', array('wp-color-picker'), '1.0' );
		   	wp_enqueue_style('alpha-color-picker');
	   		wp_register_script('iteck-admin-metabox-cookie-js', ITECK_ADDONS_ROOT_DIR.'/meta-box/js/metabox-cookie.js', array('jquery'), '1.0' );
	   		wp_enqueue_script('iteck-admin-metabox-cookie-js');
	   		wp_register_script('iteck-admin-metabox-js', ITECK_ADDONS_ROOT_DIR.'/meta-box/js/meta-box.js', array('jquery'), '1.0' );
			wp_enqueue_script('iteck-admin-metabox-js');
	   		wp_register_style('iteck-admin-metabox-css', ITECK_ADDONS_ROOT_DIR.'/meta-box/css/meta-box.css',null, '1.0' );
	   		wp_enqueue_style('iteck-admin-metabox-css');
		}
	}
}