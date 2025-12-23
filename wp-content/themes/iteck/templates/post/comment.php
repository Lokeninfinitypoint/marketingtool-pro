<?php  
    echo '<div class="iteck-comment">'; 
        if ( !post_password_required() ) { //only show comment if post not password protected

        // If comments are open or we have at least one comment, load up the comment template.
        if (  comments_open() || get_comments_number()) :
            comments_template();

            endif; 
        }
    echo '</div>';
?>