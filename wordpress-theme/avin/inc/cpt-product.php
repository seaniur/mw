<?php
/**
 * Product architecture: the `product` CPT plus two taxonomies.
 *
 * - `business_line` — now a genuinely hierarchical taxonomy: 3 top-level
 *   groups (Food, Pet Food, Feed) with the actual business lines as their
 *   children (e.g. Food > Chicken Feet Products, Pet Food > Freeze-Dried).
 *   This single tree is the site's whole navigation spine — it drives the
 *   URL structure (/food/chicken-feet-products/), the header's Products
 *   mega menu (groups = column 1, children = the cards), and the
 *   homepage's "What We Supply" cards (the groups again) — one source of
 *   truth instead of a parallel taxonomy just for the menu.
 * - `product_category` — lightweight, non-indexed grouping terms used only
 *   to section a landing page's product grid (Poultry / Marine / Fruits &
 *   Vegetables). Unaffected by the above — still linked to whichever
 *   business_line child term it belongs under via avin_business_line
 *   term meta (a term_id, so it survives the child terms being renamed).
 *
 * Products are nested under their full group/line path
 * (/food/chicken-feet-products/{product}/) via a custom %business_line_
 * path% rewrite tag — the same technique WooCommerce uses for
 * %product_cat%. Since post_name stays unique across the whole `product`
 * post type, the actual routing only ever depends on the last URL
 * segment; WordPress's own redirect_canonical() then 301s any
 * mismatched group/line prefix to the true canonical permalink (computed
 * by avin_product_permalink() below) — nothing extra to implement for
 * that part.
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
        'rewrite' => ['slug' => '%business_line_path%', 'with_front' => false],
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

/**
 * %business_line_path% captures a product permalink's entire group/line
 * prefix (e.g. "pet-food/freeze-dried") as one rewrite-rule segment, the
 * same way WooCommerce's %product_cat% tag works. Registered at priority
 * 5 so it exists before avin_register_product_cpt() (also on 'init',
 * default priority 10) builds the CPT's rewrite rules from it.
 */
function avin_add_rewrite_tags(): void
{
    add_rewrite_tag('%business_line_path%', '(.+?)', 'business_line_path=');
}
add_action('init', 'avin_add_rewrite_tags', 5);

function avin_add_query_vars(array $vars): array
{
    $vars[] = 'business_line_path';
    return $vars;
}
add_filter('query_vars', 'avin_add_query_vars');

/**
 * Resolves %business_line_path% to the requested product's actual
 * group/line path when WordPress builds its permalink — falls back to
 * "products" (so the URL still works, just ungrouped) for the rare
 * product with no business_line term set at all.
 */
function avin_product_permalink(string $link, WP_Post $post): string
{
    if ($post->post_type !== 'product' || strpos($link, '%business_line_path%') === false) {
        return $link;
    }

    $terms = get_the_terms($post, 'business_line');
    $term = ($terms && !is_wp_error($terms)) ? $terms[0] : null;
    $path = $term ? avin_term_hierarchical_path($term) : 'products';

    return str_replace('%business_line_path%', $path, $link);
}
add_filter('post_type_link', 'avin_product_permalink', 10, 2);

/**
 * A hierarchical term's full slug path, root-first (e.g. term "Freeze-
 * Dried" under parent "Pet Food" → "pet-food/freeze-dried"). Handles any
 * nesting depth, though this taxonomy is only ever 2 levels deep today.
 */
function avin_term_hierarchical_path(WP_Term $term): string
{
    $slugs = [$term->slug];
    foreach (get_ancestors($term->term_id, $term->taxonomy, 'taxonomy') as $ancestor_id) {
        $ancestor = get_term($ancestor_id, $term->taxonomy);
        if ($ancestor && !is_wp_error($ancestor)) {
            array_unshift($slugs, $ancestor->slug);
        }
    }
    return implode('/', $slugs);
}

function avin_register_taxonomies()
{
    register_taxonomy('business_line', 'product', [
        'labels' => [
            'name' => __('Business Lines', 'avin'),
            'singular_name' => __('Business Line', 'avin'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        // 'hierarchical' => true in the rewrite (not just the taxonomy
        // itself) is what makes WordPress generate the nested /group/
        // line/ rewrite rules a 2-level tree needs, not just single-
        // segment ones.
        'rewrite' => ['slug' => '', 'with_front' => false, 'hierarchical' => true],
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
        'avin_image' => 'integer',
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
/* Term edit screen: admin fields for business_line                     */
/*                                                                       */
/* Every business_line term — group or line — is now both a landing     */
/* page and (for lines) a mega-menu card, so this is where an editor    */
/* controls the copy/image both surfaces show, without touching code.   */
/* -------------------------------------------------------------------- */

function avin_business_line_fields(WP_Term $term = null): void
{
    $get = fn ($key, $default = '') => $term ? (get_term_meta($term->term_id, $key, true) ?: $default) : $default;
    $image_id = $term ? (int) get_term_meta($term->term_id, 'avin_image', true) : 0;
    $featured = $term ? (bool) get_term_meta($term->term_id, 'avin_featured', true) : false;
    ?>
	<div class="form-field">
		<label for="avin-bl-landing-heading"><?php esc_html_e('Landing Page Heading', 'avin'); ?></label>
		<input type="text" name="avin_business_line[landing_heading]" id="avin-bl-landing-heading" value="<?php echo esc_attr($get('avin_landing_heading')); ?>">
		<p><?php esc_html_e('Falls back to the term name if left empty.', 'avin'); ?></p>
	</div>
	<div class="form-field">
		<label for="avin-bl-hero-subtitle"><?php esc_html_e('Landing Page Subtitle', 'avin'); ?></label>
		<textarea name="avin_business_line[hero_subtitle]" id="avin-bl-hero-subtitle" rows="2"><?php echo esc_textarea($get('avin_hero_subtitle')); ?></textarea>
	</div>
	<div class="form-field">
		<label for="avin-bl-mega-description"><?php esc_html_e('Mega Menu Card Description', 'avin'); ?></label>
		<textarea name="avin_business_line[mega_description]" id="avin-bl-mega-description" rows="2"><?php echo esc_textarea($get('avin_mega_description')); ?></textarea>
		<p><?php esc_html_e('Short — shown on the mega menu card and the homepage category card.', 'avin'); ?></p>
	</div>
	<div class="form-field">
		<label><?php esc_html_e('Card / Landing Image', 'avin'); ?></label>
		<?php avin_render_media_picker('avin_business_line[image]', $image_id, false); ?>
	</div>
	<div class="form-field">
		<label for="avin-bl-order"><?php esc_html_e('Menu Order', 'avin'); ?></label>
		<input type="number" name="avin_business_line[order]" id="avin-bl-order" value="<?php echo esc_attr($get('avin_menu_order', 0)); ?>">
	</div>
	<?php if ($term && avin_term_is_group($term)) : ?>
		<p class="description"><?php esc_html_e('This is a top-level group (appears in the mega menu\'s first column) — "Featured" doesn\'t apply to it.', 'avin'); ?></p>
	<?php else : ?>
		<div class="form-field">
			<label>
				<input type="checkbox" name="avin_business_line[featured]" value="1"<?php checked($featured); ?>>
				<?php esc_html_e('Featured (highlighted card in the mega menu and homepage grid)', 'avin'); ?>
			</label>
		</div>
	<?php endif; ?>
	<?php
}

function avin_business_line_add_form_fields(): void
{
    echo '<div class="avin-field-group">';
    avin_business_line_fields();
    echo '</div>';
}
add_action('business_line_add_form_fields', 'avin_business_line_add_form_fields');

function avin_business_line_edit_form_fields(WP_Term $term): void
{
    echo '<div class="avin-field-group">';
    avin_business_line_fields($term);
    echo '</div>';
}
add_action('business_line_edit_form_fields', 'avin_business_line_edit_form_fields');

/**
 * WP core's own term-edit nonce (checked before wp_insert_term()/
 * wp_update_term() fire the created_/edited_ hooks below) already covers
 * these fields.
 */
function avin_save_business_line_meta(int $term_id): void
{
    if (!isset($_POST['avin_business_line']) || !current_user_can('manage_categories')) {
        return;
    }
    $data = wp_unslash($_POST['avin_business_line']);

    update_term_meta($term_id, 'avin_landing_heading', sanitize_text_field($data['landing_heading'] ?? ''));
    update_term_meta($term_id, 'avin_hero_subtitle', sanitize_textarea_field($data['hero_subtitle'] ?? ''));
    update_term_meta($term_id, 'avin_mega_description', sanitize_textarea_field($data['mega_description'] ?? ''));
    update_term_meta($term_id, 'avin_menu_order', (int) ($data['order'] ?? 0));
    update_term_meta($term_id, 'avin_featured', !empty($data['featured']));

    $image_id = absint($data['image'] ?? 0);
    if ($image_id && get_post_type($image_id) === 'attachment') {
        update_term_meta($term_id, 'avin_image', $image_id);
    } else {
        delete_term_meta($term_id, 'avin_image');
    }
}
add_action('created_business_line', 'avin_save_business_line_meta');
add_action('edited_business_line', 'avin_save_business_line_meta');

/* -------------------------------------------------------------------- */
/* Query helpers                                                        */
/* -------------------------------------------------------------------- */

/**
 * All business_line terms, both groups and lines, ordered by the
 * admin-set avin_menu_order (falling back to term_id). Cached once per
 * request — every helper below reads from this instead of its own
 * get_terms() call.
 *
 * @return WP_Term[]
 */
function avin_get_all_business_line_terms(): array
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
        return $order_a <=> $order_b ?: $a->term_id <=> $b->term_id;
    });

    return $cache = $terms;
}

/**
 * The 3 top-level groups (Food / Pet Food / Feed) — column 1 of the mega
 * menu, and the homepage's "What We Supply" cards.
 *
 * @return WP_Term[]
 */
function avin_get_business_line_groups(): array
{
    return array_values(array_filter(avin_get_all_business_line_terms(), fn (WP_Term $t) => (int) $t->parent === 0));
}

/**
 * The individual, sellable business lines (Chicken Feet Products,
 * Freeze-Dried, etc.) — i.e. every term that has a parent group. This is
 * "the 5 lines" wherever the theme lists them (footer, 404, contact
 * sidebar, the All Products filter pills).
 *
 * @return WP_Term[]
 */
function avin_get_business_lines(): array
{
    return array_values(array_filter(avin_get_all_business_line_terms(), fn (WP_Term $t) => (int) $t->parent !== 0));
}

/**
 * The lines belonging to one group, in menu order — used by both the
 * mega menu (cards under a hovered category) and taxonomy-business_line.
 * php when the queried term is itself a group (see avin_term_is_group()).
 *
 * @return WP_Term[]
 */
function avin_get_business_line_children(int $parent_term_id): array
{
    return array_values(array_filter(avin_get_all_business_line_terms(), fn (WP_Term $t) => (int) $t->parent === $parent_term_id));
}

function avin_term_is_group(WP_Term $term): bool
{
    return (int) $term->parent === 0;
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
 * then title. Since business_line is now hierarchical, querying a GROUP
 * term id also returns every product in its child lines — WP_Query's
 * default 'include_children' behavior for hierarchical taxonomies — so
 * this doubles as "everything in Food" with no extra code.
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
/* Legacy URL redirects — this theme's URL structure changed from flat   */
/* (/chicken-feet-products/, /products/{slug}/) to nested               */
/* (/food/chicken-feet-products/, /food/chicken-feet-products/{slug}/)   */
/* on an already-live site; these 301s keep old bookmarks/search results */
/* working instead of 404ing.                                           */
/* -------------------------------------------------------------------- */

function avin_legacy_business_line_slug_map(): array
{
    return [
        'freeze-dried-pet-food' => 'pet-food/freeze-dried',
        'air-dried-pet-food' => 'pet-food/air-dried',
        'freeze-dried-human-food' => 'food/freeze-dried-fruits-vegetables',
        'chicken-feet-products' => 'food/chicken-feet-products',
        'ingredients-solutions' => 'feed/ingredients-solutions',
    ];
}

function avin_redirect_legacy_urls(): void
{
    if (!is_404() || is_admin()) {
        return;
    }

    $path = trim((string) wp_parse_url(add_query_arg([]), PHP_URL_PATH), '/');
    if ($path === '') {
        return;
    }
    $segments = explode('/', $path);

    // /products/{slug}/ → wherever that product now lives.
    if ($segments[0] === 'products' && isset($segments[1])) {
        $product = get_page_by_path($segments[1], OBJECT, 'product');
        if ($product) {
            wp_safe_redirect(get_permalink($product), 301);
            exit;
        }
        return;
    }

    // /{old-business-line-slug}/(anything left over)/ → /{new group/line
    // path}/(same trailing segments)/, e.g. an old /chicken-feet-
    // products/some-product/ still finds the same product.
    $map = avin_legacy_business_line_slug_map();
    if (isset($map[$segments[0]])) {
        $remainder = array_slice($segments, 1);
        $new_path = trailingslashit(home_url('/' . $map[$segments[0]]));
        if (!empty($remainder)) {
            $new_path .= trailingslashit(implode('/', $remainder));
        }
        wp_safe_redirect($new_path, 301);
        exit;
    }
}
add_action('template_redirect', 'avin_redirect_legacy_urls', 5);

/* -------------------------------------------------------------------- */
/* Seed data — 3 groups, 5 business lines, their grouping categories    */
/* -------------------------------------------------------------------- */

/**
 * Idempotent and migration-aware: a term already published under one of
 * the pre-restructure flat slugs (see avin_legacy_business_line_slug_map())
 * is renamed/re-parented in place via wp_update_term() — preserving its
 * term_id (and therefore every product/product_category relationship
 * already pointing at it) — rather than creating a duplicate. A term
 * that doesn't exist under either its new or legacy slug is created
 * fresh. Runs once per site (see inc/setup.php), so later admin edits to
 * these terms are never overwritten.
 */
function avin_seed_taxonomy_terms(): void
{
    $groups = [
        'food' => ['name' => __('Food', 'avin'), 'order' => 1, 'heading' => __('Food', 'avin'), 'subtitle' => __('The best-sourced food products from qualified Iranian suppliers.', 'avin'), 'icon' => 'no-additives'],
        'pet-food' => ['name' => __('Pet Food', 'avin'), 'order' => 2, 'heading' => __('Pet Food', 'avin'), 'subtitle' => __('Directly manufactured, high-quality pet food products.', 'avin'), 'icon' => 'single-ingredient'],
        'feed' => ['name' => __('Feed', 'avin'), 'order' => 3, 'heading' => __('Feed', 'avin'), 'subtitle' => __('Selected ingredients and feed products for industrial buyers.', 'avin'), 'icon' => 'high-protein'],
    ];

    $group_ids = [];
    foreach ($groups as $slug => $data) {
        $term = get_term_by('slug', $slug, 'business_line');
        if (!$term) {
            $result = wp_insert_term($data['name'], 'business_line', ['slug' => $slug]);
            if (is_wp_error($result)) {
                continue;
            }
            $term = get_term($result['term_id'], 'business_line');
        }
        update_term_meta($term->term_id, 'avin_menu_order', $data['order']);
        update_term_meta($term->term_id, 'avin_landing_heading', $data['heading']);
        update_term_meta($term->term_id, 'avin_hero_subtitle', $data['subtitle']);
        update_term_meta($term->term_id, 'avin_mega_description', $data['subtitle']);
        update_term_meta($term->term_id, 'avin_icon', $data['icon']);
        $group_ids[$slug] = $term->term_id;
    }

    $lines = [
        'chicken-feet-products' => [
            'name' => __('Chicken Feet & Paws', 'avin'),
            'group' => 'food',
            'legacy_slug' => null,
            'order' => 1,
            'featured' => 0,
            'icon' => 'high-protein',
            'description' => __('Raw and frozen chicken feet and paws for international B2B buyers.', 'avin'),
            'landing_heading' => __('Chicken Feet & Paws', 'avin'),
            'hero_subtitle' => __('Chicken feet, paws, and value-added derivatives — graded, specified, and export-ready.', 'avin'),
            'groups' => [],
        ],
        'freeze-dried-fruits-vegetables' => [
            'name' => __('Freeze-Dried Fruits & Vegetables', 'avin'),
            'group' => 'food',
            'legacy_slug' => 'freeze-dried-human-food',
            'order' => 2,
            'featured' => 0,
            'icon' => 'no-additives',
            'description' => __('100% single-ingredient freeze-dried fruits and vegetables for human food applications.', 'avin'),
            'hero_subtitle' => __('Single-ingredient freeze-dried fruits and vegetables, processed to human food standards.', 'avin'),
            'groups' => ['fruits' => __('Fruits', 'avin'), 'vegetables' => __('Vegetables', 'avin')],
        ],
        'freeze-dried' => [
            'name' => __('Freeze-Dried', 'avin'),
            'group' => 'pet-food',
            'legacy_slug' => 'freeze-dried-pet-food',
            'order' => 1,
            'featured' => 1,
            'icon' => 'single-ingredient',
            'description' => __('100% single-ingredient freeze-dried products for dogs and cats.', 'avin'),
            'hero_subtitle' => __('Freeze-dried products made with human-grade raw materials, for pet food and pet treat manufacturers.', 'avin'),
            'groups' => ['poultry' => __('Poultry', 'avin'), 'marine' => __('Marine', 'avin'), 'fruits-vegetables' => __('Fruits & Vegetables', 'avin')],
        ],
        'air-dried' => [
            'name' => __('Air-Dried', 'avin'),
            'group' => 'pet-food',
            'legacy_slug' => 'air-dried-pet-food',
            'order' => 2,
            'featured' => 0,
            'icon' => 'low-moisture',
            'description' => __('High-quality air-dried animal protein products for the pet food industry.', 'avin'),
            'hero_subtitle' => __('Air-dried animal proteins for pet food and pet treat manufacturers, sourced and processed to the same quality standard as our freeze-dried line.', 'avin'),
            'groups' => ['poultry' => __('Poultry', 'avin'), 'marine' => __('Marine', 'avin'), 'fruits-vegetables' => __('Fruits & Vegetables', 'avin')],
        ],
        'ingredients-solutions' => [
            'name' => __('Animal Protein Ingredients', 'avin'),
            'group' => 'feed',
            'legacy_slug' => null,
            'order' => 1,
            'featured' => 0,
            'icon' => 'human-grade',
            'description' => __('Animal-origin protein ingredients for pet food and industrial applications.', 'avin'),
            'landing_heading' => __('Ingredients & Solutions', 'avin'),
            'hero_subtitle' => __('Poultry, blood, and fish protein powders supplied to pet food and industrial formulators.', 'avin'),
            'groups' => [],
        ],
    ];

    foreach ($lines as $slug => $data) {
        $parent_id = $group_ids[$data['group']] ?? 0;

        $term = get_term_by('slug', $slug, 'business_line');
        if (!$term && $data['legacy_slug']) {
            $term = get_term_by('slug', $data['legacy_slug'], 'business_line');
        }

        if ($term) {
            if ($term->slug !== $slug || (int) $term->parent !== $parent_id) {
                $updated = wp_update_term($term->term_id, 'business_line', [
                    'slug' => $slug,
                    'parent' => $parent_id,
                ]);
                if (!is_wp_error($updated)) {
                    $term = get_term($term->term_id, 'business_line');
                }
            }
        } else {
            $result = wp_insert_term($data['name'], 'business_line', ['slug' => $slug, 'parent' => $parent_id, 'description' => $data['description']]);
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
        update_term_meta($term->term_id, 'avin_cta_primary_label', __('View Products', 'avin'));
        update_term_meta($term->term_id, 'avin_cta_primary_url', '#products');
        update_term_meta($term->term_id, 'avin_cta_secondary_label', __('Request Partnership', 'avin'));
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
