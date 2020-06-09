<?php /* Template Name: Grant Highlights */ ?>

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

    endif; ?>

    <?php

    // Now let's get all of the grantees whose featured location is "grant-highlights"

    $posts = query_posts(array(

        'post_type' => 'grantee',

        'nopaging' => true,

        'posts_per_page' => -1,

        'orderby' => 'date',

        'order' => 'DESC',

        'grantee-featured-location' => 'grant-highlights'

    )); ?>

    <table class="highlightedgrants">

        <tbody>

        <?php if (have_posts()) : while (have_posts()) : the_post();

            // Get the highlight image for the current grantee

            $granteeHighlightImage = MultiPostThumbnails::get_post_thumbnail_id('grantee', 'grantee-highlight-image', $post->ID);
$caption = '';
                $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( 'grantee', 'grantee-highlight-image', $post->ID, 'featured' );
                $post_thumbnail_post = get_post( $post_thumbnail_id );
                $caption = trim( strip_tags( $post_thumbnail_post->post_excerpt ) );
                    if (strlen($caption) > 0) {
                        $caption = '<span class="photo-credit">Photo credit: ' . $caption . '</span>';
                    } 

            ?>

            <tr>

                <td class="grant_image">

                    <div class="img-wrapper"><?php echo $caption; ?><img alt="<?php the_title(); ?>" src="<?php echo wp_get_attachment_url($granteeHighlightImage); ?>"></div>

                </td>

                <td class="grant_description clearfix">

                    <h2 class="grant_title"><?php the_title(); ?></h2>

                    <div class="grant_info">

                        <p class="grant_region"><?php if (get_post_meta($post->ID, 'granteeLocation', TRUE)): echo get_post_meta($post->ID, 'granteeLocation', TRUE); endif; ?></p>

                        <p class="grant_amount">Grant

                            Amount: <?php if (get_post_meta($post->ID, 'granteeGrantAmount', TRUE)): echo '$' . number_format(get_post_meta($post->ID, 'granteeGrantAmount', TRUE)); endif; ?></p>

                        <p class="grant_year">Grant Year: <?php the_time('Y') ?></p>

                    </div>

                    <?php if (get_post_meta($post->ID, 'granteeGrantHighlightsDescription', TRUE)): echo wpautop(get_post_meta($post->ID, 'granteeGrantHighlightsDescription', TRUE)); endif; ?>

                </td>

            </tr>

        <?php endwhile;

        else: ?>

        <?php endif;

        wp_reset_postdata(); ?>

        </tbody>

    </table>

</div>

<?php get_footer(); ?>







