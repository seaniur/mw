<?php
/**
 * Business-line landing page (/freeze-dried-pet-food/, /air-dried-pet-food/,
 * /chicken-feet-products/, /freeze-dried-human-food/, /ingredients-
 * solutions/). Follows the brief's Landing Page journey: Hero → grouped
 * product sections (Poultry / Marine / Fruits & Vegetables, where the line
 * has them) → flat product grid otherwise, e.g. Chicken Feet & Paws' five
 * named products.
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

$groups = avin_get_category_groups_for_line($term->term_id);
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
	<?php if (!empty($groups)) : ?>
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
