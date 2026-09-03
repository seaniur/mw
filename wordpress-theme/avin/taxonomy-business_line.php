<?php
/**
 * Business-line landing page — shared by both levels of the now-
 * hierarchical business_line taxonomy:
 * - A top-level GROUP (/food/, /pet-food/, /feed/) shows a grid of its
 *   child lines (Chicken Feet Products, Freeze-Dried, etc.), the same
 *   card the mega menu and homepage use.
 * - A LEAF line (/food/chicken-feet-products/, /pet-food/freeze-dried/)
 *   shows its product grid — grouped into Poultry / Marine / Fruits &
 *   Vegetables sections where it has them, flat otherwise (e.g. Chicken
 *   Feet & Paws' five named products).
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$term = get_queried_object();
$heading = get_term_meta($term->term_id, 'avin_landing_heading', true) ?: $term->name;
$subtitle = get_term_meta($term->term_id, 'avin_hero_subtitle', true) ?: $term->description;
$cta_primary_label = get_term_meta($term->term_id, 'avin_cta_primary_label', true) ?: __('View Products', 'avin');
$cta_primary_url = get_term_meta($term->term_id, 'avin_cta_primary_url', true) ?: '#products';
$cta_secondary_label = get_term_meta($term->term_id, 'avin_cta_secondary_label', true) ?: __('Request Partnership', 'avin');
$cta_secondary_url = get_term_meta($term->term_id, 'avin_cta_secondary_url', true) ?: home_url('/contact/');

$is_group = avin_term_is_group($term);
$groups = $is_group ? [] : avin_get_category_groups_for_line($term->term_id);
?>

<section class="page-hero">
	<div class="container">
		<?php avin_breadcrumbs(); ?>
		<h1><?php echo esc_html($heading); ?></h1>
		<?php if ($subtitle) : ?>
			<p class="hero-lede"><?php echo esc_html($subtitle); ?></p>
		<?php endif; ?>
		<div class="hero-actions">
			<a href="<?php echo esc_url($cta_primary_url); ?>" class="btn btn-primary"><?php echo esc_html($cta_primary_label); ?></a>
			<a href="<?php echo esc_url($cta_secondary_url); ?>" class="btn btn-secondary"><?php echo esc_html($cta_secondary_label); ?></a>
		</div>
	</div>
</section>

<div id="products">
	<?php if ($is_group) :
        $children = avin_get_business_line_children($term->term_id);
        ?>
		<section class="section">
			<div class="container">
				<?php if (!empty($children)) : ?>
					<div class="business-line-grid">
						<?php foreach ($children as $child) :
                            $child_description = get_term_meta($child->term_id, 'avin_mega_description', true) ?: $child->description;
                            $child_featured = (bool) get_term_meta($child->term_id, 'avin_featured', true);
                            ?>
							<a href="<?php echo esc_url(avin_business_line_url($child)); ?>" class="business-line-card<?php echo $child_featured ? ' is-featured' : ''; ?>">
								<span class="business-line-card-icon"><?php echo avin_icon(get_term_meta($child->term_id, 'avin_icon', true) ?: 'single-ingredient'); ?></span>
								<h3><?php echo esc_html($child->name); ?></h3>
								<?php if ($child_description) : ?>
									<p><?php echo esc_html(avin_trim($child_description, 120)); ?></p>
								<?php endif; ?>
								<span class="business-line-card-cta"><?php esc_html_e('Explore', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p class="empty-state"><?php esc_html_e('Lines in this group are being added soon.', 'avin'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php elseif (!empty($groups)) : ?>
		<?php foreach ($groups as $group) :
            $products = avin_get_products($term->term_id, $group->term_id);
            if (empty($products)) {
                continue;
            }
            ?>
			<section class="section" aria-labelledby="group-<?php echo esc_attr($group->term_id); ?>">
				<div class="container">
					<div class="section-head section-head-compact">
						<h2 id="group-<?php echo esc_attr($group->term_id); ?>"><?php echo esc_html(get_term_meta($group->term_id, 'avin_display_label', true) ?: $group->name); ?></h2>
					</div>
					<div class="product-grid">
						<?php foreach ($products as $avin_card_post) : ?>
							<?php get_template_part('template-parts/product-card', null, ['avin_card_post' => $avin_card_post]); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endforeach; ?>
	<?php else :
        $products = avin_get_products($term->term_id);
        ?>
		<section class="section">
			<div class="container">
				<?php if (!empty($products)) : ?>
					<div class="product-grid">
						<?php foreach ($products as $avin_card_post) : ?>
							<?php get_template_part('template-parts/product-card', null, ['avin_card_post' => $avin_card_post]); ?>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p class="empty-state"><?php esc_html_e('Products in this line are being added — please send an inquiry for current availability.', 'avin'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</div>

<section class="cta-band">
	<div class="container cta-band-inner">
		<div>
			<h2><?php esc_html_e('Don\'t see the specification you need?', 'avin'); ?></h2>
			<p><?php esc_html_e('We work with buyers on grade, packaging, and volume requirements not yet listed here.', 'avin'); ?></p>
		</div>
		<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-lg"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a>
	</div>
</section>

<?php get_footer(); ?>
