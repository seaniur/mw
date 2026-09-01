<?php
/**
 * Single product page — every section from the brief's "Product Page —
 * Freeze-Dried Pet Food" structure (used as the shared template across
 * all business lines per section 9's ~80%-shared-content note): Hero,
 * Overview, Key Features, Ingredients, Format, Technical Specifications,
 * Packaging, Shelf Life & Storage, Documents, Inquiry, Related Products.
 * A section only renders when the field behind it has been filled in —
 * nothing here is hard-coded per the brief's "no parameter should be
 * hard-coded" requirement for technical specs (and, by the same logic,
 * every other admin-editable block).
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) :
    the_post();
    $product_id = get_the_ID();

    $business_lines = get_the_terms($product_id, 'business_line');
    $primary_line = ($business_lines && !is_wp_error($business_lines)) ? $business_lines[0] : null;

    $application = avin_field($product_id, 'application');
    $grade = avin_field($product_id, 'grade');
    $processing_method = avin_field($product_id, 'processing_method');
    $ingredient = avin_field($product_id, 'ingredient');
    $origin = avin_field($product_id, 'origin');
    $alt_name = avin_field($product_id, 'alt_name');

    $key_features = avin_field($product_id, 'key_features');
    $feature_meta = [
        'single-ingredient' => ['icon' => 'single-ingredient', 'label' => __('Single Ingredient', 'avin')],
        'human-grade' => ['icon' => 'human-grade', 'label' => __('Human-Grade Material', 'avin')],
        'no-additives' => ['icon' => 'no-additives', 'label' => __('No Additives', 'avin')],
        'high-protein' => ['icon' => 'high-protein', 'label' => __('High Protein', 'avin')],
        'low-moisture' => ['icon' => 'low-moisture', 'label' => __('Low Moisture', 'avin')],
    ];

    $ingredients_list = array_filter(array_map('trim', explode("\n", (string) avin_field($product_id, 'ingredients_list'))));

    $format = avin_field($product_id, 'format');
    $format_labels = [
        'whole' => __('Whole', 'avin'),
        'sliced' => __('Sliced', 'avin'),
        'cubed' => __('Cubed', 'avin'),
        'powder' => __('Powder', 'avin'),
        'pieces' => __('Pieces', 'avin'),
    ];

    $tech_specs = avin_field($product_id, 'tech_specs');

    $packaging_fields = [
        'packaging_type' => __('Packaging Type', 'avin'),
        'net_weight' => __('Net Weight', 'avin'),
        'bulk_packaging' => __('Bulk Packaging', 'avin'),
        'retail_packaging' => __('Retail Packaging', 'avin'),
    ];
    $packaging_rows = [];
    foreach ($packaging_fields as $pkey => $plabel) {
        $pval = avin_field($product_id, $pkey);
        if ($pval) {
            $packaging_rows[$plabel] = $pval;
        }
    }

    $shelf_life = avin_field($product_id, 'shelf_life');
    $storage_conditions = avin_field($product_id, 'storage_conditions');

    $moq = avin_field($product_id, 'moq');
    $supply_capacity = avin_field($product_id, 'supply_capacity');

    $certifications = avin_field($product_id, 'certifications');
    $quality_standards = array_filter(array_map('trim', explode("\n", (string) avin_field($product_id, 'quality_standards'))));

    $documents = avin_field($product_id, 'documents');

    $related_ids = avin_field($product_id, 'related_products');
    ?>

	<section class="product-hero">
		<div class="container product-hero-inner">
			<div class="product-hero-media">
				<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('avin-hero', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
				<?php else : ?>
					<span class="product-hero-media-placeholder" aria-hidden="true"><?php echo avin_icon('single-ingredient'); ?></span>
				<?php endif; ?>
			</div>
			<div class="product-hero-body">
				<?php avin_breadcrumbs(); ?>

				<?php if ($primary_line) : ?>
					<a href="<?php echo esc_url(avin_business_line_url($primary_line)); ?>" class="tag tag-accent"><?php echo esc_html($primary_line->name); ?></a>
				<?php endif; ?>

				<h1><?php the_title(); ?></h1>
				<?php if ($alt_name) : ?>
					<p class="product-alt-name"><?php echo esc_html($alt_name); ?></p>
				<?php endif; ?>

				<?php if (has_excerpt()) : ?>
					<p class="hero-lede"><?php echo esc_html(get_the_excerpt()); ?></p>
				<?php endif; ?>

				<dl class="product-meta-list">
					<?php if ($grade) : ?><div><dt><?php esc_html_e('Grade', 'avin'); ?></dt><dd><?php echo esc_html($grade); ?></dd></div><?php endif; ?>
					<?php if ($application) : ?><div><dt><?php esc_html_e('Application', 'avin'); ?></dt><dd><?php echo esc_html($application); ?></dd></div><?php endif; ?>
					<?php if ($processing_method) : ?><div><dt><?php esc_html_e('Processing', 'avin'); ?></dt><dd><?php echo esc_html($processing_method); ?></dd></div><?php endif; ?>
					<?php if ($origin) : ?><div><dt><?php esc_html_e('Origin', 'avin'); ?></dt><dd><?php echo esc_html($origin); ?></dd></div><?php endif; ?>
				</dl>

				<div class="hero-actions">
					<a href="#inquiry" class="btn btn-primary"><?php esc_html_e('Request a Quote', 'avin'); ?></a>
					<a href="#inquiry" class="btn btn-secondary" data-sample-request><?php esc_html_e('Request a Sample', 'avin'); ?></a>
				</div>
			</div>
		</div>
	</section>

	<div class="container product-body">
		<div class="product-main">

			<?php if (get_the_content()) : ?>
				<section class="product-section" aria-labelledby="overview-heading">
					<h2 id="overview-heading"><?php esc_html_e('Product Overview', 'avin'); ?></h2>
					<div class="prose"><?php the_content(); ?></div>
				</section>
			<?php endif; ?>

			<?php if ($key_features) : ?>
				<section class="product-section" aria-labelledby="features-heading">
					<h2 id="features-heading"><?php esc_html_e('Key Features', 'avin'); ?></h2>
					<ul class="feature-grid">
						<?php foreach ((array) $key_features as $feature) :
                            $meta = $feature_meta[$feature] ?? null;
                            if (!$meta) {
                                continue;
                            }
                            ?>
							<li class="feature-card">
								<?php echo avin_icon($meta['icon']); ?>
								<span><?php echo esc_html($meta['label']); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if (!empty($ingredients_list)) : ?>
				<section class="product-section" aria-labelledby="ingredients-heading">
					<h2 id="ingredients-heading"><?php esc_html_e('Ingredients', 'avin'); ?></h2>
					<ul class="plain-list">
						<?php foreach ($ingredients_list as $item) : ?>
							<li><?php echo esc_html($item); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if (!empty($format)) : ?>
				<section class="product-section" aria-labelledby="format-heading">
					<h2 id="format-heading"><?php esc_html_e('Product Format', 'avin'); ?></h2>
					<ul class="tag-list">
						<?php foreach ((array) $format as $f) : ?>
							<li class="tag"><?php echo esc_html($format_labels[$f] ?? $f); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if (!empty($tech_specs)) : ?>
				<section class="product-section" aria-labelledby="specs-heading">
					<h2 id="specs-heading"><?php esc_html_e('Technical Specifications', 'avin'); ?></h2>
					<div class="table-scroll">
						<table class="spec-table">
							<thead>
								<tr>
									<th><?php esc_html_e('Parameter', 'avin'); ?></th>
									<th><?php esc_html_e('Value', 'avin'); ?></th>
									<th><?php esc_html_e('Unit', 'avin'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($tech_specs as $row) : ?>
									<tr>
										<td><?php echo esc_html($row['label'] ?? ''); ?></td>
										<td><?php echo esc_html($row['value'] ?? ''); ?></td>
										<td><?php echo esc_html($row['unit'] ?? ''); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php endif; ?>

			<?php if (!empty($packaging_rows) || $moq || $supply_capacity) : ?>
				<section class="product-section" aria-labelledby="packaging-heading">
					<h2 id="packaging-heading"><?php esc_html_e('Packaging', 'avin'); ?></h2>
					<dl class="detail-list">
						<?php foreach ($packaging_rows as $label => $val) : ?>
							<div><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html($val); ?></dd></div>
						<?php endforeach; ?>
						<?php if ($moq) : ?><div><dt><?php esc_html_e('MOQ', 'avin'); ?></dt><dd><?php echo esc_html($moq); ?></dd></div><?php endif; ?>
						<?php if ($supply_capacity) : ?><div><dt><?php esc_html_e('Supply Capacity', 'avin'); ?></dt><dd><?php echo esc_html($supply_capacity); ?></dd></div><?php endif; ?>
					</dl>
				</section>
			<?php endif; ?>

			<?php if ($shelf_life || $storage_conditions) : ?>
				<section class="product-section" aria-labelledby="storage-heading">
					<h2 id="storage-heading"><?php esc_html_e('Shelf Life & Storage', 'avin'); ?></h2>
					<dl class="detail-list">
						<?php if ($shelf_life) : ?><div><dt><?php esc_html_e('Shelf Life', 'avin'); ?></dt><dd><?php echo esc_html($shelf_life); ?></dd></div><?php endif; ?>
						<?php if ($storage_conditions) : ?><div><dt><?php esc_html_e('Storage Conditions', 'avin'); ?></dt><dd><?php echo esc_html($storage_conditions); ?></dd></div><?php endif; ?>
					</dl>
				</section>
			<?php endif; ?>

			<?php if (!empty($certifications) || !empty($quality_standards)) : ?>
				<section class="product-section" aria-labelledby="quality-heading">
					<h2 id="quality-heading"><?php esc_html_e('Quality & Certifications', 'avin'); ?></h2>
					<?php if (!empty($certifications)) : ?>
						<ul class="tag-list">
							<?php foreach ($certifications as $cert) : ?>
								<?php if (!empty($cert['name'])) : ?>
									<li class="tag tag-accent"><?php echo avin_icon('check'); ?> <?php echo esc_html($cert['name']); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if (!empty($quality_standards)) : ?>
						<ul class="plain-list">
							<?php foreach ($quality_standards as $standard) : ?>
								<li><?php echo esc_html($standard); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php if (!empty($documents)) : ?>
				<section class="product-section" aria-labelledby="documents-heading">
					<h2 id="documents-heading"><?php esc_html_e('Documents', 'avin'); ?></h2>
					<ul class="document-list">
						<?php foreach ($documents as $doc) :
                            $file_id = (int) ($doc['file'] ?? 0);
                            if (!$file_id) {
                                continue;
                            }
                            $url = wp_get_attachment_url($file_id);
                            if (!$url) {
                                continue;
                            }
                            ?>
							<li>
								<a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
									<?php echo avin_icon('document'); ?>
									<?php echo esc_html($doc['label'] ?: basename(get_attached_file($file_id))); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<section class="product-section" id="inquiry" aria-labelledby="inquiry-heading">
				<h2 id="inquiry-heading"><?php esc_html_e('Request a Quote or Sample', 'avin'); ?></h2>
				<?php get_template_part('template-parts/inquiry-form', null, ['product_id' => $product_id]); ?>
			</section>

			<?php if (!empty($related_ids)) :
                $related_products = get_posts([
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'post__in' => $related_ids,
                    'orderby' => 'post__in',
                    'posts_per_page' => -1,
                    'no_found_rows' => true,
                ]);
                if (!empty($related_products)) :
                    ?>
					<section class="product-section" aria-labelledby="related-heading">
						<h2 id="related-heading"><?php esc_html_e('Related Products', 'avin'); ?></h2>
						<div class="product-grid">
							<?php foreach ($related_products as $avin_card_post) : ?>
								<?php get_template_part('template-parts/product-card', null, ['avin_card_post' => $avin_card_post]); ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif;
            endif; ?>
		</div>
	</div>

<?php
endwhile;
get_footer();
