<?php
/**
 * Template for My Library page.
 *
 * @package BriefReads
 */

/*
Template Name: My Library
*/

get_header();
?>
<section class="br-section">
    <div class="container">
        <header class="br-section__header">
            <div>
                <h1 class="br-section__title"><?php esc_html_e( 'My Library', 'briefreads' ); ?></h1>
                <p class="br-section__subtitle"><?php esc_html_e( 'Saved summaries are stored per user. Sign in to sync across devices.', 'briefreads' ); ?></p>
            </div>
        </header>
        <div id="br-library" data-component="library" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>">
            <?php if ( is_user_logged_in() ) : ?>
                <?php
                $bookmarks = briefreads_get_user_bookmarks();
                if ( $bookmarks ) {
                    $query = new WP_Query(
                        array(
                            'post_type'      => 'book_summary',
                            'post__in'       => $bookmarks,
                            'posts_per_page' => -1,
                        )
                    );

                    if ( $query->have_posts() ) {
                        echo '<div class="br-grid br-grid--3">';
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            get_template_part( 'template-parts/components/summary', 'card' );
                        }
                        echo '</div>';
                        wp_reset_postdata();
                    }
                } else {
                    echo '<p>' . esc_html__( 'You have no saved summaries yet. Start exploring and tap the bookmark icon to add them here.', 'briefreads' ) . '</p>';
                }
                ?>
            <?php else : ?>
                <p><?php esc_html_e( 'Sign in to store bookmarks across sessions. We also keep your reading progress synced.', 'briefreads' ); ?></p>
                <?php wp_login_form(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
