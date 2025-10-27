<?php
/**
 * Search results template limited to summaries.
 *
 * @package BriefReads
 */

get_header();
?>
<section class="br-section">
    <div class="container">
        <header class="br-section__header">
            <div>
                <h1 class="br-section__title"><?php printf( esc_html__( 'Search results for "%s"', 'briefreads' ), get_search_query() ); ?></h1>
                <p class="br-section__subtitle"><?php esc_html_e( 'Refine your search or explore trending titles below.', 'briefreads' ); ?></p>
            </div>
        </header>
        <?php if ( have_posts() ) : ?>
            <div class="br-grid br-grid--3">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/components/summary', 'card' );
                endwhile;
                ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No summaries found. Try different keywords or browse categories.', 'briefreads' ); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
