<?php
namespace IteckPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		 
/**
 * @since 1.0.0
 */
class Iteck_Info_Box extends Widget_Base {

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
		return 'iteck-infobox';
	}

	//script depend
	public function get_script_depends() { return ['iteck-infobox' ]; }

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
		return __( 'Iteck Info Box','iteck_plg' );
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
		return 'fa fa-sun-o';
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

		//--------------------------------------------------- Item Content ------------------------------------
		$this->start_controls_section(
			'section_item_content',
			[
				'label' => __( 'Item','iteck_plg' ),
			]
		);
		

		$this->add_control(
			'title',
			[
				'label' => __( 'Title','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'This is the heading', 'iteck_plg' ),
			]
		);
		
		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
			]
		);
 
		$this->add_control(
			'subtitle_position',
			[
				'label' => __( 'Subtitle Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'iteck_plg' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'iteck_plg' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'condition'	=> [
					'subtitle!'	=> ''
				]
			]
		);
		
		$this->add_control( 
			'text',
			[
				'label' => __( 'Text','iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => 'Insert your text..',
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'button_style',
			[
				'label' => __( 'Button Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
				],
				'default' => 'style-1',
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Button Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'wwww.link.com',
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this button',
			]
		);

		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'tag_text',
			[
				'label' => __( 'Tag Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Add Your text Here.', 'iteck_plg' ),
			]
		);
		
		$repeater->add_control(
			'tag_link',
			[
				'label' => __( 'Tag Link', 'iteck_plg' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'tags_list',
			[
				'label' => __( 'tags List', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'default' => [
					[
						'tag_text' => __( 'Management', 'iteck_plg' ),
						'tag_link' => __( '#0', 'iteck_plg' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ tag_text }}}',
			]
		);
 
		$this->add_control(
			'box_image_icon',
			[
				'label' => __( 'Media Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'iteck_plg' ),
						'icon' => 'fa fa-ban',
					],
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-smile-o',
					],
					'image' => [
						'title' => __( 'Image', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],

				],
				'default' => 'icon',
			]
		);
 
		$this->add_control(
			'image_icon_position',
			[
				'label' => __( 'Icon Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'iteck_plg' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'iteck_plg' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'bottom',
				'condition'	=> [
					'box_image_icon!'	=> 'none'
				]
			]
		);

		$this->add_control(
			'beside_position',
			[
				'label' => __( 'Beside Position', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'beside-content' => __( 'Beside Content', 'iteck_plg' ),
					'beside-title' => __( 'Beside Title', 'iteck_plg' ),
				],
				'default' => 'beside-content',
			]
		);

		$this->add_control(
			'iteck_info_icons',
			[
				'label' =>esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'iteck_btn_icon',
				'label_block' => true,
				'default' => [
                    'value' => '',
				],
				'condition'	=> [
					'box_image_icon'	=> 'icon'
				]
			]
		);

		$this->add_control(
            'image',
            [
                'label' => __( 'Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				'url' => Utils::get_placeholder_image_src(),
				],
				'condition'	=> [
					'box_image_icon'	=> 'image',
				]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_hover_elements',
			[
				'label' => __( 'Hover Elements Settings', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'hover_elements',
			[
				'label'         => __( 'Hover Elements', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);

        $this->add_control(
            'hover_elements_image',
            [
                'label' => __( 'Hover Elements Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'condition' => [
					'hover_elements' => 'yes',
				]
            ]
        );

		$this->add_control(
			'hover_circle_color',
			[
				'label' => esc_html__( 'Hover Circle Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .circle' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hover_elements' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_item_styling',
			[
				'label' => __( 'Item Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'iteck_plg' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .iteck-info-box .service_box h5' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_item_style');

		$this->start_controls_tab(
			'tab_item',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_icon_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background_hover',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box:hover',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_icon_styling',
			[
				'label' => __( 'Icon Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-info-box .service_box .icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-info-box .service_box .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object-fit',
			[
				'label' => esc_html__( 'Image Object Fit', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'iteck_plg' ),
					'fill' => esc_html__( 'Fill', 'iteck_plg' ),
					'cover' => esc_html__( 'Cover', 'iteck_plg' ),
					'contain' => esc_html__( 'Contain', 'iteck_plg' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .icon img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//====================================================== Title Style =======================================


		$this->start_controls_section(
			'infobox_title_styling',
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
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box h5 a',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box h5 a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Title color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box:hover h5 a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box h5' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_subtitle_styling',
			[
				'label' => __( 'Sub-Title Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'label' => esc_html__( 'Title typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box .num',
			]
		);
		
		$this->add_control(
			'subtitle_color',
			[
				'label' => __( 'Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .num' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .num' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_additional_title_styling',
			[
				'label' => __( 'Additional Title Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_title_typography',
				'label' => esc_html__( 'Additional Title typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box h5 a span, {{WRAPPER}} .iteck-info-box .service_box h5 a b',
			]
		);
		
		$this->add_control(
			'additional_title_color',
			[
				'label' => __( 'Additional Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box h5 a span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-info-box .service_box h5 a b' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		//====================================================== Text Style =======================================


		$this->start_controls_section(
			'infobox_text_styling',
			[
				'label' => __( 'Text Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Text typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box .info .text',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .info .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .info .text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		//====================================================== Button Style =======================================


		$this->start_controls_section(
			'infobox_btn_styling',
			[
				'label' => __( 'Button Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Button typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-info-box .service_box .read-more-btn',
			]
		);
		
		$this->add_control(
			'btn_color',
			[
				'label' => __( 'Button color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box .service_box .read-more-btn' => 'color: {{VALUE}};',
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
		$settings = $this->get_settings_for_display();

		?>
		<div class="iteck-info-box">
			<div class="service_box">
				<?php if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] != 'bottom'):
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'left' && $settings['beside_position'] == 'beside-content'): ?> <div class="row"><div class="col-md-3"> <?php endif;
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'left' && $settings['beside_position'] == 'beside-title'): ?> <div class="title-box"> <?php endif; ?>
				<div class="icon">
					<?php if($settings['box_image_icon'] == 'image'): ?>
						<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
					<?php elseif($settings['box_image_icon'] == 'icon'): ?>
						<i class="<?php echo esc_attr($settings['iteck_info_icons']['value']); ?>"></i>
					<?php endif; ?>
				</div>
				<?php endif;
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'left' && $settings['beside_position'] == 'beside-content'): ?> </div><div class="col-md-9"> <?php endif; ?>
				<h5 <?php if(isset($settings['align']) && $settings['align'] == 'right' && $settings['subtitle_position'] == 'top') echo 'class="flex-row-reverse justify-content-between"';  ?>>
					<a href="<?php echo esc_url($settings['link']['url']); ?>" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($settings['title']); ?></a> 
					<?php if($settings['subtitle_position'] == 'top'): ?>
						<span class="num"><?php echo wp_kses_post($settings['subtitle']); ?></span>
					<?php endif; ?>
				</h5>
				<?php 
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'left' && $settings['beside_position'] == 'beside-title'): ?> </div> <?php endif;
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'bottom'): ?>
				<div class="icon">
					<?php if($settings['box_image_icon'] == 'image'): ?>
						<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
					<?php elseif($settings['box_image_icon'] == 'icon'): ?>
						<i class="<?php echo esc_attr($settings['iteck_info_icons']['value']); ?>"></i>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<div class="info">
					<div class="text"><?php echo wp_kses_post($settings['text']); ?></div>
					<?php if($settings['subtitle_position'] == 'bottom'): ?>
						<span class="num"><?php echo wp_kses_post($settings['subtitle']); ?></span>
					<?php endif; ?>
					<div class="tags">
						<?php foreach ( $settings['tags_list'] as $index => $item ) : ?>
							<a href="<?php echo esc_url($item['tag_link']['url']); ?>" <?php if ( $item['tag_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text']); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
				<?php if(!empty($settings['button_text'])): ?>
					<a href="<?php echo esc_url($settings['link']['url']); ?>" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?> class="read-more-btn-<?php echo esc_attr($settings['button_style']) ?> mt-30">
						<?php if($settings['button_style'] == 'style-1'): echo '<i class="bi bi-arrow-right l-arrow me-2"></i>'; endif;
						echo wp_kses_post($settings['button_text']);
						if($settings['button_style'] == 'style-1'): echo '<i class="bi bi-arrow-right l-arrow ms-2"></i>'; endif; ?>
					</a>
				<?php endif;
				if($settings['box_image_icon'] != 'none' && $settings['image_icon_position'] == 'left' && $settings['beside_position'] == 'beside-content'): ?> </div></div> <?php endif; ?>
			</div>
			<?php if($settings['hover_elements'] == 'yes'): ?>
				<span class="circle"></span>
				<?php if(!empty($settings['hover_elements_image']['url'])): ?>
					<img src="<?php echo esc_url($settings['hover_elements_image']['url']) ?>" class="pattern">
				<?php endif; ?>
			<?php endif; ?>
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


