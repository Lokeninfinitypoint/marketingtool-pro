<?php
namespace IteckPlugin\Widgets;

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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function iteck_breadcrumb()
{
	$showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$delimiter = esc_html( '' ); // delimiter between crumbs
	$home = esc_html__( 'Home', 'iteck_plg' ); // text for the 'Home' link
	$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
	$the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
	

	global $post;
	$homeLink = esc_url( home_url() );
	if (is_home() || is_front_page()) {
		if ($showOnHome == 1) {
			echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a><span>' . $delimiter .'</span> '. $slug = $the_page->post_name .'</div>';
		}
	} else {
		echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a><span>' . $delimiter . '</span>';
		if (is_category()) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				echo get_category_parents($thisCat->parent, true, '<span>' . $delimiter . '</span>');
			}
			echo '<a class="active">'. 'Archive by category "' . single_cat_title('', false) . '"' . '</a>';
		} elseif (is_search()) {
			echo '<a class="active">' . 'Search results for "' . get_search_query() . '"' . '</a>';
		} elseif (is_day()) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a><span>' . $delimiter . '</span>';
			echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a><span>' . $delimiter . '</span>';
			echo '<a class="active">' . get_the_time('d') . '</a>';
		} elseif (is_month()) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a><span>' . $delimiter . '</span>';
			echo '<a class="active">' . get_the_time('F') . '</a>';
		} elseif (is_year()) {
			echo '<a class="active">' . get_the_time('Y') . '</a>';
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
				if ($showCurrent == 1) {
					echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
				}
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$cats = get_category_parents($cat, true, '<span>' . $delimiter . '</span>');
				if ($showCurrent == 0) {
					$cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
				}
				echo wp_kses_post($cats);
				if ($showCurrent == 1) {
					echo '<a class="active">' . get_the_title() . '</a>';
				}
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo '<a class="active">' . $post_type->labels->singular_name . '</a>';
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			$cat = $cat[0];
			echo get_category_parents($cat, true, '<span>' . $delimiter . '</span>');
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
			if ($showCurrent == 1) {
				echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
			}
		} elseif (is_page() && !$post->post_parent) {
			if ($showCurrent == 1) {
				echo '<a class="active">' . get_the_title() . '</a>';
			}
		} elseif (is_page() && $post->post_parent) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo wp_kses_post($breadcrumbs[$i]);
				if ($i != count($breadcrumbs)-1) {
					echo '<span>' . $delimiter . '</span>';
				}
			}
			if ($showCurrent == 1) {
				echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
			}
		} elseif (is_tag()) {
			echo '<a class="active">' . 'Posts tagged "' . single_tag_title('', false) . '"' . '</a>';
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo '<a class="active">' . 'Articles posted by ' . $userdata->display_name . '</a>';
		} elseif (is_404()) {
			echo '<a class="active">' . 'Page Not Found' . '</a>';
		}
		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ' (';
			}
			echo __('Page', 'iteck_plg' ) . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ')';
			}
		}
		echo '</div>';
	}
}
		
/**
 * @since 1.0.0
 */
class Iteck_Breadcrumbs extends Widget_Base {

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
		return 'iteck-breadcrumbs';
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
		return __( 'Iteck Breadcrumbs', 'iteck_plg' );
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
		return 'eicon-button';
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
		return [ 'iteck-category' ];
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
				'label' => __( 'Breadcrumbs Settings', 'iteck_plg' ),
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_align',
			[
				'label' => __( 'Button Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'iteck_plg' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'iteck_plg' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'iteck_plg'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .iteck-breadcrumbs' => 'text-align: {{VALUE}};',
				],
			]
		);
		

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'iteck_plg' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Price Plan Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'breadcrumbs_text_typography',
				'label' => esc_html__( 'Typography', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-breadcrumbs a, {{WRAPPER}} .iteck-breadcrumbs a',
			]
		);

		$this->add_control(
			'breadcrumbs_text_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-breadcrumbs span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iteck-breadcrumbs a' => 'color: {{VALUE}};',
				],
			]
        );

		$this->add_control(
			'breadcrumbs_link_color',
			[
				'label' => esc_html__( 'Link Color', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-breadcrumbs a.active' => 'color: {{VALUE}};',
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
		
        // Styles selections.
		
		?>

		<div class="iteck-breadcrumbs">
			<div class="path">
			<?php iteck_breadcrumb(); ?>
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


