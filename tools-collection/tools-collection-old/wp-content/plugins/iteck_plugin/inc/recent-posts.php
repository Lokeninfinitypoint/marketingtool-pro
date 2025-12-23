<?php


add_action( 'widgets_init', 'iteck_plg');

if( !function_exists('iteck_plg') ){
function iteck_plg() {

   /**
    * Registering widget areas for front page
    */
   register_widget( "iteck_blog_widget" );
}}
  /**
 * Featured Posts widget
 */
 if( !class_exists('iteck_blog_widget') ){
class iteck_blog_widget extends WP_Widget {

   function __construct() {
      $widget_ops = array( 'classname' => 'widget_featured_posts_block', 'description' => esc_html__( 'Display latest posts or posts of specific category', 'iteck') );
      $control_ops = array( 'width' => 300, 'height' =>250 );
      parent::__construct( false,$name= esc_html__( 'ITECK: Blog Featured Posts', 'iteck' ),$widget_ops);
   }

   function form( $instance ) {
      $defaults[ 'title' ] = '';
      $defaults[ 'number' ] = 3;
      $defaults[ 'type' ] = 'latest';
      $defaults[ 'category' ] = '';
      $defaults[ 'post_color' ] = 'light';


      $instance = wp_parse_args( (array) $instance, $defaults );
      $title = esc_attr( $instance[ 'title' ] );
      $number = absint( $instance[ 'number' ] );
      $type = $instance[ 'type' ];
      $category = $instance[ 'category' ];
      $post_color = $instance[ 'post_color' ]; ?>


      <p><?php esc_html_e( 'Note: Enter the Featured Post Section ID and use same for Menu item. Only used for One Page Menu.', 'iteck' ); ?></p>


      <p>
         <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title :', 'iteck' ); ?></label>
         <input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
      </p>


      <p>
         <label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number :', 'iteck' ); ?></label>
         <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="-1" value="<?php echo esc_attr($number); ?>" size="3" />
      </p>

      <p>
         <input type="radio" <?php checked( $type, 'latest' ) ?> id="<?php echo esc_attr($this->get_field_id( 'type' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>" value="latest"/><?php esc_html_e( 'Show latest Posts', 'iteck' );?><br />
         <input type="radio" <?php checked( $type,'category' ) ?> id="<?php echo esc_attr($this->get_field_id( 'type' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>" value="category"/><?php esc_html_e( 'Show posts from a category', 'iteck' );?><br />
      </p>

      <p>
         <label for="<?php echo esc_attr($this->get_field_id( 'category' )); ?>"><?php esc_html_e( 'Select category', 'iteck' ); ?>:</label>
         <?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
      </p>


      <p>
         <label for="<?php echo $this->get_field_id( 'post_color' ); ?>">
            <?php _e( 'post_color', 'iteck' ); ?>
         </label>
         <select class="widefat" id="<?php echo $this->get_field_id( 'post_color' ); ?>" name="<?php echo $this->get_field_name( 'post_color' ); ?>" style="width:100%;">
            <option value="light" <?php selected( $instance['post_color'], 'light' ); ?>><?php _e( 'Light', 'iteck' ) ?></option>
            <option value="dark" <?php selected( $instance['post_color'], 'dark' ); ?>><?php _e( 'Dark', 'iteck' ) ?></option>
         </select>
      </p>

      <?php
   }

   function update( $new_instance, $old_instance ) {
      $instance = $old_instance;

      $instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
      $instance[ 'number' ] = absint( $new_instance[ 'number' ] );
      $instance[ 'type' ] = $new_instance[ 'type' ];
      $instance[ 'category' ] = $new_instance[ 'category' ];
      $instance['post_color'] = stripslashes( $new_instance['post_color'] );

      return $instance;
	}

   function widget( $args, $instance ) { 
      extract( $args );
      extract( $instance );

      global $post;
      $title = apply_filters( 'iteck_widget_title', isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '');
      $number = empty( $instance[ 'number' ] ) ? 3 : $instance[ 'number' ];
      $type = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest' ;
      $category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';
      $post_color = isset( $instance[ 'post_color' ] ) ? $instance[ 'post_color' ] : 'light' ;

      if( $type == 'latest' ) {
         $get_featured_posts = new WP_Query( array(
            'posts_per_page'        => $number,
            'post_type'             => 'post',
            'ignore_sticky_posts'   => true
         ) );
      }
      else {
         $get_featured_posts = new WP_Query( array(
            'posts_per_page'        => $number,
            'post_type'             => 'post',
            'category__in'          => $category
         ) );
	} 
	echo $before_widget;?>


	<?php if( !empty( $title ) ){ ?>
   <h3 class="widgettitle"><?php echo wp_kses_post ($title); ?></h3>
	<div class="widget-border"></div>
   <?php } ?>
	<?php 
	$count = 0;
	while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
		if ( $count % 3 == 0 && $count > 1 ) { ?> <div class="clearfix"></div> <?php } ?>
		<div class="recent-posts-widget">

			<div class="widget-post-thumbnail">
            <a class="recent-post-img">
   				<?php if( has_post_thumbnail() ) { the_post_thumbnail('iteck-featured-image'); } ?>
   			</a>
         </div>
         <div class="widget-post-content">
			<h3 class="title <?php echo $post_color; ?>"> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h3> 

			<?php
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
			$time_string = sprintf( $time_string,esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ));
			printf( __( '<span class="'. $post_color .'"><a href="%1$s" title="%2$s" rel="bookmark"> %3$s</a></span>', 'iteck' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			$time_string
			); ?>
         </div>

		</div>
		<?php $count++;
	endwhile;
	echo $after_widget;
	?>

      <?php

   }
}}

/**************************************************************************************/
