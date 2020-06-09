<?php

function load_external_scripts() { // load external file
    if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
    wp_enqueue_script('plugins', get_template_directory_uri().'/js/plugins.js', array('jquery'), null, true);
    wp_enqueue_script('main', get_template_directory_uri().'/js/main.js', array('jquery'), null, true);
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/vendor/modernizr-2.8.3.min.js', array(), '2.8.3', false );
}

function my_jquery_enqueue() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js", false, null);
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'load_external_scripts');


//**********************************************************
//  Load styles
//**********************************************************

function adding_styles() {
    wp_enqueue_style(
        'style',
        get_template_directory_uri() . '/style.css',
        null,
        null, 'screen'
    );
    wp_enqueue_style(
        'print',
        get_template_directory_uri() . '/print.css',
        null,
        null, 'print'
    );
}

add_action( 'wp_enqueue_scripts', 'adding_styles' );


//**********************************************************
//  Allow wrapping other elements in a anchor tag in tiny_mce
//**********************************************************
add_filter('tiny_mce_before_init', 'modify_valid_children');
function modify_valid_children($settings) {
    $settings['valid_children'] = "+a[div|p|ul|ol|li|h1|h2|h3|h4|h5|h5|h6]";
    return $settings;
}


//**********************************************************
// Add custom meta boxes for home page
//**********************************************************
function wysiwyg_register_custom_meta_box() {
    add_meta_box(WYSIWYG_META_BOX_ID, __('Home page content', 'wysiwyg'), 'custom_wysiwyg', 'page');
}

function custom_wysiwyg($post) {
    echo "<h3>Box 1:</h3>";
    $content = get_post_meta($post->ID, 'box_1', true);
    wp_editor(htmlspecialchars_decode($content), 'box_1', array(
        "media_buttons" => true
    ));
    echo "<h3>Box 2:</h3>";
    $content = get_post_meta($post->ID, 'box_2', true);
    wp_editor(htmlspecialchars_decode($content), 'box_2', array(
        "media_buttons" => true
    ));
}

function custom_wysiwyg_save_postdata($post_id) {
    if (!empty($_POST['box_1'])) {
        $data = htmlspecialchars($_POST['box_1']);
        update_post_meta($post_id, 'box_1', $data);
    }
    if (!empty($_POST['box_2'])) {
        $data = htmlspecialchars($_POST['box_2']);
        update_post_meta($post_id, 'box_2', $data);
    }
}

add_action('save_post', 'custom_wysiwyg_save_postdata');


//**********************************************************
// Add page slug to body class
//**********************************************************
function add_slug_body_class($classes) {
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter('body_class', 'add_slug_body_class');


//**********************************************************
// Set up the grantees meta data
//********************************************************** 
add_action("admin_init", "grantees_meta_init");

function grantees_meta_init(){
    add_meta_box("grantees_meta", "Other information", "grantees_meta", "grantee", "normal", "high");
}

function grantees_meta() {
    global $post;
    $custom = get_post_custom($post->ID);
    $granteeWebSite = $custom["granteeWebSite"][0];
    $granteeLocation = $custom["granteeLocation"][0];
    $granteeGrantAmount = $custom["granteeGrantAmount"][0];
    $granteeShortDescription = $custom["granteeShortDescription"][0];
    $granteeGrantHighlightsDescription = $custom["granteeGrantHighlightsDescription"][0];
    ?>
    <SCRIPT TYPE="text/javascript">
        <!--
        // copyright 1999 Idocs, Inc. http://www.idocs.com
        // Distribute this script freely but keep this notice in place
        function numbersonly(myfield, e, dec) {
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;
            keychar = String.fromCharCode(key);
// control keys
            if ((key == null) || (key == 0) || (key == 8) ||
                (key == 9) || (key == 13) || (key == 27))
                return true;
// numbers
            else if ((("0123456789").indexOf(keychar) > -1))
                return true;
// decimal point jump
            else if (dec && (keychar == ".")) {
                myfield.form.elements[dec].focus();
                return false;
            }
            else
                return false;
        }
        //-->
    </SCRIPT>
    <div class="my_meta_control">
        <label>Web site:</label>
        <p>
            <input type="text" name="granteeWebSite" value="<?php echo $granteeWebSite; ?>"/>
            <span>Enter the web site of the organization. Include the "http://" part of the URL.</span>
        </p>
        <label>Location:</label>
        <p>
            <input type="text" name="granteeLocation" value="<?php echo $granteeLocation; ?>"/>
            <span>Enter the location of the organization (City, State).</span>
        </p>
        <label>Grant amount:</label>
        <p>
            <input type="text" name="granteeGrantAmount" value="<?php echo $granteeGrantAmount; ?>" onKeyPress="return numbersonly(this, event)"/>
            <span>Enter the amount of the grant.</span>
        </p>
        <?php
        $grantee_meta = get_post_meta($post->ID);
        ?>
        <p>
            <label for="programArea">Program Area:</label>
            <select name="programArea">
                <option value="" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], ''); ?>>
                    - Select -
                </option>
                ';
                <option value="democracy-and-civil-liberties" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'democracy-and-civil-liberties'); ?>>
                    Democracy and Civil Liberties
                </option>
                ';
                <option value="education-and-literacy" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'education-and-literacy'); ?>>
                    Education and Literacy
                </option>
                ';
                <option value="environment" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'environment'); ?>>
                    Environment
                </option>
                ';
                <option value="health-and-recreation" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'health-and-recreation'); ?>>
                    Health and Recreation
                </option>
                ';
                <option value="jewish-community" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'jewish-community'); ?>>
                    Jewish Community
                </option>
                ';
                <option value="reproductive-health-and-rights" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'reproductive-health-and-rights'); ?>>
                    Reproductive Health and Rights
                </option>
                ';
                <option value="sf-bay-area-institutions-and-projects" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'sf-bay-area-institutions-and-projects'); ?>>
                    SF Bay Area Institutions and Projects
                </option>
                ';
                <option value="special-projects-and-initiatives" <?php if (isset ($grantee_meta['programArea'])) selected($grantee_meta['programArea'][0], 'special-projects-and-initiatives'); ?>>
                    Special Projects and Initiatives
                </option>
                ';
            </select>
        </p>
        <p>
            <label for="typeOfSupport">Type of Support:</label>
            <select name="typeOfSupport">
                <option value="" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], ''); ?>>
                    - Select -
                </option>
                ';
                <option value="annual-grant" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'annual-grant'); ?>>
                    Annual Grant †
                </option>
                ';
                <option value="capital-support" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'capital-support'); ?>>
                    Capital Support
                </option>
                ';
                <option value="endowment-support" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'endowment-support'); ?>>
                    Endowment Support
                </option>
                ';
                <option value="general-support" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'general-support'); ?>>
                    General Support
                </option>
                ';
                <option value="project-support" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'project-support'); ?>>
                    Project Support
                </option>
                ';
                <option value="relief-efforts" <?php if (isset ($grantee_meta['typeOfSupport'])) selected($grantee_meta['typeOfSupport'][0], 'relief-efforts'); ?>>
                    Relief Efforts
                </option>
                ';
            </select>
        </p>
        <label>Grants database & recent grants description:</label>
        <p>
            <textarea name="granteeShortDescription"><?php echo $granteeShortDescription; ?></textarea>
            <span>Enter the short description for this organization which appears in the grants database and recent grants.</span>
        </p>
        <label style="margin-bottom:-30px;">Grant highlights description:</label>
        <p>
            <?php
            $content = html_entity_decode($granteeGrantHighlightsDescription); /* Please note the use of html_entity_decode */
            $settings = array('media_buttons' => false);
            wp_editor($content, 'granteeGrantHighlightsDescription', $settings);
            ?>
            <span>Enter the description for this organization if it appears on the grant highlights page.</span>
        </p>
    </div>  <?php
}

add_action('save_post', 'save_grantees_meta');

function save_grantees_meta() {
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post->ID;
    } else {
        update_post_meta($post->ID, 'granteeWebSite', $_POST['granteeWebSite']);
        update_post_meta($post->ID, 'granteeLocation', $_POST['granteeLocation']);
        update_post_meta($post->ID, 'granteeGrantAmount', $_POST['granteeGrantAmount']);
        update_post_meta($post->ID, 'granteeShortDescription', $_POST['granteeShortDescription']);
        update_post_meta($post->ID, 'granteeGrantHighlightsDescription', $_POST['granteeGrantHighlightsDescription']);
        update_post_meta($post->ID, 'programArea', $_POST['programArea']);
        update_post_meta($post->ID, 'typeOfSupport', $_POST['typeOfSupport']);
    }
}


//**********************************************************
// Set up the news item meta data
//**********************************************************

add_action("admin_init", "news_item_meta_init");

function news_item_meta_init() {
    add_meta_box("news_item_meta", "Other information", "news_item_meta", "news-item", "normal", "high");
}

function news_item_meta() {
    global $post;
    $custom = get_post_custom($post->ID);
    $newsItemPublication = $custom["newsItemPublication"][0];
    $newsItemLink = $custom["newsItemLink"][0];
    $newsItemLinkText = $custom["newsItemLinkText"][0];
    ?>
    <div class="my_meta_control">
        <label>Publication:</label>
        <p>
            <input type="text" name="newsItemPublication" value="<?php echo $newsItemPublication; ?>"/>
            <span>Enter the name of the publication for the news item.</span>
        </p>
        <label>Link text:</label>
        <p>
            <input type="text" name="newsItemLinkText" value="<?php echo $newsItemLinkText; ?>"/>
            <span>Enter the text for the link to the news item.</span>
        </p>
        <label>Link (URL):</label>
        <p>
            <input type="text" name="newsItemLink" value="<?php echo $newsItemLink; ?>"/>
        </p>
    </div>  <?php
}

add_action('save_post', 'save_news_item_meta');

function save_news_item_meta() {
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post->ID;
    } else {
        update_post_meta($post->ID, 'newsItemPublication', $_POST['newsItemPublication']);
        update_post_meta($post->ID, 'newsItemLinkText', $_POST['newsItemLinkText']);
        update_post_meta($post->ID, 'newsItemLink', $_POST['newsItemLink']);
    }
}


//**********************************************************
// Set up the press release meta data
//**********************************************************
add_action("admin_init", "press_release_meta_init");

function press_release_meta_init() {
    add_meta_box("press_release_meta", "Other information", "press_release_meta", "press-release", "normal", "high");
}

function press_release_meta() {
    global $post;
    $custom = get_post_custom($post->ID);
    $pressReleaseLink = $custom["pressReleaseLink"][0];
    $pressReleaseLinkText = $custom["pressReleaseLinkText"][0];
    ?>
    <div class="my_meta_control">
        <label>Press release link text:</label>
        <p>
            <input type="text" name="pressReleaseLinkText" value="<?php echo $pressReleaseLinkText; ?>"/>
            <span>Enter the text for the link to the press release.</span>
        </p>
        <label>Press release link:</label>
        <p>
            <input type="text" name="pressReleaseLink" value="<?php echo $pressReleaseLink; ?>"/>
        </p>
    </div>  <?php
}

add_action('save_post', 'save_press_release_meta');

function save_press_release_meta() {
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post->ID;
    } else {
        update_post_meta($post->ID, 'pressReleaseLinkText', $_POST['pressReleaseLinkText']);
        update_post_meta($post->ID, 'pressReleaseLink', $_POST['pressReleaseLink']);
    }
}


//**********************************************************
// Only add home page meta box to home page
//**********************************************************
function only_home_settings() {
    global $post;
    $frontpage_id = get_option('page_on_front');
    if ($post->ID != $frontpage_id):
        pages_meta_init();
    else:
        wysiwyg_register_custom_meta_box();
    endif;
}

add_action('add_meta_boxes', 'only_home_settings');


//**********************************************************
// Set up page meta boxes
//**********************************************************
function pages_meta_init() {
    add_meta_box("pages_meta", "Other information", "pages_meta", "page", "normal", "high");
}

function pages_meta() {
    global $post;
    $custom = get_post_custom($post->ID);
    $numberOfImages = $custom["numberOfImages"][0];
    ?>
    <div class="my_meta_control">
        <label>Number of recent grants to display in sidebar:</label>
        <p>
            <input type="text" name="numberOfImages" value="<?php echo $numberOfImages; ?>"/>
        </p>
    </div>  <?php
}

add_action('save_post', 'save_pages_meta');

function save_pages_meta() {
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post->ID;
    } else {
        update_post_meta($post->ID, 'numberOfImages', $_POST['numberOfImages']);
    }
}


//**********************************************************
// Hide some menu items in the admin
//**********************************************************

add_action('admin_menu', 'my_remove_menu_pages');

function my_remove_menu_pages() {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}


//**********************************************************
// Set up menus
//**********************************************************

add_action('init', 'register_my_menus');

function register_my_menus() {
    register_nav_menus(
        array(
            'main-navigation' => __('Main Navigation'),
            'footer-navigation' => __('Footer Navigation')
        )
    );
}


//**********************************************************
// Register widgetized areas
//**********************************************************

function widgets_init() {
    register_sidebar(array(
        'name' => 'Left sidebar',
        'id' => 'left_sidebar',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'widgets_init');


//**********************************************************
// Set up press releases
//**********************************************************

add_action('init', 'press_release_register');

function press_release_register() {
    $labels = array(
        'name' => _x('Press Releases', 'post type general name'),
        'singular_name' => _x('Press Releases', 'post type singular name'),
        'add_new' => _x('Add New', 'Press Release'),
        'add_new_item' => __('Add New Press Release'),
        'edit_item' => __('Edit Press Release'),
        'new_item' => __('New Press Release'),
        'view_item' => __('View Press Release'),
        'search_items' => __('Search Press Release'),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'revisions')
    );
    register_post_type('press-release', $args);
    flush_rewrite_rules();
}

add_action('init', 'news_item_register');

function news_item_register() {
    $labels = array(
        'name' => _x('News Items', 'post type general name'),
        'singular_name' => _x('News Items', 'post type singular name'),
        'add_new' => _x('Add New', 'News Item'),
        'add_new_item' => __('Add New News Item'),
        'edit_item' => __('Edit News Item'),
        'new_item' => __('New News Item'),
        'view_item' => __('View News Item'),
        'search_items' => __('Search News Item'),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'revisions','editor')
    );
    register_post_type('news-item', $args);
    flush_rewrite_rules();
}


//**********************************************************
// Hide view post and preview button for certain post types
//**********************************************************

function posttype_admin_css() {
    global $post_type;
    if ($post_type == 'news-item' || $post_type == 'press-release' || $post_type == 'grantee' || $post_type == 'billboard') {
        echo '<style type="text/css">#edit-slug-box,#view-post-btn,#preview-action,.updated p a{display: none;}</style>';
    }
}

add_action('admin_head', 'posttype_admin_css');


//**********************************************************
// Add image sizes
//**********************************************************

add_image_size('billboard', 2031, 549);
add_image_size('highlight', 348, 304);
add_image_size('featured', 388, 339);


//**********************************************************
// Set up home page billboards
//**********************************************************

add_action('init', 'billboard_register');

function billboard_register() {
    $labels = array(
        'name' => _x('Billboards', 'post type general name'),
        'singular_name' => _x('Billboards', 'post type singular name'),
        'add_new' => _x('Add New', 'Billboard'),
        'add_new_item' => __('Add New Billboard'),
        'edit_item' => __('Edit Billboard'),
        'new_item' => __('New Billboard'),
        'view_item' => __('View Billboard'),
        'search_items' => __('Search Billboard'),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'revisions', 'thumbnail')
    );
    register_post_type('billboard', $args);
    flush_rewrite_rules();
}


//**********************************************************
// Set up grantees
//**********************************************************

add_action('init', 'grantee_register');

function grantee_register() {
    $labels = array(
        'name' => _x('Grantees', 'post type general name'),
        'singular_name' => _x('Grantees', 'post type singular name'),
        'add_new' => _x('Add New', 'Grantee'),
        'add_new_item' => __('Add New Grantee'),
        'edit_item' => __('Edit Grantee'),
        'new_item' => __('New Grantee'),
        'view_item' => __('View Grantee'),
        'search_items' => __('Search Grantee'),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'revisions')
    );
    register_post_type('grantee', $args);
    flush_rewrite_rules();
}


//**********************************************************
// Set up grantees featured location taxonomy
//**********************************************************

add_action('init', 'create_featured_location_taxonomies', 0);

function create_featured_location_taxonomies() {
    $labels = array(
        'name' => _x('Featured Locations', 'taxonomy general name'),
        'singular_name' => _x('Featured Location', 'taxonomy singular name'),
        'search_items' => __('Search Featured Locations'),
        'all_items' => __('All Featured Locations'),
        'parent_item' => __('Parent Featured Location'),
        'parent_item_colon' => __('Parent Featured Location:'),
        'edit_item' => __('Edit Featured Location'),
        'update_item' => __('Update Featured Location'),
        'add_new_item' => __('Add New Featured Location'),
        'new_item_name' => __('New Featured Location Name'),
        'menu_name' => __('Featured Locations'),
    );
    register_taxonomy('grantee-featured-location', array('grantee'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'grantee-featured-location'),
    ));
}

//**********************************************************
// We are using the MultiPostThumbnails plugin. Set up
// the various featured images.
//**********************************************************

if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(array(
            'label' => 'Grantee Logo',
            'id' => 'grantee-logo',
            'post_type' => 'grantee'
        )
    );
}

if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(array(
            'label' => 'Highlight/Featured Image',
            'id' => 'grantee-highlight-image',
            'post_type' => 'grantee'
        )
    );
}

if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(array(
            'label' => 'Hero Image',
            'id' => 'grantee-hero-image',
            'post_type' => 'grantee'
        )
    );
}

//**********************************************************
// Add styles to the admin editor
//**********************************************************

// Update CSS within in Admin
function admin_style() {
    wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}

add_action('admin_enqueue_scripts', 'admin_style');

add_filter('posts_where', 'wpse18703_posts_where', 10, 2);

function wpse18703_posts_where($where, &$wp_query)
{
    global $wpdb;
    if ($wpse18703_title = $wp_query->get('wpse18703_title')) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(like_escape($wpse18703_title)) . '%\'';
        $where .= ' AND ' . $wpdb->posts . '.post_type =\'grantee\'';
    }
    return $where;
}

//**********************************************************
// Set up home recent grants shortcode
//**********************************************************

function home_recent_grants_sc($atts, $content = null) {
    $output = '';
    extract(shortcode_atts(array(), $atts));
    $args = array(
        'post_type' => 'grantee',
        'post_count' => 4,
        'posts_per_page' => 4,
        'orderby' => 'rand',
        'order' => 'ASC',
        'grantee-featured-location' => 'recent-grants-sidebar'
    );
    query_posts($args);
    if (have_posts()) : while (have_posts()) : the_post();
        global $post;
$caption = '';
                $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( 'grantee', 'grantee-highlight-image', $post->ID, 'featured' );
                $post_thumbnail_post = get_post( $post_thumbnail_id );
                $caption = trim( strip_tags( $post_thumbnail_post->post_excerpt ) );
                    if (strlen($caption) > 0) {
                        $caption = '<span class="photo-credit">Photo credit: ' . $caption . '</span>';
                    } 
        $output .= '    <li><a href="/our-grants/#' . $post->post_name . '">
            <figure><div class="img-wrapper">' . $caption . '<img alt="' . get_the_title() . '" src="' . MultiPostThumbnails::get_post_thumbnail_url(get_post_type(), 'grantee-highlight-image', $post->ID, 'full') . '" /></div>
                <figcaption><span class="org-name">' . get_the_title() . '</span></figcaption>
            </figure>
        </a>
    </li>';
    endwhile;
    else:
        $output .= "nothing found.";
    endif;
    wp_reset_query();
    return $output;
}
add_shortcode("home_recent_grants", "home_recent_grants_sc");
?>