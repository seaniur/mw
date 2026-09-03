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
        'height' => 96,
        'width' => 320,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('align-wide');

    // Content is authored per-language (see inc/i18n.php notes in readme);
    // the theme itself is translation-ready via the avin text domain.
    load_theme_textdomain('avin', AVIN_DIR . '/languages');

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
    $is_mega_item_screen = isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'mega_item';
    if ($post_type !== 'product' && !$is_mega_item_screen) {
        return;
    }
    wp_enqueue_style('avin-admin', AVIN_URI . '/assets/css/admin.css', [], AVIN_VERSION);
    wp_enqueue_script('avin-admin', AVIN_URI . '/assets/js/admin-meta-boxes.js', ['jquery', 'jquery-ui-sortable'], AVIN_VERSION, true);
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'avin_admin_assets');

/**
 * Allows SVG uploads for the site logo ("via SVG technology" — a vector
 * logo stays crisp at any size instead of being raster-scaled). Gated to
 * administrators only: WordPress excludes SVG from uploads by default
 * because an SVG can carry embedded <script>/event-handler XSS, and that
 * risk is only acceptable for the small set of fully-trusted accounts
 * that can already do far more damage via the theme/plugin file editor.
 * This does not sanitize SVG markup on upload — if editors below
 * Administrator ever need SVG uploads, add a proper sanitizer (e.g. the
 * enshrined/svg-sanitize library) rather than widening this capability
 * check.
 */
function avin_allow_svg_uploads(array $mimes): array
{
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'avin_allow_svg_uploads');

function avin_fix_svg_filetype($data, $file, $filename, $mimes)
{
    if (!current_user_can('manage_options')) {
        return $data;
    }
    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'svg') {
        $data['ext'] = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'avin_fix_svg_filetype', 10, 4);

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
 * The theme's fixed primary navigation: Home / Products (mega menu
 * trigger, no URL of its own — see template-parts/mega-menu.php) / Blog /
 * About / Contact. Hard-coded rather than an admin-editable wp_nav_menu
 * on purpose — the Products entry has to stay wired to the mega menu, so
 * letting it be freely renamed/removed/reordered from Appearance → Menus
 * would silently break that. Used by both header.php and
 * template-parts/mobile-nav.php so the two navs can never drift apart.
 *
 * @return array<int, array{key: string, label: string, url: string, mega?: bool}>
 */
function avin_primary_nav_items(): array
{
    return [
        ['key' => 'home', 'label' => __('Home', 'avin'), 'url' => home_url('/')],
        ['key' => 'products', 'label' => __('Products', 'avin'), 'url' => (string) get_post_type_archive_link('product'), 'mega' => true],
        ['key' => 'blog', 'label' => __('Blog', 'avin'), 'url' => avin_blog_url()],
        ['key' => 'about', 'label' => __('About', 'avin'), 'url' => home_url('/about/')],
        ['key' => 'contact', 'label' => __('Contact', 'avin'), 'url' => home_url('/contact/')],
    ];
}

/**
 * The Blog index URL — WordPress's own "Posts page" (Settings → Reading),
 * which avin_create_missing_pages() wires up to the auto-created Blog
 * Page below. Reads the live option rather than assuming the slug, so it
 * still resolves correctly if an admin later points Reading → Posts page
 * at a different Page.
 */
function avin_blog_url(): string
{
    $page_id = (int) get_option('page_for_posts');
    if ($page_id) {
        return get_permalink($page_id);
    }
    return home_url('/blog/');
}

/**
 * Required top-level Pages this theme's template hierarchy expects.
 * "home" and "blog" exist so Settings → Reading can point "Your homepage
 * displays" / "Posts page" at real Pages (front-page.php renders the
 * homepage's actual content regardless of what Page is assigned — see
 * avin_create_missing_pages() — but WordPress still needs one assigned
 * for page_for_posts, and therefore /blog/, to work at all). Business-
 * line and product URLs are generated automatically from the
 * business_line/product_category taxonomies and the product CPT, so
 * they don't need Pages.
 */
function avin_required_pages(): array
{
    return [
        'home' => __('Home', 'avin'),
        'about' => __('About Avin Tejarat Parto', 'avin'),
        'contact' => __('Contact — Send an Inquiry', 'avin'),
        'coming-soon' => __('Coming Soon', 'avin'),
        'blog' => __('Blog', 'avin'),
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
    $page_ids = [];
    foreach (avin_required_pages() as $slug => $title) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            $page_ids[$slug] = $existing->ID;
            continue;
        }
        $new_id = wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => avin_required_page_default_content($slug),
        ]);
        if ($new_id && !is_wp_error($new_id)) {
            $page_ids[$slug] = $new_id;
        }
    }

    // front-page.php always renders the homepage regardless of the Reading
    // setting, but page_for_posts (and therefore /blog/) only works when
    // show_on_front is 'page' — harmless to enforce either way since it
    // has no effect on what actually shows at "/". Only claims
    // page_on_front/page_for_posts when they're not already set, so a
    // site with its own Reading configuration is left alone.
    if (get_option('show_on_front') !== 'page') {
        update_option('show_on_front', 'page');
    }
    if (!get_option('page_on_front') && !empty($page_ids['home'])) {
        update_option('page_on_front', $page_ids['home']);
    }
    if (!get_option('page_for_posts') && !empty($page_ids['blog'])) {
        update_option('page_for_posts', $page_ids['blog']);
    }
}

function avin_on_activate(): void
{
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    avin_create_missing_pages();
    avin_seed_taxonomy_terms();
    avin_seed_mega_menu_terms();
    flush_rewrite_rules();
    update_option('avin_setup_checked', 1);
    update_option('avin_mega_menu_seeded', 1);
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
 * without needing avin_setup_checked to be cleared. avin_seed_mega_menu_
 * terms() gets the same treatment via its own one-time flag, since it
 * was added after avin_setup_checked already existed on installs from
 * earlier versions of this theme — without a separate flag, those sites
 * would never seed the mega menu's categories/items at all. The heaviest
 * one-time work (rewrite flush, business-line term seeding) still runs
 * only once, gated by avin_setup_checked.
 */
function avin_ensure_setup(): void
{
    avin_create_missing_pages();

    // Business lines have to exist before the mega menu is seeded — its
    // seed data looks a few of its Explore links up by business_line
    // slug — so this runs first and unconditionally sets its own flag
    // before the mega menu's flag is checked below.
    if (!get_option('avin_setup_checked')) {
        avin_seed_taxonomy_terms();
        flush_rewrite_rules();
        update_option('avin_setup_checked', 1);
    }

    if (!get_option('avin_mega_menu_seeded')) {
        avin_seed_mega_menu_terms();
        update_option('avin_mega_menu_seeded', 1);
    }
}
add_action('admin_init', 'avin_ensure_setup');
