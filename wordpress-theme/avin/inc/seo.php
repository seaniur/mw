<?php
/**
 * Meta title/description + Open Graph/Twitter tags. Respects an SEO
 * plugin (Yoast, Rank Math, etc.) if one is later installed — this only
 * fills the gap when none is active, using the same admin-editable
 * fields (product SEO Title/Description, term descriptions, page
 * excerpts) rather than hard-coded copy.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_seo_plugin_active(): bool
{
    return defined('WPSEO_VERSION') || class_exists('RankMath') || defined('AIOSEO_VERSION') || defined('THE_SEO_FRAMEWORK_VERSION');
}

function avin_meta_title(): string
{
    if (is_singular('product')) {
        return avin_field(get_the_ID(), 'seo_title') ?: get_the_title() . ' — ' . get_bloginfo('name');
    }
    if (is_tax('business_line')) {
        $term = get_queried_object();
        $heading = get_term_meta($term->term_id, 'avin_landing_heading', true) ?: $term->name;
        return $heading . ' — ' . get_bloginfo('name');
    }
    if (is_front_page()) {
        return get_bloginfo('name') . ' — ' . get_bloginfo('description');
    }
    if (is_post_type_archive('product')) {
        return __('All Products', 'avin') . ' — ' . get_bloginfo('name');
    }
    if (is_singular()) {
        return get_the_title() . ' — ' . get_bloginfo('name');
    }
    return wp_get_document_title();
}

function avin_meta_description(): string
{
    if (is_singular('product')) {
        $custom = avin_field(get_the_ID(), 'seo_description');
        if ($custom) {
            return $custom;
        }
        $excerpt = get_the_excerpt();
        return $excerpt ?: avin_trim(wp_strip_all_tags(get_post_field('post_content', get_the_ID())), 155);
    }
    if (is_tax('business_line')) {
        $term = get_queried_object();
        $subtitle = get_term_meta($term->term_id, 'avin_hero_subtitle', true) ?: $term->description;
        return avin_trim($subtitle, 155);
    }
    if (is_front_page()) {
        return get_bloginfo('description') ?: __('Avin Tejarat Parto is an international B2B sourcing and supply partner for the pet food industry: freeze-dried and air-dried proteins, chicken feet & paws, freeze-dried fruits & vegetables, and animal protein ingredients.', 'avin');
    }
    if (is_singular() && has_excerpt()) {
        return avin_trim(get_the_excerpt(), 155);
    }
    return '';
}

function avin_output_meta_tags(): void
{
    if (avin_seo_plugin_active()) {
        return;
    }

    $description = avin_meta_description();
    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    $title = avin_meta_title();
    $image_id = null;
    if (is_singular('product') || is_singular()) {
        $image_id = get_post_thumbnail_id();
    }
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : null;

    $og_type = is_singular('product') ? 'product' : 'website';
    $tags = array_filter([
        'og:site_name' => get_bloginfo('name'),
        'og:type' => $og_type,
        'og:title' => $title,
        'og:description' => $description,
        'og:url' => avin_current_url(),
        'og:image' => $image_url,
        'twitter:card' => $image_url ? 'summary_large_image' : 'summary',
        'twitter:title' => $title,
        'twitter:description' => $description,
    ]);

    foreach ($tags as $property => $content) {
        $attr = str_starts_with($property, 'twitter:') ? 'name' : 'property';
        printf('<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr($attr), esc_attr($property), esc_attr($content));
    }
}
add_action('wp_head', 'avin_output_meta_tags', 1);

function avin_current_url(): string
{
    global $wp;
    return home_url(add_query_arg([], $wp->request ?? ''));
}

/**
 * Only overrides the <title> when no SEO plugin (which would do this
 * better, with its own templates/variables) is active.
 */
function avin_document_title_parts(array $title): array
{
    if (avin_seo_plugin_active()) {
        return $title;
    }
    if (is_singular('product')) {
        $custom = avin_field(get_the_ID(), 'seo_title');
        if ($custom) {
            $title['title'] = $custom;
        }
    } elseif (is_tax('business_line')) {
        $term = get_queried_object();
        $title['title'] = get_term_meta($term->term_id, 'avin_landing_heading', true) ?: $term->name;
    }
    return $title;
}
add_filter('document_title_parts', 'avin_document_title_parts');
