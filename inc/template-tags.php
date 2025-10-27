<?php
/**
 * Template tags used across BriefReads theme.
 *
 * @package BriefReads
 */

/**
 * Display summary meta icons row.
 */
function briefreads_summary_meta( $post_id = null ) {
    $post_id    = $post_id ? $post_id : get_the_ID();
    $reading    = briefreads_estimated_reading_time( $post_id );
    $views      = briefreads_human_number( briefreads_get_summary_meta( $post_id, 'views', 0 ) );
    $rating     = briefreads_get_summary_meta( $post_id, 'rating', 0 );
    $rating_out = $rating ? sprintf( '%s %.1f', str_repeat( '⭐', floor( $rating ) ), $rating ) : __( 'Unrated', 'briefreads' );
    ?>
    <div class="br-summary-meta">
        <span class="br-summary-meta__item">
            <span aria-hidden="true">📖</span>
            <span><?php echo esc_html( $reading ); ?></span>
        </span>
        <span class="br-summary-meta__item">
            <span aria-hidden="true">👁</span>
            <span><?php echo esc_html( $views ); ?> <?php esc_html_e( 'views', 'briefreads' ); ?></span>
        </span>
        <span class="br-summary-meta__item">
            <span aria-hidden="true">⭐</span>
            <span><?php echo esc_html( $rating_out ); ?></span>
        </span>
    </div>
    <?php
}

/**
 * Render category chips for a summary.
 */
function briefreads_summary_terms( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $terms   = wp_get_post_terms( $post_id, 'summary_genre' );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return;
    }
    ?>
    <div class="br-chip-list">
        <?php foreach ( $terms as $term ) : ?>
            <a class="br-chip" href="<?php echo esc_url( get_term_link( $term ) ); ?>">#<?php echo esc_html( $term->name ); ?></a>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Render related summaries.
 */
function briefreads_related_summaries( $post_id = null, $limit = 3 ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $genre_terms = wp_get_post_terms( $post_id, 'summary_genre', array( 'fields' => 'ids' ) );
    $tag_terms   = wp_get_post_terms( $post_id, 'summary_tag', array( 'fields' => 'ids' ) );

    $args = array(
        'post_type'      => 'book_summary',
        'posts_per_page' => $limit,
        'post__not_in'   => array( $post_id ),
        'orderby'        => 'rand',
    );

    if ( ! empty( $genre_terms ) || ! empty( $tag_terms ) ) {
        $tax_query = array( 'relation' => 'OR' );
        if ( ! empty( $genre_terms ) ) {
            $tax_query[] = array(
                'taxonomy' => 'summary_genre',
                'field'    => 'term_id',
                'terms'    => $genre_terms,
            );
        }
        if ( ! empty( $tag_terms ) ) {
            $tax_query[] = array(
                'taxonomy' => 'summary_tag',
                'field'    => 'term_id',
                'terms'    => $tag_terms,
            );
        }
        $args['tax_query'] = $tax_query;
    }

    $related = new WP_Query( $args );

    if ( $related->have_posts() ) :
        ?>
        <ul class="br-related-list">
            <?php
            while ( $related->have_posts() ) :
                $related->the_post();
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>" class="br-related">
                        <div class="br-related__cover">
                            <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'thumbnail' );
                            } else {
                                echo '<div class="br-related__placeholder"></div>';
                            }
                            ?>
                        </div>
                        <div>
                            <strong><?php the_title(); ?></strong>
                            <span class="br-meta"><span aria-hidden="true">📖</span><?php echo esc_html( briefreads_estimated_reading_time() ); ?></span>
                        </div>
                    </a>
                </li>
                <?php
            endwhile;
            ?>
        </ul>
        <?php
        wp_reset_postdata();
    endif;
}

/**
 * Retrieve hero floating covers.
 */
function briefreads_hero_floating_covers() {
    $covers = array();

    $query = new WP_Query(
        array(
            'post_type'      => 'book_summary',
            'posts_per_page' => 6,
            'orderby'        => 'rand',
            'fields'         => 'ids',
        )
    );

    if ( $query->have_posts() ) {
        $covers = $query->posts;
    }

    return $covers;
}
