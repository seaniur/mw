<?php
/**
 * Inquiry form submission: validates, stores as an `inquiry` post (so
 * every lead is visible and searchable in wp-admin, not just a fleeting
 * email), and notifies the team by email. Handles logged-out visitors —
 * every real site visitor — via the *_nopriv_* hook.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_handle_inquiry(): void
{
    if (!isset($_POST['avin_inquiry_nonce']) || !wp_verify_nonce($_POST['avin_inquiry_nonce'], 'avin_inquiry_form')) {
        wp_die(esc_html__('Security check failed. Please go back and try again.', 'avin'), '', ['response' => 403]);
    }

    $redirect_to = isset($_POST['redirect_to']) ? esc_url_raw(wp_unslash($_POST['redirect_to'])) : home_url('/contact/');
    $redirect_to = remove_query_arg('avin_inquiry', $redirect_to);

    // Honeypot: real visitors never fill this hidden field.
    if (!empty($_POST['hp_field'])) {
        wp_safe_redirect(add_query_arg('avin_inquiry', 'success', $redirect_to));
        exit;
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($name === '' || $message === '' || !is_email($email)) {
        wp_safe_redirect(add_query_arg('avin_inquiry', 'error', $redirect_to));
        exit;
    }

    $company = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
    $country = sanitize_text_field(wp_unslash($_POST['country'] ?? ''));
    $whatsapp = sanitize_text_field(wp_unslash($_POST['whatsapp'] ?? ''));
    $quantity = sanitize_text_field(wp_unslash($_POST['quantity'] ?? ''));
    $application = sanitize_text_field(wp_unslash($_POST['application'] ?? ''));
    $request_type = in_array($_POST['request_type'] ?? '', ['quote', 'sample'], true) ? $_POST['request_type'] : 'quote';

    $product_id = absint($_POST['product_id'] ?? 0);
    $product_title = '';
    if ($product_id && get_post_type($product_id) === 'product') {
        $product_title = get_the_title($product_id);
    } else {
        $product_id = 0;
    }

    $post_title = sprintf('%s — %s', $name, $product_title ?: __('General Inquiry', 'avin'));
    $inquiry_id = wp_insert_post([
        'post_type' => 'inquiry',
        'post_title' => $post_title,
        'post_status' => 'publish',
    ]);

    if (!is_wp_error($inquiry_id) && $inquiry_id) {
        $fields = compact('name', 'company', 'country', 'email', 'whatsapp', 'quantity', 'application', 'message', 'request_type', 'product_id', 'product_title');
        foreach ($fields as $key => $value) {
            update_post_meta($inquiry_id, '_avin_' . $key, $value);
        }
    }

    $recipient = get_theme_mod('avin_inquiry_recipient') ?: get_theme_mod('avin_contact_email', get_option('admin_email'));
    $recipient = apply_filters('avin_inquiry_recipient', $recipient);

    $request_type_label = $request_type === 'sample' ? __('Sample Request', 'avin') : __('Quote Request', 'avin');
    $subject = sprintf('[%s] %s — %s', $request_type_label, $name, $product_title ?: __('General Inquiry', 'avin'));

    $body_lines = [
        __('Type:', 'avin') . ' ' . $request_type_label,
        __('Product:', 'avin') . ' ' . ($product_title ?: __('General inquiry (no specific product)', 'avin')),
        __('Name:', 'avin') . ' ' . $name,
        __('Company:', 'avin') . ' ' . ($company ?: '-'),
        __('Country:', 'avin') . ' ' . ($country ?: '-'),
        __('Email:', 'avin') . ' ' . $email,
        __('WhatsApp:', 'avin') . ' ' . ($whatsapp ?: '-'),
        __('Required Quantity:', 'avin') . ' ' . ($quantity ?: '-'),
        __('Application:', 'avin') . ' ' . ($application ?: '-'),
        '',
        __('Message:', 'avin'),
        $message,
    ];

    $sent = wp_mail($recipient, $subject, implode("\n", $body_lines), ['Reply-To: ' . $email]);

    wp_safe_redirect(add_query_arg('avin_inquiry', $sent ? 'success' : 'error', $redirect_to));
    exit;
}
add_action('admin_post_avin_inquiry', 'avin_handle_inquiry');
add_action('admin_post_nopriv_avin_inquiry', 'avin_handle_inquiry');

/* -------------------------------------------------------------------- */
/* wp-admin: make the Inquiries list actually useful to scan             */
/* -------------------------------------------------------------------- */

function avin_inquiry_columns(array $columns): array
{
    $columns = [
        'cb' => $columns['cb'],
        'title' => __('From', 'avin'),
        'avin_type' => __('Type', 'avin'),
        'avin_product' => __('Product', 'avin'),
        'avin_email' => __('Email', 'avin'),
        'date' => $columns['date'],
    ];
    return $columns;
}
add_filter('manage_inquiry_posts_columns', 'avin_inquiry_columns');

function avin_inquiry_column_content(string $column, int $post_id): void
{
    switch ($column) {
        case 'avin_type':
            $type = get_post_meta($post_id, '_avin_request_type', true);
            echo esc_html($type === 'sample' ? __('Sample', 'avin') : __('Quote', 'avin'));
            break;
        case 'avin_product':
            echo esc_html(get_post_meta($post_id, '_avin_product_title', true) ?: __('General', 'avin'));
            break;
        case 'avin_email':
            $email = get_post_meta($post_id, '_avin_email', true);
            if ($email) {
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            }
            break;
    }
}
add_action('manage_inquiry_posts_custom_column', 'avin_inquiry_column_content', 10, 2);

function avin_inquiry_meta_box(): void
{
    add_meta_box('avin_inquiry_details', __('Inquiry Details', 'avin'), 'avin_render_inquiry_meta_box', 'inquiry', 'normal', 'high');
}
add_action('add_meta_boxes_inquiry', 'avin_inquiry_meta_box');

function avin_render_inquiry_meta_box(WP_Post $post): void
{
    $labels = [
        'request_type' => __('Type', 'avin'),
        'product_title' => __('Product', 'avin'),
        'company' => __('Company', 'avin'),
        'country' => __('Country', 'avin'),
        'email' => __('Email', 'avin'),
        'whatsapp' => __('WhatsApp', 'avin'),
        'quantity' => __('Required Quantity', 'avin'),
        'application' => __('Application', 'avin'),
        'message' => __('Message', 'avin'),
    ];
    echo '<table class="widefat"><tbody>';
    foreach ($labels as $key => $label) {
        $value = get_post_meta($post->ID, '_avin_' . $key, true);
        printf(
            '<tr><th style="width:180px;text-align:left;">%1$s</th><td>%2$s</td></tr>',
            esc_html($label),
            $key === 'message' ? nl2br(esc_html($value)) : esc_html($value)
        );
    }
    echo '</tbody></table>';
}
