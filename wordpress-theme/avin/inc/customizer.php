<?php
/**
 * A handful of Customizer settings for contact details used in the footer
 * and the inquiry notification email — deliberately minimal (no page-
 * builder-style Customizer sprawl); everything else content-shaped lives
 * on Products, business-line terms, or the Pages themselves.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_customize_register(WP_Customize_Manager $wp_customize)
{
    require_once AVIN_DIR . '/inc/class-avin-customize-repeater-control.php';

    $wp_customize->add_section('avin_coming_soon', [
        'title' => __('Coming Soon Mode', 'avin'),
        'priority' => 10,
        'description' => __('When enabled, every visitor who isn\'t logged in sees the "Coming Soon" holding page (Pages → Coming Soon — edit its content there) instead of the real site. Logged-in team members always see and can edit the real site as normal; log out (or use a private/incognito window) to check what the public sees.', 'avin'),
    ]);

    $wp_customize->add_setting('avin_coming_soon_enabled', [
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('avin_coming_soon_enabled', [
        'section' => 'avin_coming_soon',
        'label' => __('Show the Coming Soon page to visitors', 'avin'),
        'type' => 'checkbox',
    ]);

    $wp_customize->add_setting('avin_coming_soon_logo', [
        'default' => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'avin_coming_soon_logo', [
        'section' => 'avin_coming_soon',
        'label' => __('Coming Soon Page Logo', 'avin'),
        'description' => __('Optional — falls back to the main Site Identity logo below if left empty.', 'avin'),
        'mime_type' => 'image',
    ]));

    $columns_field = [
        'type' => 'select',
        'choices' => array_combine(range(1, 6), range(1, 6)),
    ];
    $wp_customize->add_setting('avin_coming_soon_columns_desktop', ['default' => 4, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('avin_coming_soon_columns_desktop', array_merge($columns_field, [
        'section' => 'avin_coming_soon',
        'label' => __('Boxes per row — Desktop', 'avin'),
    ]));
    $wp_customize->add_setting('avin_coming_soon_columns_tablet', ['default' => 2, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('avin_coming_soon_columns_tablet', array_merge($columns_field, [
        'section' => 'avin_coming_soon',
        'label' => __('Boxes per row — Tablet', 'avin'),
    ]));
    $wp_customize->add_setting('avin_coming_soon_columns_mobile', ['default' => 2, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('avin_coming_soon_columns_mobile', array_merge($columns_field, [
        'section' => 'avin_coming_soon',
        'label' => __('Boxes per row — Mobile', 'avin'),
    ]));

    avin_add_repeater_control($wp_customize, 'avin_coming_soon_boxes', [
        'section' => 'avin_coming_soon',
        'label' => __('Boxes', 'avin'),
        'description' => __('Shown in a fixed card layout below the headline — the card style itself isn\'t editable here, only its content. Leave empty to keep the theme\'s default 4 boxes.', 'avin'),
        'row_label' => __('Box', 'avin'),
        'fields' => [
            ['key' => 'icon', 'type' => 'image', 'label' => __('Icon', 'avin')],
            ['key' => 'title', 'type' => 'text', 'label' => __('Title', 'avin')],
            ['key' => 'description', 'type' => 'textarea', 'label' => __('Description', 'avin')],
            ['key' => 'enabled', 'type' => 'checkbox', 'label' => __('Enabled', 'avin')],
        ],
    ]);

    $wp_customize->add_section('avin_contact', [
        'title' => __('Contact Details', 'avin'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('avin_contact_email', [
        'default' => 'sales@avinparto.com',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('avin_contact_email', [
        'section' => 'avin_contact',
        'label' => __('Contact Email', 'avin'),
        'type' => 'email',
    ]);

    $wp_customize->add_setting('avin_whatsapp_number', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('avin_whatsapp_number', [
        'section' => 'avin_contact',
        'label' => __('WhatsApp Number (with country code)', 'avin'),
        'type' => 'text',
    ]);

    $wp_customize->add_setting('avin_inquiry_recipient', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('avin_inquiry_recipient', [
        'section' => 'avin_contact',
        'label' => __('Inquiry Notification Email (defaults to Contact Email)', 'avin'),
        'type' => 'email',
    ]);
}
add_action('customize_register', 'avin_customize_register');
