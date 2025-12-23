<?php

namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Utils;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Iteck_team extends Widget_Base
{

    public function get_name()
    {
        return 'iteck-team';
    }

    public function get_title()
    {
        return __('Iteck Team', 'iteck_plg');
    }

    public function get_icon()
    {
        return 'eicon-user-circle-o';
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

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_Team',
            [
                'label' => esc_html__('Team', 'iteck_plg'),
            ]
        );

		$this->add_control(
			'hover_view',
			[
				'label' => esc_html__('Social Icons Hover View', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return' => 'yes',
			]
		);

		$this->add_control(
			'bg_style',
			[
				'label' => esc_html__('Background Style', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return' => 'yes',
				'condition' => [
					'hover_view' => 'yes'
				]
			]
		);

		$this->add_control(
			'bg_styles',
			[
				'label' => esc_html__('Style', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => 'Style 1',
					'2' => 'Style 2',
				],
				'default' => '1',
				'condition' => [
					'hover_view' => 'yes',
					'bg_style' => 'yes'
				]
			]
		);

		$this->add_control(
			'bg_style_color',
			[
				'label' => esc_html__( 'Background Style', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team .bg-style .avatar .bg_color' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hover_view' => 'yes',
					'bg_style' => 'yes'
				]
			]
		);

		$this->add_control(
			'name',
			[
				'label' => __( 'Name','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Lorem leo', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'position',
			[
				'label' => __( 'Position','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'CEO', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'item_url',
			[
				'label' => __( 'Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'example.com', 'iteck_plg' ),
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
            ]
        );
        
        $this->add_control(
			'social_links_list',
			[
				'label' => __( 'Social Links List', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tag_link' => __( '#0', 'iteck_plg' ),
					],
				],
                'prevent_empty' => false,
				'fields' => [
					
                    [
                        'name' => 'social_icon',
                        'label' =>esc_html__( 'Icon', 'iteck_plg' ),
                        'type' => Controls_Manager::ICONS,
                        'fa4compatibility' => 'iteck_btn_icon',
                        'label_block' => true,
                        'default' => [
                            'value' => 'fab fa-facebook',
                            'library' => 'brands',
                        ],
                    ],
					[
						'name' => 'social_link',
						'label' => __( 'Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
					],
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'name_style',
			[
				'label' => esc_html__('Name', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-team .info h6',
			]
		);

        $this->start_controls_tabs('tabs_name_style');

		$this->start_controls_tab(
			'tab_name_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Name Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team .info h6' => 'color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_name_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'name_color_hover',
			[
				'label' => esc_html__( 'Name Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team:hover .info h6' => 'color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
			'position_style',
			[
				'label' => esc_html__('Position', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => esc_html__( 'Position Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team .info small' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-team .info small',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'social_style',
			[
				'label' => esc_html__('Social Icons', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs('tabs_social_icon_style');

		$this->start_controls_tab(
			'tab_social_icon_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team .team_box .info .social_icons a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => __('Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-team .team_box .info .social_icons a',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
			'tab_social_icon_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-team .team_box .info .social_icons a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background_hover',
				'label' => __('Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-team .team_box .info .social_icons a:hover',
                'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{VALUE}};',
						],
					],
                ],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

?>

        <div class="iteck-team">
            <div class="<?php if($settings['hover_view'] == 'yes'): if($settings['bg_style'] == 'yes'): echo 'bg-style-'. $settings['bg_styles'] .''; else: echo 'team_box_hover'; endif; else: echo 'team_box'; endif; ?>">
                <div class="avatar">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
					<?php if($settings['hover_view'] == 'yes' && $settings['bg_style'] == 'yes' && $settings['bg_styles'] == '1'): ?>
						<span class="bg_color"></span>
					<?php endif;
					if(($settings['hover_view'] == 'yes' && $settings['bg_style'] != 'yes') || ($settings['hover_view'] == 'yes' && $settings['bg_style'] == 'yes' && $settings['bg_styles'] == '1')): ?>
                    <div class="social_icons">
						<?php if($settings['bg_style'] == 'yes'): ?>
							<span class="icon"> </span>
                        <?php endif; 
						foreach($settings['social_links_list'] as $index => $item ): ?>
                        <a href="<?php echo esc_url($item['social_link']['url']); ?>" <?php if ( $item['social_link']['is_external'] ) {echo'target="_blank"';} ?>>
                            <i class="<?php echo esc_attr($item['social_icon']['value']); ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
					<?php endif; ?>
                </div>
                <div class="info">
					<?php if($settings['hover_view'] == 'yes' && $settings['bg_style'] == 'yes' && $settings['bg_styles'] == '2'): ?>
						<div class="social_icons">
							<?php foreach($settings['social_links_list'] as $index => $item ): ?>
							<a href="<?php echo esc_url($item['social_link']['url']); ?>">
								<i class="<?php echo esc_attr($item['social_icon']['value']); ?>"></i>
							</a>
							<?php endforeach; ?>
						</div>
						<div class="inf">
					<?php endif; ?>
					<?php if($settings['bg_style'] == 'yes'): ?><small><?php echo wp_kses_post($settings['position']); ?></small><?php endif; ?>
					<?php if(!empty($settings['item_url']['url'])): ?><a href="<?php echo esc_url($settings['item_url']['url']); ?>"><?php endif; ?>
                    <h6><?php echo wp_kses_post($settings['name']); ?></h6>
					<?php if(!empty($settings['item_url']['url'])): ?></a><?php endif; ?>
					<?php if($settings['bg_style'] != 'yes'): ?><small><?php echo wp_kses_post($settings['position']); ?></small><?php endif; ?>
					<?php if($settings['hover_view'] == 'yes' && $settings['bg_style'] == 'yes' && $settings['bg_styles'] == '2'): ?></div><?php endif; ?>
					<?php if($settings['hover_view'] != 'yes'): ?>
                    <div class="social_icons">
                        <?php foreach($settings['social_links_list'] as $index => $item ): ?>
                        <a href="<?php echo esc_url($item['social_link']['url']); ?>">
                            <i class="<?php echo esc_attr($item['social_icon']['value']); ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
					<?php endif; ?>
                </div>
            </div>
        </div>

<?php

    }

    /**
     * Render Animated Headline widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template()
    {
    }
}
