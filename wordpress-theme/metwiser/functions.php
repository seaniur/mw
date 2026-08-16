<?php
/**
 * Metwiser theme setup, asset enqueue, and contact-form handler.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('METWISER_VERSION', '1.0.0');

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/icons.php';

function metwiser_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'metwiser_setup');

function metwiser_enqueue_assets()
{
    // Space Grotesk (display/headings) + IBM Plex Mono (body), matching the
    // original Next.js site's font choices.
    wp_enqueue_style(
        'metwiser-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=IBM+Plex+Mono:wght@400;500;600&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'metwiser-style',
        get_stylesheet_uri(),
        [],
        METWISER_VERSION
    );

    wp_enqueue_style(
        'metwiser-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['metwiser-style'],
        METWISER_VERSION
    );

    wp_enqueue_script(
        'metwiser-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        METWISER_VERSION,
        true
    );

    wp_localize_script('metwiser-main', 'metwiserContact', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('metwiser_contact_form'),
    ]);
}
add_action('wp_enqueue_scripts', 'metwiser_enqueue_assets');

/**
 * Contact form submission handler.
 *
 * Registered for both logged-in and logged-out ('nopriv') requests since
 * every real site visitor is logged out. Mirrors the validation, honeypot,
 * and message formatting of the previous standalone contact.php script,
 * but sends via wp_mail() so it goes through whatever mail configuration
 * (SMTP plugin, etc.) the WordPress install has set up.
 */
function metwiser_handle_contact_form()
{
    check_ajax_referer('metwiser_contact_form', 'nonce');

    // Honeypot: real visitors never fill this hidden field. Bots that
    // autofill every input will, so pretend success and drop it silently.
    if (!empty($_POST['hp_field'])) {
        wp_send_json_success();
    }

    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $interest = isset($_POST['interest']) ? sanitize_text_field(wp_unslash($_POST['interest'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    $errors = [];
    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($email === '' || !is_email($email)) {
        $errors[] = 'A valid email is required.';
    }
    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    if (!empty($errors)) {
        wp_send_json_error(['message' => implode(' ', $errors)], 422);
    }

    $interest_labels = [
        'sourcing' => 'Sourcing & Supplier Network',
        'manufacturing' => 'Manufacturing & Private Label',
        'rd' => 'R&D & Formulation',
        'quality' => 'Quality & Compliance',
        'packaging' => 'Packaging & Branding',
        'logistics' => 'Logistics & Fulfillment',
        'other' => 'Something else',
    ];
    $interest_label = $interest_labels[$interest] ?? $interest;

    $recipient = apply_filters('metwiser_contact_recipient', 'hello@metwiser.com');
    $subject = 'New contact form submission from ' . $name;

    $body = "Name: {$name}\n";
    $body .= 'Company: ' . ($company !== '' ? $company : '-') . "\n";
    $body .= "Email: {$email}\n";
    $body .= 'Looking for: ' . ($interest_label !== '' ? $interest_label : '-') . "\n\n";
    $body .= "Message:\n{$message}\n";

    $headers = ['Reply-To: ' . $email];

    $sent = wp_mail($recipient, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success();
    }

    wp_send_json_error([
        'message' => 'Unable to send your message right now. Please try again later or email us directly.',
    ], 500);
}
add_action('wp_ajax_metwiser_contact_form', 'metwiser_handle_contact_form');
add_action('wp_ajax_nopriv_metwiser_contact_form', 'metwiser_handle_contact_form');

/**
 * Falls back to the theme's bundled favicon when no WordPress Site Icon
 * has been set under Settings -> General.
 */
function metwiser_fallback_favicon()
{
    if (has_site_icon()) {
        return;
    }
    echo '<link rel="icon" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.png') . '">' . "\n";
}
add_action('wp_head', 'metwiser_fallback_favicon');

/**
 * Shared nav links used by both the header and footer templates.
 */
function metwiser_nav_links()
{
    return [
        ['href' => home_url('/'), 'label' => 'Home'],
        ['href' => home_url('/about/'), 'label' => 'About'],
        ['href' => home_url('/services/'), 'label' => 'Services'],
        ['href' => home_url('/brands/'), 'label' => 'Brands'],
        ['href' => home_url('/contact/'), 'label' => 'Contact'],
    ];
}

/**
 * True when $href matches (or is a parent of) the current request path.
 */
function metwiser_nav_is_active(string $href): bool
{
    $current = untrailingslashit(parse_url(home_url(add_query_arg([])), PHP_URL_PATH) ?? '/');
    $target = untrailingslashit(parse_url($href, PHP_URL_PATH) ?? '/');

    if ($target === '' || $target === untrailingslashit(parse_url(home_url('/'), PHP_URL_PATH) ?? '')) {
        return $current === $target;
    }

    return $current === $target || str_starts_with($current . '/', $target . '/');
}
