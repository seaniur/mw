<?php
/**
 * The header's Products mega menu, as its own small data model rather
 * than a repurposing of the business_line/product_category taxonomy:
 * this menu's structure (FOOD / PET FOOD / FEED, each opening onto its
 * own 2-3 image cards) is a navigation/discovery surface designed
 * independently of exactly how the product catalog is organized, and
 * needs its own image + subtitle + link per entry — none of which the
 * product taxonomy carries. Two taxonomies, neither attached to the
 * `product` CPT's edit screen (meta_box_cb => false keeps them out of
 * the way there) but both manageable under Products in wp-admin:
 *
 * - `mega_category` — the 3 fixed column-1 selectors (FOOD, PET FOOD,
 *   FEED). Just a name + order; no extra fields.
 * - `mega_item` — the cards each category reveals (e.g. "Chicken Feet
 *   Products" under FOOD). Each has a parent mega_category, a subtitle,
 *   an optional background image, an Explore-link URL, and an order —
 *   edited via a small custom fields UI on the term edit screen (see
 *   the *_form_fields functions below), the same wp.media picker the
 *   product meta boxes use.
 */

if (!defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------- */
/* Taxonomies                                                            */
/* -------------------------------------------------------------------- */

function avin_register_mega_menu_taxonomies(): void
{
    register_taxonomy('mega_category', 'product', [
        'labels' => [
            'name' => __('Mega Menu Categories', 'avin'),
            'singular_name' => __('Mega Menu Category', 'avin'),
            'menu_name' => __('Mega Categories', 'avin'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => false,
        'meta_box_cb' => false,
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);

    register_taxonomy('mega_item', 'product', [
        'labels' => [
            'name' => __('Mega Menu Items', 'avin'),
            'singular_name' => __('Mega Menu Item', 'avin'),
            'menu_name' => __('Mega Items', 'avin'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => false,
        'meta_box_cb' => false,
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'avin_register_mega_menu_taxonomies');

function avin_register_mega_menu_term_meta(): void
{
    register_term_meta('mega_category', 'avin_menu_order', ['type' => 'integer', 'single' => true, 'show_in_rest' => true]);

    foreach (['avin_mega_category' => 'integer', 'avin_subtitle' => 'string', 'avin_image' => 'integer', 'avin_url' => 'string', 'avin_menu_order' => 'integer'] as $key => $type) {
        register_term_meta('mega_item', $key, ['type' => $type, 'single' => true, 'show_in_rest' => true]);
    }
}
add_action('init', 'avin_register_mega_menu_term_meta');

/* -------------------------------------------------------------------- */
/* Term edit screen: custom fields for mega_item                        */
/* -------------------------------------------------------------------- */

function avin_mega_category_options(int $selected = 0): string
{
    $options = '';
    foreach (avin_get_mega_categories() as $category) {
        $options .= sprintf('<option value="%1$d"%2$s>%3$s</option>', $category->term_id, selected($selected, $category->term_id, false), esc_html($category->name));
    }
    return $options;
}

function avin_mega_item_add_form_fields(): void
{
    ?>
	<div class="form-field">
		<label for="avin-mega-category"><?php esc_html_e('Mega Category', 'avin'); ?></label>
		<select name="avin_mega_item[category]" id="avin-mega-category"><?php echo avin_mega_category_options(); ?></select>
	</div>
	<div class="form-field">
		<label for="avin-mega-subtitle"><?php esc_html_e('Subtitle', 'avin'); ?></label>
		<input type="text" name="avin_mega_item[subtitle]" id="avin-mega-subtitle">
	</div>
	<div class="form-field">
		<label><?php esc_html_e('Background Image', 'avin'); ?></label>
		<?php avin_render_media_picker('avin_mega_item[image]', 0, false); ?>
	</div>
	<div class="form-field">
		<label for="avin-mega-url"><?php esc_html_e('Explore Link URL', 'avin'); ?></label>
		<input type="url" name="avin_mega_item[url]" id="avin-mega-url" class="regular-text">
		<p><?php esc_html_e('Leave empty to link to the All Products archive for now.', 'avin'); ?></p>
	</div>
	<div class="form-field">
		<label for="avin-mega-order"><?php esc_html_e('Order', 'avin'); ?></label>
		<input type="number" name="avin_mega_item[order]" id="avin-mega-order" value="0">
	</div>
	<?php
}
add_action('mega_item_add_form_fields', 'avin_mega_item_add_form_fields');

function avin_mega_item_edit_form_fields(WP_Term $term): void
{
    $category = (int) get_term_meta($term->term_id, 'avin_mega_category', true);
    $subtitle = get_term_meta($term->term_id, 'avin_subtitle', true);
    $image_id = (int) get_term_meta($term->term_id, 'avin_image', true);
    $url = get_term_meta($term->term_id, 'avin_url', true);
    $order = (int) get_term_meta($term->term_id, 'avin_menu_order', true);
    ?>
	<tr class="form-field">
		<th scope="row"><label for="avin-mega-category"><?php esc_html_e('Mega Category', 'avin'); ?></label></th>
		<td><select name="avin_mega_item[category]" id="avin-mega-category"><?php echo avin_mega_category_options($category); ?></select></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="avin-mega-subtitle"><?php esc_html_e('Subtitle', 'avin'); ?></label></th>
		<td><input type="text" class="regular-text" name="avin_mega_item[subtitle]" id="avin-mega-subtitle" value="<?php echo esc_attr($subtitle); ?>"></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e('Background Image', 'avin'); ?></label></th>
		<td><?php avin_render_media_picker('avin_mega_item[image]', $image_id, false); ?></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="avin-mega-url"><?php esc_html_e('Explore Link URL', 'avin'); ?></label></th>
		<td>
			<input type="url" class="regular-text" name="avin_mega_item[url]" id="avin-mega-url" value="<?php echo esc_attr($url); ?>">
			<p class="description"><?php esc_html_e('Leave empty to link to the All Products archive for now.', 'avin'); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="avin-mega-order"><?php esc_html_e('Order', 'avin'); ?></label></th>
		<td><input type="number" name="avin_mega_item[order]" id="avin-mega-order" value="<?php echo esc_attr($order); ?>"></td>
	</tr>
	<?php
}
add_action('mega_item_edit_form_fields', 'avin_mega_item_edit_form_fields');

/**
 * WP core's own term-edit nonce (checked by wp-admin/edit-tags.php and
 * term.php before wp_insert_term()/wp_update_term() ever fire the
 * created_/edited_ hooks below) already covers these fields — no
 * separate nonce needed here.
 */
function avin_save_mega_item_meta(int $term_id): void
{
    if (!isset($_POST['avin_mega_item']) || !current_user_can('manage_categories')) {
        return;
    }
    $data = wp_unslash($_POST['avin_mega_item']);

    update_term_meta($term_id, 'avin_mega_category', absint($data['category'] ?? 0));
    update_term_meta($term_id, 'avin_subtitle', sanitize_text_field($data['subtitle'] ?? ''));

    $image_id = absint($data['image'] ?? 0);
    if ($image_id && get_post_type($image_id) === 'attachment') {
        update_term_meta($term_id, 'avin_image', $image_id);
    } else {
        delete_term_meta($term_id, 'avin_image');
    }

    update_term_meta($term_id, 'avin_url', esc_url_raw($data['url'] ?? ''));
    update_term_meta($term_id, 'avin_menu_order', (int) ($data['order'] ?? 0));
}
add_action('created_mega_item', 'avin_save_mega_item_meta');
add_action('edited_mega_item', 'avin_save_mega_item_meta');

/* -------------------------------------------------------------------- */
/* Query helpers                                                         */
/* -------------------------------------------------------------------- */

/** @return WP_Term[] */
function avin_get_mega_categories(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $terms = get_terms(['taxonomy' => 'mega_category', 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        return [];
    }
    usort($terms, fn (WP_Term $a, WP_Term $b) => avin_term_order($a) <=> avin_term_order($b) ?: $a->term_id <=> $b->term_id);
    return $cache = $terms;
}

/** @return WP_Term[] */
function avin_get_mega_items(int $category_term_id): array
{
    $terms = get_terms([
        'taxonomy' => 'mega_item',
        'hide_empty' => false,
        'meta_key' => 'avin_mega_category',
        'meta_value' => $category_term_id,
    ]);
    if (is_wp_error($terms)) {
        return [];
    }
    usort($terms, fn (WP_Term $a, WP_Term $b) => avin_term_order($a) <=> avin_term_order($b) ?: $a->term_id <=> $b->term_id);
    return $terms;
}

function avin_term_order(WP_Term $term): int
{
    return (int) get_term_meta($term->term_id, 'avin_menu_order', true);
}

function avin_mega_item_url(WP_Term $term): string
{
    $url = get_term_meta($term->term_id, 'avin_url', true);
    return $url ?: (string) get_post_type_archive_link('product');
}

/* -------------------------------------------------------------------- */
/* Seed data — 3 categories, 8 items, matching the approved mega menu    */
/* structure. Idempotent (matched by slug) and only runs once — see     */
/* avin_on_activate()/avin_ensure_setup() in inc/setup.php — so later    */
/* admin edits in wp-admin are never overwritten.                       */
/* -------------------------------------------------------------------- */

function avin_seed_mega_menu_terms(): void
{
    $categories = [
        'mega-food' => ['name' => __('Food', 'avin'), 'order' => 1],
        'mega-pet-food' => ['name' => __('Pet Food', 'avin'), 'order' => 2],
        'mega-feed' => ['name' => __('Feed', 'avin'), 'order' => 3],
    ];

    $category_ids = [];
    foreach ($categories as $slug => $data) {
        $term = get_term_by('slug', $slug, 'mega_category');
        if (!$term) {
            $result = wp_insert_term($data['name'], 'mega_category', ['slug' => $slug]);
            if (is_wp_error($result)) {
                continue;
            }
            $term = get_term($result['term_id'], 'mega_category');
        }
        update_term_meta($term->term_id, 'avin_menu_order', $data['order']);
        $category_ids[$slug] = $term->term_id;
    }

    $items = [
        'mega-chicken-feet-products' => ['name' => __('Chicken Feet Products', 'avin'), 'category' => 'mega-food', 'order' => 1, 'business_line' => 'chicken-feet-products', 'subtitle' => __('Raw & frozen chicken feet and paws', 'avin')],
        'mega-extracts' => ['name' => __('Extracts', 'avin'), 'category' => 'mega-food', 'order' => 2, 'business_line' => '', 'subtitle' => __('Concentrated protein extracts', 'avin')],
        'mega-freeze-dried-snacks' => ['name' => __('Freeze-Dried Snacks', 'avin'), 'category' => 'mega-food', 'order' => 3, 'business_line' => '', 'subtitle' => __('Single-ingredient freeze-dried treats', 'avin')],
        'mega-freeze-dried-products' => ['name' => __('Freeze-Dried Products', 'avin'), 'category' => 'mega-pet-food', 'order' => 1, 'business_line' => 'freeze-dried-pet-food', 'subtitle' => __('100% single-ingredient, human-grade', 'avin')],
        'mega-air-dried-products' => ['name' => __('Air-Dried Products', 'avin'), 'category' => 'mega-pet-food', 'order' => 2, 'business_line' => 'air-dried-pet-food', 'subtitle' => __('Gently air-dried animal proteins', 'avin')],
        'mega-paste-products' => ['name' => __('Paste Products', 'avin'), 'category' => 'mega-pet-food', 'order' => 3, 'business_line' => '', 'subtitle' => __('Palatable paste formats', 'avin')],
        'mega-chicken-powders' => ['name' => __('Chicken Powders', 'avin'), 'category' => 'mega-feed', 'order' => 1, 'business_line' => 'ingredients-solutions', 'subtitle' => __('Poultry meat & blood powders', 'avin')],
        'mega-marine-powders' => ['name' => __('Marine Powders', 'avin'), 'category' => 'mega-feed', 'order' => 2, 'business_line' => 'ingredients-solutions', 'subtitle' => __('Fish powder & fish meal', 'avin')],
    ];

    foreach ($items as $slug => $data) {
        $category_id = $category_ids[$data['category']] ?? 0;
        if (!$category_id) {
            continue;
        }

        $term = get_term_by('slug', $slug, 'mega_item');
        if (!$term) {
            $result = wp_insert_term($data['name'], 'mega_item', ['slug' => $slug]);
            if (is_wp_error($result)) {
                continue;
            }
            $term = get_term($result['term_id'], 'mega_item');
        }

        $url = '';
        if ($data['business_line']) {
            $line = get_term_by('slug', $data['business_line'], 'business_line');
            if ($line) {
                $url = avin_business_line_url($line);
            }
        }

        update_term_meta($term->term_id, 'avin_mega_category', $category_id);
        update_term_meta($term->term_id, 'avin_subtitle', $data['subtitle']);
        update_term_meta($term->term_id, 'avin_menu_order', $data['order']);
        if ($url) {
            update_term_meta($term->term_id, 'avin_url', $url);
        }
    }
}
