<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <?php wp_head();
     ?>
</head>
<body <?php body_class(); ?>>
<header>
    <a href="/"><img src="/wp-content/themes/ldgfund/img/logo_horizontal.svg" class="logo-horizontal" alt="LDG Fund Logo" /></a>
    <a href="/"><img src="/wp-content/themes/ldgfund/img/logo_stacked.svg" class="logo-stacked" alt="LDG Fund Logo" /></a>
    <a href="https://www.grantrequest.com/SID_1596/Default.asp?SA=&FID=&SESID=57885&RL=" class="grantee-portal-login" target="_blank">Grantee Portal Login</a>
    <a href="#" class="toggle-main-menu toggler">Menu</a>
</header>
<nav class="top-nav">
    <?php wp_nav_menu(array('theme_location' => 'main-navigation', 'container' => '')); ?>
</nav>
<div class="content">
    <div class="banner-wrapper">
        <div class="banner">
            <?php if (is_front_page()) {
                // Get all the billboards custom posts and display them in a slider on the home page
                $args = array(
                    'post_type' => 'billboard',
                    'nopaging' => true,
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                );
                query_posts($args);
                if (have_posts()) : while (have_posts()) : the_post();
                    global $post;
                    $photoCredit = '';
                    if (strlen(trim(get_the_post_thumbnail_caption($post->ID))) > 0) {
                        $photoCredit = '<span class="photo-credit">Photo credit: ' . get_the_post_thumbnail_caption($post->ID) . '</span>';
                    }
                    echo '<div class="slide">
                            <figure><div class="img-wrapper">' . get_the_post_thumbnail($post->ID, 'full')
                                   . $photoCredit . '</div><figcaption>' .
                                get_the_content()
                                . '</figcaption>' .
                            '</figure>' . 
                        '</div>';
                endwhile;
                endif;
            } else {
                remove_all_filters('posts_orderby');
                // Show grantee hero images at random on all other pages
                $args = array(
                    'post_type' => 'grantee',
                    'nopaging' => true,
                    'posts_per_page' => 1,
                    'orderby' => 'rand',
                    'grantee-featured-location' => 'hero-images'
                );
                query_posts($args);
                if (have_posts()) : while (have_posts()) : the_post();
                    global $post;
                    $heroImage = MultiPostThumbnails::get_post_thumbnail_url('grantee', 'grantee-hero-image', $post->ID, 'full');
                    // Check to see if a link was specified for the grantee
                    $heroImageHasLink = false;
                    if (get_post_meta($post->ID, 'granteeWebSite', TRUE) != '') {
                        $heroImageHasLink = true;
                    }
                    ?>
                    <div class="slide">
                        <figure>

                            <?php $caption = '';
                $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( 'grantee', 'grantee-hero-image', $post->ID, 'full' );
                $post_thumbnail_post = get_post( $post_thumbnail_id );
                $caption = trim( strip_tags( $post_thumbnail_post->post_excerpt ) );
                    if (strlen($caption) > 0) {
                        $caption = '<span class="photo-credit">Photo credit: ' . $caption . '</span>';
                    } echo '<div class="img-wrapper">'; echo $caption; ?><img alt="<?php echo $post->post_title; ?>" src="<?php echo $heroImage; ?>"/></div>
                            <figcaption>
                                <p><?php if ($heroImageHasLink) { ?>
                                    <a href="<?php echo get_post_meta($post->ID, 'granteeWebSite', TRUE); ?>" target="_blank"><?php } ?><?php echo $post->post_title; ?><?php if ($heroImageHasLink) { ?></a><?php } ?>
                                </p>
                            </figcaption>
                        </figure>
                    </div>
                <?php endwhile;
                else:
                endif;
                wp_reset_query();
            } ?>
        </div>
    </div>
    <div class="body-content">