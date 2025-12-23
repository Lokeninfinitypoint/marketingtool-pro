<div class="img-pagination">
    <?php $iteck_prevPost = get_previous_post();
    if($iteck_prevPost) {?>
        <div class="pagi-nav-box previous">
            <?php $iteck_prevthumbnail = get_the_post_thumbnail($iteck_prevPost->ID, array(150,150) ); $iteck_prev = esc_html__('Previous post', 'iteck'); ?>
            <?php previous_post_link('%link',"<div class='img-pagi'><i class='lnr lnr-arrow-left'></i> 
            $iteck_prevthumbnail</div>  <div class='imgpagi-box'><p>$iteck_prev</p> <h4 class='pagi-title'>%title</h4> </div>"); ?> 
        </div>

    <?php } $iteck_nextPost = get_next_post();  
    if($iteck_nextPost) { ?>
        <div class="pagi-nav-box next">
            <?php $iteck_nextthumbnail = get_the_post_thumbnail($iteck_nextPost->ID, array(150,150) ); $iteck_next = esc_html__('Next post', 'iteck'); ?>
            <?php next_post_link('%link',"<div class='imgpagi-box'><p>$iteck_next</p><h4 class='pagi-title'>%title</h4> </div> <div class='img-pagi'><i class='lnr lnr-arrow-right'></i>
            $iteck_nextthumbnail</div> "); ?>
        </div>
    <?php } ?>
</div><!--/.img-pagination-->