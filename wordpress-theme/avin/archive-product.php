<?php
/**
 * "All Products" catalog — every product across every business line, with
 * filter pills (real links, each producing a crawlable, bookmarkable URL
 * via ?business_line=slug — see avin_filter_product_archive() in
 * inc/cpt-product.php) rather than a JS-only filter.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$active_line = isset($_GET['business_line']) ? sanitize_title(wp_unslash($_GET['business_line'])) : '';
?>

<section class="page-hero">
	<div class="container">
		<?php avin_breadcrumbs(); ?>
		<h1><?php esc_html_e('All Products', 'avin'); ?></h1>
		<p class="hero-lede"><?php esc_html_e('Browse the full Avin Tejarat Parto catalog, or filter by business line.', 'avin'); ?></p>
	</div>
</section>

<section class="section section-tight-top">
	<div class="container">
		<div class="filter-pills" role="navigation" aria-label="<?php esc_attr_e('Filter by business line', 'avin'); ?>">
			<a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="filter-pill<?php echo $active_line === '' ? ' is-active' : ''; ?>">
				<?php esc_html_e('All', 'avin'); ?>
			</a>
			<?php foreach (avin_get_business_lines() as $line) : ?>
				<a href="<?php echo esc_url(add_query_arg('business_line', $line->slug, get_post_type_archive_link('product'))); ?>" class="filter-pill<?php echo $active_line === $line->slug ? ' is-active' : ''; ?>">
					<?php echo esc_html($line->name); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<?php if (have_posts()) : ?>
			<div class="product-grid">
				<?php
                while (have_posts()) :
                    the_post();
                    $avin_card_post = get_post();
                    get_template_part('template-parts/product-card', null, ['avin_card_post' => $avin_card_post]);
                endwhile;
                ?>
			</div>

			<div class="pagination">
				<?php
                echo paginate_links([
                    'prev_text' => __('← Previous', 'avin'),
                    'next_text' => __('Next →', 'avin'),
                ]);
                ?>
			</div>
		<?php else : ?>
			<p class="empty-state"><?php esc_html_e('No products found for this filter yet.', 'avin'); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
