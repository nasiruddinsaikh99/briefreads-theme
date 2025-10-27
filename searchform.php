<?php
/**
 * Custom search form
 *
 * @package BriefReads
 */
?>
<form role="search" method="get" class="br-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'briefreads' ); ?></label>
    <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search summaries…', 'briefreads' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
    <input type="hidden" name="post_type" value="book_summary" />
    <button type="submit" class="br-button">🔍 <?php esc_html_e( 'Search', 'briefreads' ); ?></button>
</form>
