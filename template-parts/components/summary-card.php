<?php
/**
 * Summary card component for grid displays.
 *
 * @package BriefReads
 */
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
        <div>
            <?php briefreads_summary_terms(); ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="br-summary-card__excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
        </div>
        <div class="br-summary-card__actions">
            <span class="br-pill"><?php echo esc_html( briefreads_estimated_reading_time() ); ?></span>
            <a class="br-button br-button--outline" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Summary', 'briefreads' ); ?></a>
            <?php if ( $audio = briefreads_get_summary_meta( get_the_ID(), 'audio_url' ) ) : ?>
                <a class="br-button br-button--outline" href="<?php the_permalink(); ?>#audio">🎧 <?php esc_html_e( 'Listen', 'briefreads' ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</article>
