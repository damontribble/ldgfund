<?php /* Template Name: Home Page */ ?>
<?php get_header();
wp_reset_query(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="row row-1">
        <div class="container">
            <section>
                <?php echo htmlspecialchars_decode(get_post_meta(get_the_ID(), 'box_1', true)); ?>
            </section>
        </div>
    </div>
    <div class="row row-2">
        <div class="container">
            <section class="news-updates">
                <?php echo htmlspecialchars_decode(get_post_meta(get_the_ID(), 'box_2', true)); ?>
            </section>
            <section class="recent-grants">
                <p class="section-links"><a href="/our-grants/grant-highlights/">Grant Highlights</a> |
                    <a href="/our-grants/grants-database/">Grants Database</a></p>
                <?php get_template_part('inc/recent-grants'); ?>
            </section>
        </div>
    </div>
<?php
endwhile;
endif;
get_footer(); ?>