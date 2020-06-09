</div>
</div>
<footer>
    <div class="footer-top">
        <nav class="footer-nav">
            <?php wp_nav_menu(array('theme_location' => 'footer-navigation', 'container' => '')); ?>
        </nav>
    </div>
    <div class="footer-bottom">
        <h2>
            <span><img class="logo" src="/wp-content/themes/ldgfund/img/logo_stacked.svg" alt="LDG Fund Logo" /></span>
        </h2>

        <div class="contact-info">
            <p>1 Montgomery Street, Suite 3440 | San Francisco, CA 94104-4505 | 415-771-1717</p>

            <p><a href="https://goo.gl/maps/CC3WU27huso">map</a> |
                <a href="/wp-content/uploads/Directions.pdf" target="_blank">directions</a></p>
        </div>

        <div class="copyright"><p>Copyright © 2016 Lisa and Douglas Goldman Fund. All rights reserved. |
                <a href="/about-us/privacy-policy/">Privacy Policy</a>.</p></div>
    </div>
</footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>


<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
            function () {
                (b[l].q = b[l].q || []).push(arguments)
            });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = '//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-XXXXX-X', 'auto');
    ga('send', 'pageview');
</script>
<?php wp_footer(); ?>

</body>
</html>
