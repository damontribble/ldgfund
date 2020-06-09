<?php /* Template Name: Recent Grants */ ?>
<?php get_header(); ?>
    <div class="side-nav">
        <?php wp_nav_menu( array( 'menu' => 'main-navigation', 'container' => false ) ); ?>
    </div>
    <div class="content-main">
        <?php
        // Get the intro content for the page
        if ( have_posts() ) : while ( have_posts() ) : the_post();
        the_content();
        endwhile; else:
        endif;
        // Loop through the recent grants categories. Display a header for that category and then the recent
        // grants for that category.
        // Get recent grants
        $posts = get_posts(array(
            'post_type' => 'grantee',
            'meta_key' => 'programArea', /* this has to be included to be able to order by it */
            'orderby' => 'meta_value title',
            'nopaging' => true,
            'order' => 'ASC',
            'grantee-featured-location' => 'recent-grants'
        ));
        $newProgramType = false;
        $programTypeHeader = '';
        $returnedPosts = count($posts);
        $i=1;
        foreach( $posts as $post ):
        echo '<!-- Total posts: ' . $returnedPosts . ' $i = ' . $i . ' -->';
            setup_postdata($post);
            if (get_post_meta($post->ID, 'programArea', TRUE) != $programTypeHeader) :
        if($programTypeHeader != ''):?>
        </tbody>
        </table>
        <?php        endif;

                $programTypeHeader = get_post_meta($post->ID, 'programArea', TRUE);
        echo '<h2>' . ucwords(str_replace('-',' ',$programTypeHeader)) . '</h2>';
        ?>
        <table class="recentgrants">
            <tbody>

            <?php

            endif;
                $granteeLogo = MultiPostThumbnails::get_post_thumbnail_id('grantee', 'grantee-logo', $post->ID);

                ?>
                <tr>
                    <td class="left">
                        <a href="<?php echo get_post_meta($post->ID,'granteeWebSite',TRUE); ?>">
                            <img alt="<?php the_title(); ?>" src="<?php echo wp_get_attachment_url($granteeLogo); ?>">
                        </a>
                    </td>
                    <td><?php $term_list = wp_get_post_terms($post->ID, 'type-of-support', array("fields" => "names")); ?>
                        <a id="<?php echo $post->post_name; ?>"></a>
                        <strong><?php the_title(); ?></strong>
                        <?php if(get_post_meta($post->ID,'granteeLocation',TRUE)) { ?>
                            <br><?php echo get_post_meta($post->ID,'granteeLocation',TRUE); ?>
                        <?php } if(get_post_meta($post->ID,'granteeGrantAmount',TRUE)) { ?>
                            <br><?php echo '$'.number_format(get_post_meta($post->ID,'granteeGrantAmount',TRUE)); ?>
                        <?php } if (get_post_meta($post->ID, 'typeOfSupport', TRUE)) {
                            echo  '<br><em>' . str_replace('And', 'and', ucwords(str_replace('-', ' ', get_post_meta($post->ID, 'typeOfSupport', TRUE)))) . '</em>';
                        } if(get_post_meta($post->ID,'granteeShortDescription',TRUE)) { ?>
                            <br><?php echo get_post_meta($post->ID,'granteeShortDescription',TRUE); ?>
                        <?php }  ?>
                    </td>
                </tr>
<?php
            if($i == count($posts)):?>
                </tbody>
                </table>
            <?php        endif;

$i++;
        endforeach; ?>
    </div>
<?php get_footer(); ?>