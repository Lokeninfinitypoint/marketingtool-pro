<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Gallery extends Widget_Base {

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
		return 'iteck-gallery';
	}
		//script depend
	public function get_script_depends() { return [ 'iteck-animation','jquery-swiper','iteck-swiper-slider-script' ]; }

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
		return __( 'Iteck Gallery', 'iteck_plg' );
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
		return 'eicon-blockquote';
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
				'label' => __( 'Gallery Settings', 'iteck_plg' ),
			]
		);


		$this->add_control(
			'gallery_col',
			[
				'label' => __( 'Columns number', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '4',
			]
		);
	
		$this->add_control(
			'gallery_list',
			[
				'label' => __( 'Gallery List', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => 'Main Title',
						'subtitle' => 'Sub Title',
					],
					[
						'title' => 'Main Title',
						'subtitle' => 'Sub Title',
					],

				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Title', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Main Title..', 'iteck_plg' ),
					],
					[
						'name' => 'subtitle',
						'label' => __( 'Sub Title', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Sub Title..', 'iteck_plg' ),
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Add your link here..', 'iteck_plg' ),
					],
					[
						'name' => 'image',
						'label' => __( 'Image', 'iteck_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'promotion',
						'label' => __( 'Tabs Style', 'iteck_plg' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'no',
						'label_on' => __('Show', 'iteck_plg'),
						'label_off' => __('Hide', 'iteck_plg'),
						'return_value' => 'yes',
					],
					[
						'name' => 'promotion_text',
						'label' => __( 'Promotion Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'NEW', 'iteck_plg' ),
						'condition' => [
							'promotion' => 'yes'
						]
					],
                    [
                        'name' => 'promotion_color',
                        'label' => esc_html__( 'Promotion Color', 'iteck_plg' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} {{CURRENT_ITEM}} .new_demo_label' => 'background: {{VALUE}};',
							'{{WRAPPER}} {{CURRENT_ITEM}}.item_ribbon::before' => 'background: {{VALUE}};',
							'{{WRAPPER}} {{CURRENT_ITEM}}.item_ribbon::after' => 'background: {{VALUE}};',
                        ],
						'placeholder' => __( 'NEW', 'iteck_plg' ),
						'condition' => [
							'promotion' => 'yes'
						]
                    ],

				],
				'title_field' => '{{ title }}',
			]
		);
		$this->add_control(
			'nav_prev',
			[
				'label' => __( 'Previous','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Prev Slide', 'iteck_plg' ),
				'condition' => [
					'gallery_style' => array('1','2','3','4')
				],
			]
		);
		$this->add_control(
			'nav_next',
			[
				'label' => __( 'Next','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Next Slide', 'iteck_plg' ),
				'condition' => [
					'gallery_style' => array('1','2','3','4')
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image style', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'img_height',
			[
				'label' => __( 'Image height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-gallery.style-1 .item .img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'img_scroll',
			[
				'label' => __('Image Scroll Effect', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
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
		$Count = 0;
		$col=$settings['gallery_col'];
	?>
		<div class="iteck-gallery style-1">
			<div class="container-fluid">
				<?php foreach ( $settings['gallery_list'] as $index => $item ) : ?>
					<?php if($Count % $col==0){echo '<div class="row">';} ?> 
						<div class="col-md">
							<div class="item elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> <?php if($item['promotion'] == 'yes') echo esc_attr('item_ribbon'); if($settings['img_scroll'] == 'yes') echo esc_attr(' img-scroll'); ?>">
								<div class="img">
									<img src="<?php echo esc_url ( $item['image']['url']); ?>"> 
								</div>
								<div class="cont">
									<div class="title"><?php echo  $item['title']; ?></div>
									<div class="subtitle"><?php echo  $item['subtitle']; ?></div>
								</div>
								<a class="link" href="<?php echo  $item['link']['url']; ?>" <?php if ( $item['link']['is_external'] ) {echo'target="_blank"';} ?>>
								</a>
								<?php if($item['promotion'] == 'yes'): ?>
									<div class="new_demo_label">
										<span><?php echo $item['promotion_text']; ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php $Count++; if($Count% $col==0){echo '</div>';} ?>
				<?php  endforeach;
				if($Count% $col!=0) {echo '</div>';} // put closing div if loop is not exactly a multiple of 3 
				?>
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


