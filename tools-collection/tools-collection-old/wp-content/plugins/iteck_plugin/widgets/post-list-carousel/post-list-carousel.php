<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Post_List_Carousel extends Widget_Base { 

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-post-list-carousel';
	}
	
	//script depend
	public function get_script_depends() { return [ 'jquery-swiper','wow','iteck-addons-custom-scripts','iteck-post-list-carousel']; }
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Iteck Post List Carousel', 'iteck_plg' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'iteck-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Portfolio Settings.', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'blog_post',
			[
				'label' => __('Blog Post to show', 'iteck_plg'),
				'type' => Controls_Manager::NUMBER,
				'default' => '6',

			]
		);

		$this->add_control(
			'sort_cat',
			[
				'label' => __('Sort post by Category', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'blog_cat',
			[
				'label'   => __('Category', 'iteck_plg'),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_category_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
			]
		);

		$this->add_control(
			'paged_on',
			[
				'label' => __('Always show the same list on every page(not paged).', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'label_block' => true,
				'default' => '',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __('Show Exerpt', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label' => __('Blog Excerpt Length', 'iteck_plg'),
				'type' => Controls_Manager::NUMBER,
				'default' => '150',
				'min' => 10,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_after',
			[
				'label' => __('After Excerpt text/symbol', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'show_excerpt' => 'yes',
				],
				'default' => '...',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __('Show Featured Image', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'date',
			[
				'label' => __('Show date', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'author',
			[
				'label' => __('Show Author', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ($settings['paged_on']  != 'yes') {
			$iteck_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		} else {
			$iteck_paged = '';
		}
		if ($settings['sort_cat']  == 'yes') {
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $iteck_paged,
				'post_type' => 'post',
				'cat' => $settings['blog_cat']

			));
		} else {
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $iteck_paged,
				'post_type' => 'post'
			));
		}

		?>

		<div class="iteck-post-list-carousel">
            <div class="blog_slider">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="swiper-slide">
                                <div class="blog_box">
                                    <div class="tags">
                                        <?php the_category(' - '); ?>
                                    </div>
                                    <div class="img">
                                        <img src="<?php esc_url(the_post_thumbnail_url()); ?>" alt="">
                                    </div>
                                    <div class="info">
                                        <h6><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h6>
                                        <div class="auther">
                                            <?php if($settings['author'] == 'yes'): ?>
                                                <span>
                                                    <img class="auther_img" src="<?php echo get_avatar_url( get_the_author_meta( 'ID' ) ); ?>" alt="">
                                                    <small>By <?php the_author_posts_link(); ?></small>
                                                </span>
                                            <?php endif; ?>
                                            <span>
                                                <i class="bi bi-calendar2"></i>
                                                <small><?php echo get_the_date(__('M j, Y')); ?></small>
                                            </span>
                                        </div>
                                        <div class="text">
                                            <?php $excerpt = get_the_excerpt();
                                            $excerpt = substr($excerpt, 0, $settings['excerpt']);
                                            echo $excerpt;
                                            echo esc_attr($settings['excerpt_after']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata();  ?>
                    </div>
                </div>
                <!-- ====== slider navigation ====== -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
		</div>

	<?php
		
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {

	}
}



