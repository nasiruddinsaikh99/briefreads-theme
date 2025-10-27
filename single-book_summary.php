<?php
/**
 * Single book summary template.
 *
 * @package BriefReads
 */

global $post;
get_header();

$audio_url      = briefreads_get_summary_meta( get_the_ID(), 'audio_url' );
$audio_duration = briefreads_get_summary_meta( get_the_ID(), 'audio_duration', '' );
$rating         = briefreads_get_summary_meta( get_the_ID(), 'rating', 0 );
$views          = briefreads_get_summary_meta( get_the_ID(), 'views', 0 );
$publication    = briefreads_get_summary_meta( get_the_ID(), 'publication_year', '' );
$page_count     = briefreads_get_summary_meta( get_the_ID(), 'page_count', '' );
$affiliate      = briefreads_get_summary_meta( get_the_ID(), 'affiliate_url', '' );
$author_bio     = briefreads_get_summary_meta( get_the_ID(), 'author_bio', '' );
$author_photo   = briefreads_get_summary_meta( get_the_ID(), 'author_photo', '' );
$key_takeaways  = briefreads_get_summary_meta( get_the_ID(), 'key_takeaways', array() );
$summary_author = function_exists( 'get_field' ) ? get_field( 'summary_author' ) : '';
$outline        = function_exists( 'get_field' ) ? get_field( 'summary_outline' ) : array();
if ( is_string( $author_photo ) && ! empty( $author_photo ) ) {
    if ( is_numeric( $author_photo ) ) {
        $image = wp_get_attachment_image_src( (int) $author_photo, 'medium' );
        if ( $image ) {
            $author_photo = array( 'url' => $image[0], 'alt' => get_the_title() );
        }
    } elseif ( filter_var( $author_photo, FILTER_VALIDATE_URL ) ) {
        $author_photo = array( 'url' => $author_photo, 'alt' => get_the_title() );
    }
}

?>

<section class="container">
    <header class="br-summary-header">
        <div class="br-summary-header__cover">
            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
        </div>
        <div class="br-summary-header__meta">
            <?php briefreads_summary_terms(); ?>
            <h1><?php the_title(); ?></h1>
            <p class="br-summary-meta">
                <span>👤 <?php echo esc_html( $summary_author ? $summary_author : get_the_author() ); ?></span>
                <span>📖 <?php echo esc_html( briefreads_estimated_reading_time() ); ?></span>
                <?php if ( $rating ) : ?>
                    <span class="br-rating">⭐ <?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?>/5</span>
                <?php endif; ?>
                <span>👁 <?php echo esc_html( briefreads_human_number( $views ) ); ?> <?php esc_html_e( 'views', 'briefreads' ); ?></span>
            </p>
            <div class="br-summary-actions">
                <button class="br-button br-button--outline js-toggle-bookmark" data-post-id="<?php the_ID(); ?>">
                    <?php esc_html_e( 'Save', 'briefreads' ); ?>
                </button>
                <button class="br-button br-button--outline js-share-summary" data-url="<?php the_permalink(); ?>">
                    <?php esc_html_e( 'Share', 'briefreads' ); ?>
                </button>
                <?php if ( $audio_url ) : ?>
                    <button class="br-button br-button--accent js-init-audio" data-audio="<?php echo esc_url( $audio_url ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>">
                        <?php esc_html_e( 'Listen Now ▶', 'briefreads' ); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="br-summary-layout">
        <article class="br-summary-content">
            <section class="br-summary-pre-box">
                <h2><?php esc_html_e( 'What You\'ll Learn', 'briefreads' ); ?></h2>
                <ul>
                    <?php
                    if ( ! empty( $key_takeaways ) ) {
                        foreach ( $key_takeaways as $item ) {
                            $text = is_array( $item ) ? ( $item['takeaway'] ?? '' ) : $item;
                            if ( $text ) {
                                echo '<li>' . esc_html( $text ) . '</li>';
                            }
                        }
                    } else {
                        echo '<li>' . esc_html__( 'Key lessons from the book distilled into actionable insights.', 'briefreads' ) . '</li>';
                        echo '<li>' . esc_html__( 'How to apply the author’s framework in your daily life.', 'briefreads' ) . '</li>';
                        echo '<li>' . esc_html__( 'Memorable quotes and practical takeaways.', 'briefreads' ) . '</li>';
                    }
                    ?>
                </ul>
            </section>

            <section class="br-toc" data-component="toc">
                <button class="br-toc__toggle" type="button" aria-expanded="true">
                    <span><?php esc_html_e( 'Table of Contents', 'briefreads' ); ?></span>
                    <span aria-hidden="true">▾</span>
                </button>
                <ol class="br-toc__list">
                    <?php
                    if ( $outline && is_array( $outline ) ) {
                        foreach ( $outline as $row ) {
                            if ( ! empty( $row['heading'] ) ) {
                                printf( '<li><a href="#%1$s">%2$s</a></li>', sanitize_title( $row['heading'] ), esc_html( $row['heading'] ) );
                            }
                        }
                    } else {
                        $headings = briefreads_extract_headings( get_the_content() );
                        foreach ( $headings as $heading ) {
                            printf( '<li><a href="#%1$s">%2$s</a></li>', sanitize_title( $heading ), esc_html( $heading ) );
                        }
                    }
                    ?>
                </ol>
            </section>

            <div class="br-adjustments">
                <strong><?php esc_html_e( 'Customize your reading experience', 'briefreads' ); ?></strong>
                <button class="js-font-size" data-direction="-1">A-</button>
                <button class="js-font-size" data-direction="1">A+</button>
                <button class="js-line-height" data-direction="-1">⇣</button>
                <button class="js-line-height" data-direction="1">⇡</button>
                <span class="br-estimate" data-remaining><?php esc_html_e( 'Estimated time remaining: calculating…', 'briefreads' ); ?></span>
            </div>

            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </article>

        <aside class="br-sidebar">
            <section class="br-sidebar-card">
                <h3><?php esc_html_e( 'About the Book', 'briefreads' ); ?></h3>
                <ul>
                    <?php if ( $publication ) : ?>
                        <li>📅 <?php esc_html_e( 'Published', 'briefreads' ); ?>: <?php echo esc_html( $publication ); ?></li>
                    <?php endif; ?>
                    <?php if ( $page_count ) : ?>
                        <li>📄 <?php esc_html_e( 'Page count', 'briefreads' ); ?>: <?php echo esc_html( $page_count ); ?></li>
                    <?php endif; ?>
                    <li>🏷 <?php esc_html_e( 'Category', 'briefreads' ); ?>: <?php echo wp_kses_post( get_the_term_list( get_the_ID(), 'summary_genre', '', ', ' ) ); ?></li>
                </ul>
                <?php if ( $affiliate ) : ?>
                    <a class="br-button" href="<?php echo esc_url( $affiliate ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Buy the Full Book', 'briefreads' ); ?></a>
                <?php endif; ?>
            </section>

            <?php if ( $author_bio ) : ?>
                <section class="br-sidebar-card">
                    <h3><?php esc_html_e( 'About the Author', 'briefreads' ); ?></h3>
                    <?php if ( is_array( $author_photo ) && isset( $author_photo['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $author_photo['url'] ); ?>" alt="<?php echo esc_attr( $author_photo['alt'] ?? '' ); ?>" class="br-author-photo" />
                    <?php endif; ?>
                    <p><?php echo wp_kses_post( $author_bio ); ?></p>
                </section>
            <?php endif; ?>

            <section class="br-sidebar-card">
                <h3><?php esc_html_e( 'Related Summaries', 'briefreads' ); ?></h3>
                <?php briefreads_related_summaries( get_the_ID(), 4 ); ?>
            </section>

            <section class="br-sidebar-card br-newsletter">
                <h3><?php esc_html_e( 'Stay in the loop', 'briefreads' ); ?></h3>
                <p><?php esc_html_e( 'Get one actionable summary delivered daily.', 'briefreads' ); ?></p>
                <form class="br-newsletter__form">
                    <label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Email address', 'briefreads' ); ?></label>
                    <input type="email" id="newsletter-email" placeholder="<?php esc_attr_e( 'you@example.com', 'briefreads' ); ?>" required />
                    <button class="br-button" type="submit"><?php esc_html_e( 'Subscribe', 'briefreads' ); ?></button>
                </form>
            </section>
        </aside>
    </div>
</section>

<?php if ( $audio_url ) : ?>
<div id="audio" class="br-floating-player" data-component="audio-player" data-audio="<?php echo esc_url( $audio_url ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-duration="<?php echo esc_attr( $audio_duration ); ?>">
    <button class="br-player-toggle" type="button" aria-expanded="true">▾</button>
    <div class="br-floating-player__header">
        <div>
            <strong><?php the_title(); ?></strong>
            <div class="br-floating-player__chapter" data-chapter><?php esc_html_e( 'Chapter 1: Introduction', 'briefreads' ); ?></div>
        </div>
        <button class="br-button js-player-play" type="button">▶</button>
    </div>
    <div class="br-floating-player__controls">
        <button class="js-player-prev" type="button">⏮</button>
        <div class="br-floating-player__scrubber">
            <span data-current>00:00</span>
            <input type="range" min="0" max="100" value="0" step="1" />
            <span data-duration><?php echo esc_html( $audio_duration ? $audio_duration : '00:00' ); ?></span>
        </div>
        <button class="js-player-next" type="button">⏭</button>
        <button class="js-player-speed" type="button">1x</button>
        <div class="js-player-volume">
            <label class="screen-reader-text" for="br-volume"><?php esc_html_e( 'Volume', 'briefreads' ); ?></label>
            <input type="range" id="br-volume" min="0" max="1" step="0.05" value="1" />
        </div>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>
