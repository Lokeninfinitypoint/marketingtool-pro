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
class Iteck_Products_Tabs extends Widget_Base {

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
		return 'iteck-products-tabs';
	}
	
	//script depend
	public function get_script_depends() { return [ 'iteck-mixitup','iteck-products-tabs' ]; }

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
		return __( 'iteck Products Tabs', 'iteck_plg' );
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
				'label' => __( 'Product Settings.', 'iteck_plg' ),
			]
		);
		
		
		$this->add_control(
			'product_item',
			[
				'label' => __( 'Item to display', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '8',
			]
		);
		
		$this->add_control(
			'sort_cat',
			[
				'label' => __( 'Sort Product by Product Category', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'product_cat',
			[
				'label'   => __( 'Category to Show', 'iteck_plg' ),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_products_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
			]
		);

        $this->add_control(
			'all_products_text',
			[
				'label' => esc_html__( 'All Products Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'All Category', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'add to cart', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'adding_text',
			[
				'label' => esc_html__( 'Adding to Cart Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'adding to cart', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'added_text',
			[
				'label' => esc_html__( 'Added to Cart Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'added to cart', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'tab_img',
			[
				'label' => esc_html__( 'Author Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
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

		
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $settings['product_item'],
			'order' => 'desc',
			'orderby' => 'title'
		);

		if($settings['sort_cat'] == 'yes' && !empty($settings['product_cat'])):
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',   // taxonomy name
					'field' => 'term_id',
					'terms' => $settings['product_cat'],           // term_id, slug or name                // term id, term slug or term name
				)
			);
		endif;

		$args = new \WP_Query( $args );


?>



		        
        <div class="iteck-products-tabs">
			<div class="content">
				<div class="mix_tabs mb-20">
					<span class="active tab-link" data-filter="all"> <img src="<?php echo esc_url( $settings['tab_img']['url'] ); ?>" alt="" class="icon"><?php echo wp_kses_post( $settings['all_products_text'] ) ?></span>
					<?php 
					$woo_cats_array = [];
					if ($args->have_posts()) : while ( $args->have_posts() ) : $args->the_post(); global $post; global $product;
					$woo_cats = get_the_terms( $post->ID, 'product_cat' );
					if($woo_cats) {
						foreach($woo_cats as $woo_cat) { $woo_cats_array[str_replace(' ', '-', strtolower($woo_cat->name))] = esc_html($woo_cat->name); }   
					} 
					endwhile; endif;
					array_unique($woo_cats_array);
					foreach($woo_cats_array as $cat => $cat_value) { ?>
						<span class="tab-link" data-filter=".<?php echo str_replace(' ', '-', strtolower($cat)) ?>"> <img src="<?php echo esc_url( $settings['tab_img']['url'] ); ?>" alt="" class="icon"><?php echo esc_html($cat_value); ?></span>
					<?php }; ?>
				</div>
				<div class="row items">
					<?php if ($args->have_posts()) : while ( $args->have_posts() ) : $args->the_post(); global $post; ?>
						<div class="col-lg-3 mix <?php $woo_cats = get_the_terms( $post->ID, 'product_cat' ); if($woo_cats) {
						foreach($woo_cats as $woo_cat) { echo str_replace(' ', '-', strtolower($woo_cat->name)).' '; }   
					} ?>">
							<div class="project-card">
								<div class="top-inf">
									<?php if ( is_plugin_active( 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php' ) ) echo do_shortcode('[ti_wishlists_addtowishlist product_id="'. $post->ID .'" variation_id="'. $product->get_id() .'"]'); ?>
									<span> <i class="fas fa-sort icon"></i> 1.00 </span>
								</div>
								<div class="img img-cover">
									<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
									
									<?php
									echo apply_filters( 'woocommerce_loop_add_to_cart_link',
										sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="btn product_type_%s add_to_cart_button ajax_add_to_cart %s">
											<span class="button__cart">%s</span>
											<span class="button__loader h-rotatingNeuron">%s</span>
											<span class="button__added">%s</span></a>',
											esc_url( $product->add_to_cart_url() ),
											esc_attr( $product->get_id() ),
											esc_attr( $product->get_sku() ),
											$product->is_purchasable() && $product->is_in_stock() ? 'd-flex align-items-center justify-content-center' : 'h-display-none',
											esc_attr( $product->get_type() ),
											'<span class="add_to_cart"><i class="fal fa-shopping-basket me-1"></i>  '. $settings['button_text'] .'</span>',
											'<span class="loading_popup"><i class="fal fa-refresh me-1"></i>'. $settings['adding_text'] .'</span>',
											'<span class="added_popup"><i class="fal fa-check me-1"></i> '. $settings['added_text'] .'</i></span>'
										),
									$product );
									?>
								</div>
								<div class="info">
									<small class="bid"> Highest bid <span class="bid-value"> 6/50 </span> </small>
									<h6 class="title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h6>
									<div class="btm-inf">
										<p class="btm-inf-item"> <i class="fal fa-users icon"></i> 10+ Place Bit </p>
										<p class="btm-inf-item"> <i class="fal fa-history icon"></i> History </p>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; else: ?>
                
						<div class="alert alert-warning"><?php _e('There is no Product Post Found. You need to  choose the product category to show or create at least 1 product post first.','iteck-plg'); ?></div>
					
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



