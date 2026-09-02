<?php
/**
 * Theme supports, asset enqueue, menu locations, image sizes.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'script', 'style', 'navigation-widgets']);
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', [
        'height' => 64,
        'width' => 220,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('align-wide');

    // Content is authored per-language (see inc/i18n.php notes in readme);
    // the theme itself is translation-ready via the avin text domain.
    load_theme_textdomain('avin', AVIN_DIR . '/languages');

    register_nav_menus([
        'primary' => __('Primary — beside the Products mega menu (About, Certifications, etc.)', 'avin'),
        'footer' => __('Footer links', 'avin'),
    ]);

    add_image_size('avin-card', 640, 480, true);
    add_image_size('avin-hero', 1600, 900, true);
    add_image_size('avin-thumb', 160, 160, true);
}
add_action('after_setup_theme', 'avin_setup');

function avin_enqueue_assets()
{
    wp_enqueue_style('avin-style', get_stylesheet_uri(), [], AVIN_VERSION);
    wp_enqueue_style('avin-main', AVIN_URI . '/assets/css/main.css', ['avin-style'], AVIN_VERSION);

    if (is_rtl()) {
        wp_enqueue_style('avin-rtl', AVIN_URI . '/assets/css/rtl.css', ['avin-main'], AVIN_VERSION);
    }

    wp_enqueue_script('avin-main', AVIN_URI . '/assets/js/main.js', [], AVIN_VERSION, true);

    wp_localize_script('avin-main', 'avinInquiry', [
        'productLabel' => __('Product', 'avin'),
    ]);
}
add_action('wp_enqueue_scripts', 'avin_enqueue_assets');

/**
 * The theme's own admin styling for meta boxes (repeaters, spec tables)
 * lives separately so it never ships to the public site.
 */
function avin_admin_assets($hook)
{
    global $post_type;
    if ($post_type !== 'product') {
        return;
    }
    wp_enqueue_style('avin-admin', AVIN_URI . '/assets/css/admin.css', [], AVIN_VERSION);
    wp_enqueue_script('avin-admin', AVIN_URI . '/assets/js/admin-meta-boxes.js', ['jquery', 'jquery-ui-sortable'], AVIN_VERSION, true);
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'avin_admin_assets');

/**
 * Falls back to the theme's bundled favicon when no WordPress Site Icon
 * has been set under Settings → General.
 */
function avin_fallback_favicon()
{
    if (has_site_icon()) {
        return;
    }
    $favicon = AVIN_URI . '/assets/images/favicon.png';
    echo '<link rel="icon" href="' . esc_url($favicon) . '">' . "\n";
}
add_action('wp_head', 'avin_fallback_favicon');

/**
 * Preconnect/DNS hints are intentionally omitted — the theme ships no
 * third-party fonts or scripts, so there is nothing to warm a connection
 * for. Keep it that way: the brief calls for lightweight, high-Core-Web-
 * Vitals delivery over decorative type.
 */

/**
 * Shown beside the Products mega menu when no "primary" menu has been
 * assigned yet under Appearance → Menus, so the header isn't empty on a
 * fresh install.
 */
function avin_primary_menu_fallback(): void
{
    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/about/')), esc_html__('About', 'avin'));
}

/**
 * Required top-level Pages this theme's template hierarchy expects
 * (page-about.php, page-contact.php). Business-line and product URLs are
 * generated automatically from the business_line/product_category
 * taxonomies and the product CPT, so they don't need Pages.
 */
function avin_required_pages(): array
{
    return [
        'about' => __('About Avin Tejarat Parto', 'avin'),
        'contact' => __('Contact — Send an Inquiry', 'avin'),
        'coming-soon' => __('Coming Soon', 'avin'),
    ];
}

/**
 * Seed content for a required Page, used only the one time the Page is
 * created — after that it's the team's to edit from wp-admin like any
 * other Page, this never overwrites it again.
 */
function avin_required_page_default_content(string $slug): string
{
    if ($slug === 'coming-soon') {
        return "<p>" . __("Our new website is on its way. Avin Tejarat Parto supplies freeze-dried and air-dried proteins, chicken feet &amp; paws, freeze-dried fruits &amp; vegetables, and animal protein ingredients to pet food manufacturers and distributors worldwide.", 'avin') . "</p>";
    }
    return '';
}

function avin_create_missing_pages(): void
{
    foreach (avin_required_pages() as $slug => $title) {
        if (get_page_by_path($slug)) {
            continue;
        }
        wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => avin_required_page_default_content($slug),
        ]);
    }
}

function avin_on_activate(): void
{
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    avin_create_missing_pages();
    avin_seed_taxonomy_terms();
    flush_rewrite_rules();
    update_option('avin_setup_checked', 1);
}
add_action('after_switch_theme', 'avin_on_activate');

/**
 * Self-heals installs that activated an older copy of this theme, or had a
 * required page/term later trashed, without needing a deactivate/
 * reactivate cycle. avin_create_missing_pages() always re-runs (it's a
 * cheap, idempotent get_page_by_path() check per page) specifically so
 * that updating the theme's files on an already-activated, already-live
 * site — e.g. this file starting to require a new Page like "Coming
 * Soon" — picks up the new page automatically on the next wp-admin visit,
 * without needing avin_setup_checked to be cleared. The heavier one-time
 * work (rewrite flush, term seeding) still only runs once.
 */
function avin_ensure_setup(): void
{
    avin_create_missing_pages();

    if (get_option('avin_setup_checked')) {
        return;
    }
    avin_seed_taxonomy_terms();
    flush_rewrite_rules();
    update_option('avin_setup_checked', 1);
}
add_action('admin_init', 'avin_ensure_setup');
