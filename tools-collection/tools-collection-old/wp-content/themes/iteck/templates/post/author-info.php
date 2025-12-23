
<!--Author info--> 
<div class="iteck-author-info">
    <div class="iteck-author-avatar">
        <?php echo get_avatar( get_the_author_meta( 'ID' )); ?>
    </div>
    <div class="iteck-author-info-content"> 
        <div class="iteck-author-info-title">
            <div class="iteck-author-name">
                <?php the_author_posts_link(); ?>
            </div>
            <div class="iteck-author-role">

                <?php
                    $authorID = get_the_author_meta('ID');
                    $theAuthorDataRoles = get_userdata($authorID);
                    $theRolesAuthor = $theAuthorDataRoles -> roles;
                    echo implode(',',$theRolesAuthor);
                ?>
            </div>
        </div>
        <div class="iteck-author-info-text">
            <?php the_author_meta( 'description' ) ?>
        </div>

        <div class="iteck-author-social">
            <?php if ( class_exists('Iteck_User_Social_Profiles')){?>
                <?php
                    $prefix_name_url = get_the_author_meta( 'name' );
                    $prefix_twitter_url = get_the_author_meta( 'twitter' );
                    $prefix_facebook_url = get_the_author_meta( 'facebook' );
                    $prefix_instagram_url = get_the_author_meta( 'instagram' );
                    $prefix_pinterest_url = get_the_author_meta( 'pinterest' );
                ?>
                <?php if ( ! empty( $prefix_twitter_url ) ) : ?><a href="<?php the_author_meta( 'twitter' ) ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
                <?php if ( ! empty( $prefix_facebook_url ) ) : ?><a href="<?php the_author_meta( 'facebook' ) ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
                <?php if ( ! empty( $prefix_instagram_url ) ) : ?><a href="<?php the_author_meta( 'instagram' ) ?>"><span class="fa fa-instagram"></span></a><?php endif; ?>
                <?php if ( ! empty( $prefix_pinterest_url ) ) : ?><a href="<?php the_author_meta( 'pinterest' ) ?>"><span class="fa fa-pinterest"></span></a><?php endif; ?>
            <?php } ?>
        </div>
    </div>
</div>