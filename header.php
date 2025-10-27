<?php
/**
 * Header template
 *
 * @package BriefReads
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="br-progress-bar" id="br-progress-bar" aria-hidden="true"></div>
<?php wp_body_open(); ?>
<header class="br-site-header">
    <div class="container br-site-header__inner">
        <a class="br-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            } else {
                bloginfo( 'name' );
            }
            ?>
        </a>
        <nav class="br-primary-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'briefreads' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => '__return_false',
                )
            );
            ?>
        </nav>
        <div class="br-header-actions">
            <button class="br-search-toggle" type="button" aria-expanded="false" aria-controls="br-search-overlay">
                <?php briefreads_icon( 'search' ); ?>
                <span class="screen-reader-text"><?php esc_html_e( 'Open search', 'briefreads' ); ?></span>
            </button>
            <button class="br-toggle-mode" type="button" aria-pressed="false">
                🌙<span class="screen-reader-text"><?php esc_html_e( 'Toggle theme', 'briefreads' ); ?></span>
            </button>
            <a class="br-button br-button--outline" href="<?php echo esc_url( get_post_type_archive_link( 'book_summary' ) ); ?>"><?php esc_html_e( 'Browse Summaries', 'briefreads' ); ?></a>
        </div>
    </div>
</header>

<div class="br-search-overlay" id="br-search-overlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="br-search-overlay__inner">
        <div class="br-search-overlay__header">
            <h2><?php esc_html_e( 'Search BriefReads', 'briefreads' ); ?></h2>
            <button class="br-search-close" type="button" aria-label="<?php esc_attr_e( 'Close search', 'briefreads' ); ?>">
                <?php briefreads_icon( 'close' ); ?>
            </button>
        </div>
        <?php get_search_form(); ?>
    </div>
</div>

<nav class="br-mobile-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'briefreads' ); ?>">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php briefreads_icon( 'home' ); ?>
        <span><?php esc_html_e( 'Home', 'briefreads' ); ?></span>
    </a>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'book_summary' ) ); ?>">
        <?php briefreads_icon( 'categories' ); ?>
        <span><?php esc_html_e( 'Browse', 'briefreads' ); ?></span>
    </a>
    <a href="<?php echo esc_url( home_url( '/my-library/' ) ); ?>">
        <?php briefreads_icon( 'library' ); ?>
        <span><?php esc_html_e( 'Library', 'briefreads' ); ?></span>
    </a>
    <button class="br-search-toggle" type="button">
        <?php briefreads_icon( 'search' ); ?>
        <span><?php esc_html_e( 'Search', 'briefreads' ); ?></span>
    </button>
</nav>

<main id="content" class="br-site-content">
