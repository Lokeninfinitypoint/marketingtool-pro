<?php get_header(); ?>

<div class="ws-page-content">
    <div class="ws-container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
        ?>
                <article class="ws-page">
                    <header class="ws-page-header">
                        <h1><?php the_title(); ?></h1>
                        <?php if (get_the_excerpt()) : ?>
                            <p class="ws-page-excerpt"><?php the_excerpt(); ?></p>
                        <?php endif; ?>
                    </header>
                    
                    <div class="ws-page-body">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="ws-page-comments">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                </article>
        <?php
            endwhile;
        else :
        ?>
            <div class="ws-no-content">
                <h1>Page Not Found</h1>
                <p>Sorry, the page you are looking for doesn't exist.</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="ws-btn ws-btn-primary">Go Home</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>