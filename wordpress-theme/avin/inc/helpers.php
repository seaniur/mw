<?php
/**
 * Small shared utilities used across templates and admin screens.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inline SVG icon set. Kept as one lookup table so templates and the
 * "Key Features" meta box render the exact same marks — icons ship as
 * inline SVG (not an icon font) so they respect currentColor, need no
 * extra HTTP request, and never flash unstyled.
 */
function avin_icon(string $name, string $class = ''): string
{
    $icons = [
        'single-ingredient' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="2.5" fill="currentColor" stroke="none"/></svg>',
        'human-grade' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5 20 7v5c0 5-3.4 8.3-8 9.5-4.6-1.2-8-4.5-8-9.5V7z"/><path d="m9 12 2.2 2.2L15.5 10"/></svg>',
        'no-additives' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M7 7l10 10"/></svg>',
        'high-protein' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V9.5L12 4l8 5.5V20"/><path d="M9 20v-6h6v6"/></svg>',
        'low-moisture' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3s6 6.5 6 11a6 6 0 1 1-12 0c0-4.5 6-11 6-11Z"/></svg>',
        'chevron-down' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>',
        'chevron-end' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>',
        'arrow-end' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
        'menu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
        'close' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 6 12 12M18 6 6 18"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m20 20-4.3-4.3"/></svg>',
        'document' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M9 12h6M9 16h6"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>',
        'globe' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.4 2.3 3.6 5.2 3.6 8.5S14.4 18.2 12 20.5C9.6 18.2 8.4 15.3 8.4 12S9.6 5.8 12 3.5Z"/></svg>',
        'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5A9.4 9.4 0 0 0 3.8 16.8L2.5 21.5l4.8-1.3A9.4 9.4 0 1 0 12 2.5Zm5.5 13.3c-.2.6-1.3 1.2-1.9 1.3-.5.1-1.1.1-1.7-.1-.4-.1-1-.3-1.6-.6-2.9-1.3-4.7-4.2-4.9-4.4-.1-.2-1.2-1.6-1.2-3s.7-2.1 1-2.4c.2-.3.5-.4.7-.4h.5c.2 0 .4 0 .6.4.2.5.7 1.8.8 1.9.1.2.1.4 0 .6-.1.2-.2.3-.3.5-.2.2-.3.3-.5.5-.2.2-.3.4-.1.7.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.5 1.5.3.1.5.1.6-.1.2-.2.7-.8.9-1.1.2-.3.4-.2.6-.1.2.1 1.5.7 1.8.9.3.1.5.2.5.3.1.2.1.6-.1 1.1Z"/></svg>',
        // Business-line category icons (Coming Soon page boxes).
        'dog' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 8c-1.5-1.5-3-1.8-3-1s.7 2.6 2 3.8"/><path d="M17 8c1.5-1.5 3-1.8 3-1s-.7 2.6-2 3.8"/><path d="M6.5 10.5C6.5 7 9 5 12 5s5.5 2 5.5 5.5c0 3.8-2 6.5-3.3 7.6-.7.6-1.4.9-2.2.9s-1.5-.3-2.2-.9C7.5 17 6.5 14.3 6.5 10.5Z"/><circle cx="12" cy="13" r="0.8" fill="currentColor" stroke="none"/><path d="M10.3 15.2c.5.5 1.1.7 1.7.7s1.2-.2 1.7-.7"/></svg>',
        'chicken-feet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v10"/><path d="M12 14 6.5 20"/><path d="M12 14v7"/><path d="M12 14l5.5 6"/><path d="M5.5 19.3l1.6 1.6M11 20v1M16.9 19.3l-1.6 1.6"/></svg>',
        'powder' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3h8l1.5 4.5a6.5 6.5 0 1 1-11 0Z"/><path d="M9 8h6"/><circle cx="10.5" cy="13" r="0.6" fill="currentColor" stroke="none"/><circle cx="13.5" cy="14.5" r="0.6" fill="currentColor" stroke="none"/><circle cx="12" cy="17" r="0.6" fill="currentColor" stroke="none"/></svg>',
    ];

    if (!isset($icons[$name])) {
        return '';
    }

    return '<span class="icon icon-' . esc_attr($name) . ($class ? ' ' . esc_attr($class) : '') . '" aria-hidden="true">' . $icons[$name] . '</span>';
}

/**
 * True when $url matches (or is a parent path of) the current request —
 * used to set aria-current on nav links.
 */
function avin_nav_is_current(string $url): bool
{
    $current = untrailingslashit((string) wp_parse_url(home_url(add_query_arg([])), PHP_URL_PATH));
    $target = untrailingslashit((string) wp_parse_url($url, PHP_URL_PATH));

    if ($target === '') {
        return $current === $target;
    }

    return $current === $target || str_starts_with($current . '/', $target . '/');
}

/**
 * Builds the current page's breadcrumb trail from WP's own conditional
 * tags, so it's computed once and shared by both the visible <nav> here
 * and the BreadcrumbList JSON-LD in inc/schema.php (which runs in
 * <head>, before any template body — including a manually built trail —
 * would otherwise exist).
 *
 * @return array<int, array{label: string, url?: string}>
 */
function avin_get_breadcrumb_trail(): array
{
    $trail = [['label' => __('Home', 'avin'), 'url' => home_url('/')]];

    if (is_front_page()) {
        return $trail;
    }

    if (is_singular('product')) {
        $product_id = get_the_ID();
        $trail[] = ['label' => __('Products', 'avin'), 'url' => get_post_type_archive_link('product')];
        $lines = get_the_terms($product_id, 'business_line');
        if ($lines && !is_wp_error($lines)) {
            $trail[] = ['label' => $lines[0]->name, 'url' => avin_business_line_url($lines[0])];
        }
        $trail[] = ['label' => get_the_title($product_id)];
        return $trail;
    }

    if (is_tax('business_line')) {
        $term = get_queried_object();
        $trail[] = ['label' => __('Products', 'avin'), 'url' => get_post_type_archive_link('product')];
        $trail[] = ['label' => get_term_meta($term->term_id, 'avin_landing_heading', true) ?: $term->name];
        return $trail;
    }

    if (is_post_type_archive('product')) {
        $trail[] = ['label' => __('All Products', 'avin')];
        return $trail;
    }

    if (is_page()) {
        $trail[] = ['label' => get_the_title()];
        return $trail;
    }

    if (is_search()) {
        $trail[] = ['label' => __('Search Results', 'avin')];
        return $trail;
    }

    if (is_404()) {
        $trail[] = ['label' => __('Page Not Found', 'avin')];
        return $trail;
    }

    return $trail;
}

/**
 * Renders a breadcrumb trail as visible HTML. Omit $trail to have it
 * computed automatically from the current page (the normal case — every
 * template calls this with no arguments).
 *
 * @param array<int, array{label: string, url?: string}>|null $trail
 */
function avin_breadcrumbs(?array $trail = null): void
{
    $trail = $trail ?? avin_get_breadcrumb_trail();
    if (count($trail) < 2) {
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'avin') . '"><ol>';
    $last = count($trail) - 1;
    foreach ($trail as $i => $step) {
        echo '<li>';
        if ($i === $last || empty($step['url'])) {
            echo '<span aria-current="page">' . esc_html($step['label']) . '</span>';
        } else {
            echo '<a href="' . esc_url($step['url']) . '">' . esc_html($step['label']) . '</a>';
        }
        echo '</li>';
    }
    echo '</ol></nav>';
}

/**
 * Shortens a string to $limit characters on a word boundary, for card/menu
 * descriptions pulled from admin-entered term/post content.
 */
function avin_trim(string $text, int $limit = 110): string
{
    $text = trim(wp_strip_all_tags($text));
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $limit)) . '…';
}
