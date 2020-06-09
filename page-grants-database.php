<?php /* Template Name: Grants Database */ ?>
<?php get_header();


?>
    <div class="side-nav">
        <?php wp_nav_menu(array('menu' => 'main-navigation', 'container' => false)); ?>
    </div>
    <div class="content-main" style="padding-bottom:0;">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php the_content();
    ?>
    <!-- Start Counting Loop -->
    </div>
    <?php
    function _toInt($str)
    {
        return (int)preg_replace("/([^0-9\\.])/i", "", $str);
    }

    ?>
    <!-- End Counting Loop -->




    <?php
    // Check the current order and flip it
    function oppositeOrder()
    {
        if ($_GET['order'] == 'asc') {
            return 'desc';
        } else {
            return 'asc';
        }
    }

    // Set defaults
    $orderby = 'title';
    $order = 'asc';
    $titleLinkOrder = 'desc';
    $amountLinkOrder = 'asc';
    $programAreaLinkOrder = 'asc';
    $typeOfSupportLinkOrder = 'asc';
    $locationLinkOrder = 'asc';
    $grantyearLinkOrder = 'asc';
    $metaKeyParam;
    $yearParam;
    $yearURLParam;
    $programParam;
    $supportParam;
    $sortOrder = $_GET['order'];
    $titleCellCurrent = true;

    // If orderby is specified in the querystring, set the appropriate orders
    // and orderbys. Alse set which cell is showing as current for the sort.
    if ($_GET['orderby']) {
        switch ($_GET['orderby']) {
            case 'title':
                $titleLinkOrder = oppositeOrder();
                $orderby = $_GET['orderby'];

                break;
            case 'grantee-grant-amount':
                $amountLinkOrder = oppositeOrder();
                $orderby = 'meta_value_num';
                $metaKeyParam = 'granteeGrantAmount';
                $titleLinkOrder = 'asc';
                $titleCellCurrent = false;
                $amountCellCurrent = true;
                break;
            case 'program-area':
                $programAreaLinkOrder = oppositeOrder();
                $orderby = 'meta_value';
                $metaKeyParam = 'programArea';
                $titleLinkOrder = 'asc';
                $titleCellCurrent = false;
                $programAreaCellCurrent = true;
                break;
            case 'type-of-support':
                $typeOfSupportLinkOrder = oppositeOrder();
                $orderby = 'meta_value';
                $metaKeyParam = 'typeOfSupport';
                $titleLinkOrder = 'asc';
                $titleCellCurrent = false;
                $typeOfSupportCellCurrent = true;
                break;
            case 'grantee-location':
                $locationLinkOrder = oppositeOrder();
                $orderby = 'meta_value';
                $metaKeyParam = 'granteeLocation';
                $titleLinkOrder = 'asc';
                $titleCellCurrent = false;
                $locationCellCurrent = true;
                break;

            case 'grantyear':
                $grantyearLinkOrder = oppositeOrder();
                $orderby = 'year';
                $metaKeyParam = 'granteeLocation';
                $titleLinkOrder = 'asc';
                $titleCellCurrent = false;
                $locationCellCurrent = false;
                $grantyearCellCurrent = true;
                break;
        }

    }

    // Get other paramters from querystring
    if ($_GET['order']) {
        $order = $_GET['order'];
    }

    if ($_GET['grantyear']) {
        $yearParam = $_GET['grantyear'];
        $yearParamArray = explode(" ", $yearParam);
        $yearURLParam = '&grantyear=' . $_GET['grantyear'];
    }

    if (isset($_GET['program'])) {
        $programParam = $_GET['program'];
        $programParamArray = explode(" ", $programParam);
        $programURLParam = '&program=' . $_GET['program'];
    }

    if (isset($_GET['support'])) {
        $supportParam = $_GET['support'];
        $supportParamArray = explode(" ", $supportParam);
        $supportURLParam = '&support=' . $_GET['support'];
    }

    // Get how many filters have been applied so we can display it in the
    // filter button on mobile.
    $filterCountText = '';
    $programCount = 0;
    $supportCount = 0;
    $yearCount = 0;
    if (count($programParamArray) > 0 && $programParamArray[0] != '') {
        $programCount = count($programParamArray);
    }
    if (count($supportParamArray) > 0 && $supportParamArray[0] != '') {
        $supportCount = count($supportParamArray);
    }
    if (count($yearParamArray) > 0 && $yearParamArray[0] != '') {
        $yearCount = count($yearParamArray);
    }
    $filterCount = $programCount + $supportCount + $yearCount;
    if ($filterCount > 0) {
        $filterCountText = '(' . $filterCount . ')';
    }

    // Set up a date query for the years selected in the filter
    $date_query = array(
        array(
            'year' => $yearParamArray,
            'compare' => 'IN'
        ),
    );


    // Set up the meta queries for program area and type of support
    $meta_query = array('relation' => 'AND');
    if (isset($_GET['program']) && $_GET['program'] != '') {
        $meta_query[] = array(
            'key' => 'programArea',
            'value' => $programParamArray,
            'compare' => 'IN',
        );
    }
    if (isset($_GET['support']) && $_GET['support'] != '') {
        $meta_query[] = array(
            'key' => 'typeOfSupport',
            'value' => $supportParamArray,
            'compare' => 'IN',
        );
    }

    // Set up the full wp_query
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $temp = $wp_query;
    $wp_query = null;
    $wp_query = new WP_Query();
    $postsPerPage = 25;
    $args = array(
        'post_type' => 'grantee',
        'posts_per_page' => $postsPerPage,
        'orderby' => $orderby,
        'order' => $order,
        'paged' => $paged,
        'date_query' => $date_query,
        'meta_query' => $meta_query,
        'meta_key' => $metaKeyParam

    );

    if (isset($_GET['title'])) {
        $titleURLParam = '&title=' . $_GET['title'];
        $orderby = 'date';
        $args['wpse18703_title'] = $_GET['title'];
    }
    $wp_query->query($args);

    // Set up another wp_query to count the amounts of the grants
    $loop = new WP_Query();
    $args = array(
        'post_type' => 'grantee',
        'orderby' => $orderby,
        'posts_per_page' => -1,
        'order' => $order,
        'date_query' => $date_query,
        'meta_query' => $meta_query,
        'meta_key' => $metaKeyParam
    );
    if (isset($_GET['title'])) {
        $args['wpse18703_title'] = $_GET['title'];
    }

    // Total up the amount of the found grants
    $loop->query($args);
    if ($loop->have_posts()) :
        while ($loop->have_posts()) : $loop->the_post();

            $amt = _toInt(get_post_meta($post->ID, 'granteeGrantAmount', TRUE));
            if ($amt) {
                $grants_total += $amt;
            }; endwhile;
        $grants_total = number_format($grants_total, 2);
    else: $grants_total = number_format(0, 2);
    endif;
    wp_reset_postdata();


    ?>


    <div id="grant-search-wrapper">
        <div class="grant-search-header">
            <div>
                <form id="new-query-form" method="GET">
                    <span class="desktop-only">Search For Grants:</span>
                    <input name="title" value="" type="text" id="title" placeholder="Enter search keywords here">
                    <input value="Search" class="submit" type="submit">
                    <input type="hidden" name="program" id="programParams">
                    <input type="hidden" name="support" id="supportParams">
                    <input type="hidden" name="grantyear" id="grantYearParams">
                    <?php
                    // Show the search within results checkbox if we are already displaying filtered results
                    if ((isset($_GET['program']) and $_GET['program'] != '') or (isset($_GET['support']) and $_GET['support'] != '') or (isset($_GET['grantyear']) and $_GET['grantyear'] != '')) {
                        ?>
                        <label for="search-within-results"><input value="true" type="checkbox" id="search-within-results" name="search-within-results">
                            Search within filtered results</label>
                    <?php } ?>
                </form>
            </div>
            <div id="grant-search-summary">
                <?php
                // Determine which grants you are showing currently (i.e. Grants 26-50)
                if ($paged > 1) {
                    $startingGrant = ($paged * $postsPerPage) - 1;
                    if ($wp_query->post_count % $postsPerPage) {
                        $endingGrant = $wp_query->post_count % $postsPerPage - 1 + $startingGrant;
                    } else {
                        $endingGrant = $startingGrant + $postsPerPage - 1;
                    }

                } else {
                    if ($wp_query->found_posts == 0) {
                        $startingGrant = 0;
                        $endingGrant = 0;
                    } elseif ($wp_query->found_posts < $postsPerPage) {
                        $startingGrant = 1;
                        $endingGrant = $startingGrant + $wp_query->found_posts - 1;

                    } elseif ($wp_query->found_posts > $postsPerPage) {
                        $startingGrant = 1;
                        $endingGrant = $startingGrant + $postsPerPage - 1;

                    }
                }
                ?>
                <div id="results">
                    <span class="grant-totals"> <span class="desktop-only">Showing <?php echo $startingGrant; ?>
                            -<?php echo $endingGrant; ?> of </span><?php echo $wp_query->found_posts; ?> grants equaling $<?php echo $grants_total; ?> </span>
                    <div class="desktop-pagination desktop-only"> <?php wp_pagenavi(); ?></div>
                    <span class="mobile-only filter-sort-buttons"><button id="filter-db">
                            Filter <?php echo $filterCountText; ?></button> <button id="sort-db">Sort
                        </button></span> <span class="mobile-only mobile-pagination"><label>Page: </label>
                        <?php $totalPages = $wp_query->max_num_pages; ?>
                        <select id="pagination-dropdown">
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>

                        </select></span></div>
            </div>
            <!-- grant-search-summary -->
            <?php
            // If we are currently looking at filtered results, show the current selections as links.
            // When these links are clicked, that filter is removed.
            if ((isset($_GET['program']) and $_GET['program'] != '') or (isset($_GET['support']) and $_GET['support'] != '') or (isset($_GET['grantyear']) and $_GET['grantyear'] != '')) {
                ?>
                <div class="current-filters">
                    <p>Your Selections:
                        <?php
                        $programRemoveLinks = '';
                        if (isset($_GET['program']) and $_GET['program'] != '') {
                            $selectedPrograms = explode(" ", $_GET['program']);
                            foreach ($selectedPrograms as &$program) {
                                $linkText = str_replace('Sf', 'SF', str_replace('And', 'and', ucwords(str_replace('-', ' ', $program))));
                                echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="program" data-filter-value="' . $program . '">X</a></span>';
                            }
                        }

                        $supportRemoveLinks = '';
                        if (isset($_GET['support']) and $_GET['support'] != '') {
                            $selectedSupports = explode(" ", $_GET['support']);
                            foreach ($selectedSupports as &$support) {
                                $linkText = str_replace('And', 'and', ucwords(str_replace('-', ' ', $support)));
                                echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="support" data-filter-value="' . $support . '">X</a></span>';
                            }
                        }

                        $grantyearRemoveLinks = '';

                        if (isset($_GET['grantyear']) and $_GET['grantyear'] != '') {
                            $selectedGrantYears = explode(" ", $_GET['grantyear']);
                            foreach ($selectedGrantYears as &$grantyear) {

                                $linkText = ucwords(str_replace("-", " ", $grantyear));

                                echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="grantyear" data-filter-value="' . $grantyear . '">X</a></span>';
                            }
                        }
                        ?>
                    </p>
                </div>
            <?php } ?>
        </div>
        <div class="mobile-filters">
            <div class="mobile-filters-header">
                <a class="remove-all-filters">Remove all filters</a>
                <a class="close-mobile-filters">Close</a>
                <?php
                // If we are currently looking at filtered results, show the current selections as links.
                // When these links are clicked, that filter is removed.
                if ((isset($_GET['program']) and $_GET['program'] != '') or (isset($_GET['support']) and $_GET['support'] != '') or (isset($_GET['grantyear']) and $_GET['grantyear'] != '')) {
                    ?>
                    <div class="current-filters">
                        <p>
                            <?php
                            $programRemoveLinks = '';
                            if (isset($_GET['program']) and $_GET['program'] != '') {
                                $selectedPrograms = explode(" ", $_GET['program']);
                                foreach ($selectedPrograms as &$program) {
                                    $linkText = ucwords(str_replace("-", " ", $program));
                                    echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="program" data-filter-value="' . $program . '">X</a></span>';
                                }
                            }
                            $supportRemoveLinks = '';
                            if (isset($_GET['support']) and $_GET['support'] != '') {
                                $selectedSupports = explode(" ", $_GET['support']);
                                foreach ($selectedSupports as &$support) {
                                    $linkText = ucwords(str_replace("-", " ", $support));
                                    echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="support" data-filter-value="' . $support . '">X</a></span>';
                                }
                            }
                            $grantyearRemoveLinks = '';

                            if (isset($_GET['grantyear']) and $_GET['grantyear'] != '') {
                                $selectedGrantYears = explode(" ", $_GET['grantyear']);
                                foreach ($selectedGrantYears as &$grantyear) {
                                    $linkText = ucwords(str_replace("-", " ", $grantyear));
                                    echo '<span>' . $linkText . ' <a class="remove-filter" data-filter-type="grantyear" data-filter-value="' . $grantyear . '">X</a></span>';
                                }
                            }
                            ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
            <a class="filter-link">Program Area</a>
            <div class="filter-values" data-filter-type="program">
                <ul>
                    <li><label><input type="checkbox" value="democracy-and-civil-liberties" data-filter-type="program">
                            Democracy and Civil Liberties</label></li>
                    <li><label><input type="checkbox" value="education-and-literacy" data-filter-type="program">
                            Education and Literacy</label></li>
                    <li><label><input type="checkbox" value="environment" data-filter-type="program">
                            Environment</label></li>
                    <li><label><input type="checkbox" value="health-and-recreation" data-filter-type="program"> Health
                            and Recreation</label></li>
                    <li><label><input type="checkbox" value="jewish-community" data-filter-type="program"> Jewish
                            Community</label></li>
                    <li><label><input type="checkbox" value="reproductive-health-and-rights" data-filter-type="program">
                            Reproductive Health and Rights</label></li>
                    <li>
                        <label><input type="checkbox" value="sf-bay-area-institutions-and-projects" data-filter-type="program">
                            SF Bay Area Institutions and Projects</label></li>
                    <li>
                        <label><input type="checkbox" value="special-projects-and-initiatives" data-filter-type="program">
                            Special Projects and Initiatives</label></li>
                </ul>
            </div>
            <a class="filter-link">Type of Support</a>
            <div class="filter-values" data-filter-type="support">
                <ul>
                    <li><label><input type="checkbox" value="annual-grant" data-filter-type="support"> Annual
                            Grant</label></li>
                    <li><label><input type="checkbox" value="capital-support" data-filter-type="support"> Capital
                            Support</label></li>
                    <li><label><input type="checkbox" value="endowment" data-filter-type="support"> Endowment</label>
                    </li>
                    <li><label><input type="checkbox" value="general-support" data-filter-type="support"> General
                            Support</label></li>
                    <li><label><input type="checkbox" value="project-support" data-filter-type="support"> Project
                            Support</label></li>
                    <li><label><input type="checkbox" value="relief-efforts" data-filter-type="support"> Relief Efforts</label>
                    </li>
                </ul>
            </div>
            <a class="filter-link">Year</a>
            <div class="filter-values" data-filter-type="grantyear">
                <ul>
                    <?php
                    for ($i = 2008; $i <= date('Y'); $i++) {

                        echo '<li><label><input type="checkbox" value="' . $i . '" data-filter-type="grantyear"> ' . $i . '</label></li>' . "\n";
                    }
                    ?>
                </ul>

            </div>
        </div>
        <div class="mobile-sort">
            <div class="mobile-sort-header clearfix">
                <a class="close-mobile-sort">Close</a>
            </div>
            <ul>
                <li <?php if ($titleCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=title&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Organization
                        Name: A-Z</a></li>
                <li <?php if ($titleCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=title&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Organization
                        Name: Z-A</a></li>
                <li <?php if ($amountCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantee-grant-amount&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Grant
                        Amount: Low-High</a></li>
                <li <?php if ($amountCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantee-grant-amount&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Grant
                        Amount: High-Low</a></li>
                <li <?php if ($programAreaCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=program-area&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Program
                        Area: A-Z</a></li>
                <li <?php if ($programAreaCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=program-area&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Program
                        Area: Z-A</a></li>
                <li <?php if ($typeOfSupportCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=type-of-support&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Type
                        of Support: A-Z</a></li>
                <li <?php if ($typeOfSupportCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=type-of-support&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Type
                        of Support: Z-A</a></li>
                <li <?php if ($locationCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantee-location&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Location:
                        A-Z</a></li>
                <li <?php if ($locationCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantee-location&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Location:
                        Z-A</a></li>
                <li <?php if ($grantyearCellCurrent and $_GET['order'] == 'asc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantyear&order=asc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Year:
                        Early-Late</a></li>
                <li <?php if ($grantyearCellCurrent and $_GET['order'] == 'desc') {
                    echo 'class="current"';
                } ?>>
                    <a href="<?php echo '?orderby=grantyear&order=desc' . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Year:
                        Late-Early</a></li>
            </ul>
        </div>
        <?php if (have_posts()) : ?>
            <table id="grant-search-table">
                <tbody>
                <tr>
                    <th <?php if ($titleCellCurrent) {
                        echo 'class="current"';
                    } ?>>Organization
                        <a href="<?php echo '?orderby=title&order=' . $titleLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>" class="<?php echo $sortOrder; ?> sort-link">Sort</a>
                    </th>
                    <th style="width:120px;" <?php if ($amountCellCurrent) {
                        echo 'class="current"';
                    } ?>>Grant Amount
                        <a class="<?php echo $sortOrder; ?> sort-link" href="<?php echo '?orderby=grantee-grant-amount&order=' . $amountLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Sort</a>
                    </th>
                    <th <?php if ($programAreaCellCurrent) {
                        echo 'class="current"';
                    } ?>>Program Area
                        <a class="<?php echo $sortOrder; ?> sort-link" href="<?php echo '?orderby=program-area&order=' . $programAreaLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Sort</a><a class="filter-link">Filter</a>

                        <div class="filter-values" data-filter-type="program">
                            <ul>
                                <li>
                                    <label><input type="checkbox" value="democracy-and-civil-liberties" data-filter-type="program">
                                        Democracy and Civil Liberties</label></li>
                                <li>
                                    <label><input type="checkbox" value="education-and-literacy" data-filter-type="program">
                                        Education and Literacy</label></li>
                                <li><label><input type="checkbox" value="environment" data-filter-type="program">
                                        Environment</label></li>
                                <li>
                                    <label><input type="checkbox" value="health-and-recreation" data-filter-type="program">
                                        Health and Recreation</label></li>
                                <li><label><input type="checkbox" value="jewish-community" data-filter-type="program">
                                        Jewish Community</label></li>
                                <li>
                                    <label><input type="checkbox" value="reproductive-health-and-rights" data-filter-type="program">
                                        Reproductive Health and Rights</label></li>
                                <li>
                                    <label><input type="checkbox" value="sf-bay-area-institutions-and-projects" data-filter-type="program">
                                        SF Bay Area Institutions and Projects</label></li>
                                <li>
                                    <label><input type="checkbox" value="special-projects-and-initiatives" data-filter-type="program">
                                        Special Projects and Initiatives</label></li>
                            </ul>
                            <p class="apply-filters"><a>Apply All Filters</a></p>

                        </div>
                    </th>
                    <th <?php if ($typeOfSupportCellCurrent) {
                        echo 'class="current"';
                    } ?>>Type of Support
                        <a class="<?php echo $sortOrder; ?> sort-link" href="<?php echo '?orderby=type-of-support&order=' . $typeOfSupportLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Sort</a><a class="filter-link">Filter</a>
                        <div class="filter-values" data-filter-type="support">
                            <ul>
                                <li><label><input type="checkbox" value="annual-grant" data-filter-type="support">
                                        Annual Grant</label></li>
                                <li><label><input type="checkbox" value="capital-support" data-filter-type="support">
                                        Capital Support</label></li>
                                <li><label><input type="checkbox" value="endowment" data-filter-type="support">
                                        Endowment</label></li>
                                <li><label><input type="checkbox" value="general-support" data-filter-type="support">
                                        General Support</label></li>
                                <li><label><input type="checkbox" value="project-support" data-filter-type="support">
                                        Project Support</label></li>
                                <li><label><input type="checkbox" value="relief-efforts" data-filter-type="support">
                                        Relief Efforts</label></li>
                            </ul>
                            <p class="apply-filters"><a>Apply All Filters</a></p>
                        </div>
                    </th>
                    <th <?php if ($locationCellCurrent) {
                        echo 'class="current"';
                    } ?>> Location
                        <a class="<?php echo $sortOrder; ?> sort-link" href="<?php echo '?orderby=grantee-location&order=' . $locationLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Sort</a>
                    </th>

                    <th <?php if ($grantyearCellCurrent) {
                        echo 'class="current"';
                    } ?>>Year
                        <a class="<?php echo $sortOrder; ?> sort-link" href="<?php echo '?orderby=grantyear&order=' . $grantyearLinkOrder . $yearURLParam . $programURLParam . $supportURLParam . $titleURLParam; ?>">Sort</a><a class="filter-link">Filter</a>
                        <div class="filter-values" data-filter-type="grantyear">
                            <ul>
                                <?php
                                for ($i = 2008; $i <= date('Y'); $i++) {

                                    echo '<li><label><input type="checkbox" value="' . $i . '" data-filter-type="grantyear"> ' . $i . '</label></li>' . "\n";
                                }
                                ?>
                            </ul>
                            <p class="apply-filters"><a>Apply All Filters</a></p>
                        </div>

                    </th>
                </tr>
                <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                    <tr class="hideInfo">
                        <td class="granteeName"><?php if (get_post_meta($post->ID, 'granteeShortDescription', TRUE)) { ?>
                            <a href="#" class="open-row"><?php } ?><?php the_title(); ?><?php if (get_post_meta($post->ID, 'granteeShortDescription', TRUE)) { ?></a><?php } ?>
                        </td>
                        <td>
                            <span>Grant Amount</span><span><?php if (get_post_meta($post->ID, 'granteeGrantAmount', TRUE)) {
                                    echo '$' . number_format(get_post_meta($post->ID, 'granteeGrantAmount', TRUE));
                                } ?></span></td>
                        <td><span>Program Area</span><span><?php if (get_post_meta($post->ID, 'programArea', TRUE)) {
                                    echo str_replace('Sf', 'SF', str_replace('And', 'and', ucwords(str_replace('-', ' ', get_post_meta($post->ID, 'programArea', TRUE)))));
                                } ?></span></td>
                        <td>
                            <span>Type of Support</span><span><?php if (get_post_meta($post->ID, 'typeOfSupport', TRUE)) {
                                    echo str_replace('And', 'and', ucwords(str_replace('-', ' ', get_post_meta($post->ID, 'typeOfSupport', TRUE))));
                                } ?></span></td>
                        <td><span>Location</span><span><?php if (get_post_meta($post->ID, 'granteeLocation', TRUE)) {
                                    echo get_post_meta($post->ID, 'granteeLocation', TRUE);
                                } ?></span></td>
                        <td><span>Year</span><span><?php the_time('Y') ?></span></td>
                    </tr>
                    <?php if (get_post_meta($post->ID, 'granteeShortDescription', TRUE)) { ?>
                        <tr class="short-description">
                        <td colspan="6" class="description"><?php echo get_post_meta($post->ID, 'granteeShortDescription', TRUE); ?>
                            <?php if (get_post_meta($post->ID, 'granteeWebSite', TRUE) != '') { ?><br>
                                More information:
                                <a href="<?php echo get_post_meta($post->ID, 'granteeWebSite', TRUE); ?>" target="_blank"><?php echo get_post_meta($post->ID, 'granteeWebSite', TRUE); ?></a>
                            <?php } ?>
                        </td>
                        </tr><?php } ?>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else:
            echo '<div class="content-main"><p><strong>No grantees found.</strong></p></div>';
        endif; ?>
        <div id="grant-search-footer" class="desktop-only"><?php wp_pagenavi(); ?></div>


        <?php $wp_query = null;
        $wp_query = $temp;
        wp_reset_postdata(); ?>

        <div class="content-main" style="padding-top:0;">
            <small> <ul>
                <li>Annual Grants for general operating support are awarded and paid to those organizations
                representing special interests. The Fund does not accept applications for Annual Grants.</li>
                <li>Special Projects and SF Bay Area Institutions and Projects are by invitation only.</li>
                <li>Not all grants are listed on the website and some recent grants may not be included as the database is
                updated quarterly.</li>
            </ul>
            </small>
        </div>

    </div>

<?php endwhile;
else: ?>
<?php endif; ?>
<?php get_footer(); ?>