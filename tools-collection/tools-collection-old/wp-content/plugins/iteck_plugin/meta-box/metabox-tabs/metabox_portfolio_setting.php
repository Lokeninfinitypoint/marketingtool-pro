<?php
/**
 * Metabox For Portfolio Setting.
 *
 * @package iteck
 */
?>
<?php 
iteck_meta_box_dropdown('iteck_port_format',
				esc_html__('Choose Portfolio Format Here', 'iteck_plg'),
				array('port_standard' => esc_html__('Portfolio Gallery at Top', 'iteck_plg'),
					  'port_two' => esc_html__('Portfolio Gallery at Right', 'iteck_plg'),
					 )
			);
iteck_meta_box_dropdown('iteck_top_type',
				esc_html__('Choose Portfolio Format Here', 'iteck_plg'),
				array('top_content_slider' => esc_html__('Images Background', 'iteck_plg'),
					  'top_content_video' => esc_html__('Video Background', 'iteck_plg'),
					  'top_content_youtube' => esc_html__('Youtube Background', 'iteck_plg'),
					 )
			);
iteck_meta_box_upload('iteck_port_slider_setting', 
				esc_html__('Portfolio Top Image', 'iteck_plg'),
				esc_html__('Upload Your Top Image here. <br/>You still need to fill this if you choose the video/youtube background. <br/>
		So the image will replace the video/youtube background in touch devices. ', 'iteck_plg')
			);

iteck_meta_box_text('iteck_port_youtube_link',
				esc_html__('Youtube ID', 'iteck_plg'),
				esc_html__('Insert Youtube ID here. e.g EMy5krGcoOU', 'iteck_plg')
			);
iteck_meta_box_text('iteck_port_youtube_quality',
				esc_html__('Youtube Quality', 'iteck_plg'),
				esc_html__('Insert Youtube video quality here. You can input <b>small, medium, large, hd720, hd1080, highres</b>. Default value is <b>large</b>', 'iteck_plg')
			);
iteck_meta_box_text('iteck_port_video_link',
				esc_html__('Video Link', 'iteck_plg'),
				esc_html__('Insert the video directlink here. eg. https://www.quirksmode.org/html5/videos/big_buck_bunny.mp4', 'iteck_plg')
			);
iteck_meta_box_upload('iteck_gallery_port_img', 
				esc_html__('Portfolio Side Image', 'iteck_plg'),
				esc_html__('Upload Your Side Image here.', 'iteck_plg')
			);
iteck_meta_box_text('iteck_product_id', 
				esc_html__('Product Id', 'iteck_plg'),
			);


