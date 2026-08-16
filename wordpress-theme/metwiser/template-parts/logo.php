<?php
/**
 * Logo mark + wordmark, used in the header and footer.
 * Accepts an optional $args['class'] string for the wrapping <span>.
 */
$class = $args['class'] ?? '';
?>
<span class="inline-flex items-center gap-2.5 <?php echo esc_attr($class); ?>">
    <img
        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brand/logo.png'); ?>"
        alt="Metwiser"
        width="64"
        height="64"
        class="h-8 w-8 object-contain"
    >
    <span class="font-display text-2xl font-bold tracking-tight lowercase">
        <span class="text-ink">met</span><span class="text-orange">wiser</span>
    </span>
</span>
