<?php
/**
 * Every homepage section from the homepage brief, registered as its own
 * Customizer section under one "Homepage" panel. Nothing here is
 * hard-coded content — front-page.php reads all of it via avin_repeater()
 * / get_theme_mod(), and every section has its own enable/disable
 * checkbox per the brief's CMS requirements. Product Categories (section
 * 03) is the one exception that isn't a repeater: it deliberately reuses
 * the business_line taxonomy's 3 top-level groups (Food/Pet Food/Feed —
 * see inc/cpt-product.php) rather than a separate content source, so an
 * admin edits "Food" in one place, not two.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_customize_register_homepage(WP_Customize_Manager $wp_customize)
{
    // Safe to call even if inc/customizer.php's own avin_customize_register()
    // already did — require_once — but this file doesn't depend on
    // execution order between the two 'customize_register' callbacks
    // either way.
    require_once AVIN_DIR . '/inc/class-avin-customize-repeater-control.php';

    $wp_customize->add_panel('avin_homepage', [
        'title' => __('Homepage', 'avin'),
        'priority' => 5,
        'description' => __('Every section on the homepage, top to bottom. Each has its own Enable/Disable checkbox — turn a section off without deleting its content.', 'avin'),
    ]);

    /* ---- 01. Hero Slider ------------------------------------------- */
    $wp_customize->add_section('avin_home_hero', ['title' => __('01 — Hero Slider', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_repeater_control($wp_customize, 'avin_home_hero_slides', [
        'section' => 'avin_home_hero',
        'label' => __('Slides', 'avin'),
        'row_label' => __('Slide', 'avin'),
        'description' => __('At least one enabled slide is required for the hero to show.', 'avin'),
        'fields' => [
            ['key' => 'image', 'type' => 'image', 'label' => __('Background Image', 'avin')],
            ['key' => 'heading', 'type' => 'text', 'label' => __('Heading', 'avin')],
            ['key' => 'description', 'type' => 'textarea', 'label' => __('Description', 'avin')],
            ['key' => 'cta_text', 'type' => 'text', 'label' => __('CTA Text', 'avin')],
            ['key' => 'cta_link', 'type' => 'url', 'label' => __('CTA Link', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['heading' => __('Reliable Sourcing. Controlled Production.', 'avin'), 'description' => __('A B2B sourcing, manufacturing, and export platform for international food and pet food buyers.', 'avin'), 'cta_text' => __('Explore Products', 'avin'), 'cta_link' => home_url('/products/'), 'enabled' => true],
            ['heading' => __('We Find. We Produce. We Deliver.', 'avin'), 'description' => __('For food: we find the best sources in Iran. For pet food: we manufacture directly, with controlled production.', 'avin'), 'cta_text' => __('Send an Inquiry', 'avin'), 'cta_link' => home_url('/contact/'), 'enabled' => true],
        ],
    ]);

    /* ---- 02. Company Value / Trust ----------------------------------- */
    $wp_customize->add_section('avin_home_trust', ['title' => __('02 — Company Value', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_trust_enabled', 'avin_home_trust', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_trust_heading', 'avin_home_trust', ['label' => __('Headline', 'avin'), 'default' => __('Reliable Supply Starts With the Right Source.', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_trust_copy', 'avin_home_trust', ['type' => 'textarea', 'label' => __('Copy', 'avin'), 'default' => __('We source qualified food products from Iran with a focus on stable supply, consistent specifications and controlled quality.', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_trust_points', [
        'section' => 'avin_home_trust',
        'label' => __('Value Points', 'avin'),
        'row_label' => __('Point', 'avin'),
        'fields' => [
            ['key' => 'text', 'type' => 'text', 'label' => __('Text', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['text' => __('Qualified Iranian Sources', 'avin'), 'enabled' => true],
            ['text' => __('Stable Supply', 'avin'), 'enabled' => true],
            ['text' => __('Quality Control', 'avin'), 'enabled' => true],
            ['text' => __('Export-Ready Supply', 'avin'), 'enabled' => true],
        ],
    ]);

    /* ---- 03. Product Categories (Food / Pet Food / Feed) ------------- */
    $wp_customize->add_section('avin_home_categories', [
        'title' => __('03 — Product Categories', 'avin'),
        'panel' => 'avin_homepage',
        'description' => __('These 3 cards are the Food / Pet Food / Feed business-line groups — edit each one\'s title, description, and image on its own term (Products → Business Lines → edit Food/Pet Food/Feed), not here.', 'avin'),
    ]);
    avin_add_setting($wp_customize, 'avin_home_categories_enabled', 'avin_home_categories', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_categories_heading', 'avin_home_categories', ['label' => __('Headline', 'avin'), 'default' => __('What We Supply', 'avin')]);

    /* ---- 04. Sourcing — Food ------------------------------------------ */
    $wp_customize->add_section('avin_home_sourcing', ['title' => __('04 — Sourcing (Food)', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_sourcing_enabled', 'avin_home_sourcing', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_sourcing_heading', 'avin_home_sourcing', ['label' => __('Headline', 'avin'), 'default' => __('Your Source for Reliable Iranian Food Products', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_sourcing_copy', 'avin_home_sourcing', ['type' => 'textarea', 'label' => __('Copy', 'avin'), 'default' => __('We identify and qualify the right suppliers in Iran, then manage specifications, quality control and supply continuity for international buyers.', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_sourcing_steps', [
        'section' => 'avin_home_sourcing',
        'label' => __('Process Steps', 'avin'),
        'row_label' => __('Step', 'avin'),
        'fields' => [
            ['key' => 'label', 'type' => 'text', 'label' => __('Step', 'avin')],
            ['key' => 'image', 'type' => 'image', 'label' => __('Icon / Image (optional)', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['label' => __('Source', 'avin'), 'enabled' => true],
            ['label' => __('Verify', 'avin'), 'enabled' => true],
            ['label' => __('Control', 'avin'), 'enabled' => true],
            ['label' => __('Supply', 'avin'), 'enabled' => true],
        ],
    ]);
    avin_add_setting($wp_customize, 'avin_home_sourcing_cta_text', 'avin_home_sourcing', ['label' => __('CTA Text', 'avin'), 'default' => __('Explore Food Products', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_sourcing_cta_link', 'avin_home_sourcing', ['type' => 'url', 'label' => __('CTA Link (defaults to the Food page)', 'avin')]);

    /* ---- 05. Pet Food Manufacturing ------------------------------------ */
    $wp_customize->add_section('avin_home_petfood', ['title' => __('05 — Pet Food Manufacturing', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_petfood_enabled', 'avin_home_petfood', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_petfood_heading', 'avin_home_petfood', ['label' => __('Headline', 'avin'), 'default' => __('Made by Us. Built for Your Market.', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_petfood_copy', 'avin_home_petfood', ['type' => 'textarea', 'label' => __('Copy', 'avin'), 'default' => __('We manufacture our own pet food products with direct control over ingredients, production and quality — giving buyers a more reliable path from production to export.', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_petfood_highlights', [
        'section' => 'avin_home_petfood',
        'label' => __('Highlights', 'avin'),
        'row_label' => __('Highlight', 'avin'),
        'fields' => [
            ['key' => 'text', 'type' => 'text', 'label' => __('Text', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['text' => __('Direct Manufacturing', 'avin'), 'enabled' => true],
            ['text' => __('Controlled Production', 'avin'), 'enabled' => true],
            ['text' => __('Consistent Quality', 'avin'), 'enabled' => true],
            ['text' => __('Export-Ready Products', 'avin'), 'enabled' => true],
        ],
    ]);
    avin_add_image_setting($wp_customize, 'avin_home_petfood_image', 'avin_home_petfood', ['label' => __('Product / Factory Image (optional)', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_petfood_cta_text', 'avin_home_petfood', ['label' => __('CTA Text', 'avin'), 'default' => __('Explore Pet Food', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_petfood_cta_link', 'avin_home_petfood', ['type' => 'url', 'label' => __('CTA Link (defaults to the Pet Food page)', 'avin')]);

    /* ---- 06. Featured Products ------------------------------------------ */
    $wp_customize->add_section('avin_home_featured', ['title' => __('06 — Featured Products', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_featured_enabled', 'avin_home_featured', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_featured_heading', 'avin_home_featured', ['label' => __('Headline', 'avin'), 'default' => __('Featured Products', 'avin')]);

    $product_options = [];
    foreach (get_posts(['post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'no_found_rows' => true]) as $product) {
        $product_options[] = ['value' => $product->ID, 'label' => $product->post_title];
    }
    avin_add_repeater_control($wp_customize, 'avin_home_featured_products', [
        'section' => 'avin_home_featured',
        'label' => __('Products', 'avin'),
        'row_label' => __('Product', 'avin'),
        'description' => __('Image, name, and description default to the product\'s own — the override fields are only for showing something different here.', 'avin'),
        'fields' => [
            ['key' => 'product', 'type' => 'select', 'label' => __('Product', 'avin'), 'options' => $product_options, 'placeholder' => __('— Select a product —', 'avin')],
            ['key' => 'image_override', 'type' => 'image', 'label' => __('Image Override (optional)', 'avin')],
            ['key' => 'name_override', 'type' => 'text', 'label' => __('Name Override (optional)', 'avin')],
            ['key' => 'description_override', 'type' => 'textarea', 'label' => __('Description Override (optional)', 'avin')],
            ['key' => 'link_override', 'type' => 'url', 'label' => __('Link Override (optional)', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
    ]);
    avin_add_setting($wp_customize, 'avin_home_featured_cta_text', 'avin_home_featured', ['label' => __('CTA Text', 'avin'), 'default' => __('View All Products', 'avin')]);

    /* ---- 07. Private Label ----------------------------------------------- */
    $wp_customize->add_section('avin_home_private_label', ['title' => __('07 — Private Label', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_private_label_enabled', 'avin_home_private_label', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_private_label_heading', 'avin_home_private_label', ['label' => __('Headline', 'avin'), 'default' => __('Private Label for Export Markets', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_private_label_copy', 'avin_home_private_label', ['type' => 'textarea', 'label' => __('Copy', 'avin'), 'default' => __('We develop and produce private label pet food products for export markets, tailored to your product, packaging and market requirements.', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_private_label_steps', [
        'section' => 'avin_home_private_label',
        'label' => __('Process Steps', 'avin'),
        'row_label' => __('Step', 'avin'),
        'fields' => [
            ['key' => 'label', 'type' => 'text', 'label' => __('Step', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['label' => __('Product', 'avin'), 'enabled' => true],
            ['label' => __('Formulation', 'avin'), 'enabled' => true],
            ['label' => __('Packaging', 'avin'), 'enabled' => true],
            ['label' => __('Export', 'avin'), 'enabled' => true],
        ],
    ]);
    avin_add_setting($wp_customize, 'avin_home_private_label_cta_text', 'avin_home_private_label', ['label' => __('CTA Text', 'avin'), 'default' => __('Discuss Your Private Label Project', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_private_label_cta_link', 'avin_home_private_label', ['type' => 'url', 'label' => __('CTA Link (defaults to Contact)', 'avin')]);

    /* ---- 08. How We Work ----------------------------------------------- */
    $wp_customize->add_section('avin_home_how_we_work', ['title' => __('08 — How We Work', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_how_we_work_enabled', 'avin_home_how_we_work', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_how_we_work_heading', 'avin_home_how_we_work', ['label' => __('Headline', 'avin'), 'default' => __('From Source to Shipment', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_how_we_work_steps', [
        'section' => 'avin_home_how_we_work',
        'label' => __('Steps', 'avin'),
        'row_label' => __('Step', 'avin'),
        'fields' => [
            ['key' => 'number', 'type' => 'text', 'label' => __('Number', 'avin'), 'placeholder' => '01'],
            ['key' => 'label', 'type' => 'text', 'label' => __('Label', 'avin')],
            ['key' => 'description', 'type' => 'textarea', 'label' => __('Description', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['number' => '01', 'label' => __('Source', 'avin'), 'description' => __('Find qualified suppliers and raw materials.', 'avin'), 'enabled' => true],
            ['number' => '02', 'label' => __('Control', 'avin'), 'description' => __('Verify specifications and quality.', 'avin'), 'enabled' => true],
            ['number' => '03', 'label' => __('Produce', 'avin'), 'description' => __('Manufacture products under controlled processes.', 'avin'), 'enabled' => true],
            ['number' => '04', 'label' => __('Deliver', 'avin'), 'description' => __('Prepare export-ready products for shipment.', 'avin'), 'enabled' => true],
        ],
    ]);

    /* ---- 09. Quality ----------------------------------------------------- */
    $wp_customize->add_section('avin_home_quality', ['title' => __('09 — Quality', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_quality_enabled', 'avin_home_quality', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_quality_heading', 'avin_home_quality', ['label' => __('Headline', 'avin'), 'default' => __('Quality Control at Every Step', 'avin')]);
    avin_add_repeater_control($wp_customize, 'avin_home_quality_points', [
        'section' => 'avin_home_quality',
        'label' => __('Focus Points', 'avin'),
        'row_label' => __('Point', 'avin'),
        'fields' => [
            ['key' => 'text', 'type' => 'text', 'label' => __('Text', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
        'default' => [
            ['text' => __('Supplier qualification', 'avin'), 'enabled' => true],
            ['text' => __('Product specifications', 'avin'), 'enabled' => true],
            ['text' => __('Production control', 'avin'), 'enabled' => true],
            ['text' => __('Quality inspection', 'avin'), 'enabled' => true],
            ['text' => __('Consistency', 'avin'), 'enabled' => true],
        ],
    ]);
    avin_add_repeater_control($wp_customize, 'avin_home_quality_badges', [
        'section' => 'avin_home_quality',
        'label' => __('Certification Badges (optional)', 'avin'),
        'row_label' => __('Badge', 'avin'),
        'fields' => [
            ['key' => 'image', 'type' => 'image', 'label' => __('Badge Image', 'avin')],
            ['key' => 'title', 'type' => 'text', 'label' => __('Title', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
    ]);

    /* ---- 10. B2B CTA / RFQ ------------------------------------------------ */
    $wp_customize->add_section('avin_home_cta', ['title' => __('10 — B2B CTA / RFQ', 'avin'), 'panel' => 'avin_homepage']);
    avin_add_setting($wp_customize, 'avin_home_cta_enabled', 'avin_home_cta', ['type' => 'checkbox', 'label' => __('Show this section', 'avin'), 'default' => true]);
    avin_add_setting($wp_customize, 'avin_home_cta_heading', 'avin_home_cta', ['label' => __('Headline', 'avin'), 'default' => __('Looking for the Right Supply Partner?', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_cta_copy', 'avin_home_cta', ['type' => 'textarea', 'label' => __('Copy', 'avin'), 'default' => __('Tell us what you need. We\'ll help you find the right product, source or production solution.', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_cta_primary_text', 'avin_home_cta', ['label' => __('Primary CTA Text', 'avin'), 'default' => __('Request a Quote', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_cta_primary_link', 'avin_home_cta', ['type' => 'url', 'label' => __('Primary CTA Link (defaults to Contact)', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_cta_secondary_text', 'avin_home_cta', ['label' => __('Secondary CTA Text (optional)', 'avin'), 'default' => __('Talk to Our Team', 'avin')]);
    avin_add_setting($wp_customize, 'avin_home_cta_secondary_link', 'avin_home_cta', ['type' => 'url', 'label' => __('Secondary CTA Link (optional)', 'avin')]);
}
add_action('customize_register', 'avin_customize_register_homepage');
