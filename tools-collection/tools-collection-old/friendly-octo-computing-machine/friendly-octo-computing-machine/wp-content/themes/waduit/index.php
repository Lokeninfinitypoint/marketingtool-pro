<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class('entry'); ?>>
  <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
  <div class="entry-content"><?php the_excerpt(); ?></div>
</article>
<?php endwhile; else: ?>
<p>No content yet.</p>
<?php endif; ?>
<?php get_footer(); ?>
