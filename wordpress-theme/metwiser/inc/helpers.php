<?php
/**
 * Small markup helpers shared across templates.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns the WhatsApp icon-link markup used next to phone numbers.
 *
 * @param string $phone Digits only, international format, e.g. "905375031493".
 */
function metwiser_whatsapp_link(string $phone): string
{
    $url = esc_url('https://wa.me/' . $phone);

    return '<a href="' . $url . '" target="_blank" rel="noreferrer noopener" aria-label="Chat on WhatsApp" class="shrink-0 text-muted transition-colors hover:text-orange">'
        . '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.87 9.87 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 1.67c2.24 0 4.35.87 5.94 2.46a8.29 8.29 0 0 1 2.44 5.9c0 4.6-3.75 8.35-8.35 8.35a8.35 8.35 0 0 1-4.25-1.16l-.3-.18-3.12.82.83-3.04-.2-.31a8.3 8.3 0 0 1-1.28-4.43c0-4.6 3.75-8.41 8.29-8.41Zm-4.6 4.42c-.16 0-.42.06-.64.31-.22.25-.85.83-.85 2.02s.87 2.35.99 2.51c.12.16 1.7 2.72 4.19 3.7 2.07.83 2.49.66 2.94.62.45-.04 1.45-.59 1.65-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28-.24-.12-1.45-.71-1.68-.8-.22-.08-.39-.12-.55.12-.16.24-.63.8-.77.96-.14.16-.28.18-.52.06-.24-.12-1.01-.37-1.93-1.19-.71-.63-1.19-1.42-1.33-1.66-.14-.24-.02-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.34-.76-1.83-.2-.48-.4-.42-.55-.42Z"/></svg>'
        . '</a>';
}

/**
 * Renders a section eyebrow: a small gradient dot + uppercase gold label.
 */
function metwiser_eyebrow(string $text, string $class = ''): void
{
    echo '<span class="inline-flex items-center gap-2 text-[0.7rem] font-semibold tracking-[0.18em] text-gold uppercase ' . esc_attr($class) . '">'
        . '<span class="h-1.5 w-1.5 rounded-full gradient-dot"></span>' . esc_html($text) . '</span>';
}

/**
 * The repeated "Text →" link pattern (arrow nudges on hover), used across
 * CTAs. $target = '_blank' to open in a new tab (external brand links).
 */
function metwiser_arrow_link(string $href, string $text, string $class = '', string $target = ''): void
{
    $rel = $target === '_blank' ? ' rel="noreferrer noopener"' : '';
    $target_attr = $target ? ' target="' . esc_attr($target) . '"' : '';
    echo '<a href="' . esc_url($href) . '"' . $target_attr . $rel . ' class="group inline-flex w-fit cursor-pointer items-center gap-2 text-sm font-medium tracking-tight text-ink transition-colors hover:text-orange ' . esc_attr($class) . '">'
        . esc_html($text)
        . metwiser_icon('arrow-up-right', 16, 'transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5')
        . '</a>';
}

/**
 * Equirectangular lat/lng -> 800x400 viewBox projection, matching the
 * dotted-map background image's projection so markers line up with it.
 */
function metwiser_map_project_point(float $lat, float $lng): array
{
    return [
        'x' => ($lng + 180) * (800 / 360),
        'y' => (90 - $lat) * (400 / 180),
    ];
}

/**
 * Quadratic-bezier arc between two projected points, curving upward
 * (same shape as the original React map's createCurvedPath).
 */
function metwiser_map_curved_path(array $start, array $end): string
{
    $mid_x = ($start['x'] + $end['x']) / 2;
    $mid_y = min($start['y'], $end['y']) - 50;

    return "M {$start['x']} {$start['y']} Q {$mid_x} {$mid_y} {$end['x']} {$end['y']}";
}
