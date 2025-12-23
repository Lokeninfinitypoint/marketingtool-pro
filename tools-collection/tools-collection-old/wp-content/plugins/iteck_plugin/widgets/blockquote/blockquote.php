<?php
namespace IteckPlugin\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Iteck_Blockquote extends Widget_Base {

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [ 'elementor-icons-fa-brands' ];
		}

		return [];
	}

	public function get_name() {
		return 'iteck-blockquote';
	}

	public function get_title() {
		return __( 'Iteck Blockquote', 'iteck_plg' );
	}

	public function get_icon() {
		return 'eicon-blockquote';
	}

	public function get_keywords() {
		return [ 'blockquote', 'quote', 'paragraph', 'testimonial', 'text', 'twitter', 'tweet' ];
	}
	public function get_categories()
    {
        return ['iteck-elements'];
    }

	protected function register_controls() {
		$this->start_controls_section(
			'section_blockquote_content',
			[
				'label' => __( 'Blockquote', 'iteck_plg' ),
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
			'blockquote_content',
			[
				'label' => __( 'Content', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg' ) . __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'iteck_plg' ),
				'placeholder' => __( 'Enter your quote', 'iteck_plg' ),
				'rows' => 10,
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => __( 'Author', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'John Doe', 'iteck_plg' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'tweet_button',
			[
				'label' => __( 'Tweet Button', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'iteck_plg' ),
				'label_off' => __( 'Off', 'iteck_plg' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'tweet_button_view',
			[
				'label' => __( 'View', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon-text' => 'Icon & Text',
					'icon' => 'Icon',
					'text' => 'Text',
				],
				'prefix_class' => 'elementor-blockquote--button-view-',
				'default' => 'icon-text',
				'render_type' => 'template',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'tweet_button_skin',
			[
				'label' => __( 'Skin', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'classic' => 'Classic',
					'bubble' => 'Bubble',
					'link' => 'Link',
				],
				'default' => 'classic',
				'prefix_class' => 'elementor-blockquote--button-skin-',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'tweet_button_label',
			[
				'label' => __( 'Label', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Tweet', 'iteck_plg' ),
				'condition' => [
					'tweet_button' => 'yes',
					'tweet_button_view!' => 'icon',
				],
			]
		);


		$this->add_control(
			'user_name',
			[
				'label' => __( 'Username', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '@username',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'tweet_date',
			[
				'label' => __( 'Tweet date', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '15 Dec, 2022',
				'default' => __( '15 Dec, 2022', 'iteck_plg' ),
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_page' => __( 'Current Page', 'iteck_plg' ),
					'none' => __( 'None', 'iteck_plg' ),
					'custom' => __( 'Custom', 'iteck_plg' ),
				],
				'default' => 'current_page',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'url',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __( 'https://your-link.com', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'url_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => __( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-blockquote__content',
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__content +footer' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_author_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Author', 'iteck_plg' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_text_color',
			[
				'label' => __( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__author' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .elementor-blockquote__author',
			]
		);

		$this->add_responsive_control(
			'author_gap',
			[
				'label' => __( 'Gap', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__author' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'alignment' => 'center',
					'tweet_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem' ],
			]
		);

		$this->add_control(
			'button_color_source',
			[
				'label' => __( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'official' => __( 'Official', 'iteck_plg' ),
					'custom' => __( 'Custom', 'iteck_plg' ),
				],
				'default' => 'official',
				'prefix_class' => 'elementor-blockquote--button-color-',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'iteck_plg' ),
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button' => 'background-color: {{VALUE}}',
					'body:not(.rtl) {{WRAPPER}} .elementor-blockquote__tweet-button:before, body {{WRAPPER}}.elementor-blockquote--align-left .elementor-blockquote__tweet-button:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',
					'body.rtl {{WRAPPER}} .elementor-blockquote__tweet-button:before, body {{WRAPPER}}.elementor-blockquote--align-right .elementor-blockquote__tweet-button:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				],
				'condition' => [
					'button_color_source' => 'custom',
					'tweet_button_skin!' => 'link',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'iteck_plg' ),
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' => __( 'Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button:hover' => 'background-color: {{VALUE}}',

					'body:not(.rtl) {{WRAPPER}} .elementor-blockquote__tweet-button:hover:before, body {{WRAPPER}}.elementor-blockquote--align-left .elementor-blockquote__tweet-button:hover:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',

					'body.rtl {{WRAPPER}} .elementor-blockquote__tweet-button:before, body {{WRAPPER}}.elementor-blockquote--align-right .elementor-blockquote__tweet-button:hover:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				],
				'condition' => [
					'button_color_source' => 'custom',
					'tweet_button_skin!' => 'link',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__tweet-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$default_fonts = '';

		if ( $default_fonts ) {
			$default_fonts = ', ' . $default_fonts;
		}

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .elementor-blockquote__tweet-button span, {{WRAPPER}} .elementor-blockquote__tweet-button i',
				'separator' => 'before',
				'fields_options' => [
					'font_family' => [
						'selectors' => [
							'{{WRAPPER}} .elementor-blockquote__tweet-button' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
						],
					],
				],
			]
		);

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$tweet_button_view = $settings['tweet_button_view'];
		$share_link = 'https://twitter.com/intent/tweet';

		$text = rawurlencode( $settings['blockquote_content'] );

		if ( ! empty( $settings['author_name'] ) ) {
			$text .= ' — ' . $settings['author_name'];
		}

		$share_link = add_query_arg( 'text', $text, $share_link );

		if ( 'current_page' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( home_url() . add_query_arg( false, false ) ), $share_link );
		} elseif ( 'custom' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( $settings['url'] ), $share_link );
		}

		if ( ! empty( $settings['user_name'] ) ) {
			$user_name = $settings['user_name'];
			if ( '@' === substr( $user_name, 0, 1 ) ) {
				$user_name = substr( $user_name, 1 );
			}
			$share_link = add_query_arg( 'via', rawurlencode( $user_name ), $share_link );
		}

		$this->add_render_attribute( [
			'blockquote_content' => [ 'class' => 'elementor-blockquote__content' ],
			'author_name' => [ 'class' => 'elementor-blockquote__author' ],
			'tweet_button_label' => [ 'class' => 'elementor-blockquote__tweet-label' ],
		] );

		$this->add_inline_editing_attributes( 'blockquote_content' );
		$this->add_inline_editing_attributes( 'author_name', 'none' );
		$this->add_inline_editing_attributes( 'tweet_button_label', 'none' );
		?>
		<blockquote class="iteck-blockquote">
			<?php if ( ! empty( $settings['author_name'] ) || 'yes' === $settings['tweet_button'] ) : ?>
				<div class="twitter-header">
					<div class="twitter-user">
						<div class="tweet-user-img">
							<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="img">
						</div>
						<div class="tweet-user-info">
							<?php if ( ! empty( $settings['author_name'] ) ) : ?>
								<cite <?php echo $this->get_render_attribute_string( 'author_name' ); ?>><?php echo $settings['author_name']; ?></cite>
								<small class="color-999">
									 <?php if ( ! empty( $settings['user_name'] ) ) {echo $settings['user_name'];} ?>
									 <?php if ( ! empty( $settings['tweet_date'] ) ) {echo $settings['tweet_date'];} ?>
									</small>
							<?php endif ?>
						</div>
					</div>
					<?php if ( 'yes' === $settings['tweet_button'] ) : ?>
						<div class="twitter-button">
							<a href="<?php echo esc_attr( $share_link ); ?>" class="elementor-blockquote__tweet-button" target="_blank">
								<?php if ( 'text' !== $tweet_button_view ) : ?>
									<?php
									$icon = [
										'value' => 'fab fa-twitter',
										'library' => 'fa-brands',
									];
									if ( ! Icons_Manager::is_migration_allowed() || ! Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ) ) : ?>
										<i class="fa fa-twitter" aria-hidden="true"></i>
									<?php endif; ?>
									<?php if ( 'icon-text' !== $tweet_button_view ) : ?>
										<span class="elementor-screen-only"><?php esc_html_e( 'Tweet', 'iteck_plg' ); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ( 'icon-text' === $tweet_button_view || 'text' === $tweet_button_view ) : ?>
									<span <?php echo $this->get_render_attribute_string( 'tweet_button_label' ); ?>><?php echo $settings['tweet_button_label']; ?></span>
								<?php endif; ?>
							</a>
						</div>
					<?php endif ?>
				</div>
			<?php endif ?>
			<div class="twitter-content">
				<p <?php echo $this->get_render_attribute_string( 'blockquote_content' ); ?>>
					<?php echo $settings['blockquote_content']; ?>
				</p>
			</div>
		</blockquote>
		<?php
	}

	/**
	 * Render Blockquote widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {

	}
}
