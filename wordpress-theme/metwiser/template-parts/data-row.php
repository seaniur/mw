<?php
/**
 * A labeled contact value ("TEL", "EMAIL", "HQ" ...), optionally linked,
 * with an optional trailing icon (e.g. the WhatsApp link).
 *
 * $args: label (string), value (string), href (string, optional), trailing (string HTML, optional)
 */
$label = $args['label'] ?? '';
$value = $args['value'] ?? '';
$href = $args['href'] ?? '';
$trailing = $args['trailing'] ?? '';

$value_html = $href
    ? '<a href="' . esc_url($href) . '" class="transition-colors hover:text-orange">' . esc_html($value) . '</a>'
    : esc_html($value);
?>
<div class="flex items-baseline gap-3 py-2.5">
    <span class="h-1.5 w-1.5 shrink-0 rounded-full gradient-dot"></span>
    <span class="w-20 shrink-0 text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase"><?php echo esc_html($label); ?></span>
    <span class="text-sm text-ink"><?php echo $value_html; ?></span>
    <?php echo $trailing; ?>
</div>
