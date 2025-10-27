<?php
/**
 * Front page template.
 *
 * @package BriefReads
 */

get_header();
?>

<?php get_template_part( 'template-parts/components/hero' ); ?>

<section class="br-section">
    <div class="container">
        <div class="br-section__header">
            <h2 class="br-section__title"><?php esc_html_e( 'Editor\'s Picks', 'briefreads' ); ?></h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'book_summary' ) ); ?>" class="br-button br-button--outline"><?php esc_html_e( 'View all summaries', 'briefreads' ); ?></a>
        </div>
        <div class="br-carousel">
            <?php
            $featured = new WP_Query(
                array(
                    'post_type'      => 'book_summary',
                    'posts_per_page' => 6,
                    'meta_key'       => 'briefreads_featured',
                    'meta_value'     => '1',
                )
            );

            if ( ! $featured->have_posts() ) {
                $featured = new WP_Query(
                    array(
                        'post_type'      => 'book_summary',
                        'posts_per_page' => 6,
                        'orderby'        => 'rand',
                    )
                );
            }

            if ( $featured->have_posts() ) :
                while ( $featured->have_posts() ) :
                    $featured->the_post();
                    ?>
                    <article class="br-card br-carousel__item">
                        <a class="br-book-card__cover" href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
                            <span class="br-book-card__badge"><?php esc_html_e( "Editor's Pick", 'briefreads' ); ?></span>
                        </a>
                        <div>
                            <span class="br-pill"><?php echo esc_html( briefreads_estimated_reading_time() ); ?></span>
                            <h3 class="br-book-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="br-book-card__excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<section class="br-section">
    <div class="container">
        <div class="br-section__header">
            <h2 class="br-section__title"><?php esc_html_e( 'Categories', 'briefreads' ); ?></h2>
            <p class="br-section__subtitle"><?php esc_html_e( 'Explore by genre or theme', 'briefreads' ); ?></p>
        </div>
        <div class="br-grid br-grid--3">
            <?php
            $terms = get_terms(
                array(
                    'taxonomy'   => 'summary_genre',
                    'hide_empty' => true,
                )
            );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                foreach ( $terms as $term ) :
                    ?>
                    <a class="br-category-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                        <div class="br-category-card__icon" aria-hidden="true">📚</div>
                        <strong><?php echo esc_html( $term->name ); ?></strong>
                        <span><?php echo esc_html( sprintf( _n( '%d summary', '%d summaries', $term->count, 'briefreads' ), $term->count ) ); ?></span>
                    </a>
                    <?php
                endforeach;
            else :
                echo '<p>' . esc_html__( 'Add summary genres to highlight categories.', 'briefreads' ) . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<section class="br-section">
    <div class="container">
        <div class="br-section__header">
            <h2 class="br-section__title"><?php esc_html_e( 'Latest Summaries', 'briefreads' ); ?></h2>
            <a class="br-button br-button--outline" href="<?php echo esc_url( get_post_type_archive_link( 'book_summary' ) ); ?>"><?php esc_html_e( 'See all', 'briefreads' ); ?></a>
        </div>
        <div class="br-grid br-grid--3">
            <?php
            $latest = new WP_Query(
                array(
                    'post_type'      => 'book_summary',
                    'posts_per_page' => 6,
                )
            );

            if ( $latest->have_posts() ) :
                while ( $latest->have_posts() ) :
                    $latest->the_post();
                    get_template_part( 'template-parts/components/summary', 'card' );
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'No summaries yet. Start publishing to see them here.', 'briefreads' ) . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<section class="br-section">
    <div class="container">
        <div class="br-section__header">
            <h2 class="br-section__title"><?php esc_html_e( 'Trending Now', 'briefreads' ); ?></h2>
            <p class="br-section__subtitle"><?php esc_html_e( 'Based on reads and bookmarks', 'briefreads' ); ?></p>
        </div>
        <div class="br-carousel">
            <?php
            $trending = new WP_Query(
                array(
                    'post_type'      => 'book_summary',
                    'posts_per_page' => 6,
                    'meta_key'       => 'briefreads_views',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                )
            );

            if ( $trending->have_posts() ) :
                while ( $trending->have_posts() ) :
                    $trending->the_post();
                    ?>
                    <article class="br-card br-carousel__item">
                        <a class="br-book-card__cover" href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
                        </a>
                        <div>
                            <h3 class="br-book-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="br-meta"><span aria-hidden="true">📖</span><?php echo esc_html( briefreads_estimated_reading_time() ); ?></p>
                            <p class="br-meta"><span aria-hidden="true">👁</span><?php echo esc_html( briefreads_human_number( briefreads_get_summary_meta( get_the_ID(), 'views', 0 ) ) ); ?></p>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'Summaries will appear here as they gain traction.', 'briefreads' ) . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
