<?php
/**
 * Every dynamic product field from the brief's "Dynamic Product Fields"
 * section, as one small config-driven framework instead of one-off
 * meta boxes per field — no ACF or other plugin dependency, no field left
 * hard-coded in a template. Add a row to avin_product_field_groups() and
 * it appears in wp-admin, saves, and is readable via avin_field().
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The full field schema. Each top-level entry becomes one meta box on the
 * product edit screen, titled and ordered as in the brief (Product
 * Identity, Key Features, Ingredients, Technical Specifications,
 * Packaging, Shelf Life & Storage, Commercial, Quality & Certifications,
 * Documents, Media, Related Products, SEO).
 */
function avin_product_field_groups(): array
{
    return [
        'identity' => [
            'title' => __('Product Identity', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'alt_name', 'label' => __('Alternative Name', 'avin'), 'type' => 'text'],
                ['key' => 'ingredient', 'label' => __('Ingredient', 'avin'), 'type' => 'text'],
                ['key' => 'origin', 'label' => __('Origin', 'avin'), 'type' => 'text', 'placeholder' => __('e.g. Iran', 'avin')],
                ['key' => 'grade', 'label' => __('Grade', 'avin'), 'type' => 'text', 'placeholder' => __('e.g. Grade A, Human-Grade', 'avin')],
                ['key' => 'application', 'label' => __('Application', 'avin'), 'type' => 'text', 'placeholder' => __('e.g. Pet Food, Pet Treat', 'avin')],
                ['key' => 'processing_method', 'label' => __('Processing Method', 'avin'), 'type' => 'select', 'options' => [
                    'Freeze-Dried' => __('Freeze-Dried', 'avin'),
                    'Air-Dried' => __('Air-Dried', 'avin'),
                    'Raw & Frozen' => __('Raw & Frozen', 'avin'),
                    'Fried' => __('Fried', 'avin'),
                    'Powder / Milled' => __('Powder / Milled', 'avin'),
                ]],
                ['key' => 'format', 'label' => __('Product Format', 'avin'), 'type' => 'checkboxes', 'options' => [
                    'whole' => __('Whole', 'avin'),
                    'sliced' => __('Sliced', 'avin'),
                    'cubed' => __('Cubed', 'avin'),
                    'powder' => __('Powder', 'avin'),
                    'pieces' => __('Pieces', 'avin'),
                ]],
            ],
        ],
        'features' => [
            'title' => __('Key Features', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'key_features', 'label' => __('Key Features', 'avin'), 'type' => 'checkboxes', 'options' => [
                    'single-ingredient' => __('Single Ingredient', 'avin'),
                    'human-grade' => __('Human-Grade Material', 'avin'),
                    'no-additives' => __('No Additives', 'avin'),
                    'high-protein' => __('High Protein', 'avin'),
                    'low-moisture' => __('Low Moisture', 'avin'),
                ]],
            ],
        ],
        'ingredients' => [
            'title' => __('Ingredients', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'ingredients_list', 'label' => __('Ingredients (one per line)', 'avin'), 'type' => 'textarea', 'rows' => 4],
            ],
        ],
        'specs' => [
            'title' => __('Technical Specifications', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'tech_specs', 'label' => __('Specifications', 'avin'), 'type' => 'repeater', 'columns' => [
                    'label' => __('Parameter', 'avin'),
                    'value' => __('Value', 'avin'),
                    'unit' => __('Unit', 'avin'),
                ]],
            ],
        ],
        'packaging' => [
            'title' => __('Packaging', 'avin'),
            'context' => 'side',
            'fields' => [
                ['key' => 'packaging_type', 'label' => __('Packaging Type', 'avin'), 'type' => 'text'],
                ['key' => 'net_weight', 'label' => __('Net Weight', 'avin'), 'type' => 'text'],
                ['key' => 'bulk_packaging', 'label' => __('Bulk Packaging', 'avin'), 'type' => 'text'],
                ['key' => 'retail_packaging', 'label' => __('Retail Packaging', 'avin'), 'type' => 'text'],
            ],
        ],
        'storage' => [
            'title' => __('Shelf Life & Storage', 'avin'),
            'context' => 'side',
            'fields' => [
                ['key' => 'shelf_life', 'label' => __('Shelf Life', 'avin'), 'type' => 'text'],
                ['key' => 'storage_conditions', 'label' => __('Storage Conditions', 'avin'), 'type' => 'text'],
            ],
        ],
        'commercial' => [
            'title' => __('Commercial', 'avin'),
            'context' => 'side',
            'fields' => [
                ['key' => 'moq', 'label' => __('MOQ (Minimum Order Quantity)', 'avin'), 'type' => 'text'],
                ['key' => 'supply_capacity', 'label' => __('Supply Capacity', 'avin'), 'type' => 'text'],
            ],
        ],
        'quality' => [
            'title' => __('Quality & Certifications', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'certifications', 'label' => __('Certifications', 'avin'), 'type' => 'repeater', 'columns' => [
                    'name' => __('Certification', 'avin'),
                ]],
                ['key' => 'quality_standards', 'label' => __('Quality Standards (one per line)', 'avin'), 'type' => 'textarea', 'rows' => 3],
            ],
        ],
        'documents' => [
            'title' => __('Documents', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'documents', 'label' => __('Documents', 'avin'), 'type' => 'repeater_media', 'columns' => [
                    'label' => __('Label', 'avin'),
                ]],
            ],
        ],
        'media' => [
            'title' => __('Media', 'avin'),
            'context' => 'side',
            'fields' => [
                ['key' => 'gallery', 'label' => __('Product Gallery', 'avin'), 'type' => 'gallery'],
                ['key' => 'packaging_image', 'label' => __('Packaging Image', 'avin'), 'type' => 'media'],
                ['key' => 'process_image', 'label' => __('Process Image', 'avin'), 'type' => 'media'],
            ],
        ],
        'related' => [
            'title' => __('Related Products', 'avin'),
            'context' => 'side',
            'fields' => [
                ['key' => 'related_products', 'label' => __('Related Products', 'avin'), 'type' => 'post_picker'],
            ],
        ],
        'seo' => [
            'title' => __('SEO', 'avin'),
            'context' => 'normal',
            'fields' => [
                ['key' => 'seo_title', 'label' => __('SEO Title', 'avin'), 'type' => 'text', 'placeholder' => __('Defaults to the product title', 'avin')],
                ['key' => 'seo_description', 'label' => __('Meta Description', 'avin'), 'type' => 'textarea', 'rows' => 2],
            ],
        ],
    ];
}

function avin_meta_key(string $field_key): string
{
    return '_avin_' . $field_key;
}

/**
 * Reads a saved field value, with checkbox/repeater/gallery/post_picker
 * fields normalized to an array so templates never need is_array() guards.
 */
function avin_field(int $post_id, string $field_key)
{
    $value = get_post_meta($post_id, avin_meta_key($field_key), true);
    return $value === '' ? null : $value;
}

/* -------------------------------------------------------------------- */
/* Register + render meta boxes                                         */
/* -------------------------------------------------------------------- */

function avin_add_meta_boxes()
{
    foreach (avin_product_field_groups() as $group_key => $group) {
        add_meta_box(
            'avin_' . $group_key,
            $group['title'],
            'avin_render_field_group',
            'product',
            $group['context'] ?? 'normal',
            'default',
            ['group_key' => $group_key]
        );
    }
}
add_action('add_meta_boxes', 'avin_add_meta_boxes');

function avin_render_field_group(WP_Post $post, array $box)
{
    static $nonce_done = false;
    if (!$nonce_done) {
        wp_nonce_field('avin_save_product_meta', 'avin_meta_nonce');
        $nonce_done = true;
    }

    $group_key = $box['args']['group_key'];
    $group = avin_product_field_groups()[$group_key];

    echo '<div class="avin-field-group">';
    foreach ($group['fields'] as $field) {
        avin_render_field($post->ID, $field);
    }
    echo '</div>';
}

function avin_render_field(int $post_id, array $field)
{
    $key = $field['key'];
    $name = 'avin_meta[' . $key . ']';
    $value = get_post_meta($post_id, avin_meta_key($key), true);
    $id = 'avin-field-' . $key;

    echo '<p class="avin-field avin-field-' . esc_attr($field['type']) . '">';
    if (!in_array($field['type'], ['checkboxes'], true)) {
        echo '<label for="' . esc_attr($id) . '"><strong>' . esc_html($field['label']) . '</strong></label>';
    } else {
        echo '<strong>' . esc_html($field['label']) . '</strong>';
    }

    switch ($field['type']) {
        case 'text':
            printf(
                '<input type="text" id="%1$s" name="%2$s" value="%3$s" placeholder="%4$s" class="widefat">',
                esc_attr($id),
                esc_attr($name),
                esc_attr((string) $value),
                esc_attr($field['placeholder'] ?? '')
            );
            break;

        case 'textarea':
            printf(
                '<textarea id="%1$s" name="%2$s" rows="%3$d" class="widefat">%4$s</textarea>',
                esc_attr($id),
                esc_attr($name),
                (int) ($field['rows'] ?? 3),
                esc_textarea((string) $value)
            );
            break;

        case 'select':
            echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" class="widefat">';
            echo '<option value="">' . esc_html__('—', 'avin') . '</option>';
            foreach ($field['options'] as $opt_value => $opt_label) {
                printf(
                    '<option value="%1$s"%2$s>%3$s</option>',
                    esc_attr($opt_value),
                    selected($value, $opt_value, false),
                    esc_html($opt_label)
                );
            }
            echo '</select>';
            break;

        case 'checkboxes':
            $selected = is_array($value) ? $value : [];
            echo '<span class="avin-checkbox-list">';
            foreach ($field['options'] as $opt_value => $opt_label) {
                printf(
                    '<label><input type="checkbox" name="%1$s[]" value="%2$s"%3$s> %4$s</label>',
                    esc_attr($name),
                    esc_attr($opt_value),
                    checked(in_array($opt_value, $selected, true), true, false),
                    esc_html($opt_label)
                );
            }
            echo '</span>';
            break;

        case 'repeater':
            avin_render_repeater($name, $field, is_array($value) ? $value : [], false);
            break;

        case 'repeater_media':
            avin_render_repeater($name, $field, is_array($value) ? $value : [], true);
            break;

        case 'media':
            avin_render_media_picker($name, (int) $value, false);
            break;

        case 'gallery':
            avin_render_media_picker($name, is_array($value) ? $value : [], true);
            break;

        case 'post_picker':
            avin_render_post_picker($id, $name, $post_id, is_array($value) ? $value : []);
            break;
    }

    echo '</p>';
}

function avin_render_repeater(string $name, array $field, array $rows, bool $with_media): void
{
    $columns = $field['columns'];
    if ($with_media) {
        $columns = ['file' => __('File', 'avin')] + $columns;
    }

    echo '<table class="avin-repeater widefat" data-repeater>';
    echo '<thead><tr>';
    foreach ($columns as $col_label) {
        echo '<th>' . esc_html($col_label) . '</th>';
    }
    echo '<th></th></tr></thead>';
    echo '<tbody data-repeater-rows>';

    if (empty($rows)) {
        $rows = [[]];
    }
    foreach (array_values($rows) as $i => $row) {
        avin_render_repeater_row($name, $columns, $row, $i, $with_media);
    }

    echo '</tbody></table>';
    echo '<button type="button" class="button" data-repeater-add>' . esc_html__('+ Add Row', 'avin') . '</button>';

    echo '<template data-repeater-template>';
    avin_render_repeater_row($name, $columns, [], '__INDEX__', $with_media);
    echo '</template>';
}

function avin_render_repeater_row(string $name, array $columns, array $row, $index, bool $with_media): void
{
    echo '<tr data-repeater-row>';
    foreach ($columns as $col_key => $col_label) {
        $field_name = sprintf('%s[%s][%s]', $name, $index, $col_key);
        if ($col_key === 'file') {
            $attachment_id = (int) ($row['file'] ?? 0);
            echo '<td>';
            avin_render_media_picker($field_name, $attachment_id, false, true);
            echo '</td>';
            continue;
        }
        printf(
            '<td><input type="text" name="%1$s" value="%2$s" class="widefat"></td>',
            esc_attr($field_name),
            esc_attr($row[$col_key] ?? '')
        );
    }
    echo '<td><button type="button" class="button-link avin-repeater-remove" data-repeater-remove aria-label="' . esc_attr__('Remove row', 'avin') . '">✕</button></td>';
    echo '</tr>';
}

/**
 * A single-image or multi-image (gallery) picker backed by wp.media.
 * $compact renders a small inline variant used inside repeater rows.
 */
function avin_render_media_picker(string $name, $value, bool $multiple, bool $compact = false): void
{
    $ids = $multiple ? array_filter((array) $value) : ((int) $value ?: null);
    $wrap_class = 'avin-media-picker' . ($compact ? ' avin-media-picker-compact' : '');

    echo '<div class="' . esc_attr($wrap_class) . '" data-media-picker data-multiple="' . ($multiple ? '1' : '0') . '">';

    if ($multiple) {
        echo '<div class="avin-media-preview" data-media-preview>';
        foreach ((array) $ids as $attachment_id) {
            echo avin_media_thumb_markup((int) $attachment_id);
        }
        echo '</div>';
        echo '<input type="hidden" name="' . esc_attr($name) . '" data-media-value value="' . esc_attr(implode(',', (array) $ids)) . '">';
    } else {
        echo '<div class="avin-media-preview" data-media-preview>';
        if ($ids) {
            echo avin_media_thumb_markup((int) $ids);
        }
        echo '</div>';
        echo '<input type="hidden" name="' . esc_attr($name) . '" data-media-value value="' . esc_attr($ids ?: '') . '">';
    }

    echo '<button type="button" class="button" data-media-select>' . ($compact ? esc_html__('Select', 'avin') : esc_html__('Select Image', 'avin')) . '</button> ';
    echo '<button type="button" class="button-link" data-media-clear>' . esc_html__('Clear', 'avin') . '</button>';
    echo '</div>';
}

function avin_media_thumb_markup(int $attachment_id): string
{
    if (!$attachment_id) {
        return '';
    }
    if (wp_attachment_is_image($attachment_id)) {
        $img = wp_get_attachment_image($attachment_id, [60, 60]);
        return '<span class="avin-media-thumb" data-id="' . esc_attr($attachment_id) . '">' . $img . '</span>';
    }
    $filename = esc_html(basename(get_attached_file($attachment_id) ?: ''));
    return '<span class="avin-media-thumb avin-media-file" data-id="' . esc_attr($attachment_id) . '">' . avin_icon('document') . ' ' . $filename . '</span>';
}

function avin_render_post_picker(string $id, string $name, int $current_post_id, array $selected): void
{
    $products = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'exclude' => [$current_post_id],
        'no_found_rows' => true,
    ]);

    echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '[]" multiple size="8" class="widefat">';
    foreach ($products as $product) {
        printf(
            '<option value="%1$d"%2$s>%3$s</option>',
            $product->ID,
            selected(in_array($product->ID, $selected, true), true, false),
            esc_html($product->post_title)
        );
    }
    echo '</select>';
    echo '<span class="description">' . esc_html__('Ctrl/Cmd-click to select multiple.', 'avin') . '</span>';
}

/* -------------------------------------------------------------------- */
/* Save                                                                  */
/* -------------------------------------------------------------------- */

function avin_save_product_meta(int $post_id, WP_Post $post)
{
    if (!isset($_POST['avin_meta_nonce']) || !wp_verify_nonce($_POST['avin_meta_nonce'], 'avin_save_product_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $posted = wp_unslash($_POST['avin_meta'] ?? []);

    foreach (avin_product_field_groups() as $group) {
        foreach ($group['fields'] as $field) {
            $key = $field['key'];
            $meta_key = avin_meta_key($key);
            $raw = $posted[$key] ?? null;

            switch ($field['type']) {
                case 'text':
                case 'select':
                    update_post_meta($post_id, $meta_key, sanitize_text_field((string) $raw));
                    break;

                case 'textarea':
                    update_post_meta($post_id, $meta_key, sanitize_textarea_field((string) $raw));
                    break;

                case 'checkboxes':
                    $allowed = array_keys($field['options']);
                    $clean = array_values(array_intersect((array) $raw, $allowed));
                    if (empty($clean)) {
                        delete_post_meta($post_id, $meta_key);
                    } else {
                        update_post_meta($post_id, $meta_key, $clean);
                    }
                    break;

                case 'repeater':
                case 'repeater_media':
                    $columns = array_keys($field['columns']);
                    if ($field['type'] === 'repeater_media') {
                        $columns[] = 'file';
                    }
                    $clean_rows = [];
                    foreach ((array) $raw as $row) {
                        $clean_row = [];
                        $has_content = false;
                        foreach ($columns as $col) {
                            if ($col === 'file') {
                                $file_id = absint($row['file'] ?? 0);
                                $clean_row['file'] = $file_id && get_post_type($file_id) === 'attachment' ? $file_id : 0;
                                $has_content = $has_content || $clean_row['file'];
                            } else {
                                $clean_row[$col] = sanitize_text_field((string) ($row[$col] ?? ''));
                                $has_content = $has_content || $clean_row[$col] !== '';
                            }
                        }
                        if ($has_content) {
                            $clean_rows[] = $clean_row;
                        }
                    }
                    if (empty($clean_rows)) {
                        delete_post_meta($post_id, $meta_key);
                    } else {
                        update_post_meta($post_id, $meta_key, $clean_rows);
                    }
                    break;

                case 'media':
                    $attachment_id = absint($raw);
                    if ($attachment_id && get_post_type($attachment_id) === 'attachment') {
                        update_post_meta($post_id, $meta_key, $attachment_id);
                    } else {
                        delete_post_meta($post_id, $meta_key);
                    }
                    break;

                case 'gallery':
                    $ids = array_filter(array_map('absint', is_array($raw) ? $raw : explode(',', (string) $raw)));
                    $ids = array_values(array_filter($ids, fn ($id) => get_post_type($id) === 'attachment'));
                    if (empty($ids)) {
                        delete_post_meta($post_id, $meta_key);
                    } else {
                        update_post_meta($post_id, $meta_key, $ids);
                    }
                    break;

                case 'post_picker':
                    $ids = array_values(array_filter(array_map('absint', (array) $raw), fn ($id) => get_post_type($id) === 'product'));
                    if (empty($ids)) {
                        delete_post_meta($post_id, $meta_key);
                    } else {
                        update_post_meta($post_id, $meta_key, $ids);
                    }
                    break;
            }
        }
    }
}
add_action('save_post_product', 'avin_save_product_meta', 10, 2);
