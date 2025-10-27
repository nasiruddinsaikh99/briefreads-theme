<?php
/**
 * Footer template
 *
 * @package BriefReads
 */
?>
</main>
<footer class="br-footer">
    <div class="container">
        <div class="br-footer__top">
            <div>
                <a class="br-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                <p><?php echo wp_kses_post( briefreads_get_option( 'footer_description', __( 'BriefReads distills transformational books into immersive summaries you can read, listen to, and act on in minutes.', 'briefreads' ) ) ); ?></p>
            </div>
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div><?php dynamic_sidebar( 'footer-1' ); ?></div>
            <?php endif; ?>
            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <div><?php dynamic_sidebar( 'footer-2' ); ?></div>
            <?php endif; ?>
            <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                <div><?php dynamic_sidebar( 'footer-3' ); ?></div>
            <?php endif; ?>
        </div>
        <div class="br-footer__bottom">
            <span>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
            <div class="br-footer__links">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'br-footer-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
