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
class Iteck_Markets extends Widget_Base {

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
		return 'iteck-markets';
	}

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
		return __( 'Iteck Markets', 'iteck_plg' );
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
				'label' => __( 'Items Settings', 'iteck_plg' ),
			]
		);

        

		$group_repeater = new \Elementor\Repeater();

        $group_repeater->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'BTC', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'name_per',
			[
				'label' => esc_html__( 'Name per', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'USDT', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'last_price',
			[
				'label' => esc_html__( 'Last Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '19,274.26', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'last_price_per',
			[
				'label' => esc_html__( 'Last Price per', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$19,274.26', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'change_percent',
			[
				'label' => esc_html__( 'Change Percent', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '+0.01%', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'change',
			[
				'label' => esc_html__( '24H Change', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$59,846,075.84', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'change_per',
			[
				'label' => esc_html__( '24H Change', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '3,105 BTC', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$group_repeater->add_control(
			'logo_img',
			[
				'label' => esc_html__( 'Logo Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$group_repeater->add_control(
			'chart_img',
			[
				'label' => esc_html__( 'Chart Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $group_repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Trade Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
            'link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
			'projects_repeater',
			[
				'label' => esc_html__( 'Projects', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $group_repeater->get_controls(),
				'default' => [
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
				],
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
		
        ?>
		<div class="iteck-markets-table">
            <div class="markets-table">
                <div class="table-head wow fadeInUp">
                    <div class="item">
                        <p> Instrument </p>
                    </div>
                    <div class="item">
                        <p> Last Price </p>
                    </div>
                    <div class="item">
                        <p> 24H Change </p>
                    </div>
                    <div class="item">
                        <p> 24H Change </p>
                    </div>
                </div>
                <div class="table-body">
                    <?php foreach($settings['projects_repeater'] as $index => $item): ?>
                        <div class="body-row wow fadeInUp">
                            <div class="item">
                                <div class="icon-40 me-3">
                                    <img src="<?php echo esc_url($item['logo_img']['url']) ?>" alt="">
                                </div>
                                <p class="text-white-50"> <strong class="text-white text-uppercase"><?php echo wp_kses_post($item['name']); ?></strong> /<?php echo wp_kses_post($item['name_per']); ?> </p>
                            </div>
                            <div class="item">
                                <p class="text-white-50"> <strong class="text-white me-1"> <?php echo wp_kses_post($item['last_price']); ?> </strong> <?php echo wp_kses_post($item['last_price_per']); ?> </p>
                            </div>
                            <div class="item">
                                <p class="per-up"><?php echo wp_kses_post($item['change_percent']); ?></p>
                            </div>
                            <div class="item">
                                <p class="text-white w-100"> <strong> <?php echo wp_kses_post($item['change']); ?>  </strong> </p>
                                <p class="text-white-50 w-100"> <strong> <?php echo wp_kses_post($item['change_per']); ?>  </strong> </p>
                            </div>
                            <div class="item">
                                <img src="<?php echo esc_url($item['chart_img']['url']) ?>" alt="" class="line_chart">
                            </div>
                            <div class="item">
                                <a href="<?php echo esc_url($item['link']['url']) ?>" <?php if ( $item['link']['is_external'] ) {echo'target="_blank"';} ?> class="trad-btn"> <?php echo wp_kses_post($item['button_text']); ?> </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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


