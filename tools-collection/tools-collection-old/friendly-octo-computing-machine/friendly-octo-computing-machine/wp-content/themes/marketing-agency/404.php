<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Marketing Agency
 */

get_header(); ?>

<main id="maincontent" role="main" class="content-vw">
	<div class="container">
		<div class="page-content">
	    	<h1><?php echo esc_html(get_theme_mod('marketing_agency_404_page_title',__('404 Not Found','marketing-agency')));?></h1>
			<p class="text-404"><?php echo esc_html(get_theme_mod('marketing_agency_404_page_content',__('Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.','marketing-agency')));?></p>
			<?php if( get_theme_mod('marketing_agency_404_page_button_text','GO BACK') != ''){ ?>
				<div class="more-btn">
			        <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(get_theme_mod('marketing_agency_404_page_button_text',__('GO BACK','marketing-agency')));?><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('marketing_agency_404_page_button_text',__('GO BACK','marketing-agency')));?></span></a>
			    </div>
			<?php } ?>
		</div>
		<div class="clearfix"></div>
	</div>
</main>

<?php get_footer(); ?>