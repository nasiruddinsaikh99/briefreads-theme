<?php
/**
 * Default template fallback.
 *
 * @package BriefReads
 */

get_header();
?>
<div class="container br-section">
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
        <p><?php esc_html_e( 'No content found.', 'briefreads' ); ?></p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
