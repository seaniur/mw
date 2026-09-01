<?php
/**
 * Schema.org JSON-LD — the structured half of the brief's SEO/AEO
 * requirement. Organization + WebSite ship on every page; Product and
 * BreadcrumbList are added where relevant. Kept in one small `@graph` per
 * page rather than several tags, which is both easier for crawlers to
 * parse and easier to extend.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_organization_schema(): array
{
    $logo_id = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : null;

    $contact_point = array_filter([
        '@type' => 'ContactPoint',
        'contactType' => 'sales',
        'email' => get_theme_mod('avin_contact_email', '') ?: null,
        'telephone' => get_theme_mod('avin_whatsapp_number', '') ?: null,
        'areaServed' => 'Worldwide',
    ]);

    return array_filter([
        '@type' => 'Organization',
        '@id' => home_url('/#organization'),
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'logo' => $logo_url,
        'description' => get_bloginfo('description') ?: null,
        'contactPoint' => count($contact_point) > 2 ? [$contact_point] : null,
    ]);
}

function avin_website_schema(): array
{
    return [
        '@type' => 'WebSite',
        '@id' => home_url('/#website'),
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'publisher' => ['@id' => home_url('/#organization')],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => home_url('/?post_type=product&s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

/**
 * @param array<int, array{label: string, url?: string}> $trail
 */
function avin_breadcrumb_schema(array $trail): ?array
{
    if (count($trail) < 2) {
        return null;
    }
    $items = [];
    foreach ($trail as $i => $step) {
        $items[] = array_filter([
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => wp_strip_all_tags($step['label']),
            'item' => $step['url'] ?? null,
        ]);
    }
    return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

function avin_product_schema(int $product_id): array
{
    $tech_specs = avin_field($product_id, 'tech_specs');
    $properties = [];
    if (is_array($tech_specs)) {
        foreach ($tech_specs as $row) {
            if (empty($row['label'])) {
                continue;
            }
            $properties[] = array_filter([
                '@type' => 'PropertyValue',
                'name' => $row['label'],
                'value' => trim(($row['value'] ?? '') . ' ' . ($row['unit'] ?? '')),
            ]);
        }
    }

    $business_lines = get_the_terms($product_id, 'business_line');
    $category = ($business_lines && !is_wp_error($business_lines)) ? $business_lines[0]->name : null;

    $image_id = get_post_thumbnail_id($product_id);

    return array_filter([
        '@type' => 'Product',
        '@id' => get_permalink($product_id) . '#product',
        'name' => get_the_title($product_id),
        'description' => get_the_excerpt($product_id) ?: wp_strip_all_tags(get_post_field('post_content', $product_id)),
        'image' => $image_id ? wp_get_attachment_image_url($image_id, 'large') : null,
        'sku' => (string) $product_id,
        'category' => $category,
        'brand' => ['@id' => home_url('/#organization')],
        'additionalProperty' => !empty($properties) ? $properties : null,
        'countryOfOrigin' => avin_field($product_id, 'origin') ?: null,
    ]);
}

function avin_output_schema(): void
{
    $graph = [avin_organization_schema(), avin_website_schema()];

    if (is_singular('product')) {
        $graph[] = avin_product_schema(get_the_ID());
    }

    $schema = avin_breadcrumb_schema(avin_get_breadcrumb_trail());
    if ($schema) {
        $graph[] = $schema;
    }

    echo '<script type="application/ld+json">' . wp_json_encode([
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'avin_output_schema');
