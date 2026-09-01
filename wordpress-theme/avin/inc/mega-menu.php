<?php
/**
 * Data assembly for the Products mega menu. Markup lives in
 * template-parts/mega-menu.php (desktop) and template-parts/mobile-nav.php
 * — this file only decides *what* appears and in *what order*, pulled live
 * from the business_line taxonomy so a new business line or an edited
 * description shows up in the menu without touching a template.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Presentation grouping for the desktop mega menu's columns. Purely a
 * display concern (the brief's own example groups 4 of the 5 lines this
 * way and says "exact visual arrangement may be adapted"), so it's a
 * small filterable map rather than another taxonomy — nothing here changes
 * which business lines exist or how they're queried.
 *
 * @return array<string, string> business_line slug => column heading
 */
function avin_mega_menu_column_map(): array
{
    return apply_filters('avin_mega_menu_column_map', [
        'freeze-dried-pet-food' => __('Pet Food', 'avin'),
        'air-dried-pet-food' => __('Pet Food', 'avin'),
        'chicken-feet-products' => __('Animal Products', 'avin'),
        'freeze-dried-human-food' => __('Human Food', 'avin'),
        'ingredients-solutions' => __('Ingredients', 'avin'),
    ]);
}

/**
 * Business lines grouped into mega-menu columns, in column display order,
 * each line carrying the fields the template needs pre-resolved (URL,
 * description, featured flag) so template-parts/mega-menu.php stays pure
 * markup.
 *
 * @return array<int, array{heading: string, lines: array}>
 */
function avin_get_mega_menu_columns(): array
{
    $column_map = avin_mega_menu_column_map();
    $columns = [];

    foreach (avin_get_business_lines() as $line) {
        $heading = $column_map[$line->slug] ?? __('Products', 'avin');
        if (!isset($columns[$heading])) {
            $columns[$heading] = [];
        }

        $description = get_term_meta($line->term_id, 'avin_mega_description', true) ?: $line->description;

        $columns[$heading][] = [
            'term' => $line,
            'name' => $line->name,
            'url' => avin_business_line_url($line),
            'description' => avin_trim($description, 90),
            'featured' => (bool) get_term_meta($line->term_id, 'avin_featured', true),
            'icon' => get_term_meta($line->term_id, 'avin_icon', true) ?: 'single-ingredient',
        ];
    }

    $result = [];
    foreach ($columns as $heading => $lines) {
        $result[] = ['heading' => $heading, 'lines' => $lines];
    }

    return $result;
}
