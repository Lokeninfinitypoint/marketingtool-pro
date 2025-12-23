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

class Iteck_testimonial extends Widget_Base
{

	public function get_name()
	{
		return 'iteck-testimonial';
	}

	public function get_title()
	{
		return __('Iteck Testimonial', 'iteck_plg');
	}

	public function get_icon()
	{
		return 'eicon-testimonial';
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
			'section_testimonial',
			[
				'label' => esc_html__('Testimonial', 'iteck_plg'),
			]
		);

		$this->add_control(
			'float_style',
			[
				'label' => __('Float Style', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'info_position',
			[
				'label' => __('Info Positions', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__('Top', 'iteck_plg'),
					'bottom' => esc_html__('Bottom', 'iteck_plg'),
				],
				'default' => 'top',
				'condition' => [
					'float_style!' => 'yes',
				]
			]
		);

		$this->add_control(
			'card_style',
			[
				'label' => __('Card Style', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
				'condition' => [
					'float_style!' => 'yes',
					'info_position' => 'bottom'
				]
			]
		);

		$this->add_control(
			'name',
			[
				'label' => __('Name', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Lorem leo', 'iteck_plg'),
			]
		);

		$this->add_control(
			'position',
			[
				'label' => __('Position', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('CEO', 'iteck_plg'),
			]
		);

		$this->add_control(
			'review',
			[
				'label' => esc_html__('Reveiw', 'iteck_plg'),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __('Image', 'iteck_plg'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'rate',
			[
				'label' => esc_html__('Rate', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
				'default' => '5',
			]
		);

		$this->add_control(
			'rate_style',
			[
				'label' => __('Rate Style', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'stars' => esc_html__('Stars', 'iteck_plg'),
					'custom' => esc_html__('Custom', 'iteck_plg'),
				],
				'default' => 'stars',
			]
		);

		$this->add_control(
			'rate_icon',
			[
				'label' => __('Rate Icon', 'iteck_plg'),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'rate_style' => 'custom',
				]
			]
		);

		$this->add_control(
			'qoute_image',
			[
				'label' => __('Qoute Image', 'iteck_plg'),
				'type' => Controls_Manager::MEDIA,
				'conditions' => [
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'card_style',
							'operator' => '==',
							'value'   => 'yes',
						),
						array(
							'name'     => 'float_style',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__('Item style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__('Border', 'iteck_plg'),
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .iteck-testimonial .client_card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__('Image style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__('Image Size', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card .user_img img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card .user_img img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__('Name style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__('Name Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card .inf_content .name' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-testimonial .client_card .user_img h6' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card .inf_content .name, {{WRAPPER}} .iteck-testimonial .client_card .user_img h6',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_position_style',
			[
				'label' => esc_html__('Position style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => esc_html__('Position Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card .inf_content .name .text-muted' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-testimonial .client_card .user_img p' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card .inf_content .name .text-muted, {{WRAPPER}} .iteck-testimonial .client_card .user_img p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_review_style',
			[
				'label' => esc_html__('Review style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'review_color',
			[
				'label' => esc_html__('Review Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card .inf_content .review-text' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'review_typography',
				'selector' => '{{WRAPPER}} .iteck-testimonial .client_card .inf_content .review-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_responsive_control(
			'review_min_height',
			[
				'label' => esc_html__('Min Height', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonial .client_card .inf_content .review-text' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

?>

		<div class="iteck-testimonial <?php if ($settings['float_style'] == 'yes') echo 'float-style';
										else echo 'card-style-' . $settings['info_position'] . '';
										if ($settings['card_style'] == 'yes') echo ' card-style'; ?>">
			<div class="client_card">
				<?php if ($settings['float_style'] == 'yes' || $settings['info_position'] == 'bottom') : ?>
					<div class="inf_content">
						<?php if ($settings['card_style'] == 'yes') : ?>
							<span class="card-tag"> Product Quality </span>
							<p class="review-text"><?php echo wp_kses_post($settings['review']); ?></p>
						<?php endif; ?>
						<div class="stars">
							<?php for ($i = 0; $i < $settings['rate']; $i++) :
								if ($settings['rate_style'] == 'stars') : ?>
									<i class="fa fa-star"></i>
								<?php else : ?>
									<img class="rate-icon" src="<?php echo esc_url($settings['rate_icon']['url']) ?>" alt="rate-icon">
							<?php endif;
							endfor; ?>
						</div>
						<?php if ($settings['card_style'] != 'yes') : ?>
							<p class="review-text"><?php echo wp_kses_post($settings['review']); ?></p>
						<?php endif; ?>
						<?php if (!empty($settings['qoute_image']['url'])) : ?>
							<img src="<?php echo esc_url($settings['qoute_image']['url']); ?>" alt="" class="icon">
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="user_img">
					<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
					<?php if ($settings['float_style'] == 'yes' || $settings['info_position'] == 'bottom') : ?>
						<div class="inf">
							<p><?php echo wp_kses_post($settings['position']); ?></p>
							<h6><?php echo wp_kses_post($settings['name']); ?></h6>
						</div>
					<?php endif; ?>
				</div>
				<?php if ($settings['float_style'] != 'yes' && $settings['info_position'] == 'top') : ?>
					<div class="inf_content">
						<div class="rate_stars">
							<?php for ($i = 0; $i < $settings['rate']; $i++) :
								if ($settings['rate_style'] == 'stars') : ?>
									<i class="fa fa-star"></i>
								<?php else : ?>
									<img class="rate-icon" src="<?php echo esc_url($settings['rate_icon']['url']) ?>" alt="rate-icon">
							<?php endif;
							endfor; ?>
						</div>
						<h6 class="review-text"><?php echo wp_kses_post($settings['review']); ?></h6>
						<p class="name"><?php echo wp_kses_post($settings['name']); ?> <span class="text-muted">/ <?php echo wp_kses_post($settings['position']); ?></span></p>
					</div>
				<?php endif; ?>
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
