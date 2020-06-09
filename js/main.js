//**********************************************************
// Function for getting querystring parameter by name
//**********************************************************

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

//**********************************************************
// Function for checking if querystring parameter exists
//**********************************************************

function paramExists(param) {
    var url = window.location.href;
    if (url.indexOf('?' + param + '=') != -1)
        return true;
    else if (url.indexOf('&' + param + '=') != -1)
        return true;
    return false;
}


//**********************************************************
// TODO
//**********************************************************

function replaceInitialQueryString(url, param, value) {
    var re = new RegExp("([?|&])" + param + "=.*?(&|$)", "i");
    if (url.match(re))
        return url.replace(re, '$1' + param + "=" + value + '$2');
    else
        return url + '?' + param + "=" + value;
}

//**********************************************************
// TODO
//**********************************************************

function replaceQueryString(url, param, value) {
    var re = new RegExp("([?|&])" + param + "=.*?(&|$)", "i");
    if (url.match(re))
        return url.replace(re, '$1' + param + "=" + value + '$2');
    else
        return url + '&' + param + "=" + value;
}

$(document).ready(function () {


    //**********************************************************
    // If there are less than 10 news items on the news page then
    // hide the "load more" button.
    //**********************************************************

    if ($('body').hasClass('page-template-page-in-the-news')) {
        if ($(".news-item-entry").length <= 10) {
            $('#loadMore').remove();
        }
    }

    //**********************************************************
    // If there are less than 10 press releases on the press releases
    // page then hide the "load more" button.
    //**********************************************************
    if ($('body').hasClass('page-template-page-press-releases')) {
        if ($(".press-release-entry").length <= 10) {
            $('#loadMore').remove();
        }
    }

    //**********************************************************
    // Show news items in groups of 10 until there are no more
    // to show.
    //**********************************************************
    if ($('body').hasClass('page-template-page-in-the-news')) {
        $(".news-item-entry").hide();
        size_entries = $(".news-item-entry").size();
        x = 10;
        $('.news-item-entry:lt(' + x + ')').show();
        $('#loadMore').click(function (e) {
            e.preventDefault();
            x = (x + 10 <= size_entries) ? x + 10 : size_entries;
            $('.news-item-entry:lt(' + x + ')').delay(1000).show(0, function () {
                $('#loadMore').removeClass('active');
                if ($('.news-item-entry:visible').length == $('.news-item-entry').length) {
                    $('#loadMore').remove();
                }
            });
            $(this).addClass('active');
        });
    }

    //**********************************************************
    // Show press releases in groups of 10 until there are no more
    // to show.
    //**********************************************************
    if ($('body').hasClass('page-template-page-press-releases')) {
        $(".press-release-entry").hide();
        size_entries = $(".press-release-entry").size();
        x = 10;
        $('.press-release-entry:lt(' + x + ')').show();
        $('#loadMore').click(function (e) {
            e.preventDefault();
            x = (x + 10 <= size_entries) ? x + 10 : size_entries;
            $('.press-release-entry:lt(' + x + ')').delay(1000).show(0, function () {
                $('#loadMore').removeClass('active');
                if ($('.press-release-entry:visible').length == $('.press-release-entry').length) {
                    $('#loadMore').remove();
                }
            });
            $(this).addClass('active');
        });
    }

    //**********************************************************
    // When the grants database page loads, precheck any of the
    // program area filter checkboxes based on the paramters in the querystring.
    //**********************************************************
    var prevSelectedPrograms = getParameterByName('program');
    $('.filter-values[data-filter-type=program] input').each(function () {
        if (prevSelectedPrograms.indexOf($(this).val()) >= 0) {
            $(this).prop('checked', true);
        }
    });

    //**********************************************************
    // When the grants database page loads, precheck any of the
    // type of support filter checkboxes based on the paramters in the querystring.
    //**********************************************************
    var prevSelectedSupport = getParameterByName('support');
    $('.filter-values[data-filter-type=support] input').each(function () {
        if (prevSelectedSupport.indexOf($(this).val()) >= 0) {
            $(this).prop('checked', true);
        }
    });

    //**********************************************************
    // When the grants database page loads, precheck any of the
    // year filter checkboxes based on the paramters in the querystring.
    //**********************************************************
    var prevSelectedGrantYear = getParameterByName('grantyear');
    $('.filter-values[data-filter-type=grantyear] input').each(function () {
        if (prevSelectedGrantYear.indexOf($(this).val()) >= 0) {
            $(this).prop('checked', true);
        }
    });

    //**********************************************************
    // A function to get the current URL
    //**********************************************************
    function getURL() {
        return location.protocol + '//' + location.host + location.pathname
    }


    //**********************************************************
    // Applies the checked filters from the table headers (on desktop)
    //**********************************************************
    function applyTableFilters() {
        var currentURL = getURL();
        // Get the selected program area filters
        selectedProgramFilters = $('th .filter-values[data-filter-type=program]').find('input:checked');
        selectedProgramFilterValues = '';
        selectedProgramFilters.each(function () {
            if (selectedProgramFilterValues == '') {
                selectedProgramFilterValues += $(this).val();
            } else {
                selectedProgramFilterValues += '+' + $(this).val();
            }
        });

        // Get the selected type of support filters
        selectedSupportFilters = $('th .filter-values[data-filter-type=support]').find('input:checked');
        selectedSupportFilterValues = '';
        selectedSupportFilters.each(function () {
            if (selectedSupportFilterValues == '') {
                selectedSupportFilterValues += $(this).val();
            } else {
                selectedSupportFilterValues += '+' + $(this).val();
            }
        });

        // Get the selected grant year filters
        selectedGrantYearFilters = $('th .filter-values[data-filter-type=grantyear]').find('input:checked');
        selectedGrantYearFilterValues = '';
        selectedGrantYearFilters.each(function () {
            if (selectedGrantYearFilterValues == '') {
                selectedGrantYearFilterValues += $(this).val();
            } else {
                selectedGrantYearFilterValues += '+' + $(this).val();
            }
        });

        // Check to see if the current URL is anything other than page 1.
        // If it is, send them back to page one of results when applying the filter.
        if (currentURL.indexOf('/page/') >= 0) {
            pageDir = currentURL.substring(currentURL.indexOf('page'), currentURL.lastIndexOf("/"));
            currentURL = currentURL.replace(pageDir, '');
        }

        // Send them to the grants database with the filters specified in the querystring
        window.location.href = currentURL + '?orderby=' + getParameterByName('orderby') + '&order=' + getParameterByName('order') + '&title=' + getParameterByName('title') + '&program=' + selectedProgramFilterValues + '&support=' + selectedSupportFilterValues + '&grantyear=' + selectedGrantYearFilterValues;
    }


    //**********************************************************
    // Applies the checked filters from the table headers (on mobile)
    //**********************************************************
    function applyMobileFilters() {
        var currentURL = getURL();

        // Get the selected program area filters
        selectedProgramFilters = $('.mobile-filters .filter-values[data-filter-type=program]').find('input:checked');
        selectedProgramFilterValues = '';
        selectedProgramFilters.each(function () {
            if (selectedProgramFilterValues == '') {
                selectedProgramFilterValues += $(this).val();
            } else {
                selectedProgramFilterValues += '+' + $(this).val();
            }
        });

        // Get the selected type of support filters
        selectedSupportFilters = $('.mobile-filters .filter-values[data-filter-type=support]').find('input:checked');
        selectedSupportFilterValues = '';
        selectedSupportFilters.each(function () {
            if (selectedSupportFilterValues == '') {
                selectedSupportFilterValues += $(this).val();
            } else {
                selectedSupportFilterValues += '+' + $(this).val();
            }
        });

        // Get the selected grant year filters
        selectedGrantYearFilters = $('.mobile-filters .filter-values[data-filter-type=grantyear]').find('input:checked');
        selectedGrantYearFilterValues = '';
        selectedGrantYearFilters.each(function () {
            if (selectedGrantYearFilterValues == '') {
                selectedGrantYearFilterValues += $(this).val();
            } else {
                selectedGrantYearFilterValues += '+' + $(this).val();
            }
        });

        // Check to see if the current URL is anything other than page 1.
        // If it is, send them back to page one of results when applying the filter.
        if (currentURL.indexOf('/page/') >= 0) {
            pageDir = currentURL.substring(currentURL.indexOf('page'), currentURL.lastIndexOf("/"));
            currentURL = currentURL.replace(pageDir, '');
        }

        // Send them to the grants database with the filters specified in the querystring
        window.location.href = currentURL + '?orderby=' + getParameterByName('orderby') + '&order=' + getParameterByName('order') + '&title=' + getParameterByName('title') + '&program=' + selectedProgramFilterValues + '&support=' + selectedSupportFilterValues + '&grantyear=' + selectedGrantYearFilterValues;
    }

    //**********************************************************
    // Closes mobile filters div
    //**********************************************************
    $('.close-mobile-filters').click(function () {
        $('.mobile-filters').hide();
    });

    //**********************************************************
    // Closes mobile sort div
    //**********************************************************
    $('.close-mobile-sort').click(function () {
        $('.mobile-sort').hide();
    });

    //**********************************************************
    // Removes all filters
    //**********************************************************
    $('.remove-all-filters').click(function () {
        $('.mobile-filters input').each(function () {
            $(this).prop('checked', false);
        });
        applyMobileFilters();
    });

    //**********************************************************
    // Apply table header filters when link is clicked
    //**********************************************************
    $('th .apply-filters a').click(function () {
        applyTableFilters();
    });

    //**********************************************************
    // Apply mobile filters when checkbox is selected
    //**********************************************************
    $('.mobile-filters input').click(function () {
        applyMobileFilters();
    });

    //**********************************************************
    // Hide and show table header filters
    //**********************************************************
    $('.filter-link').click(function () {
        if ($(this).next().filter('.filter-values').is(':visible')) {
            $(this).next().hide();
            $(this).removeClass('active');
        } else {
            $('.filter-values').hide();
            $(this).next().show();
            $(this).addClass('active');
        }
    });

    //**********************************************************
    // Hide and show mobile filters
    //**********************************************************
    $('#filter-db').click(function () {
        if ($('.mobile-filters').is(':visible')) {
            $('.mobile-filters').hide();
        } else {
            $('.mobile-sort').hide();
            $('.mobile-filters').show();
        }
    });


    //**********************************************************
    // Hide and show mobile sort
    //**********************************************************
    $('#sort-db').click(function () {
        if ($('.mobile-sort').is(':visible')) {
            $('.mobile-sort').hide();
        } else {
            $('.mobile-filters').hide();
            $('.mobile-sort').show();

        }
    });

    //**********************************************************
    // Remove filters when clicking on "X" icon next to
    // selected filters on desktop.
    //**********************************************************
    $('.grant-search-header .remove-filter').click(function (e) {
        clickedFilterRemover = $(this);

        var currentURL = getURL();
        if (currentURL.indexOf('/page/') >= 0) {
            pageDir = currentURL.substring(currentURL.indexOf('page'), currentURL.lastIndexOf("/"));
            currentURL = currentURL.replace(pageDir, '');
        }

        // Update the selected program area filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'program') {
            selectedProgramFilters = $('th .filter-values[data-filter-type=program]').find('input:checked');
            selectedProgramFilterValues = '';
            selectedProgramFilters.each(function () {
                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedProgramFilterValues == '') {
                        selectedProgramFilterValues += $(this).val();
                    } else {
                        selectedProgramFilterValues += '+' + $(this).val();
                    }
                }

            });
        } else {
            selectedProgramFilters = $('th .filter-values[data-filter-type=program]').find('input:checked');
            selectedProgramFilterValues = '';
            selectedProgramFilters.each(function () {
                if (selectedProgramFilterValues == '') {
                    selectedProgramFilterValues += $(this).val();
                } else {
                    selectedProgramFilterValues += '+' + $(this).val();
                }
            });
        }

        // Update the selected type of support filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'support') {
            selectedSupportFilters = $('th .filter-values[data-filter-type=support]').find('input:checked');
            selectedSupportFilterValues = '';
            selectedSupportFilters.each(function () {
                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedSupportFilterValues == '') {
                        selectedSupportFilterValues += $(this).val();
                    } else {
                        selectedSupportFilterValues += '+' + $(this).val();
                    }
                }

            });
        } else {
            selectedSupportFilters = $('th .filter-values[data-filter-type=support]').find('input:checked');
            selectedSupportFilterValues = '';
            selectedSupportFilters.each(function () {
                if (selectedSupportFilterValues == '') {
                    selectedSupportFilterValues += $(this).val();
                } else {
                    selectedSupportFilterValues += '+' + $(this).val();
                }
            });
        }

        // Update the selected grant year filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'grantyear') {
            selectedGrantYearFilters = $('th .filter-values[data-filter-type=grantyear]').find('input:checked');
            selectedGrantYearFilterValues = '';
            selectedGrantYearFilters.each(function () {
                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedGrantYearFilterValues == '') {
                        selectedGrantYearFilterValues += $(this).val();
                    } else {
                        selectedGrantYearFilterValues += '+' + $(this).val();
                    }
                }

            });
        } else {
            selectedGrantYearFilters = $('th .filter-values[data-filter-type=grantyear]').find('input:checked');
            selectedGrantYearFilterValues = '';
            selectedGrantYearFilters.each(function () {
                if (selectedGrantYearFilterValues == '') {
                    selectedGrantYearFilterValues += $(this).val();
                } else {
                    selectedGrantYearFilterValues += '+' + $(this).val();
                }
            });
        }

        // Send them to the grants database with the filters specified in the querystring
        window.location.href = currentURL + '?title=' + getParameterByName('title') + '&orderby=' + getParameterByName('orderby') + '&order=' + getParameterByName('order') + '&program=' + selectedProgramFilterValues + '&support=' + selectedSupportFilterValues + '&grantyear=' + selectedGrantYearFilterValues;
    });

    //**********************************************************
    // Remove filters when clicking on "X" icon next to
    // selected filters on mobile.
    //**********************************************************
    $('.mobile-filters .remove-filter').click(function (e) {
        e.preventDefault();
        clickedFilterRemover = $(this);

        var currentURL = getURL();
        if (currentURL.indexOf('/page/') >= 0) {
            pageDir = currentURL.substring(currentURL.indexOf('page'), currentURL.lastIndexOf("/"));
            currentURL = currentURL.replace(pageDir, '');
        }

        // Update the selected program area filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'program') {
            selectedProgramFilters = $('.mobile-filters .filter-values[data-filter-type=program]').find('input:checked');
            selectedProgramFilterValues = '';
            selectedProgramFilters.each(function () {
                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedProgramFilterValues == '') {
                        selectedProgramFilterValues += $(this).val();
                    } else {
                        selectedProgramFilterValues += '+' + $(this).val();
                    }
                }
            });
        } else {
            selectedProgramFilters = $('.mobile-filters .filter-values[data-filter-type=program]').find('input:checked');
            selectedProgramFilterValues = '';
            selectedProgramFilters.each(function () {
                if (selectedProgramFilterValues == '') {
                    selectedProgramFilterValues += $(this).val();
                } else {
                    selectedProgramFilterValues += '+' + $(this).val();
                }
            });
        }

        // Update the selected type of support filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'support') {
            selectedSupportFilters = $('.mobile-filters .filter-values[data-filter-type=support]').find('input:checked');
            selectedSupportFilterValues = '';
            selectedSupportFilters.each(function () {
                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedSupportFilterValues == '') {
                        selectedSupportFilterValues += $(this).val();
                    } else {
                        selectedSupportFilterValues += '+' + $(this).val();
                    }
                }

            });
        } else {
            selectedSupportFilters = $('.mobile-filters .filter-values[data-filter-type=support]').find('input:checked');
            selectedSupportFilterValues = '';
            selectedSupportFilters.each(function () {
                if (selectedSupportFilterValues == '') {
                    selectedSupportFilterValues += $(this).val();
                } else {
                    selectedSupportFilterValues += '+' + $(this).val();
                }
            });
        }

        // Update the selected grant year filter values after the filter has been removed.
        if (clickedFilterRemover.attr('data-filter-type') == 'grantyear') {
            selectedGrantYearFilters = $('.mobile-filters .filter-values[data-filter-type=grantyear]').find('input:checked');
            selectedGrantYearFilterValues = '';
            selectedGrantYearFilters.each(function () {

                if ($(this).val() != clickedFilterRemover.attr('data-filter-value')) {
                    if (selectedGrantYearFilterValues == '') {
                        selectedGrantYearFilterValues += $(this).val();
                    } else {
                        selectedGrantYearFilterValues += '+' + $(this).val();
                    }
                }
            });
        } else {
            selectedGrantYearFilters = $('.mobile-filters .filter-values[data-filter-type=grantyear]').find('input:checked');
            selectedGrantYearFilterValues = '';
            selectedGrantYearFilters.each(function () {
                if (selectedGrantYearFilterValues == '') {
                    selectedGrantYearFilterValues += $(this).val();
                } else {
                    selectedGrantYearFilterValues += '+' + $(this).val();
                }
            });
        }

        // Send them to the grants database with the filters specified in the querystring
        window.location.href = currentURL + '?title=' + getParameterByName('title') + '&orderby=' + getParameterByName('orderby') + '&order=' + getParameterByName('order') + '&program=' + selectedProgramFilterValues + '&support=' + selectedSupportFilterValues + '&grantyear=' + selectedGrantYearFilterValues;
    });


    //**********************************************************
    // If "search within filtered results" is checked, store the
    // the previous filter paramters in hidden fields so they
    // will be submitted with the new search.
    //**********************************************************

    $('#search-within-results').click(function () {
        if ($(this).prop('checked')) {
            $('#programParams').val(getParameterByName('program'));
            $('#supportParams').val(getParameterByName('support'));
            $('#grantYearParams').val(getParameterByName('grantyear'));

        } else {
            $('#programParams').val('');
            $('#supportParams').val('');
            $('#grantYearParams').val('');
        }

    });

    //**********************************************************
    // Old code no longer used. Was in use when table headers
    // used selects to do their filtering.
    //**********************************************************
/*
    $('#grant-search-table select').change(function () {
        if (window.location.href.indexOf('?') != -1) {

            document.location.href = 'http://' + window.location.host + '/our-grants/grants-database/' + replaceQueryString(window.location.search, $(this).attr('id'), $(this).val());
        } else {
            document.location.href = 'http://' + window.location.host + '/our-grants/grants-database/' + replaceInitialQueryString(window.location.search, $(this).attr('id'), $(this).val());
        }
    });
    if (paramExists('grantyear')) {
        $('#grantyear').val(getParameterByName('grantyear'));
    }

    if (paramExists('program')) {
        $('#program').val(getParameterByName('program'));
    }

    if (paramExists('support')) {
        $('#support').val(getParameterByName('support'));
    }

    if (paramExists('title')) {
        $('#title').val(getParameterByName('title'));
    }


    if (window.location.href.indexOf('/page/') >= 0) {
        var url = document.URL;
        var arr = url.split('/');
        $('#pagination-dropdown').val(arr[arr.length - 2]);
    }
*/


    //**********************************************************
    // Make pagination dropdown load new page of results
    // depending upon selection.
    //**********************************************************
    $('#pagination-dropdown').change(function () {
        var page = $(this).val(); // get selected value
        if (page) { // require a URL
            if (window.location.href.indexOf('/page/') >= 0) {
                var url = document.URL;
                var arr = url.split('/');
                shortUrl = url.substring(0, url.lastIndexOf(arr[arr.length - 2]));
                window.location = shortUrl + page + '/' + location.search; // redirect
            } else {
                window.location = window.location.pathname + '/page/' + page + '/' + location.search; // redirect
            }
        }
        return false;
    });

    //**********************************************************
    // Set up home page slider.
    //**********************************************************
    $('.home .banner').slick({
        slide: '.slide',
        dots: true,
        infinite: true,
        arrows: false,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 3000
    });


    $('.granteeName a').unbind('click');
    $('.granteeName a').click(function (e) {
        e.preventDefault();
        if ($(this).hasClass('opened')) {
            $(this).removeClass('opened').parent().parent().removeClass('showInfo').addClass('hideInfo').next().filter('.short-description').removeClass('showInfo').addClass('hideInfo');
        } else {
            $(this).addClass('opened').parent().parent().removeClass('hideInfo').addClass('showInfo').next().filter('.short-description').removeClass('hideInfo').addClass('showInfo');

        }


    });


    //**********************************************************
    // Mobile menu toggling.
    //**********************************************************
    $(".toggle-main-menu").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
        $(this).parent().toggleClass("active");
        $("#menu-main-navigation").toggle();
        $('#menu-main-navigation li').removeClass('hover');
    });


    //**********************************************************
    // Make changes based on the width of the screen on resize.
    //**********************************************************
    $(window).bind('resize orientationchange', function () {
        windowAdjust();
    });

    var windowAdjust = function () {
        ww = document.body.clientWidth;

        if (ww < 768) {


            $("#menu-main-navigation li").unbind('mouseenter mouseleave'); // Disable hover behavior on list items
            $("#menu-main-navigation li.menu-item-has-children > a").unbind('click').bind('click', function (e) {
                // Deactivate links with children and make them toggle the class
                // of their parent list items. Must be attached to anchor element to prevent bubbling.
                e.preventDefault();
                $(this).parent("li").toggleClass("hover");
            });
        }

        if (ww < 600) {
            
            //**********************************************************
            // Turn the recent grants in the footer into a carousel
            // on small devices.
            //**********************************************************
            $('.recent-grants ul').slick({
                dots: true,
                infinite: true,
                arrows: false,
                speed: 500,
                fade: true,
                cssEase: 'linear'
            });
        } else {
            $('.recent-grants ul').slick('unslick');
        }


    }

    windowAdjust();


});
