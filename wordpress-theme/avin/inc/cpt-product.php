<?php
/**
 * Product architecture: the `product` CPT plus two taxonomies.
 *
 * - `business_line`  — the 5 top-level lines from the brief (Freeze-Dried
 *   Pet Food, Air-Dried Pet Food, Chicken Feet & Paws, Freeze-Dried Fruits
 *   & Vegetables, Animal Protein Ingredients). Flat, one per product. Its
 *   archive *is* the business-line landing page and sits at the site root
 *   (e.g. /freeze-dried-pet-food/) per the brief's SEO architecture.
 * - `product_category` — lightweight, non-indexed grouping terms used only
 *   to section a landing page's product grid (Poultry / Marine / Fruits &
 *   Vegetables). The brief is explicit that these should NOT become their
 *   own pages, so the taxonomy is registered without rewrite rules.
 *
 * Products live at a flat /products/{slug}/ URL rather than nested under
 * their business line. That's deliberate: a product's business line can
 * change (a SKU gets reclassified) without ever breaking or 301-redirecting
 * its canonical URL — a flat structure the brief's own "no attribute-
 * combination URLs" principle (section 13) argues for.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_register_product_cpt()
{
    register_post_type('product', [
        'labels' => [
            'name' => __('Products', 'avin'),
            'singular_name' => __('Product', 'avin'),
            'add_new_item' => __('Add New Product', 'avin'),
            'edit_item' => __('Edit Product', 'avin'),
            'all_items' => __('All Products', 'avin'),
            'search_items' => __('Search Products', 'avin'),
            'not_found' => __('No products found', 'avin'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-archive',
        'menu_position' => 5,
        'hierarchical' => false,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'has_archive' => 'products',
        'rewrite' => ['slug' => 'products', 'with_front' => false],
        'taxonomies' => ['business_line', 'product_category'],
    ]);

    register_post_type('inquiry', [
        'labels' => [
            'name' => __('Inquiries', 'avin'),
            'singular_name' => __('Inquiry', 'avin'),
            'all_items' => __('Inquiries', 'avin'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title'],
        'capabilities' => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap' => true,
    ]);
}
add_action('init', 'avin_register_product_cpt');

function avin_register_taxonomies()
{
    register_taxonomy('business_line', 'product', [
        'labels' => [
            'name' => __('Business Lines', 'avin'),
            'singular_name' => __('Business Line', 'avin'),
        ],
        'public' => true,
        'show_in_rest' => true,
        // Hierarchical purely to get WordPress's checkbox-style admin UI
        // (like Categories) instead of the free-type tag input — there
        // are only ever 5 business lines, an editor should pick one from
        // a list, not type a term name. No term actually uses a parent.
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => '', 'with_front' => false],
    ]);

    register_taxonomy('product_category', 'product', [
        'labels' => [
            'name' => __('Product Category Groups', 'avin'),
            'singular_name' => __('Category Group', 'avin'),
        ],
        'public' => true,
        'publicly_queryable' => false,
        'show_in_rest' => true,
        // Same reasoning as business_line above: checkbox UI for a small
        // controlled set (Poultry / Marine / Fruits & Vegetables, etc.).
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => false,
    ]);
}
add_action('init', 'avin_register_taxonomies');

/**
 * Term meta registered so it's available in the block editor / REST and so
 * get_term_meta() calls elsewhere don't need a magic string list.
 */
function avin_register_term_meta()
{
    $business_line_meta = [
        'avin_menu_order' => 'integer',
        'avin_featured' => 'boolean',
        'avin_icon' => 'string',
        'avin_landing_heading' => 'string',
        'avin_hero_subtitle' => 'string',
        'avin_mega_description' => 'string',
        'avin_cta_primary_label' => 'string',
        'avin_cta_primary_url' => 'string',
        'avin_cta_secondary_label' => 'string',
        'avin_cta_secondary_url' => 'string',
    ];
    foreach ($business_line_meta as $key => $type) {
        register_term_meta('business_line', $key, [
            'type' => $type,
            'single' => true,
            'show_in_rest' => true,
        ]);
    }

    $category_meta = [
        'avin_business_line' => 'integer',
        'avin_menu_order' => 'integer',
        'avin_display_label' => 'string',
    ];
    foreach ($category_meta as $key => $type) {
        register_term_meta('product_category', $key, [
            'type' => $type,
            'single' => true,
            'show_in_rest' => true,
        ]);
    }
}
add_action('init', 'avin_register_term_meta');

/* -------------------------------------------------------------------- */
/* Query helpers                                                        */
/* -------------------------------------------------------------------- */

/**
 * All business_line terms ordered the way they should appear in the mega
 * menu and any "our business lines" grid — by the admin-set avin_menu_order,
 * falling back to term_id (creation order) so freshly added lines still
 * show up somewhere sane.
 *
 * @return WP_Term[]
 */
function avin_get_business_lines(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $terms = get_terms(['taxonomy' => 'business_line', 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        return [];
    }

    usort($terms, function (WP_Term $a, WP_Term $b) {
        $order_a = (int) get_term_meta($a->term_id, 'avin_menu_order', true);
        $order_b = (int) get_term_meta($b->term_id, 'avin_menu_order', true);
        if ($order_a === $order_b) {
            return $a->term_id <=> $b->term_id;
        }
        return $order_a <=> $order_b;
    });

    return $cache = $terms;
}

function avin_get_featured_business_line(): ?WP_Term
{
    foreach (avin_get_business_lines() as $line) {
        if (get_term_meta($line->term_id, 'avin_featured', true)) {
            return $line;
        }
    }
    return null;
}

/**
 * product_category terms scoped to one business line, ordered by
 * avin_menu_order. Used to render the Poultry / Marine / Fruits &
 * Vegetables sections on a landing page's product grid.
 *
 * @return WP_Term[]
 */
function avin_get_category_groups_for_line(int $business_line_term_id): array
{
    $terms = get_terms([
        'taxonomy' => 'product_category',
        'hide_empty' => false,
        'meta_key' => 'avin_business_line',
        'meta_value' => $business_line_term_id,
    ]);

    if (is_wp_error($terms)) {
        return [];
    }

    usort($terms, function (WP_Term $a, WP_Term $b) {
        $order_a = (int) get_term_meta($a->term_id, 'avin_menu_order', true);
        $order_b = (int) get_term_meta($b->term_id, 'avin_menu_order', true);
        return $order_a <=> $order_b ?: $a->term_id <=> $b->term_id;
    });

    return $terms;
}

/**
 * Published products directly in a business line (optionally further
 * filtered to one category group), newest-curated-first via menu_order
 * then title.
 *
 * @return WP_Post[]
 */
function avin_get_products(int $business_line_term_id, ?int $category_term_id = null, int $limit = -1): array
{
    $tax_query = [
        ['taxonomy' => 'business_line', 'field' => 'term_id', 'terms' => $business_line_term_id],
    ];
    if ($category_term_id) {
        $tax_query[] = ['taxonomy' => 'product_category', 'field' => 'term_id', 'terms' => $category_term_id];
    }

    $query = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'tax_query' => $tax_query,
        'no_found_rows' => true,
    ]);

    return $query->posts;
}

/**
 * Lets /products/?business_line=slug filter the "All Products" archive
 * without a separate template — the archive's own filter pills (see
 * archive-product.php) link back to themselves this way.
 */
function avin_filter_product_archive(WP_Query $query): void
{
    if (is_admin() || !$query->is_main_query() || !is_post_type_archive('product')) {
        return;
    }
    if (!empty($_GET['business_line'])) {
        $query->set('tax_query', [[
            'taxonomy' => 'business_line',
            'field' => 'slug',
            'terms' => sanitize_title(wp_unslash($_GET['business_line'])),
        ]]);
    }
}
add_action('pre_get_posts', 'avin_filter_product_archive');

function avin_business_line_url(WP_Term $term): string
{
    $url = get_term_link($term);
    return is_wp_error($url) ? home_url('/') : $url;
}

/* -------------------------------------------------------------------- */
/* Seed data — five business lines + their grouping categories           */
/* -------------------------------------------------------------------- */

/**
 * Idempotent: only creates terms that don't already exist (matched by
 * slug), so re-running on every activation is safe and self-healing if a
 * term was accidentally deleted.
 */
function avin_seed_taxonomy_terms(): void
{
    $lines = [
        'freeze-dried-pet-food' => [
            'name' => __('Freeze-Dried Pet Food', 'avin'),
            'order' => 1,
            'featured' => 1,
            'icon' => 'single-ingredient',
            'description' => __('100% single-ingredient freeze-dried products for dogs and cats.', 'avin'),
            'hero_subtitle' => __('Freeze-dried products made with human-grade raw materials, for pet food and pet treat manufacturers.', 'avin'),
            'cta_primary' => __('View Products', 'avin'),
            'cta_secondary' => __('Request Partnership', 'avin'),
            'groups' => ['poultry' => __('Poultry', 'avin'), 'marine' => __('Marine', 'avin'), 'fruits-vegetables' => __('Fruits & Vegetables', 'avin')],
        ],
        'air-dried-pet-food' => [
            'name' => __('Air-Dried Pet Food', 'avin'),
            'order' => 2,
            'icon' => 'low-moisture',
            'description' => __('High-quality air-dried animal protein products for the pet food industry.', 'avin'),
            'hero_subtitle' => __('Air-dried animal proteins for pet food and pet treat manufacturers, sourced and processed to the same quality standard as our freeze-dried line.', 'avin'),
            'cta_primary' => __('View Products', 'avin'),
            'cta_secondary' => __('Request Partnership', 'avin'),
            'groups' => ['poultry' => __('Poultry', 'avin'), 'marine' => __('Marine', 'avin'), 'fruits-vegetables' => __('Fruits & Vegetables', 'avin')],
        ],
        'chicken-feet-products' => [
            'name' => __('Chicken Feet & Paws', 'avin'),
            'order' => 3,
            'icon' => 'high-protein',
            'description' => __('Raw and frozen chicken feet and paws for international B2B buyers.', 'avin'),
            'landing_heading' => __('Chicken Feet & Paws', 'avin'),
            'hero_subtitle' => __('Chicken feet, paws, and value-added derivatives — graded, specified, and export-ready.', 'avin'),
            'cta_primary' => __('View Products', 'avin'),
            'cta_secondary' => __('Request Partnership', 'avin'),
            'groups' => [],
        ],
        'freeze-dried-human-food' => [
            'name' => __('Freeze-Dried Fruits & Vegetables', 'avin'),
            'order' => 4,
            'icon' => 'no-additives',
            'description' => __('100% single-ingredient freeze-dried fruits and vegetables for human food applications.', 'avin'),
            'hero_subtitle' => __('Single-ingredient freeze-dried fruits and vegetables, processed to human food standards.', 'avin'),
            'cta_primary' => __('View Products', 'avin'),
            'cta_secondary' => __('Request Partnership', 'avin'),
            'groups' => ['fruits' => __('Fruits', 'avin'), 'vegetables' => __('Vegetables', 'avin')],
        ],
        'ingredients-solutions' => [
            'name' => __('Animal Protein Ingredients', 'avin'),
            'order' => 5,
            'icon' => 'human-grade',
            'description' => __('Animal-origin protein ingredients for pet food and industrial applications.', 'avin'),
            'landing_heading' => __('Ingredients & Solutions', 'avin'),
            'hero_subtitle' => __('Poultry, blood, and fish protein powders supplied to pet food and industrial formulators.', 'avin'),
            'cta_primary' => __('View Products', 'avin'),
            'cta_secondary' => __('Request Partnership', 'avin'),
            'groups' => [],
        ],
    ];

    foreach ($lines as $slug => $data) {
        $term = get_term_by('slug', $slug, 'business_line');
        if (!$term) {
            $result = wp_insert_term($data['name'], 'business_line', ['slug' => $slug, 'description' => $data['description']]);
            if (is_wp_error($result)) {
                continue;
            }
            $term = get_term($result['term_id'], 'business_line');
        }

        update_term_meta($term->term_id, 'avin_menu_order', $data['order']);
        update_term_meta($term->term_id, 'avin_featured', !empty($data['featured']) ? 1 : 0);
        update_term_meta($term->term_id, 'avin_icon', $data['icon']);
        update_term_meta($term->term_id, 'avin_mega_description', $data['description']);
        update_term_meta($term->term_id, 'avin_hero_subtitle', $data['hero_subtitle']);
        update_term_meta($term->term_id, 'avin_landing_heading', $data['landing_heading'] ?? $data['name']);
        // Hero CTAs on the landing page itself: "View Products" scrolls to
        // the on-page grid, "Request Partnership" goes to the inquiry page.
        update_term_meta($term->term_id, 'avin_cta_primary_label', $data['cta_primary']);
        update_term_meta($term->term_id, 'avin_cta_primary_url', '#products');
        update_term_meta($term->term_id, 'avin_cta_secondary_label', $data['cta_secondary']);
        update_term_meta($term->term_id, 'avin_cta_secondary_url', home_url('/contact/'));

        $group_order = 1;
        foreach ($data['groups'] as $group_slug => $group_name) {
            $full_slug = $slug . '-' . $group_slug;
            // Term *name* is disambiguated with its business line (multiple
            // lines reuse group names like "Poultry"/"Marine", and product
            // edit screens list every product_category term in one flat
            // checkbox tree) — avin_display_label carries the plain name
            // ("Poultry") that landing pages actually render as a heading.
            $admin_label = $data['name'] . ' — ' . $group_name;
            $group_term = get_term_by('slug', $full_slug, 'product_category');
            if (!$group_term) {
                $group_result = wp_insert_term($admin_label, 'product_category', ['slug' => $full_slug]);
                if (is_wp_error($group_result)) {
                    $group_order++;
                    continue;
                }
                $group_term = get_term($group_result['term_id'], 'product_category');
            }
            update_term_meta($group_term->term_id, 'avin_business_line', $term->term_id);
            update_term_meta($group_term->term_id, 'avin_menu_order', $group_order);
            update_term_meta($group_term->term_id, 'avin_display_label', $group_name);
            $group_order++;
        }
    }
}
