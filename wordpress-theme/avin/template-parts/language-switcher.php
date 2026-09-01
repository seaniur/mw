<?php
/**
 * Renders a language switcher when a multilingual plugin is active.
 *
 * The theme itself is fully translation-ready (every string goes through
 * __()/_e() with the `avin` text domain, content fields are per-post/
 * per-term so each language gets its own copy, and templates use logical
 * CSS properties so RTL Farsi and LTR English/Russian both lay out
 * correctly) but actually running three languages needs a translation
 * management plugin — Polylang or WPML — to store and route the per-
 * language content. See readme.txt "Multilingual (FA / EN / RU)" for the
 * recommended setup. Until one is installed this renders nothing, rather
 * than fake language links that 404.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('pll_the_languages')) {
    $languages = pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => false,
    ]);
    if (empty($languages)) {
        return;
    }
    echo '<div class="lang-switcher">' . avin_icon('globe') . '<ul>';
    foreach ($languages as $lang) {
        printf(
            '<li><a href="%1$s" lang="%2$s"%3$s>%4$s</a></li>',
            esc_url($lang['url']),
            esc_attr($lang['locale']),
            !empty($lang['current_lang']) ? ' aria-current="true"' : '',
            esc_html(strtoupper($lang['slug']))
        );
    }
    echo '</ul></div>';
    return;
}

if (function_exists('icl_get_languages')) {
    $languages = icl_get_languages('skip_missing=0');
    if (empty($languages)) {
        return;
    }
    echo '<div class="lang-switcher">' . avin_icon('globe') . '<ul>';
    foreach ($languages as $lang) {
        printf(
            '<li><a href="%1$s" lang="%2$s"%3$s>%4$s</a></li>',
            esc_url($lang['url']),
            esc_attr($lang['language_code']),
            !empty($lang['active']) ? ' aria-current="true"' : '',
            esc_html(strtoupper($lang['language_code']))
        );
    }
    echo '</ul></div>';
}
