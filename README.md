# BriefReads WordPress Theme

BriefReads is a modern, minimalist WordPress theme crafted for book summary platforms. It delivers a calm reading experience, immersive audio playback, and deep discovery features optimised for short-form book digests.

## Highlights
- Custom post type **Book Summary** with genre and tag taxonomies
- ACF-powered options for colour palette, typography, and global content blocks
- Rich single summary layout featuring key takeaways, sticky table of contents, reading progress bar, and floating audio player with resume support
- Front page with hero search, featured carousel, category grid, latest and trending sections
- Advanced archive filters for genre, reading time, and sorting
- Dark mode, font and line-height controls, estimated time remaining, and smooth TOC scroll
- Bookmark library integrated with user meta and local storage fallback for guests
- Mobile-first design with sticky bottom navigation and collapsible audio mini-player

## Installation
1. Copy the theme folder into your WordPress `wp-content/themes/` directory.
2. Activate **BriefReads** from the WordPress dashboard.
3. Install and activate the free [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) plugin to unlock the pre-defined field groups and options panel. The theme gracefully degrades when ACF is not active.
4. Visit **BriefReads Settings** under the WordPress menu to configure brand colours, typography, footer details, and hero copy.

## Content Authoring
- Publish book summaries using the **Book Summary** post type.
- Assign genres (hierarchical) and tags (non-hierarchical) for discovery.
- Upload cover art, add key takeaways, audio files, author bios, and purchase links through the provided ACF fields.
- Mark summaries as featured to display them in the home page carousel.

## Developer Notes
- Global helper functions live in `inc/helpers.php` and `inc/template-tags.php`.
- JavaScript features are bundled in `assets/js/main.js` (UX enhancements) and `assets/js/audio-player.js` (sticky player).
- Editor styles are provided via `assets/css/editor.css` to mirror the front-end typography inside Gutenberg.

## Accessibility & Performance
- Semantic markup and keyboard navigable components
- Prefers-reduced-motion support, lazy-friendly image aspect ratios, and responsive design tokens
- Local storage persists theme preference, font size, line height, and audio progress

## Extending
The theme is intentionally modular—add custom template parts under `template-parts/components/`, extend ACF groups in `inc/acf.php`, or adjust the SCSS-style design tokens at the top of `style.css` to evolve the brand system.
