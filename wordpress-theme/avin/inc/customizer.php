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
