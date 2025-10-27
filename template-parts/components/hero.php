<?php
/**
 * Homepage hero component.
 *
 * @package BriefReads
 */

$covers = briefreads_hero_floating_covers();
?>
<section class="br-hero">
    <div class="br-hero__background"></div>
    <div class="br-hero__floating-covers">
        <?php
        $positions = array(
            array( 'top' => '10%', 'left' => '5%' ),
            array( 'top' => '20%', 'right' => '12%' ),
            array( 'bottom' => '10%', 'left' => '20%' ),
            array( 'bottom' => '15%', 'right' => '20%' ),
        );
        $i         = 0;
        foreach ( $covers as $cover_id ) {
            $style = isset( $positions[ $i ] ) ? $positions[ $i ] : array( 'top' => rand( 10, 60 ) . '%', 'left' => rand( 10, 70 ) . '%' );
            $i++;
            ?>
            <span style="<?php foreach ( $style as $key => $value ) { echo esc_attr( $key ) . ':' . esc_attr( $value ) . ';'; } ?>"></span>
            <?php
        }
        ?>
    </div>
    <div class="container">
        <div class="br-hero__content">
            <p class="br-pill"><?php esc_html_e( 'Curated Summaries', 'briefreads' ); ?></p>
            <h1 class="br-hero__title"><?php echo esc_html( briefreads_get_option( 'hero_headline', __( 'Read Books in Minutes, Not Days', 'briefreads' ) ) ); ?></h1>
            <p class="br-hero__subtitle"><?php echo wp_kses_post( briefreads_get_option( 'hero_subtitle', __( 'Digest the world’s most impactful books with concise, actionable summaries and immersive audio experiences.', 'briefreads' ) ) ); ?></p>
            <form class="br-hero__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label for="hero-search" class="screen-reader-text"><?php esc_html_e( 'Search summaries', 'briefreads' ); ?></label>
                <input type="search" id="hero-search" name="s" placeholder="<?php esc_attr_e( 'Search by title, author, or keyword…', 'briefreads' ); ?>" />
                <input type="hidden" name="post_type" value="book_summary" />
                <button type="submit" class="br-button">🔍 <?php esc_html_e( 'Search', 'briefreads' ); ?></button>
            </form>
            <div class="br-hero__metrics">
                <span>📚 <?php echo esc_html( ( wp_count_posts( 'book_summary' )->publish ?? 0 ) ); ?> <?php esc_html_e( 'summaries', 'briefreads' ); ?></span>
                <span>🎧 <?php esc_html_e( 'Immersive audio for every summary', 'briefreads' ); ?></span>
                <span>✨ <?php esc_html_e( 'New additions weekly', 'briefreads' ); ?></span>
            </div>
        </div>
    </div>
</section>
