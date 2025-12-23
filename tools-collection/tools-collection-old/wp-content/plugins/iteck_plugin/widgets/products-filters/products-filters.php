<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Products_Filters extends Widget_Base {

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
		return 'iteck-products-filters';
	}
	
	//script depend
	public function get_script_depends() { return [ 'iteck-jquery-ui','iteck-products-filters' ]; }
	
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
		return __( 'iteck Products Filters', 'iteck_plg' );
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
				'label' => __( 'Products Filters Settings.', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'filters_title',
			[
				'label' => esc_html__( 'Filters Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Filters', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'filters_reset_text',
			[
				'label' => esc_html__( 'Filters Reset Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Reset All', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'tags_title',
			[
				'label' => esc_html__( 'Tags Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tags', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'colors_title',
			[
				'label' => esc_html__( 'Colors Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Colors', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'price_title',
			[
				'label' => esc_html__( 'Price Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Price', 'iteck_plg' ),
				'label_block' => true,
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

        $destudio_work = new \WP_Query(array(
            'post_type' =>  'product', 'iteck_plg',
        ));

		$taxonomy  = 'product_tag';
		$tquery    = new \WP_Term_Query( array(
			'taxonomy'     => $taxonomy,
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => false,
		) );

        ?>

        <div class="iteck-products-filters">

			<div class="card-title d-flex justify-content-between">
				<span><?php esc_html_e($settings['filters_title']) ?></span>
				<a href="?" class="text-uppercase fw-normal fs-10px"> <i class="bi bi-arrow-repeat me-1"></i> <?php esc_html_e($settings['filters_reset_text']); ?> </a>
			</div>

			<div class="products-search">
				<form role="search" method="get" class="search-group" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" 
						class="et-search-field" placeholder="%1$s" placeholder="%2$s" name="s" 
						title="%3$s" />',
						esc_attr__( 'Search &hellip;', 'iteck_plg' ),
						get_search_query(),
						esc_attr__( 'Search for:', 'iteck_plg' )
						);
					?>
					<input type="hidden" class="" value="product" name="post_type" />
					<button type="submit" id="searchsubmit_header"><i class="bi bi-search"></i></button>
				</form>
			</div>
            
			<form class="filters-form" method="get">

				<?php if(!empty($settings['tags_title'])): ?>
				<div class="sub-tilte d-flex align-items-center justify-content-between">
					<span><?php esc_html_e($settings['tags_title']) ?></span>
				</div>
				<?php endif; ?>

				<div class="filter-card-item tags-filter filter-card-scroll">

					<?php

					foreach($tquery->get_terms() as $term): ?>

						<div class="form-check category-checkBox">
							<input class="form-check-input" type="checkbox" name="tag-<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" id="<?php echo $term->slug; ?>" <?php if(isset($_GET['tag-'.$term->slug]) && $_GET['tag-'.$term->slug] == $term->slug) echo 'checked' ;?>>
							<label class="form-check-label" for="<?php echo $term->slug; ?>"><?php echo $term->name; ?></label>
						</div>

					<?php endforeach;

					?>

				</div>

				<?php if(!empty($settings['colors_title'])): ?>
				<div class="sub-tilte d-flex align-items-center justify-content-between">
					<span><?php esc_html_e($settings['colors_title']) ?></span>
				</div>
				<?php endif; ?>

				<div class="filter-card-item variations-filter filter-card-scroll">

					<?php

					$variations_list = array();

					if ($destudio_work->have_posts()) :  
						while  ($destudio_work->have_posts()) : $destudio_work->the_post();

							global $post; global $product;

							if( $product->is_type( 'variable' ) ):
							
								$variations = $product->get_available_variations(); 
								
								foreach ($variations as $key => $variation): 

									foreach ($variation['attributes'] as $attribute => $term_slug ) : 
									
										// Get the taxonomy slug
										$taxonmomy = str_replace( 'attribute_', '', $attribute );

										// Get the attribute label name
										$attr_label_name = wc_attribute_label( $taxonmomy );

										// Display attribute labe name
										$term_name = get_term_by( 'slug', $term_slug, $taxonmomy )->name;

										$variations_list[$attr_label_name.'-'.$term_name] =  [
											'type' => $attr_label_name,
											'value' => $term_name,
										];
								
									endforeach;

								endforeach;

							endif;

						endwhile;  
						
					endif; 

					array_unique($variations_list, SORT_REGULAR);
					
					foreach ($variations_list as $variation ): ?>

					
						<div class="form-check category-checkBox">
							<input class="form-check-input" type="checkbox" name="<?php echo $variation['type'].$variation['value']; ?>" value="<?php echo $variation['value']; ?>" id="<?php echo $variation['type'].$variation['value']; ?>" <?php if(isset($_GET[$variation['type'].$variation['value']]) && $_GET[$variation['type'].$variation['value']] == $variation['value']) echo 'checked' ;?>>
							<label class="form-check-label" for="<?php echo $variation['type'].$variation['value']; ?>"><?php echo $variation['value']; ?></label>
						</div>

					<?php endforeach; ?>

				</div>

				<?php if(!empty($settings['price_title'])): ?>
				<div class="sub-tilte d-flex align-items-center justify-content-between">
					<span><?php esc_html_e($settings['price_title']) ?></span>
				</div>
				<?php endif; ?>
		
				<div class="slider-range-content">

					<?php
					$products = wc_get_products(array());
										
					//get all prices from this category
					$all_prices[] = array();

					foreach ($products as $product):
						$all_prices[] = $product->get_price();
					endforeach; 

					$max_store_price = max(array_filter($all_prices));
					
					if(isset($_GET['min-price']) && isset($_GET['max-price'])):
						$min_price = $_GET['min-price'];
						$max_price = $_GET['max-price'];
					else:
						$min_price = min(array_filter($all_prices));
						$max_price = max(array_filter($all_prices));
					endif;
					
					?>
					
					<div class="amount-input" data-max-store-value="<?php echo esc_attr($max_store_price); ?>">
						<div class="amount">
							<small>$ Min</small>
							<input type="text" id="amount1" name="min-price" data-min-value="<?php echo esc_attr($min_price); ?>" readonly>
						</div>
						<div class="amount">
							<small>$ Max</small>
							<input type="text" id="amount2" name="max-price" data-max-value="<?php echo esc_attr($max_price); ?>" readonly>
						</div>
						<button type="submit">go</button>
					</div>
					<div id="slider-range"></div>
				</div>

			</form>


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



