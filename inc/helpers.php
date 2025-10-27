<?php
/**
 * Helper functions for BriefReads theme.
 *
 * @package BriefReads
 */

/**
 * Get theme option from ACF options or theme mod fallback.
 *
 * @param string $key Option key.
 * @param mixed  $default Default value.
 */
function briefreads_get_option( $key, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, 'option' );
        if ( null !== $value && '' !== $value ) {
            return $value;
        }
    }

    $fallback = get_theme_mod( $key, $default );
    return '' !== $fallback ? $fallback : $default;
}

/**
 * Return sanitized meta value for summaries.
 *
 * @param int    $post_id Post ID.
 * @param string $key Meta key without prefix.
 * @param mixed  $default Default value.
 */
function briefreads_get_summary_meta( $post_id, $key, $default = '' ) {
    $value = get_post_meta( $post_id, 'briefreads_' . $key, true );

    if ( 'rating' === $key ) {
        $value = floatval( $value );
        return $value > 0 ? $value : 0;
    }

    if ( 'views' === $key ) {
        return intval( $value );
    }

    if ( 'key_takeaways' === $key && is_string( $value ) ) {
        $decoded = json_decode( $value, true );
        if ( is_array( $decoded ) ) {
            return $decoded;
        }
    }

    if ( empty( $value ) && function_exists( 'get_field' ) ) {
        $field_value = get_field( $key, $post_id );
        if ( null !== $field_value && '' !== $field_value ) {
            $value = $field_value;
        }
    }

    if ( ! empty( $value ) ) {
        return $value;
    }

    return $default;
}

/**
 * Calculate reading time estimation.
 *
 * @param int $post_id Post ID.
 */
function briefreads_estimated_reading_time( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();

    $reading_time = briefreads_get_summary_meta( $post_id, 'reading_time' );
    if ( $reading_time ) {
        return $reading_time;
    }

    $content = get_post_field( 'post_content', $post_id );
    $words   = str_word_count( wp_strip_all_tags( $content ) );
    $minutes = ceil( $words / 230 );

    return sprintf( _n( '%d min read', '%d mins read', $minutes, 'briefreads' ), $minutes );
}

/**
 * Retrieve saved bookmarks for current user.
 */
function briefreads_get_user_bookmarks() {
    if ( ! is_user_logged_in() ) {
        return array();
    }

    $bookmarks = (array) get_user_meta( get_current_user_id(), 'briefreads_bookmarks', true );
    return array_map( 'intval', $bookmarks );
}

/**
 * Determine if summary is bookmarked.
 *
 * @param int $post_id Post ID.
 */
function briefreads_is_bookmarked( $post_id ) {
    return in_array( $post_id, briefreads_get_user_bookmarks(), true );
}

/**
 * Format number with suffix.
 *
 * @param int $number Number.
 */
function briefreads_human_number( $number ) {
    if ( $number > 1000000 ) {
        return round( $number / 1000000, 1 ) . 'M';
    }

    if ( $number > 1000 ) {
        return round( $number / 1000, 1 ) . 'K';
    }

    return number_format_i18n( $number );
}

/**
 * Render SVG icon from template-parts/components/icons.
 *
 * @param string $icon Icon slug.
 */
function briefreads_icon( $icon ) {
    $file = get_template_directory() . '/template-parts/components/icons/' . $icon . '.svg';

    if ( file_exists( $file ) ) {
        $svg = file_get_contents( $file ); // phpcs:ignore
        echo wp_kses( $svg, array( 'svg' => array( 'xmlns' => true, 'viewBox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ), 'path' => array( 'd' => true, 'fill' => true ) ) );
    }
}

/**
 * Extract headings from content for TOC fallback.
 */
function briefreads_extract_headings( $content ) {
    $headings = array();
    if ( empty( $content ) ) {
        return $headings;
    }

    if ( preg_match_all( '/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches ) ) {
        foreach ( $matches[1] as $heading ) {
            $headings[] = wp_strip_all_tags( $heading );
        }
    }

    if ( empty( $headings ) && preg_match_all( '/<h3[^>]*>(.*?)<\/h3>/i', $content, $matches ) ) {
        foreach ( $matches[1] as $heading ) {
            $headings[] = wp_strip_all_tags( $heading );
        }
    }

    return array_unique( $headings );
}
