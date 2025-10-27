<?php
/**
 * BriefReads theme functions and definitions
 *
 * @package BriefReads
 */

if ( ! defined( 'BRIEFREADS_VERSION' ) ) {
    define( 'BRIEFREADS_VERSION', '1.0.0' );
}

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/acf.php';

if ( ! function_exists( 'briefreads_setup' ) ) {
    /**
     * Theme setup
     */
    function briefreads_setup() {
        load_theme_textdomain( 'briefreads', get_template_directory() . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
        add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 180, 'flex-width' => true ) );
        add_theme_support( 'align-wide' );

        register_nav_menus(
            array(
                'primary' => __( 'Primary Menu', 'briefreads' ),
                'browse'  => __( 'Browse Menu', 'briefreads' ),
                'footer'  => __( 'Footer Menu', 'briefreads' ),
                'mobile'  => __( 'Mobile Menu', 'briefreads' ),
            )
        );
    }
}
add_action( 'after_setup_theme', 'briefreads_setup' );

/**
 * Register widget areas
 */
function briefreads_widgets_init() {
    register_sidebar(
        array(
            'name'          => __( 'Sidebar', 'briefreads' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets for secondary column content.', 'briefreads' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar(
            array(
                'name'          => sprintf( __( 'Footer Column %d', 'briefreads' ), $i ),
                'id'            => 'footer-' . $i,
                'description'   => __( 'Displays widgets in the footer.', 'briefreads' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }
}
add_action( 'widgets_init', 'briefreads_widgets_init' );

/**
 * Enqueue styles and scripts
 */
function briefreads_scripts() {
    wp_enqueue_style( 'briefreads-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap', array(), BRIEFREADS_VERSION );
    wp_enqueue_style( 'briefreads-style', get_stylesheet_uri(), array( 'briefreads-fonts' ), BRIEFREADS_VERSION );

    wp_enqueue_script( 'briefreads-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), BRIEFREADS_VERSION, true );
    wp_enqueue_script( 'briefreads-audio', get_template_directory_uri() . '/assets/js/audio-player.js', array(), BRIEFREADS_VERSION, true );

    $settings = array(
        'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
        'themeToggleLabel'       => __( 'Toggle dark mode', 'briefreads' ),
        'bookmarkAddedLabel'     => __( 'Saved to your library', 'briefreads' ),
        'bookmarkRemovedLabel'   => __( 'Removed from your library', 'briefreads' ),
        'fontSizeDefault'        => 18,
        'lineHeightDefault'      => 1.75,
        'speedLabels'            => array( '1x', '1.25x', '1.5x', '2x' ),
        'scrollProgressSelector' => '.br-summary-content',
    );
    wp_localize_script( 'briefreads-main', 'briefReadsSettings', $settings );
}
add_action( 'wp_enqueue_scripts', 'briefreads_scripts' );

/**
 * Register custom post type and taxonomy
 */
function briefreads_register_content_types() {
    $labels = array(
        'name'               => _x( 'Book Summaries', 'post type general name', 'briefreads' ),
        'singular_name'      => _x( 'Book Summary', 'post type singular name', 'briefreads' ),
        'menu_name'          => _x( 'Book Summaries', 'admin menu', 'briefreads' ),
        'name_admin_bar'     => _x( 'Book Summary', 'add new on admin bar', 'briefreads' ),
        'add_new'            => _x( 'Add New', 'book summary', 'briefreads' ),
        'add_new_item'       => __( 'Add New Book Summary', 'briefreads' ),
        'new_item'           => __( 'New Book Summary', 'briefreads' ),
        'edit_item'          => __( 'Edit Book Summary', 'briefreads' ),
        'view_item'          => __( 'View Book Summary', 'briefreads' ),
        'all_items'          => __( 'All Book Summaries', 'briefreads' ),
        'search_items'       => __( 'Search Book Summaries', 'briefreads' ),
        'parent_item_colon'  => __( 'Parent Book Summaries:', 'briefreads' ),
        'not_found'          => __( 'No summaries found.', 'briefreads' ),
        'not_found_in_trash' => __( 'No summaries found in Trash.', 'briefreads' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments' ),
        'rewrite'            => array( 'slug' => 'summaries' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'book_summary', $args );

    register_taxonomy(
        'summary_genre',
        'book_summary',
        array(
            'labels'            => array(
                'name'          => __( 'Genres', 'briefreads' ),
                'singular_name' => __( 'Genre', 'briefreads' ),
            ),
            'hierarchical'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'summary-genre' ),
            'show_in_rest'      => true,
        )
    );

    register_taxonomy(
        'summary_tag',
        'book_summary',
        array(
            'labels'            => array(
                'name'          => __( 'Summary Tags', 'briefreads' ),
                'singular_name' => __( 'Summary Tag', 'briefreads' ),
            ),
            'hierarchical'      => false,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'summary-tag' ),
            'show_in_rest'      => true,
        )
    );
}
add_action( 'init', 'briefreads_register_content_types' );

/**
 * Register book summary meta fields
 */
function briefreads_register_meta() {
    $meta_fields = array(
        'reading_time'           => 'string',
        'audio_url'             => 'string',
        'audio_duration'        => 'string',
        'rating'                => 'number',
        'views'                 => 'integer',
        'publication_year'      => 'integer',
        'page_count'            => 'integer',
        'affiliate_url'         => 'string',
        'author_bio'            => 'string',
        'author_photo'          => 'string',
        'estimated_time_remaining' => 'string',
        'key_takeaways'         => 'string',
        'featured'              => 'boolean',
        'reading_time_minutes'   => 'integer',
    );

    foreach ( $meta_fields as $field => $type ) {
        register_post_meta(
            'book_summary',
            'briefreads_' . $field,
            array(
                'single'       => true,
                'type'         => $type,
                'show_in_rest' => true,
                'auth_callback' => function() {
                    return current_user_can( 'edit_posts' );
                },
            )
        );
    }
}
add_action( 'init', 'briefreads_register_meta' );

/**
 * Increment views on single summaries.
 */
function briefreads_track_views() {
    if ( is_singular( 'book_summary' ) ) {
        $post_id = get_the_ID();
        $views   = (int) get_post_meta( $post_id, 'briefreads_views', true );
        update_post_meta( $post_id, 'briefreads_views', $views + 1 );
    }
}
add_action( 'wp', 'briefreads_track_views' );

/**
 * AJAX handler for bookmarks stored in user meta
 */
function briefreads_toggle_bookmark() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( __( 'You must be logged in to save summaries.', 'briefreads' ) );
    }

    $post_id = isset( $_POST['postId'] ) ? absint( $_POST['postId'] ) : 0;
    if ( ! $post_id ) {
        wp_send_json_error( __( 'Invalid summary.', 'briefreads' ) );
    }

    $user_id   = get_current_user_id();
    $bookmarks = (array) get_user_meta( $user_id, 'briefreads_bookmarks', true );

    if ( in_array( $post_id, $bookmarks, true ) ) {
        $bookmarks = array_diff( $bookmarks, array( $post_id ) );
        update_user_meta( $user_id, 'briefreads_bookmarks', $bookmarks );
        wp_send_json_success( array( 'status' => 'removed' ) );
    } else {
        $bookmarks[] = $post_id;
        update_user_meta( $user_id, 'briefreads_bookmarks', array_unique( $bookmarks ) );
        wp_send_json_success( array( 'status' => 'added' ) );
    }
}
add_action( 'wp_ajax_briefreads_toggle_bookmark', 'briefreads_toggle_bookmark' );
add_action( 'wp_ajax_nopriv_briefreads_toggle_bookmark', 'briefreads_toggle_bookmark' );

/**
 * Modify excerpt length on summary archives
 */
function briefreads_excerpt_length( $length ) {
    if ( is_post_type_archive( 'book_summary' ) || is_tax( array( 'summary_genre', 'summary_tag' ) ) ) {
        return 18;
    }
    return $length;
}
add_filter( 'excerpt_length', 'briefreads_excerpt_length', 999 );

/**
 * Include Gutenberg editor styles.
 */
function briefreads_block_editor_assets() {
    wp_enqueue_style( 'briefreads-editor-style', get_template_directory_uri() . '/assets/css/editor.css', array(), BRIEFREADS_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'briefreads_block_editor_assets' );


/**
 * Calculate and persist numeric reading time for filtering.
 */
function briefreads_persist_reading_time( $post_id, $post, $update ) {
    if ( 'book_summary' !== $post->post_type ) {
        return;
    }

    $meta_time = briefreads_get_summary_meta( $post_id, 'reading_time' );
    $minutes   = 0;
    if ( $meta_time ) {
        if ( is_numeric( $meta_time ) ) {
            $minutes = absint( $meta_time );
        } elseif ( preg_match( '/(\d+)/', $meta_time, $matches ) ) {
            $minutes = absint( $matches[1] );
        }
    }

    if ( ! $minutes ) {
        $content = get_post_field( 'post_content', $post_id );
        $words   = str_word_count( wp_strip_all_tags( $content ) );
        $minutes = max( 1, ceil( $words / 230 ) );
    }

    update_post_meta( $post_id, 'briefreads_reading_time_minutes', $minutes );
}
add_action( 'save_post', 'briefreads_persist_reading_time', 20, 3 );

/**
 * Automatically add IDs to summary headings for TOC linking.
 */

function briefreads_add_heading_ids( $content ) {
    if ( is_singular( 'book_summary' ) ) {
        $content = preg_replace_callback( '/<h(2|3)([^>]*)>(.*?)<\/h\1>/i', function( $matches ) {
            $id = sanitize_title( wp_strip_all_tags( $matches[3] ) );
            $attributes = trim( $matches[2] );
            if ( false === strpos( $attributes, 'id=' ) ) {
                $attributes .= ( $attributes ? ' ' : '' ) . 'id="' . esc_attr( $id ) . '"';
            }
            return '<h' . $matches[1] . ( $attributes ? ' ' . $attributes : '' ) . '>' . $matches[3] . '</h' . $matches[1] . '>';
        }, $content );
    }
    return $content;
}
add_filter( 'the_content', 'briefreads_add_heading_ids', 15 );
