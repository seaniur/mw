<?php
/**
 * Product grid card: image, name, ingredient, format, up to two key
 * features, and a "View Product" link — the fields the brief's Product
 * Grid section calls for. Expects the global $post (via setup_postdata)
 * or an explicit $avin_card_post to be set before include.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** @var WP_Post|null $avin_card_post */
$product = $avin_card_post ?? get_post();
if (!$product) {
    return;
}

$ingredient = avin_field($product->ID, 'ingredient');
$format = avin_field($product->ID, 'format');
$features = avin_field($product->ID, 'key_features');
$feature_labels = [
    'single-ingredient' => __('Single Ingredient', 'avin'),
    'human-grade' => __('Human-Grade', 'avin'),
    'no-additives' => __('No Additives', 'avin'),
    'high-protein' => __('High Protein', 'avin'),
    'low-moisture' => __('Low Moisture', 'avin'),
];
$format_labels = [
    'whole' => __('Whole', 'avin'),
    'sliced' => __('Sliced', 'avin'),
    'cubed' => __('Cubed', 'avin'),
    'powder' => __('Powder', 'avin'),
    'pieces' => __('Pieces', 'avin'),
];
?>
<article class="product-card">
	<a href="<?php echo esc_url(get_permalink($product)); ?>" class="product-card-media">
		<?php if (has_post_thumbnail($product)) : ?>
			<?php echo get_the_post_thumbnail($product, 'avin-card', ['loading' => 'lazy']); ?>
		<?php else : ?>
			<span class="product-card-media-placeholder" aria-hidden="true"><?php echo avin_icon('single-ingredient'); ?></span>
		<?php endif; ?>
	</a>
	<div class="product-card-body">
		<h3 class="product-card-title">
			<a href="<?php echo esc_url(get_permalink($product)); ?>"><?php echo esc_html(get_the_title($product)); ?></a>
		</h3>
		<?php if ($ingredient) : ?>
			<p class="product-card-ingredient"><?php echo esc_html($ingredient); ?></p>
		<?php endif; ?>
		<?php if ($format) : ?>
			<p class="product-card-format">
				<?php echo esc_html(implode(' · ', array_map(fn ($f) => $format_labels[$f] ?? $f, (array) $format))); ?>
			</p>
		<?php endif; ?>
		<?php if ($features) : ?>
			<ul class="product-card-features">
				<?php foreach (array_slice((array) $features, 0, 2) as $feature) : ?>
					<li><?php echo avin_icon('check'); ?> <?php echo esc_html($feature_labels[$feature] ?? $feature); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<a href="<?php echo esc_url(get_permalink($product)); ?>" class="product-card-link">
			<?php esc_html_e('View Product', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?>
		</a>
	</div>
</article>
