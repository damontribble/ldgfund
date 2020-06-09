<?php /* Template Name: Page Without Sidebar */ ?>
<?php get_header(); ?>
    <div class="side-nav">
        <?php wp_nav_menu( array( 'menu' => 'main-navigation', 'container' => false ) ); ?>
    </div>
    <div class="content-main">
        <?php  if ( have_posts() ) : while ( have_posts() ) : the_post();
        the_content();
        endwhile; else:
        endif; ?>
    </div>
<?php get_footer(); ?>