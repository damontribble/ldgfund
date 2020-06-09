<?php /* Template Name: Press Releases */ ?>
<?php get_header(); ?>
    <div class="side-nav">
        <?php wp_nav_menu(array('menu' => 'main-navigation', 'container' => false)); ?>
    </div>
    <div class="content-main">
        <?php
        // Get the intro content for the page
        if (have_posts()) : while (have_posts()) : the_post();
            the_content();
        endwhile;
        else:
        endif;
        // Get the press releases
        $myPosts = new WP_Query();
        $myPosts->query('post_type=press-release&nopaging=true');
        while ($myPosts->have_posts()) : $myPosts->the_post();
            ?>
            <div class="press-release-entry">
                <p><?php the_date('F j, Y'); ?><br><strong><?php the_title(); ?></strong></p>
                <p><?php echo get_the_content(); ?>
                    <br><span class="file">
<strong><a href="<?php echo get_post_meta($post->ID, 'pressReleaseLink', TRUE); ?>" target="file_popup">
        <?php echo get_post_meta($post->ID, 'pressReleaseLinkText', TRUE); ?></a></strong>
</span>
                </p>
            </div>
        <?php endwhile;
        wp_reset_query(); ?>
        <p style="text-align:center;"><a href="#" id="loadMore">Load More</a></p>
    </div>
    <div class="recent-grants">
        <?php get_template_part('inc/recent-grants'); ?>
    </div>
<?php get_footer(); ?>