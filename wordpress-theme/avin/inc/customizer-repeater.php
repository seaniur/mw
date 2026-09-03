<?php
/**
 * Front-end helper for the repeater Customizer control — see
 * inc/class-avin-customize-repeater-control.php for the control class
 * itself (that file is only ever loaded inside customize_register(),
 * since it extends WP_Customize_Control, which doesn't exist outside the
 * Customizer; this one has no such dependency and needs to be available
 * on every page render, so it's required unconditionally from
 * functions.php).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Decodes a repeater setting's stored JSON into an array of rows, each
 * row an associative array of its field values. Drops disabled rows
 * (field key "enabled" === false) unless $include_disabled is true.
 * Safe to call even if the setting was never saved (empty default) or
 * holds malformed JSON.
 *
 * @return array<int, array<string, mixed>>
 */
function avin_repeater(string $theme_mod, bool $include_disabled = false): array
{
    $raw = get_theme_mod($theme_mod, '');
    if (!$raw) {
        return [];
    }
    $rows = json_decode($raw, true);
    if (!is_array($rows)) {
        return [];
    }
    if ($include_disabled) {
        return $rows;
    }
    return array_values(array_filter($rows, fn ($row) => !isset($row['enabled']) || $row['enabled']));
}

/**
 * Sanitize callback shared by every repeater setting: keeps only valid
 * JSON that decodes to an array, sanitizing each row's own field values
 * (text fields stripped of markup; "enabled" coerced to a real bool). Any
 * URL/image fields are re-validated again at output time by the
 * templates that render them (esc_url()/absint()), so this is the first
 * line of defense, not the only one.
 */
function avin_sanitize_repeater_json(string $value): string
{
    $rows = json_decode(wp_unslash($value), true);
    if (!is_array($rows)) {
        return wp_json_encode([]);
    }

    $clean = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $clean_row = [];
        foreach ($row as $key => $val) {
            $key = sanitize_key($key);
            if ($key === 'enabled') {
                $clean_row[$key] = (bool) $val;
            } elseif (is_numeric($val)) {
                $clean_row[$key] = $val;
            } else {
                $clean_row[$key] = sanitize_textarea_field((string) $val);
            }
        }
        $clean[] = $clean_row;
    }

    return wp_json_encode($clean);
}

/**
 * Registers one repeater setting + control in a single call — every
 * repeater section (Coming Soon boxes, hero slides, process steps,
 * quality badges, etc.) is otherwise identical boilerplate. Must be
 * called after inc/class-avin-customize-repeater-control.php has been
 * required (avin_customize_register() in inc/customizer.php does this
 * before calling anything that uses it).
 *
 * @param array{section: string, label: string, fields: array, description?: string, row_label?: string, priority?: int, default?: array} $args
 */
function avin_add_repeater_control(WP_Customize_Manager $wp_customize, string $id, array $args): void
{
    $wp_customize->add_setting($id, [
        'type' => 'theme_mod',
        'default' => wp_json_encode($args['default'] ?? []),
        'sanitize_callback' => 'avin_sanitize_repeater_json',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new Avin_Customize_Repeater_Control($wp_customize, $id, [
        'section' => $args['section'],
        'label' => $args['label'],
        'description' => $args['description'] ?? '',
        'fields' => $args['fields'],
        'row_label' => $args['row_label'] ?? __('Item', 'avin'),
        'priority' => $args['priority'] ?? 10,
    ]));
}

/**
 * Registers one plain (text/textarea/url/checkbox/number) Customizer
 * setting + control — the boilerplate every simple homepage-section field
 * shares. Not for images (use avin_add_image_setting()) or repeaters (use
 * avin_add_repeater_control()), both of which need their own control class.
 *
 * @param array{type?: string, label: string, default?: mixed, description?: string, sanitize?: callable, priority?: int} $args
 */
function avin_add_setting(WP_Customize_Manager $wp_customize, string $id, string $section, array $args): void
{
    $type = $args['type'] ?? 'text';
    $default_sanitizers = [
        'checkbox' => 'rest_sanitize_boolean',
        'textarea' => 'sanitize_textarea_field',
        'url' => 'esc_url_raw',
        'number' => 'absint',
    ];

    $wp_customize->add_setting($id, [
        'default' => $args['default'] ?? ($type === 'checkbox' ? false : ''),
        'sanitize_callback' => $args['sanitize'] ?? ($default_sanitizers[$type] ?? 'sanitize_text_field'),
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control($id, [
        'section' => $section,
        'label' => $args['label'],
        'description' => $args['description'] ?? '',
        'type' => $type === 'textarea' ? 'textarea' : ($type === 'checkbox' ? 'checkbox' : ($type === 'url' ? 'url' : ($type === 'number' ? 'number' : 'text'))),
        'priority' => $args['priority'] ?? 10,
    ]);
}

/**
 * Registers a single-image Customizer setting (attachment ID), the
 * shared boilerplate for e.g. a section's one optional large photo.
 */
function avin_add_image_setting(WP_Customize_Manager $wp_customize, string $id, string $section, array $args): void
{
    $wp_customize->add_setting($id, [
        'default' => 0,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $id, [
        'section' => $section,
        'label' => $args['label'],
        'description' => $args['description'] ?? '',
        'mime_type' => 'image',
        'priority' => $args['priority'] ?? 10,
    ]));
}
