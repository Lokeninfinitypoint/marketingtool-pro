<?php
namespace IteckPlugin;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget. 
 *
 * @since 1.0.0
 */
class IteckPlugin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->init();
		$this->add_actions();
		add_filter( 'elementor/icons_manager/additional_tabs',  [$this, 'additional_tabs'] );
		add_filter( 'elementor/icons_manager/additional_tabs',  [$this, 'peicon_tab']);
	}
	const VERSION = '1.0.0';




	// public $iteck_elements = array(
	// 	'heading',
	// 	'info-box'
	// );
	
     public function additional_tabs($tabs)
      {
        $json_url =ITECK_URL.'assets/fonts/flaticon/flaticon.json';

        $flaticon = [
          'name'          => 'flaticon',
          'label'         => esc_html__( 'Iteck Icons', 'iteck_plg' ),
          'url'           => false,
          'enqueue'       => false,
          'prefix'        => '',
          'displayPrefix' => '',
          'labelIcon'     => 'fab fa-font-awesome-alt',
          'ver'           => '1.0.0',
          'fetchJson'     => $json_url,
        ];
        array_push( $tabs, $flaticon);


        return $tabs;
      }
     
     public function peicon_tab($petab)
      {
        $pe_json_url =ITECK_URL.'assets/fonts/peicon/peicon.json';

        $peicon = [
          'name'          => 'peicon',
          'label'         => esc_html__( 'Pe Icons', 'iteck_plg' ),
          'url'           => false,
          'enqueue'       => false,
          'prefix'        => '',
          'displayPrefix' => '',
          'labelIcon'     => 'fab fa-font-awesome-alt',
          'ver'           => '1.0.0',
          'fetchJson'     => $pe_json_url,
        ];
        array_push( $petab, $peicon);


        return $petab;
      }

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	public function add_actions() {

		//register all script 
		add_action( 'elementor/widgets/widgets_registered',[ $this, 'on_widgets_registered' ] );

		//blog masonry script 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-blog-masonry',ITECK_URL .'assets/js/blog-mason.js', array('jquery'), null, true  );} ); 

		//Swiper slider script
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('jquery-swiper',ITECK_URL .'assets/js/swiper.min.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-slider-script',ITECK_URL .'assets/js/slider.js', array('jquery'), null, true  );} );

		//Animated headline
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('animated-headline',ITECK_URL .'assets/js/animated.headline.js', array('jquery'), null, true  );} );
		
		//WOW Animate
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('wow',ITECK_URL .'assets/js/wow.min.js', array('jquery'), null, true  );} );

		//simpleParallax
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('simpleParallax',ITECK_URL .'assets/js/simpleParallax.min.js', array('jquery'), null, true  );} );

		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteckMatter',ITECK_URL .'assets/js/matter.min.js', array('jquery'), null, true  );} );

		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteckGsap',ITECK_URL .'assets/js/gsap.min.js', array('jquery'), null, true  );} );


		//Throwable
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-throwable',ITECK_URL .'assets/js/throwable.js', array('jquery'), null, true  );} );

		//VideoButton 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('fancy-box',ITECK_URL .'assets/js/jquery.fancybox.js', array('jquery'), null, true  );} );

		//Video Popup 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('lity',ITECK_URL .'assets/js/lity.js', array('jquery'), null, true  );} );

		//Counter up
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('counterup',ITECK_URL .'assets/js/jquery.counterup.js', array('jquery'), null, true  );} );
		//Countdown
        add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-countdown',ITECK_URL .'assets/js/jquery.countdown.min.js', array('jquery'), null, true  );} );

		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-progress',ITECK_URL .'assets/js/progress-bar.js', array('jquery'), null, true  );} );

		//isotope
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('jquery-isotope',ITECK_URL .'assets/js/isotope.min.js', array('jquery'), null, true  );} );

		//jQuery UI
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-jquery-ui',ITECK_URL .'assets/js/jquery-ui.min.js', array('jquery'), null, true  );} );

		//iteck price filter silder
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-products-filters',ITECK_URL .'assets/js/products-filters.js', array('jquery'), null, true  );} );


		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('jquery-waypoints',ITECK_URL .'assets/js/jquery.waypoints.min.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-skills',ITECK_URL .'assets/js/skills.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-bootstrap-bundle',ITECK_URL .'assets/js/bootstrap.bundle.min.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-imgbox-slider',ITECK_URL .'assets/js/imgbox-slider.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-image-comparison-slider',ITECK_URL .'assets/js/image-comparison-slider.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-work-process',ITECK_URL .'assets/js/work-process.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-blog-slider-script',ITECK_URL .'assets/js/blog-slider.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('prefixfree',ITECK_URL .'assets/js/prefixfree.min.js', array('jquery'), null, true  );} );
		//gallery popup 

		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-gallery-popup',ITECK_URL .'assets/js/popup-gallery.js', array('jquery'), null, true  );} );
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-post-list-carousel',ITECK_URL .'assets/js/post-list-carousel.js', array('jquery'), null, true  );} ); 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-blog-script',ITECK_URL .'assets/js/blog-carousel.js', array('jquery'), null, true  );} ); 
		
		//gallery  masonry
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-masonry-gallery',ITECK_URL .'assets/js/mason-gallery.js', array('jquery'), null, true  );} );
		
		//share script
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-share',ITECK_URL .'assets/js/share.js', array('jquery'), null, true  );} );

		//Portfolio filter mixitup
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-mixitup',ITECK_URL .'assets/js/mixitup.min.js', array('jquery'), null, true  );} );

		//Portfolio 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-portfolio',ITECK_URL .'assets/js/portfolio.js', array('jquery'), null, true  );} );

		//Pricing 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-pricing',ITECK_URL .'assets/js/pricing.js', array('jquery'), null, true  );} );

		//Info Box 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-infobox',ITECK_URL .'assets/js/info-box.js', array('jquery'), null, true  );} );

		//Image 
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-image',ITECK_URL .'assets/js/image.js', array('jquery'), null, true  );} );
		
		//testmonial
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-testimonial',ITECK_URL .'assets/js/testimonial.js', array('jquery'), null, true  );} );
		//Header search
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-header-search',ITECK_URL .'assets/js/header-search.js', array('jquery'), null, true  );} );
		//Header Offcanvas
		add_action( 'elementor/frontend/after_register_scripts', function() {  wp_register_script('iteck-header-offcanvas',ITECK_URL .'assets/js/header-offcanvas.js', array('jquery'), null, true  );} );

		//Video Popup
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('iteck-fancy-box-style',ITECK_URL .'assets/css/jquery.fancybox.css', array(), null, 'all'  );} );
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('lity',ITECK_URL .'assets/css/lity.css', array(), null, 'all'  );} );
		
		// Styles
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('fa-style-addons',ITECK_URL .'assets/fonts/fa/css/fontawesome.min.css', array(), null, 'all'  );} );
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('iteck-style-addons',ITECK_URL .'assets/fonts/flaticon/flaticon.css', array(), null, 'all'  );} );
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('peicon-style-addons',ITECK_URL .'assets/fonts/peicon/pe-icon-7-stroke.css', array(), null, 'all'  );} );
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('bootstrap-icons',ITECK_URL .'assets/fonts/bootstrap-icons/bootstrap-icons.css', array(), null, 'all'  );} );
		add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('swiper-slider-style',ITECK_URL .'assets/css/swiper.min.css', array(), null, 'all'  );} );
		
		// Icon fonts
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_arrows', ITECK_URL . '/assets/fonts/linea/arrows/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_basic', ITECK_URL . '/assets/fonts/linea/basic/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_basic_2', ITECK_URL . '/assets/fonts/linea/basic_ela/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_ecommerce', ITECK_URL . '/assets/fonts/linea/basic/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_music', ITECK_URL . '/assets/fonts/linea/basic/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_software', ITECK_URL . '/assets/fonts/linea/software/css/style.css', array(), '', 'all');} );
		add_action( 'wp_enqueue_scripts', function() {wp_enqueue_style('linea_weather', ITECK_URL . '/assets/fonts/linea/weather/css/style.css', array(), '', 'all');} );


		// //Styles
		// add_action( 'elementor/frontend/after_enqueue_styles', function() {  wp_enqueue_style('iteck-frontend',ITECK_URL .'assets/css/frontend.css', array(), null, 'all'  );} ); 
		
	}

	public function widget_scripts(){
		// custom-scripts
		wp_enqueue_script( 'iteck-parallax', ITECK_URL.'assets/js/iteck-parallax.js', [ 'jquery' ], self::VERSION, true );

        wp_enqueue_script( 'iteck-addons-custom-scripts', ITECK_URL.'assets/front/js/custom-scripts.js', [ 'jquery' ], self::VERSION, true );
	}
	public function init(){
		// Register Widget Scripts
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widget_scripts' ] );

	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * List of elements
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function widgets() {
		$widgets_path    = dirname( __FILE__ ) . '/widgets/';
		$iteck_widgets = array_diff(scandir($widgets_path), array('.', '..'));
		return $iteck_widgets;
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0 
	 *
	 * @access private
	 */
	private function includes() {
		foreach ( $this->widgets() as $widget_name ) {
			require_once( __DIR__ . '/widgets/'.$widget_name.'/'.$widget_name.'.php' );
		}
	}
	

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {
		// Register Widgets
		foreach ( $this->widgets() as $widget_name ) {
			$widget_name__ = str_replace( '-', '_', $widget_name );
				$class_name= str_replace( '_', ' ', $widget_name__ );
				$class_name	 =ucwords(strtolower($class_name));
				$class_name= str_replace( ' ', '_', $class_name );
				$class_name='IteckPlugin\Widgets\Iteck_'.$class_name;
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name());
		}
	}
}

new IteckPlugin();



