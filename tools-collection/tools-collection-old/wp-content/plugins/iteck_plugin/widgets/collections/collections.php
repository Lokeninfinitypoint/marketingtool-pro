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

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class Iteck_Collections extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-collections';
    }

    //script depend
    public function get_script_depends()
    {
        return ['jquery-swiper','wow','iteck-addons-custom-scripts'];
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
    public function get_title()
    {
        return __('Iteck Collections', 'iteck_plg');
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
    public function get_icon()
    {
        return 'fa fa-clone';
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
    public function get_categories()
    {
        return ['iteck-elements'];
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
    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Collections Settings.', 'iteck_plg'),
            ]
        );

        $group_repeater = new \Elementor\Repeater();

        $group_repeater->add_control(
			'collection_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'ThemeCamp', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'items_count',
			[
				'label' => esc_html__( 'Items Count', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '100', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'items_count_text',
			[
				'label' => esc_html__( 'Items Count Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Items', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$group_repeater->add_control(
			'collection_images',
			[
				'label' => esc_html__( 'Collection Images', 'iteck_plg' ),
				'type' => Controls_Manager::GALLERY,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$group_repeater->add_control(
			'author_img',
			[
				'label' => esc_html__( 'Author Image', 'iteck_plg' ),
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
			'items_count_img',
			[
				'label' => esc_html__( 'Items Count Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

        $group_repeater->add_control(
            'link',
            [
                'label' => __( 'Project Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
			'collection_repeater',
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

        $this->start_controls_section(
			'title_styling',
			[
				'label' => __( 'Title Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-collections .top-info h6',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-collections .top-info h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'items_styling',
			[
				'label' => __( 'Items Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'items_count_typography',
				'label' => esc_html__( 'Items Count typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-collections .collection-card .top-info p .count',
			]
		);
		
		$this->add_control(
			'items_count_color',
			[
				'label' => __( 'Items Count color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-collections .collection-card .top-info p .count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'items_count_text_typography',
				'label' => esc_html__( 'Items Count Text typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-collections .collection-card .top-info p .count-text',
			]
		);
		
		$this->add_control(
			'items_count_text_color',
			[
				'label' => __( 'Items Count Text color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-collections .collection-card .top-info p .count-text' => 'color: {{VALUE}};',
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
    protected function render()
    {

        $settings = $this->get_settings();

?>

        <div class="iteck-collections">
            <div class="collections-slider">
                <div class="swiper-container overflow-visible">
                    <div class="swiper-wrapper">
                        <?php foreach ( $settings['collection_repeater'] as $index => $item ) : ?>
                            <div class="swiper-slide">
                                <div class="collection-card">
                                    <div class="top-info">
                                        <h6><?php echo wp_kses_post( $item['collection_title'] ) ?></h6>
                                        <p> <img src="<?php echo esc_url( $item['items_count_img']['url'] ) ?>" alt=""> <span class="count"> <?php echo wp_kses_post( $item['items_count'] ) ?> </span> <span class="count-text"> <?php echo wp_kses_post( $item['items_count_text'] ) ?> </span> </p>
                                    </div>
                                    <div class="auther-img">
                                        <img src="<?php echo esc_url( $item['author_img']['url'] ) ?>" alt="">
                                    </div>
                                    <div class="imgs">
                                        <?php $images_counter = 1; foreach($item['collection_images'] as $image): ?>
                                            <?php if($images_counter == 1): ?>
                                            <div class="main-img img-cover">
                                                <img src="<?php echo esc_url( $image['url'] ) ?>" alt="">
                                            </div>
                                            <?php elseif($images_counter == 2): ?>
                                                <div class="sub-imgs">
                                                    <img src="<?php echo esc_url( $image['url'] ) ?>" alt="">
                                            <?php else: ?>
                                                    <img src="<?php echo esc_url( $image['url'] ) ?>" alt="">
                                            <?php endif; ?>
                                        <?php $images_counter++; endforeach;
                                        if(number_format(count($item['collection_images'])) >= 2) echo '</div>'; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
    protected function content_template()
    {
    }
}
