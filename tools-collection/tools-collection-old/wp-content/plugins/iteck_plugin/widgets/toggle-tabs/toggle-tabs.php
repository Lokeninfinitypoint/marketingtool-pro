<?php

namespace IteckPlugin\Widgets;


use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;


if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Elementor toggle widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class Iteck_Toggle_Tabs extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve toggle widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-toggle-tabs';
    }

    //script depend
    public function get_script_depends()
    {
        return ['iteck-bootstrap-bundle', 'iteck-toggle-tabs'];
    }


    /**
     * Get widget title.
     *
     * Retrieve toggle widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Iteck Toggle Tabs', 'iteck_plg');
    }

    /**
     * Get widget icon.
     *
     * Retrieve toggle widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-toggle';
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
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['tabs', 'accordion', 'toggle'];
    }

    /**
     * Register toggle widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_toggle',
            [
                'label' => esc_html__('Toggle', 'iteck_plg'),
            ]
        );

        $this->add_control(
            'cat_section_title',
            [
                'label' => esc_html__('Category Section Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Question Category:', 'iteck_plg'),
                'label_block' => true,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'type',
            [
                'label' => __('Type', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'category' => __('New Category', 'iteck_plg'),
                    'item' => __('Item', 'iteck_plg'),
                ],
                'default' => 'item'
            ]
        );

        $repeater->add_control(
            'cat_title',
            [
                'label' => esc_html__('Category Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Category Title', 'iteck_plg'),
                'label_block' => true,
                'condition' => [
                    'type' => 'category'
                ]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Toggle Title', 'iteck_plg'),
                'label_block' => true,
                'condition' => [
                    'type' => 'item'
                ]
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'iteck_plg'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Toggle Content', 'iteck_plg'),
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'type' => 'item'
                ]
            ]
        );

        $this->add_control(
            'tabs_repeater',
            [
                'label' => esc_html__('Toggle Items', 'iteck_plg'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Toggle #1', 'iteck_plg'),
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg'),
                    ],
                    [
                        'title' => esc_html__('Toggle #2', 'iteck_plg'),
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg'),
                    ],
                ],
                'title_field' => '{{{ type }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style',
            [
                'label' => esc_html__('Toggle', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'iteck_plg'),
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-item',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__('Space Between', 'iteck_plg'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_title',
            [
                'label' => esc_html__('Title', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_background',
            [
                'label' => esc_html__('Background', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button.collapsed' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_background_active',
            [
                'label' => esc_html__('Background Active', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // The title selector specificity is to override Theme Style
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button.collapsed' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button.collapsed:after' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => esc_html__('Active Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__('Border', 'iteck_plg'),
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button',
            ]
        );

        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => __('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_icon',
            [
                'label' => esc_html__('Icon', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button.collapsed:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_active_color',
            [
                'label' => esc_html__('Active Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-item .accordion-button:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_content',
            [
                'label' => esc_html__('Content', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_background_color',
            [
                'label' => esc_html__('Background', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-body' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-body',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => esc_html__('Border', 'iteck_plg'),
                'selector' => '{{WRAPPER}} .iteck-toggle-tabs .accordion-body',
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => __('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-toggle-tabs .accordion-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render toggle widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $myuid = uniqid();

?>
        <div class="iteck-toggle-tabs" id="iteck-toggle-tabs-<?php echo $myuid; ?>">

            <div class="row gx-5">
                <div class="col-lg-4">
                    <div class="faq-category">
                        <h5><?php echo wp_kses_post($settings['cat_section_title']); ?></h5>
                        <ul>
                            <?php $first = true; foreach ( $settings['tabs_repeater'] as $index => $item ) : if($item['type'] == 'category'): ?>
                                <li>
                                    <a href="#cat-<?php echo $item['_id']; ?>" class="<?php if($first) echo 'active'; ?>"> <?php echo wp_kses_post($item['cat_title']); ?> </a>
                                    <span></span>
                                </li>
                            <?php endif; $first = false; endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="faq-questions pt-lg-0">
                        <?php $counter = 1; $first = true; foreach ( $settings['tabs_repeater'] as $index => $item ) :
                            if($item['type'] == 'category'): ?>
                                <h5 id="cat-<?php echo $item['_id']; ?>" class="sec-title <?php if(!$first) echo 'pt-80'; echo ' group-'.wp_kses_post($counter); ?>"> <span> <?php if($counter < 10) echo '0'; echo wp_kses_post($counter); ?>. </span> <?php echo wp_kses_post($item['cat_title']); ?> </h5>
                            <?php $counter++; else: ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?php if(!$first) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#item-<?php echo $item['_id']; ?>" aria-expanded="<?php if(!$first) echo 'false'; else echo 'true'; ?>" aria-controls="collapse-<?php echo $item['_id']; ?>">
                                            <?php echo wp_kses_post($item['title']); ?>
                                        </button>
                                    </h2>
                                    <div id="item-<?php echo $item['_id']; ?>" class="accordion-collapse collapse <?php if($first) echo 'show'; ?>" aria-labelledby="heading-<?php echo $item['_id']; ?>" data-bs-parent="#item-<?php echo $item['_id']; ?>">
                                        <div class="accordion-body">
                                            <div class="text">
                                            <?php echo wp_kses_post($item['content']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;
                        if($first && $item['type'] == 'category') $first = true; else $first = false; endforeach; ?>
                    </div>
                </div>
            </div>

        </div>


<?php
    }

    /**
     * Render toggle widget output in the editor.
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
