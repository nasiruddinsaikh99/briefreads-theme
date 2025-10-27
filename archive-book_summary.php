<?php
/**
 * Archive template for book summaries.
 *
 * @package BriefReads
 */

get_header();
?>
<section class="br-section">
    <div class="container">
        <header class="br-section__header">
            <div>
                <h1 class="br-section__title"><?php post_type_archive_title(); ?></h1>
                <?php if ( is_post_type_archive() ) : ?>
                    <p class="br-section__subtitle"><?php esc_html_e( 'Filter by reading time, category, or popularity to discover your next read.', 'briefreads' ); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <div class="br-archive-layout">
            <aside class="br-archive-filters">
                <form method="get" class="br-filters-form">
                    <h2><?php esc_html_e( 'Filters', 'briefreads' ); ?></h2>
                    <div class="br-filter-group">
                        <h3><?php esc_html_e( 'Genre', 'briefreads' ); ?></h3>
                        <?php
                        $terms = get_terms(
                            array(
                                'taxonomy'   => 'summary_genre',
                                'hide_empty' => false,
                            )
                        );
                        foreach ( $terms as $term ) :
                            $checked = isset( $_GET['genre'] ) && in_array( $term->slug, (array) $_GET['genre'], true );
                            ?>
                            <label>
                                <input type="checkbox" name="genre[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( $checked ); ?> />
                                <span><?php echo esc_html( $term->name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="br-filter-group">
                        <h3><?php esc_html_e( 'Reading time', 'briefreads' ); ?></h3>
                        <label><?php esc_html_e( 'Max minutes', 'briefreads' ); ?>
                            <input type="number" name="max_time" min="5" max="60" value="<?php echo isset( $_GET['max_time'] ) ? intval( $_GET['max_time'] ) : ''; ?>" />
                        </label>
                    </div>
                    <div class="br-filter-group">
                        <h3><?php esc_html_e( 'Sort by', 'briefreads' ); ?></h3>
                        <select name="sort">
                            <option value="latest" <?php selected( isset( $_GET['sort'] ) ? $_GET['sort'] : '', 'latest' ); ?>><?php esc_html_e( 'Latest', 'briefreads' ); ?></option>
                            <option value="popular" <?php selected( isset( $_GET['sort'] ) ? $_GET['sort'] : '', 'popular' ); ?>><?php esc_html_e( 'Popular', 'briefreads' ); ?></option>
                            <option value="rating" <?php selected( isset( $_GET['sort'] ) ? $_GET['sort'] : '', 'rating' ); ?>><?php esc_html_e( 'Rating', 'briefreads' ); ?></option>
                            <option value="title" <?php selected( isset( $_GET['sort'] ) ? $_GET['sort'] : '', 'title' ); ?>><?php esc_html_e( 'Title', 'briefreads' ); ?></option>
                        </select>
                    </div>
                    <button type="submit" class="br-button"><?php esc_html_e( 'Apply filters', 'briefreads' ); ?></button>
                </form>
            </aside>
            <div class="br-archive-results">
                <?php
                $meta_query = array();
                if ( isset( $_GET['max_time'] ) && $_GET['max_time'] ) {
                    $meta_query[] = array(
                        'key'     => 'briefreads_reading_time_minutes',
                        'value'   => intval( $_GET['max_time'] ),
                        'type'    => 'NUMERIC',
                        'compare' => '<=',
                    );
                }

                $tax_query = array();
                if ( isset( $_GET['genre'] ) && ! empty( $_GET['genre'] ) ) {
                    $tax_query[] = array(
                        'taxonomy' => 'summary_genre',
                        'field'    => 'slug',
                        'terms'    => array_map( 'sanitize_text_field', (array) $_GET['genre'] ),
                    );
                }

                $orderby = 'date';
                $order   = 'DESC';
                if ( isset( $_GET['sort'] ) ) {
                    switch ( $_GET['sort'] ) {
                        case 'popular':
                            $orderby = 'meta_value_num';
                            $args['meta_key'] = 'briefreads_views';
                            break;
                        case 'rating':
                            $orderby = 'meta_value_num';
                            $args['meta_key'] = 'briefreads_rating';
                            break;
                        case 'title':
                            $orderby = 'title';
                            $order   = 'ASC';
                            break;
                        default:
                            $orderby = 'date';
                    }
                }

                $paged = max( 1, get_query_var( 'paged' ) );
                $args  = array(
                    'post_type'      => 'book_summary',
                    'posts_per_page' => 9,
                    'paged'          => $paged,
                    'orderby'        => $orderby,
                    'order'          => $order,
                );

                if ( $meta_query ) {
                    $args['meta_query'] = $meta_query;
                }

                if ( $tax_query ) {
                    $args['tax_query'] = $tax_query;
                }

                $query = new WP_Query( $args );

                if ( $query->have_posts() ) :
                    echo '<div class="br-grid br-grid--3">';
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        get_template_part( 'template-parts/components/summary', 'card' );
                    endwhile;
                    echo '</div>';
                    global $wp_query;
                    $backup = $wp_query;
                    $wp_query = $query;
                    the_posts_pagination();
                    $wp_query = $backup;
                    wp_reset_postdata();
                else :
                    echo '<p>' . esc_html__( 'No summaries match your filters yet. Try adjusting your selection.', 'briefreads' ) . '</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
