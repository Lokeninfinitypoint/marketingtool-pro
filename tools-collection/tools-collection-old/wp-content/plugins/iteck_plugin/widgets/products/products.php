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
class Iteck_Products extends Widget_Base {

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
		return 'iteck-products';
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
		return __( 'iteck Products', 'iteck_plg' );
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
			'page_show',
			[
				'label' => __( 'Show Pagination', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'iteck_plg' ),
				'label_off' => __( 'Hide', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'sort_cat!' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'page_align',
			[
				'label' => __( 'Pagination Alignment', 'iteck_plg' ),
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
				'condition' => [
					'page_show' => 'yes',
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .pagi-box' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'new_tag_text',
			[
				'label' => esc_html__( 'New Tag Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'new', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'reviews_text',
			[
				'label' => esc_html__( 'Reviews Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Reviews', 'iteck_plg' ),
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

		$orderby = 'title';
		$order = 'asc';
		if ( isset( $_GET['orderby'] ) ) {
			$getorderby = $_GET['orderby'];
		}
		if (isset($getorderby) && $getorderby == 'popularity') {
			$orderby = 'meta_value_num';
			$order = 'desc';
			$meta_key = 'total_sales';
		} elseif (isset($getorderby) && $getorderby == 'rating') {

			$fields .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

			$where .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

			$join .= "
				LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
				LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
			";

			$orderby = "average_rating DESC, $wpdb->posts.post_date DESC";

			$groupby = "$wpdb->posts.ID";   

		} elseif (isset($getorderby) && $getorderby == 'date') {
			$orderby = 'date';
			$order = 'desc';
		} elseif (isset($getorderby) && $getorderby == 'price') {
			$orderby = 'meta_value_num';
			$order = 'asc';
			$meta_key = '_price';
		} elseif (isset($getorderby) && $getorderby == 'price-desc') {
			$orderby = 'meta_value_num';
			$order = 'desc';
			$meta_key = '_price';
		}

		$iteck_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$color_list = [];
		$tags_list = [];
		if(isset($_GET)):
			foreach($_GET as $key => $value):
				if(str_contains($key, 'color') || strpos($key, 'color') !== false):
					$color_list[] = $value;
					$is_variations = true;
				endif;
				if(str_contains($key, 'tag') || strpos($key, 'tag') !== false):
					$tags_list[] = $value;
					$is_tags = true;
				endif;
			endforeach;
			if(isset($_GET['min-price']) && isset($_GET['max-price']) && $is_variations):
				$min_price = $_GET['min-price'];
				$max_price = $_GET['max-price'];
				$price_range['slug'] = 'meta_query';
				$price_range['query'] = array(
					'relation'    => 'AND',
					array(
						'key' => '_price',
						'value' => array($min_price, $max_price),
						'compare' => 'BETWEEN',
						'type' => 'NUMERIC'
					),
					array( 
						'key'     => 'attribute_pa_color', // Product variation attribute
						'value'   => $color_list, // Term slugs only
						'compare' => 'IN',
					),
				);
			elseif(isset($_GET['min-price']) && isset($_GET['max-price']) && !$is_variations):
				$min_price = $_GET['min-price'];
				$max_price = $_GET['max-price'];

				$price_range = array(
					'slug' => 'meta_query',
					'query' => array(
						array(
							'key' => '_price',
							'value' => array($min_price, $max_price),
							'compare' => 'BETWEEN',
							'type' => 'NUMERIC'
						),
					),
				);
			elseif(isset($is_variations) && $is_variations):
				$price_range = array(
					'slug' => 'meta_query',
					'query' => array(
						array( 
							'key'     => 'attribute_pa_color', // Product variation attribute
							'value'   => $color_list, // Term slugs only
							'compare' => 'IN',
						),
					),
				);
			endif;
		endif;

		if(isset($is_variations) && $is_variations) {
			$product_type = 'product_variation';
		} else {
			$product_type = 'product';
		}
		
		if ( $settings['sort_cat']  == 'yes' ) {
			$destudio_work = array(
				'posts_per_page'   => $settings['product_item'],
				'post_type' =>  'product', 'iteck_plg',
				'orderby'               => $orderby, // $ordering_args['orderby'],
				'order'                 => $order, // $ordering_args['order'],
				'meta_key'              => $meta_key,
				'fields'                => $fields,
				'where'                 => $where,
				'join'                  => $join,
				'groupby'               => $groupby,
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',   // taxonomy name
						'field' => 'term_id',
						'terms' => $settings['product_cat'],           // term_id, slug or name                // term id, term slug or term name
					)
				),
				$price_range['slug'] => $price_range['query'],
			); 
		} else {
			$destudio_work = array(
				'paged' => $iteck_paged,
				'posts_per_page'   => $settings['product_item'],
				'post_type' =>  $product_type, 'iteck_plg',
				'orderby'               => $orderby, // $ordering_args['orderby'],
				'order'                 => $order, // $ordering_args['order'],
			); 

            if(isset($meta_key)){
                $destudio_work['meta_key'] = $meta_key;
            }
            if(isset($fields)){
                $destudio_work['fields'] = $fields;
            }
            if(isset($where)){
                $destudio_work['where'] = $where;
            }
            if(isset($join)){
                $destudio_work['join'] = $join;
            }
            if(isset($groupby)){
                $destudio_work['groupby'] = $groupby;
            }
            if(isset($price_range['slug']) && isset($price_range['query'])){
                $destudio_work[$price_range['slug']] = $price_range['query'];
            }

			if ( isset( $ordering_args['meta_key'] ) ) {
				$destudio_work['meta_key'] = $ordering_args['meta_key'];
			}
		}

		$destudio_work = new \WP_Query($destudio_work);

		if($settings['product_item'] < $destudio_work->post_count){
			$page_products = $settings['product_item'];
		} else {
			$page_products = $destudio_work->post_count;
		}

        ?>
		        
        <div class="iteck-product">

			<div class="top-filter mb-10">
				<div class="row align-items-center">
					<div class="col-lg-6">
						<p class="color-999 fs-12px mb-3 mb-lg-0"> <span class="color-000">1 - <?php echo $page_products; ?></span> of <?php echo $destudio_work->post_count; ?> results</p>
					</div>
					<div class="col-lg-6">
						<div class="r-side">
							<div class="row align-items-center">
								<div class="col-8">
									<form class="woocommerce-ordering form-group" method="get">
										<label for="">Sort by</label>
										<select name="orderby" class="orderby form-select">
											<?php
												$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
													'menu_order' => __( 'Default sorting', 'iteck_plg' ),
													'popularity' => __( 'Sort by popularity', 'iteck_plg' ),
													'rating'     => __( 'Sort by average rating', 'iteck_plg' ),
													'date'       => __( 'Sort by newness', 'iteck_plg' ),
													'price'      => __( 'Sort by price: low to high', 'iteck_plg' ),
													'price-desc' => __( 'Sort by price: high to low', 'iteck_plg' )
												) );

												if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
													unset( $catalog_orderby['rating'] );

												foreach ( $catalog_orderby as $id => $name )
													echo '<option value="' . esc_attr( $id ) . '" ' . selected( $getorderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
											?>
										</select>
										<?php
											// Keep query string vars intact
											foreach ( $_GET as $key => $val ) {
												if ( 'orderby' === $key || 'submit' === $key )
													continue;

												if ( is_array( $val ) ) {
													foreach( $val as $innerVal ) {
														echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
													}

												} else {
													echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
												}
											}
										?>
									</form>
								</div>
								<div class="col-4">
									<div class="grid-list-btns">
										<span class="grid-btn bttn active">
											<i class="bi bi-grid-3x3"></i>
										</span>
										<span class="list-btn bttn">
											<i class="bi bi-list-task"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
    
            <div class="products">
                <div class="row gx-2 gx-lg-4">
                    <?php 
                    
                    if ($destudio_work->have_posts()) :  while  ($destudio_work->have_posts()) : $destudio_work->the_post();
                    global $post; global $product;
					$parent_product_id = wp_get_post_parent_id($product->get_id());
					$parent_product = wc_get_product($parent_product_id);
					$terms = get_the_terms( $parent_product_id, 'product_tag' );
					$term_array = array();
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
						foreach ( $terms as $term ) {
							$term_array[] = $term->name;
						}
					}
					if (isset($is_tags) && array_filter(array_intersect(array($term_array), array($tags_list))) || !isset($is_tags)):
					
                    ?>

                        <div class="col-6 col-lg-3 card-width" id="post-<?php the_ID(); ?>">
                        
                            <div class="product-card">
                                <div class="img">
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url($parent_product_id)); ?>" alt="">
                                    <?php 
                                    $product_date = get_the_date('Y-m-d', $product->get_id());
                                    if(strtotime('-1 week') < strtotime($product_date)): ?>
                                    <span class="label new"><?php esc_html_e($settings['new_tag_text']) ?></span>
                                    <?php endif; 
                                    if ( $product->is_on_sale() ): ?>
                                    <span class="label sale-off <?php if(strtotime('-1 week') < strtotime($product_date)) echo 'new-onsale' ?>">
                                    <?php
                                        echo number_format(( ($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() ) * 100, '0') . '% off';
                                    ?>
                                    </span>
                                    <?php endif; ?>
									<?php if ( is_plugin_active( 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php' ) ) echo do_shortcode('[ti_wishlists_addtowishlist product_id="'. $parent_product_id .'" variation_id="'. $product->get_id() .'"]'); ?>
                                </div>
                                <div class="info">
                                    <h6 class="category">
                                        <?php
										$id = isset($is_variations) ? $parent_product_id : $product->get_id();
                                        $term_names_array = wp_get_post_terms( $id, 'product_cat', array('fields' => 'names') ); // Array of product category term names
                                        $term_names_string = count($term_names_array) > 0 ? implode(', ', $term_names_array) : ''; // Convert to a coma separated string
                                        echo $term_names_string; // Display the string
                                        ?>
                                    </h6>
                                    <h5 class="title"><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h5>
                                    <div class="rate">
                                        <div class="woocommerce stars">
                                            <?php if ($average = $product->get_average_rating()) : ?>
                                                <?php echo '<div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>'; ?>
                                            <?php endif; $review_count = $product->get_review_count(); ?>
                                        </div>
                                        <span class="rev"><?php echo $review_count; ?> <?php esc_html_e($settings['reviews_text']) ?></span>
                                    </div>
                                </div>
                                <div class="price">
                                    <?php 
									if ( $product->is_type( 'variable' ) ):
										$variations = $product->get_available_variations();
										echo $product->get_variation_price( 'min' ).' - '.$product->get_variation_price( 'max' );
									else:
										$regular_price = get_post_meta( get_the_ID(), '_regular_price', true );
										$sale_price = get_post_meta( get_the_ID(), '_sale_price', true );
										if( $product->is_on_sale() ): ?>
											<span class="price-sale"><?php echo wc_price( $sale_price ); ?></span><span class="old-price"><?php echo wc_price( $regular_price ); ?></span>
										<?php else:
											echo wc_price( $regular_price );
										endif;
									endif; ?> 
                                </div>

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
                                            '<span>'. $settings['button_text'] .'</span>',
                                            '<span class="loading_popup">'. $settings['adding_text'] .' <i class="fa fa-refresh"></i></span>',
                                            '<span class="added_popup">'. $settings['added_text'] .' <i class="fa fa-check"></i></span>'
                                        ),
                                    $product );
                                ?>
                            </div>            
                            
                        </div><!--.port-item-->
            
                    <?php endif; endwhile;  ?>

                </div>
                
                <?php  
                if  ($settings['page_show'] == 'yes' && $settings['sort_cat']  != 'yes' ) {  ?>

                    <!--pagination--> 
                    <div class="products-pagination">
                        <?php
                        previous_posts_link( '&laquo; PREV', $destudio_work->max_num_pages);
                        iteck_pagination($destudio_work->max_num_pages);
                        next_posts_link( 'NEXT &raquo;', $destudio_work->max_num_pages);
                        ?>
                    </div>
                    
                <?php };
                    
                else: ?>
                
                    <div class="alert alert-warning"><?php _e('There is no Product Post Found. You need to  choose the product category to show or create at least 1 product post first.','iteck-plg'); ?></div>
                
                <?php endif;  wp_reset_postdata();  ?>            
                                
            </div><!--/.product-body-->
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



