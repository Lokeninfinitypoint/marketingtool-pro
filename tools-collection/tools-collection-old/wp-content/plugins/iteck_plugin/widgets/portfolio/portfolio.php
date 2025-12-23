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
class Iteck_Portfolio extends Widget_Base { 

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
		return 'iteck-portfolio';
	}
	
	//script depend
	public function get_script_depends() { return [ 'jquery-isotope','wow','iteck-mixitup','iteck-portfolio']; }
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
		return __( 'Iteck Portfolio', 'iteck_plg' );
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
			'portfolio_item',
			[
				'label' => __( 'Item to display', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '8',
			]
		);
		
		$this->add_control(
			'content_on_hover',
			[
				'label' => __( 'Show Content on Hover', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'sort_cat',
			[
				'label' => __( 'Sort Portfolio by Portfolio Category', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'blog_cat',
			[
				'label'   => __( 'Category to Show', 'iteck_plg' ),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_tax_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
			]
		);
		
		$this->add_control(
			'port_order',
			[
				'label' => __( 'Orders', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DESC' => __( 'Descending', 'iteck_plg' ),
					'ASC' => __( 'Ascending', 'iteck_plg' ),
					'rand' => __( 'Random', 'iteck_plg' ),
				],
				'default' => 'DESC',
			]
		);

		$this->add_control(
			'show_tags',
			[
				'label' => __( 'Show Tags', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_filters',
			[
				'label' => __( 'Show Filters', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);
        
        $this->add_control(
			'columns_number',
			[
				'label' => __( 'Columns number', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'12' => __( '1 Column', 'iteck_plg' ),
					'6' => __( '2 Columns', 'iteck_plg' ),
					'4' => __( '3 Columns', 'iteck_plg' ),
					'3' => __( '4 Columns', 'iteck_plg' ),
				],
				'default' => '6',
			]
		);
        
        $this->add_control(
			'info_style',
			[
				'label' => __( 'Info Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'box' => __( 'Hover Box', 'iteck_plg' ),
					'overlay' => __( 'Hover Overlay', 'iteck_plg' ),
				],
				'default' => 'box',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_title_styling',
			[
				'label' => __( 'Portfolio Title', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_on_hover!' => 'yes'
				]
			]
		);

		$this->add_control(
			'item_title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card .title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card:hover .title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio .portfolio-card:hover .title a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_category_styling',
			[
				'label' => __( 'Portfolio Category', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_on_hover!' => 'yes'
				]
			]
		);

		$this->add_control(
			'item_category_color',
			[
				'label' => esc_html__( 'Category Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card .info .category a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_category_color_hover',
			[
				'label' => esc_html__( 'Category Color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card:hover .info .category a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_category_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio .portfolio-card .info .category a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_excerpt_styling',
			[
				'label' => __( 'Portfolio Excerpt', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_on_hover!' => 'yes'
				]
			]
		);

		$this->add_control(
			'item_excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card .info .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_excerpt_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio .portfolio-card .info .text',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_tags',
			[
				'label' => esc_html__('Tags', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_on_hover!' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'tags_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-portfolio .portfolio-card .info .tags a',
			]
		);

        $this->add_control(
			'tags_color',
			[
				'label' => __('Tags Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio .portfolio-card .info .tags a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'tags_bg',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-portfolio .portfolio-card .info .tags a',
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

		$iteck_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($settings['port_order'] != 'rand') {
			$order = 'order';
			$ord_val = $settings['port_order'];
		} else {
			$order = 'orderby';
			$ord_val = 'rand';
		}
		
		if ( $settings['sort_cat']  == 'yes' ) {
			$destudio_work = new \WP_Query(array(
				'posts_per_page'   => $settings['portfolio_item'],
				'post_type' =>  'portfolio', 'iteck_plg',
				$order       =>  $ord_val,
				'tax_query' => array(
					array(
						'taxonomy' => 'portfolio_category',   // taxonomy name
						'field' => 'term_id',
						'terms' => $settings['blog_cat'],           // term_id, slug or name                // term id, term slug or term name
					)
				)
			)); 
		} else {
			$destudio_work = new \WP_Query(array(
				'paged' => $iteck_paged,
				'posts_per_page'   => $settings['portfolio_item'],
				'post_type' =>  'portfolio', 'iteck_plg',
				$order       =>  $ord_val
			)); 
		}

		?>

		<div class="iteck-portfolio">
			<?php if($settings['show_filters'] == 'yes'): ?>
			<div class="controls">
				<?php
				$destudio_terms = get_terms(array(
					'taxonomy' => 'portfolio_category',
					'hide_empty' => false,
					'include' => $settings['blog_cat'],
				)); // Get all terms of a taxonomy 
				if ( $destudio_terms && !is_wp_error( $destudio_terms ) ) : ?>
					<span data-filter='*' class="active">
						
					</span>
					<button type="button" class="control" data-filter="all">
						<?php if ( class_exists('ReduxFrameworkPlugin')&& iteck_option( 'iteck_portfolio_all') ) { 
						echo esc_attr( iteck_option( 'iteck_portfolio_all'));} else { esc_html_e('All','iteck_plg'); } ?>
					</button>
					<?php foreach ( $destudio_terms as $destudio_term ): ?>
						<button type="button" class="control" data-filter=".<?php echo  strtolower(str_replace(array('&amp;', ' ', '.'), array( '','-', ''), $destudio_term->name)); ?>"><?php echo esc_attr( $destudio_term->name); ?></button>
					<?php endforeach;
				endif;?>
			</div>
			<?php endif; ?>
			<div class="content">
				<div class="row mix-container <?php if($settings['content_on_hover'] == 'yes') echo 'hover-'.esc_attr($settings['info_style']) ?>">
					<?php $item_count = 1; if ($destudio_work->have_posts()) : while  ($destudio_work->have_posts()) : $destudio_work->the_post();
					global $post ; ?>
					<div class="col-lg-<?php if($settings['columns_number'] == '6' && $item_count == 1) echo '7'; elseif($settings['columns_number'] == '6' && $item_count == 2) echo '5'; else echo $settings['columns_number']; ?> mix <?php $destudio_terms = get_the_terms( get_the_ID(), 'portfolio_category' ); if(is_array($destudio_terms) && count($destudio_terms) > 0) { foreach ($destudio_terms as $destudio_term) { 
					echo  strtolower(str_replace(array('&amp;', ' ', '.'), array( '','-', ''), $destudio_term->name)). ' '; } }
					$destudio_allClasses = get_post_class(); foreach ($destudio_allClasses as $destudio_class) { 
					echo esc_attr( $destudio_class . " "); } ?>">
						<?php if($settings['content_on_hover'] == 'yes'): ?>
						<a href="<?php esc_url(the_permalink()); ?>" class="project-card d-block mb-30 info-style-<?php echo esc_attr($settings['info_style']) ?>">
							<div class="img">
								<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
							</div>
							<div class="info">
								<h5 class="h5"><?php the_title(); ?></h5>
								
								<?php
								$destudio_taxonomy = 'portfolio_category';
								$destudio_taxs = wp_get_post_terms($post->ID, $destudio_taxonomy);
								$count = 1;
								foreach ($destudio_taxs as $destudio_tax) { 
									if($count != 1) echo ', '; ?>
									<small class="small cat"><?php echo $destudio_tax->name; ?></small>
									<?php $count++;
								}; ?>
								
							</div>
						</a>
						<?php else: ?>
						<div class="portfolio-card mb-50">
							<div class="img">
								<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
							</div>
							<div class="info">
								<h5 class="title">
									<a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a> 
								</h5>
								<small class="category">
									<?php
									$destudio_taxonomy = 'portfolio_category';
									$destudio_taxs = wp_get_post_terms($post->ID, $destudio_taxonomy);
									$destudio_cats = array();
									$count = 1;
									foreach ($destudio_taxs as $destudio_tax) { 
										if($count != 1) echo ', '; ?>
										<a class="cat" href="<?php echo esc_url( get_term_link( $destudio_tax->slug, $destudio_taxonomy ) ); ?>"><?php echo $destudio_tax->name; ?></a>
										<?php $count++;
									}; ?>
								</small>
								<?php if($settings['show_excerpt'] == 'yes'): ?>
								<div class="text"><?php the_excerpt(); ?></div>
								<?php endif;
								if($settings['show_tags'] == 'yes'): ?>
								<div class="tags">
								<?php if  (has_tag()) { ?>
									the_tags(' : ');?>
								<?php } ?>


								<?php
									$iteck_taxonomy_tag = 'porto_tag';
									$iteck_taxs_tag = wp_get_post_terms($post->ID, $iteck_taxonomy_tag);
									$iteck_tags = array();
									$count = 1;
									foreach ($iteck_taxs_tag as $iteck_tax_tag) { 
										if($count != 1) ?>
										<a class="cat" href="<?php echo esc_url( get_term_link( $iteck_tax_tag->slug, $iteck_taxonomy_tag ) ); ?>"><?php echo $iteck_tax_tag->name; ?></a>
										<?php $count++;
									}; ?>  
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<?php $item_count++; endwhile;
					else: ?>
						<div class="alert alert-warning"><?php _e('There is no Portfolio Post Found. You need to  choose the portfolio category to show or create at least 1 portfolio post first.','iteck-plg'); ?></div>
					<?php endif;  wp_reset_postdata();  ?>
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



