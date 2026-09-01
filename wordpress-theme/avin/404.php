<?php
/**
 * 404 page — points visitors back to the business lines and search
 * rather than a dead end, since a mistyped product URL is the most likely
 * cause on a catalog site like this.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<section class="section container not-found">
	<p class="eyebrow"><?php esc_html_e('404', 'avin'); ?></p>
	<h1><?php esc_html_e('Page not found', 'avin'); ?></h1>
	<p class="hero-lede"><?php esc_html_e('The page you\'re looking for may have moved. Try one of our business lines below, or search our products.', 'avin'); ?></p>

	<form class="site-search-form not-found-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
		<label class="screen-reader-text" for="avin-404-search"><?php esc_html_e('Search products', 'avin'); ?></label>
		<input type="search" id="avin-404-search" name="s" placeholder="<?php esc_attr_e('Search products…', 'avin'); ?>">
		<input type="hidden" name="post_type" value="product">
		<button type="submit" class="btn btn-primary btn-sm"><?php esc_html_e('Search', 'avin'); ?></button>
	</form>

	<div class="business-line-grid">
		<?php foreach (avin_get_business_lines() as $line) : ?>
			<a href="<?php echo esc_url(avin_business_line_url($line)); ?>" class="business-line-card">
				<span class="business-line-card-icon"><?php echo avin_icon(get_term_meta($line->term_id, 'avin_icon', true) ?: 'single-ingredient'); ?></span>
				<h3><?php echo esc_html($line->name); ?></h3>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php get_footer(); ?>
