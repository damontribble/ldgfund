<?php
$numberOfImagesToDisplay;
?>
<?php if (is_page_template('home.php')) { ?>
    <h2>Recent Grants</h2>
<?php } ?>

<ul class="recent-grants-slider">
    <?php if (is_page_template('home.php')) {
        // On the home page, show the recent grants shortcode.
        echo do_shortcode('[home_recent_grants]'); ?>

    <?php } else {
        // On all other pages, show recent grants as below.
        remove_all_filters('posts_orderby');
        // Set the number of recent grants images to show.
        $numberOfImagesToDisplay = 4;
        if (get_post_meta($post->ID, 'numberOfImages', TRUE) != '') {
            $numberOfImagesToDisplay = get_post_meta($post->ID, 'numberOfImages', TRUE);
        }
        // Get all the recent grants set to show up in the recent grants sidebar.
        $args = array(
            'post_type' => 'grantee',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'post_count' => 4,
            'order' => 'ASC',
            'grantee-featured-location' => 'recent-grants-sidebar'
        );

        query_posts($args);
        if (have_posts()) : while (have_posts()) : the_post();
            global $post;
            // Get the grant highlight image.
            $featuredImage = MultiPostThumbnails::get_post_thumbnail_url('grantee', 'grantee-highlight-image', $post->ID, 'featured');
            // Check to see if the grantee has a web link specified.
            $featuredImageHasLink = false;
            if (get_post_meta($post->ID, 'granteeWebSite', TRUE) != '') {
                $featuredImageHasLink = true;
            }
            ?>

            <li>
                <figure><?php 
                $caption = 'test';
                $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( 'grantee', 'grantee-highlight-image', $post->ID, 'featured' );
                $post_thumbnail_post = get_post( $post_thumbnail_id );
                $caption = trim( strip_tags( $post_thumbnail_post->post_excerpt ) );
                    if (strlen($caption) > 0) {
                        $caption = '<span class="photo-credit">Photo credit: ' . $caption . '</span>';
                    } echo '<div class="img-wrapper">'; echo $caption; ?><img alt="<?php echo $post->post_title; ?>" src="<?php echo $featuredImage; ?>"/></div>
                    <figcaption><span class="org-name"><?php if ($featuredImageHasLink) { ?>
                            <a href="<?php echo get_post_meta($post->ID, 'granteeWebSite', TRUE); ?>" target="_blank"><?php } ?><?php echo $post->post_title; ?><?php if ($featuredImageHasLink) { ?></a><?php } ?></span>
                    </figcaption>
                </figure>

            </li>
        <?php endwhile;
        else:

        endif;


        wp_reset_query();
    } ?>
</ul>
<style type="text/css">
    @media screen and (min-width: 62.5em) {
    <?php
            // Hide recent grant images.
    if ($numberOfImagesToDisplay == 1) {
    echo '.recent-grants-slider li:nth-child(2), .recent-grants-slider li:nth-child(3), .recent-grants-slider li:nth-child(4){ display:none; } ';
    }

               if ($numberOfImagesToDisplay == 2) {
    echo '.recent-grants-slider li:nth-child(3), .recent-grants-slider li:nth-child(4){ display:none; } ';
    }

                           if ($numberOfImagesToDisplay == 3) {
    echo '.recent-grants-slider li:nth-child(4){ display:none; } ';
    }



?>

    }
</style>