<?php
/**
 * Summary card component for grid displays.
 *
 * @package BriefReads
 */
?>
<?php
$reading_time = briefreads_estimated_reading_time();
$rating       = briefreads_get_summary_meta( get_the_ID(), 'rating', 0 );
$views        = briefreads_get_summary_meta( get_the_ID(), 'views', 0 );
$audio        = briefreads_get_summary_meta( get_the_ID(), 'audio_url' );
?>
<article <?php post_class( 'br-card br-summary-card' ); ?>>
    <a class="br-summary-card__cover" href="<?php the_permalink(); ?>">
        <?php
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( 'medium_large' );
        } else {
            echo '<div class="br-related__placeholder"></div>';
        }
        ?>
    </a>
    <div class="br-summary-card__meta">
        <div class="br-summary-card__header">
            <?php briefreads_summary_terms(); ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </div>
        <p class="br-summary-card__excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
        <div class="br-summary-card__stats">
            <?php if ( $reading_time ) : ?>
                <span class="br-meta">📖 <?php echo esc_html( $reading_time ); ?></span>
            <?php endif; ?>
            <?php if ( $rating ) : ?>
                <span class="br-meta">⭐ <?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span>
            <?php endif; ?>
            <?php if ( $views ) : ?>
                <span class="br-meta">👁 <?php echo esc_html( briefreads_human_number( $views ) ); ?></span>
            <?php endif; ?>
            <?php if ( $audio ) : ?>
                <span class="br-meta">🎧 <?php esc_html_e( 'Audio', 'briefreads' ); ?></span>
            <?php endif; ?>
        </div>
        <div class="br-summary-card__actions">
            <span class="br-pill"><?php echo esc_html( $reading_time ); ?></span>
            <a class="br-button br-button--outline" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Summary', 'briefreads' ); ?></a>
            <?php if ( $audio ) : ?>
                <a class="br-button br-button--subtle" href="<?php the_permalink(); ?>#audio">🎧 <?php esc_html_e( 'Listen', 'briefreads' ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</article>
