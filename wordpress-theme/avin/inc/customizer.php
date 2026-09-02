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
