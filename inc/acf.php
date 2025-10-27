<?php
/**
 * Register ACF option pages and field groups programmatically.
 *
 * @package BriefReads
 */

if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page(
        array(
            'page_title' => __( 'BriefReads Settings', 'briefreads' ),
            'menu_title' => __( 'BriefReads Settings', 'briefreads' ),
            'menu_slug'  => 'briefreads-settings',
            'capability' => 'manage_options',
            'redirect'   => false,
        )
    );
}

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group(
        array(
            'key'    => 'group_briefreads_theme',
            'title'  => __( 'Global Theme Settings', 'briefreads' ),
            'fields' => array(
                array(
                    'key'   => 'field_br_colors',
                    'label' => __( 'Brand Colors', 'briefreads' ),
                    'name'  => 'brand_colors',
                    'type'  => 'group',
                    'sub_fields' => array(
                        array(
                            'key'   => 'field_br_primary',
                            'label' => __( 'Primary', 'briefreads' ),
                            'name'  => 'primary_color',
                            'type'  => 'color_picker',
                            'default_value' => '#1a2332',
                        ),
                        array(
                            'key'   => 'field_br_accent',
                            'label' => __( 'Accent', 'briefreads' ),
                            'name'  => 'accent_color',
                            'type'  => 'color_picker',
                            'default_value' => '#ff6b6b',
                        ),
                        array(
                            'key'   => 'field_br_accent_alt',
                            'label' => __( 'Secondary Accent', 'briefreads' ),
                            'name'  => 'accent_alt_color',
                            'type'  => 'color_picker',
                            'default_value' => '#00b894',
                        ),
                        array(
                            'key'   => 'field_br_background',
                            'label' => __( 'Background', 'briefreads' ),
                            'name'  => 'background_color',
                            'type'  => 'color_picker',
                            'default_value' => '#fafafa',
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_br_typography',
                    'label' => __( 'Typography', 'briefreads' ),
                    'name'  => 'typography',
                    'type'  => 'group',
                    'sub_fields' => array(
                        array(
                            'key'   => 'field_br_base_font',
                            'label' => __( 'Base Font', 'briefreads' ),
                            'name'  => 'base_font',
                            'type'  => 'text',
                            'default_value' => 'Inter, Helvetica, Arial, sans-serif',
                        ),
                        array(
                            'key'   => 'field_br_heading_font',
                            'label' => __( 'Heading Font', 'briefreads' ),
                            'name'  => 'heading_font',
                            'type'  => 'text',
                            'default_value' => 'Playfair Display, Georgia, serif',
                        ),
                        array(
                            'key'   => 'field_br_font_size',
                            'label' => __( 'Base Font Size', 'briefreads' ),
                            'name'  => 'base_font_size',
                            'type'  => 'number',
                            'default_value' => 18,
                        ),
                        array(
                            'key'   => 'field_br_line_height',
                            'label' => __( 'Base Line Height', 'briefreads' ),
                            'name'  => 'base_line_height',
                            'type'  => 'number',
                            'default_value' => 1.75,
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_br_footer',
                    'label' => __( 'Footer Content', 'briefreads' ),
                    'name'  => 'footer_content',
                    'type'  => 'group',
                    'sub_fields' => array(
                        array(
                            'key'   => 'field_br_footer_description',
                            'label' => __( 'Description', 'briefreads' ),
                            'name'  => 'description',
                            'type'  => 'textarea',
                        ),
                        array(
                            'key'   => 'field_br_footer_social',
                            'label' => __( 'Social Links', 'briefreads' ),
                            'name'  => 'social_links',
                            'type'  => 'repeater',
                            'button_label' => __( 'Add Social Link', 'briefreads' ),
                            'sub_fields'   => array(
                                array(
                                    'key'   => 'field_br_social_label',
                                    'label' => __( 'Label', 'briefreads' ),
                                    'name'  => 'label',
                                    'type'  => 'text',
                                ),
                                array(
                                    'key'   => 'field_br_social_url',
                                    'label' => __( 'URL', 'briefreads' ),
                                    'name'  => 'url',
                                    'type'  => 'url',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_br_hero_headline',
                    'label' => __( 'Hero Headline', 'briefreads' ),
                    'name'  => 'hero_headline',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_br_hero_subtitle',
                    'label' => __( 'Hero Subtitle', 'briefreads' ),
                    'name'  => 'hero_subtitle',
                    'type'  => 'textarea',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'briefreads-settings',
                    ),
                ),
            ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_briefreads_summary',
            'title'  => __( 'Summary Details', 'briefreads' ),
            'fields' => array(
                array(
                    'key'   => 'field_br_summary_author',
                    'label' => __( 'Original Author', 'briefreads' ),
                    'name'  => 'summary_author',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_br_summary_rating',
                    'label' => __( 'Rating (0 - 5)', 'briefreads' ),
                    'name'  => 'rating',
                    'type'  => 'number',
                    'min'   => 0,
                    'max'   => 5,
                    'step'  => 0.1,
                ),
                array(
                    'key'   => 'field_br_summary_reading_time',
                    'label' => __( 'Reading Time', 'briefreads' ),
                    'name'  => 'reading_time',
                    'type'  => 'text',
                    'instructions' => __( 'E.g. 12 min read', 'briefreads' ),
                ),
                array(
                    'key'   => 'field_br_summary_audio',
                    'label' => __( 'Audio File', 'briefreads' ),
                    'name'  => 'audio_file',
                    'type'  => 'file',
                    'return_format' => 'url',
                ),
                array(
                    'key'   => 'field_br_summary_audio_duration',
                    'label' => __( 'Audio Duration', 'briefreads' ),
                    'name'  => 'audio_duration',
                    'type'  => 'text',
                    'instructions' => __( 'E.g. 18:30', 'briefreads' ),
                ),
                array(
                    'key'   => 'field_br_summary_takeaways',
                    'label' => __( 'Key Takeaways', 'briefreads' ),
                    'name'  => 'key_takeaways',
                    'type'  => 'repeater',
                    'button_label' => __( 'Add Takeaway', 'briefreads' ),
                    'sub_fields'   => array(
                        array(
                            'key'   => 'field_br_summary_takeaway',
                            'label' => __( 'Takeaway', 'briefreads' ),
                            'name'  => 'takeaway',
                            'type'  => 'textarea',
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_br_summary_outline',
                    'label' => __( 'Table of Contents', 'briefreads' ),
                    'name'  => 'summary_outline',
                    'type'  => 'repeater',
                    'button_label' => __( 'Add Section', 'briefreads' ),
                    'sub_fields'   => array(
                        array(
                            'key'   => 'field_br_summary_outline_heading',
                            'label' => __( 'Section Heading', 'briefreads' ),
                            'name'  => 'heading',
                            'type'  => 'text',
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_br_summary_featured',
                    'label' => __( 'Feature on homepage', 'briefreads' ),
                    'name'  => 'featured',
                    'type'  => 'true_false',
                    'ui'    => 1,
                ),
                array(
                    'key'   => 'field_br_summary_purchase',
                    'label' => __( 'Purchase URL', 'briefreads' ),
                    'name'  => 'affiliate_url',
                    'type'  => 'url',
                ),
                array(
                    'key'   => 'field_br_summary_publication',
                    'label' => __( 'Original Publication Year', 'briefreads' ),
                    'name'  => 'publication_year',
                    'type'  => 'number',
                ),
                array(
                    'key'   => 'field_br_summary_page_count',
                    'label' => __( 'Original Page Count', 'briefreads' ),
                    'name'  => 'page_count',
                    'type'  => 'number',
                ),
                array(
                    'key'   => 'field_br_summary_author_bio',
                    'label' => __( 'Author Bio', 'briefreads' ),
                    'name'  => 'author_bio',
                    'type'  => 'textarea',
                ),
                array(
                    'key'   => 'field_br_summary_author_photo',
                    'label' => __( 'Author Photo', 'briefreads' ),
                    'name'  => 'author_photo',
                    'type'  => 'image',
                    'return_format' => 'array',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'book_summary',
                    ),
                ),
            ),
        )
    );
}
