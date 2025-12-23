<?php
/**
 * Metabox Map
 *
 * @package Iteck
 */
?>
<?php
function iteck_meta_box_text($iteck_id, $iteck_label, $iteck_desc = '', $iteck_short_desc = '')
{
	global $post;

	$html = '';
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
		$html .= '<div class="left-part">';
			$html .= $iteck_label;
			if($iteck_desc) {
				$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
			}
		$html .='</div>';
		$html .= '<div class="right-part">';
			$html .= '<input type="text" id="' . esc_attr($iteck_id) . '" name="' . esc_attr($iteck_id) . '" value="' . get_post_meta($post->ID, $iteck_id, true) . '" />';
			if($iteck_short_desc) {
				$html .= '<span class="short-description">' . esc_attr($iteck_short_desc) . '</span>';
			}
		$html .= '</div>';
		$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_dropdown($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '')
{
	global $post;
	

	$html = $iteck_select_class = '';

			$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
					$html .= '<div class="left-part">';
							$html .= $iteck_label;
							if($iteck_desc) {
									$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
							}
					$html .='</div>';
					$html .= '<div class="right-part">';
							$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.$iteck_select_class.'" name="' . esc_attr($iteck_id) . '">';
							foreach($iteck_options as $key => $option) {
									if(get_post_meta($post->ID, $iteck_id, true) == (string)$key && get_post_meta($post->ID, $iteck_id, true) != '') {
											$iteck_selected = 'selected="selected"';
									}else {
													$iteck_selected = '';
									}

									$html .= '<option ' . $iteck_selected . ' value="' . esc_attr($key) . '">' . esc_attr($option) . '</option>';

							}
							$html .= '</select>';
					$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_dropdown_sidebar($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '', $iteck_child_hidden = '')
{
	global $post;

	$html = $iteck_select_class = '';
	$flag = false;
		$iteck_child_hidden = ( $iteck_child_hidden ) ? ' hide-child '.$iteck_child_hidden : '';
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box'.$iteck_child_hidden.'">';
			$html .= '<div class="left-part">';
				$html .= $iteck_label;
				if($iteck_desc) {
					$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
				}
			$html .='</div>';
			$html .= '<div class="right-part">';
				$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.esc_attr($iteck_select_class).'" name="' . esc_attr($iteck_id) . '">';
				foreach($iteck_options as $key => $option) {
					if(get_post_meta($post->ID, $iteck_id, true) == $key && get_post_meta($post->ID, $iteck_id, true) != '') {
						$iteck_selected = 'selected="selected"';
					}else {
							$iteck_selected = '';
					}

					$html .= '<option ' . $iteck_selected . ' value="' . esc_attr($key) . '">' . esc_attr($option) . '</option>';

				}
				$html .= '</select>';
			$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}

/* menu dropdown */

function iteck_meta_box_dropdown_menu($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '')
{
	global $post;

	$html = $iteck_select_class = '';
	$flag = false;

	
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
			$html .= '<div class="left-part">';
				$html .= $iteck_label;
				if($iteck_desc) {
					$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
				}
			$html .='</div>';
			$html .= '<div class="right-part">';
				$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.esc_attr($iteck_select_class).'" name="' . esc_attr($iteck_id) . '">';
				$html .= '<option value="">Default</option>';
				$iteck_menus = wp_get_nav_menus();
				$iteck_menu_array = array();
				foreach ($iteck_menus as $key => $value) {
					if(get_post_meta($post->ID, $iteck_id, true) == $value->slug && get_post_meta($post->ID, $iteck_id, true) != '') {
						$iteck_selected = 'selected="selected"';
					}else {
							$iteck_selected = ''; 
					}

					$html .= '<option ' . $iteck_selected . ' value="' . esc_attr($value->slug) . '">' . esc_attr($value->name) . '</option>';
				}
				$html .= '</select>';
			$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_dropdown_custom_headers($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '')
{
	global $post;

	$html = $iteck_select_class = '';
	$flag = false;

	
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
			$html .= '<div class="left-part">';
				$html .= $iteck_label;
				if($iteck_desc) {
					$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
				}
			$html .='</div>';
			$html .= '<div class="right-part">';
				$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.esc_attr($iteck_select_class).'" name="' . esc_attr($iteck_id) . '">';
				$html .= '<option value="">Default</option>';
				$iteck_custom_headers = new WP_Query( array( 'post_type' => 'header' ) );
				$posts = $iteck_custom_headers->posts; 
				foreach ($posts as $key => $value) {
					if(get_post_meta($post->ID, $iteck_id, true) == $value->ID && get_post_meta($post->ID, $iteck_id, true) != '') {
						$iteck_selected = 'selected="selected"';
					}else {
							$iteck_selected = '';
					}

					$html .= '<option ' . $iteck_selected . ' value="' . esc_attr($value->ID) . '">' . esc_attr($value->post_name) . '</option>';
				}
				$html .= '</select>';
			$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_dropdown_custom_footers($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '')
{
	global $post;

	$html = $iteck_select_class = '';
	$flag = false;

	
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
			$html .= '<div class="left-part">';
				$html .= $iteck_label;
				if($iteck_desc) {
					$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
				}
			$html .='</div>';
			$html .= '<div class="right-part">';
				$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.esc_attr($iteck_select_class).'" name="' . esc_attr($iteck_id) . '">';
				$html .= '<option value="">Default</option>';
				$iteck_custom_footers = new WP_Query( array( 'post_type' => 'footer' ) );
				$posts = $iteck_custom_footers->posts; 
				foreach ($posts as $key => $value) {
					if(get_post_meta($post->ID, $iteck_id, true) == $value->ID && get_post_meta($post->ID, $iteck_id, true) != '') {
						$iteck_selected = 'selected="selected"'; 
					}else {
							$iteck_selected = '';
					}

					$html .= '<option ' . $iteck_selected . ' value="' . esc_attr($value->ID) . '">' . esc_attr($value->post_name) . '</option>';
				}
				$html .= '</select>';
			$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}
function iteck_meta_box_dropdown_custom_sidepanels($iteck_id, $iteck_label, $iteck_options, $iteck_desc = '')
{
	global $post;

	$html = $iteck_select_class = '';
	$flag = false;

	
		$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
			$html .= '<div class="left-part">';
				$html .= $iteck_label;
				if($iteck_desc) {
					$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
				}
			$html .='</div>';
			$html .= '<div class="right-part">';
				$html .= '<select id="' . esc_attr($iteck_id) . '" class="'.esc_attr($iteck_select_class).'" name="' . esc_attr($iteck_id) . '">';
				$html .= '<option value="">Default</option>';
				$iteck_custom_sidepanels = new WP_Query( array( 'post_type' => 'sidepanel' ) );
				$posts = $iteck_custom_sidepanels->posts; 
				foreach ($posts as $key => $value) {
					if(get_post_meta($post->ID, $iteck_id, true) == $value->ID && get_post_meta($post->ID, $iteck_id, true) != '') {
						$iteck_selected_sidepanel = 'selected="selected"'; 
					}else {
							$iteck_selected_sidepanel = '';
					}

					$html .= '<option ' . $iteck_selected_sidepanel . ' value="' . esc_attr($value->ID) . '">' . esc_attr($value->post_name) . '</option>';
				}
				$html .= '</select>';
			$html .='</div>';	
		$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_textarea($iteck_id, $iteck_label, $iteck_desc = '', $iteck_default = '' )
{
	global $post;
	$html = '';
	$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
	$html .= '<div class="left-part">';
		$html .= $iteck_label;
		if($iteck_desc) {
			$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
		}
	$html .='</div>';
	
	if( get_post_meta($post->ID, $iteck_id, true)) {
		$iteck_value = get_post_meta($post->ID, $iteck_id, true);
	} else {
		$iteck_value = '';
	}
	$html .= '<div class="right-part">';
		$html .= '<textarea cols="120" id="' . esc_attr($iteck_id) . '" name="' . esc_attr($iteck_id) . '">' . esc_attr($iteck_value) . '</textarea>';
	$html .='</div>';
	$html .= '</div>';

	echo sprintf("%s",$html);
}

function iteck_meta_box_upload($iteck_id, $iteck_label, $iteck_desc = '')
{
	global $post;

	$html = '';
	$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
	$html .= '<div class="left-part">';
		$html .= $iteck_label;
		if($iteck_desc) {
			$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
		}
	$html .='</div>';
	$html .= '<div class="right-part">';
		$html .= '<input name="' . esc_attr($iteck_id) . '" class="upload_field" id="iteck_upload" type="text" value="' . get_post_meta($post->ID,  $iteck_id, true) . '" />';
		$html .= '<input name="'. $iteck_id.'_thumb" class="'. $iteck_id.'_thumb" id="'. $iteck_id.'_thumb" type="hidden" value="'.get_post_meta($post->ID,  $iteck_id, true).'" />';
				$html .= '<img class="upload_image_screenshort" src="'.get_post_meta($post->ID,  $iteck_id, true).'" />';
		$html .= '<input class="iteck_upload_button" id="iteck_upload_button" type="button" value="'.__( 'Browse', 'iteck_plg' ).'" />';
		$html .= '<span class="iteck_remove_button button">'.__( 'Remove', 'iteck_plg' ).'</span>';
				
	$html .='</div>';
	$html .= '</div>';
	echo sprintf("%s",$html);
}

function iteck_meta_box_upload_multiple($iteck_id, $iteck_label, $iteck_desc = '')
{
	global $post;

	$html = '';
	$html .= '<div class="'.esc_attr($iteck_id).'_box description_box">';
		$html .= '<div class="left-part">';
			$html .= $iteck_label;
			if($iteck_desc) {
				$html .= '<span class="description">' . esc_attr($iteck_desc) . '</span>';
			}
		$html .='</div>';
		$html .= '<div class="right-part">';
		
			$html .= '<input name="' . esc_attr($iteck_id) . '" class="upload_field" id="iteck_upload" type="hidden" value="'.get_post_meta($post->ID,  $iteck_id, true).'" />';
			$html .= '<div class="multiple_images">';
			$iteck_val = explode(",",get_post_meta($post->ID,  $iteck_id, true));
			
			foreach ($iteck_val as $key => $value) {
				if(!empty($value)):
					$iteck_image_url = wp_get_attachment_url( $value );
					$html .='<div id='.esc_attr($value).'>';
						$html .= '<img class="upload_image_screenshort_multiple" src="'.$iteck_image_url.'" style="width:100px;" />';
						$html .= '<a href="javascript:void(0)" class="remove">'.__( 'Remove', 'iteck_plg' ).'</a>';
					$html .= '</div>';
				endif;
			}
			$html .= '</div>';
			$html .= '<input class="iteck_upload_button_multiple" id="iteck_upload_button_multiple" type="button" value="Browse" />'.__( ' Select Files', 'iteck_plg' );
					
		$html .='</div>';
	$html .= '</div>';
	echo sprintf( "%s", $html );
}

	if ( ! function_exists( 'iteck_meta_box_colorpicker' ) ) {
		function iteck_meta_box_colorpicker( $id, $label, $desc = '', $iteck_dependency = '' ) {
			global $post;
	        
			$dependency_attr = '';
			$dependency_arr = array();

			if( !empty($iteck_dependency) ){
				$val = array();
				$dependency_arr[] = 'data-element="'.$iteck_dependency['element'].'"';
				foreach ($iteck_dependency['value'] as $key => $value) {
					$val[] = $value; 
				}
				$dep_list = implode(",", $val);
				$dependency_arr[] = 'data-value="'.$dep_list.'"';
				$dependency_attr = implode(" ", $dependency_arr);
			}

			$html = '';
			$html .= '<div class="'.$id.'_box description_box"'.$dependency_attr.'>';
				$html .= '<div class="left-part">';
					$html .= $label;
					if($desc) {
						$html .= '<span class="description">' . $desc . '</span>';
					}
				$html .='</div>';
				$html .= '<div class="right-part">';
					$html .= '<input type="text" class="iteck-color-picker" id="' . $id . '" name="' . $id . '" value="' . get_post_meta($post->ID, $id, true) . '" />';
				$html .='</div>';
			$html .='</div>';
			echo $html;
		}
	}