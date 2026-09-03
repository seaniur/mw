<?php
/**
 * Homepage — a CMS-driven page builder per the homepage brief: every
 * section below is optional (Appearance → Customize → Homepage → each
 * section's own "Show this section" checkbox), and every heading/copy/
 * image/link is editable there too — nothing here is hard-coded content,
 * only the layout. A section that has nothing configured in it simply
 * doesn't render (see each section's own emptiness check below) rather
 * than showing a blank shell.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

/* ====================================================================
   01 — Hero Slider
   ==================================================================== */
$avin_hero_slides = array_values(array_filter(avin_repeater('avin_home_hero_slides'), fn ($s) => !empty($s['heading']) || !empty($s['description'])));
if (!empty($avin_hero_slides)) :
    ?>
	<section class="home-hero" data-hero-slider>
		<div class="home-hero-slides">
			<?php foreach ($avin_hero_slides as $i => $slide) :
                $image_id = (int) ($slide['image'] ?? 0);
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'avin-hero') : '';
                ?>
				<div class="home-hero-slide<?php echo $i === 0 ? ' is-active' : ''; ?>"<?php echo $image_url ? ' style="background-image:url(\'' . esc_url($image_url) . '\')"' : ''; ?>>
					<div class="container home-hero-slide-inner">
						<?php if (!empty($slide['heading'])) : ?>
							<h1><?php echo esc_html($slide['heading']); ?></h1>
						<?php endif; ?>
						<?php if (!empty($slide['description'])) : ?>
							<p class="hero-lede"><?php echo esc_html($slide['description']); ?></p>
						<?php endif; ?>
						<?php if (!empty($slide['cta_text'])) : ?>
							<div class="hero-actions">
								<a href="<?php echo esc_url($slide['cta_link'] ?: home_url('/contact/')); ?>" class="btn btn-primary"><?php echo esc_html($slide['cta_text']); ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if (count($avin_hero_slides) > 1) : ?>
			<div class="home-hero-nav">
				<button type="button" class="home-hero-arrow" data-hero-prev aria-label="<?php esc_attr_e('Previous slide', 'avin'); ?>"><?php echo avin_icon('chevron-end'); ?></button>
				<div class="home-hero-dots">
					<?php foreach ($avin_hero_slides as $i => $slide) : ?>
						<button type="button" class="home-hero-dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-hero-dot="<?php echo esc_attr($i); ?>" aria-label="<?php echo esc_attr(sprintf(__('Go to slide %d', 'avin'), $i + 1)); ?>"></button>
					<?php endforeach; ?>
				</div>
				<button type="button" class="home-hero-arrow home-hero-arrow-next" data-hero-next aria-label="<?php esc_attr_e('Next slide', 'avin'); ?>"><?php echo avin_icon('chevron-end'); ?></button>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php
/* ====================================================================
   02 — Company Value / Trust
   ==================================================================== */
if (get_theme_mod('avin_home_trust_enabled', true)) :
    $trust_heading = get_theme_mod('avin_home_trust_heading', '');
    $trust_copy = get_theme_mod('avin_home_trust_copy', '');
    $trust_points = avin_repeater('avin_home_trust_points');
    if ($trust_heading || $trust_copy || !empty($trust_points)) :
        ?>
		<section class="section" aria-labelledby="trust-heading">
			<div class="container">
				<div class="section-head">
					<?php if ($trust_heading) : ?><h2 id="trust-heading"><?php echo esc_html($trust_heading); ?></h2><?php endif; ?>
					<?php if ($trust_copy) : ?><p class="section-lede"><?php echo esc_html($trust_copy); ?></p><?php endif; ?>
				</div>
				<?php if (!empty($trust_points)) : ?>
					<ul class="value-point-list">
						<?php foreach ($trust_points as $point) :
                            if (empty($point['text'])) {
                                continue;
                            }
                            ?>
							<li><?php echo avin_icon('check'); ?> <?php echo esc_html($point['text']); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   03 — Product Categories (Food / Pet Food / Feed business-line groups)
   ==================================================================== */
if (get_theme_mod('avin_home_categories_enabled', true)) :
    $categories_heading = get_theme_mod('avin_home_categories_heading', '');
    $avin_groups = avin_get_business_line_groups();
    if (!empty($avin_groups)) :
        ?>
		<section class="section section-tinted" aria-labelledby="categories-heading">
			<div class="container">
				<?php if ($categories_heading) : ?>
					<div class="section-head"><h2 id="categories-heading"><?php echo esc_html($categories_heading); ?></h2></div>
				<?php endif; ?>
				<div class="business-line-grid">
					<?php foreach ($avin_groups as $group) :
                        $group_description = get_term_meta($group->term_id, 'avin_mega_description', true) ?: $group->description;
                        $group_image_id = (int) get_term_meta($group->term_id, 'avin_image', true);
                        ?>
						<a href="<?php echo esc_url(avin_business_line_url($group)); ?>" class="business-line-card<?php echo $group_image_id ? ' has-image' : ''; ?>">
							<?php if ($group_image_id) : ?>
								<span class="business-line-card-image"><?php echo wp_get_attachment_image($group_image_id, 'avin-card'); ?></span>
							<?php else : ?>
								<span class="business-line-card-icon"><?php echo avin_icon(get_term_meta($group->term_id, 'avin_icon', true) ?: 'single-ingredient'); ?></span>
							<?php endif; ?>
							<h3><?php echo esc_html($group->name); ?></h3>
							<?php if ($group_description) : ?>
								<p><?php echo esc_html(avin_trim($group_description, 140)); ?></p>
							<?php endif; ?>
							<span class="business-line-card-cta"><?php esc_html_e('Explore', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   04 — Sourcing (Food)
   ==================================================================== */
if (get_theme_mod('avin_home_sourcing_enabled', true)) :
    $sourcing_heading = get_theme_mod('avin_home_sourcing_heading', '');
    $sourcing_copy = get_theme_mod('avin_home_sourcing_copy', '');
    $sourcing_steps = array_values(array_filter(avin_repeater('avin_home_sourcing_steps'), fn ($s) => !empty($s['label'])));
    $sourcing_cta_text = get_theme_mod('avin_home_sourcing_cta_text', '');
    if ($sourcing_heading || $sourcing_copy || !empty($sourcing_steps)) :
        $food_group = get_term_by('slug', 'food', 'business_line');
        $sourcing_cta_link = get_theme_mod('avin_home_sourcing_cta_link', '') ?: ($food_group ? avin_business_line_url($food_group) : (string) get_post_type_archive_link('product'));
        ?>
		<section class="section" aria-labelledby="sourcing-heading">
			<div class="container">
				<div class="section-head">
					<?php if ($sourcing_heading) : ?><h2 id="sourcing-heading"><?php echo esc_html($sourcing_heading); ?></h2><?php endif; ?>
					<?php if ($sourcing_copy) : ?><p class="section-lede"><?php echo esc_html($sourcing_copy); ?></p><?php endif; ?>
				</div>
				<?php if (!empty($sourcing_steps)) : ?>
					<ol class="process-steps">
						<?php foreach ($sourcing_steps as $i => $step) :
                            $step_image_id = (int) ($step['image'] ?? 0);
                            ?>
							<li class="process-step">
								<?php if ($step_image_id) : ?>
									<span class="process-step-icon"><?php echo wp_get_attachment_image($step_image_id, [40, 40]); ?></span>
								<?php else : ?>
									<span class="process-step-number"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
								<?php endif; ?>
								<span class="process-step-label"><?php echo esc_html($step['label']); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
				<?php if ($sourcing_cta_text) : ?>
					<a href="<?php echo esc_url($sourcing_cta_link); ?>" class="btn btn-secondary"><?php echo esc_html($sourcing_cta_text); ?> <?php echo avin_icon('arrow-end'); ?></a>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   05 — Pet Food Manufacturing
   ==================================================================== */
if (get_theme_mod('avin_home_petfood_enabled', true)) :
    $petfood_heading = get_theme_mod('avin_home_petfood_heading', '');
    $petfood_copy = get_theme_mod('avin_home_petfood_copy', '');
    $petfood_highlights = array_values(array_filter(avin_repeater('avin_home_petfood_highlights'), fn ($h) => !empty($h['text'])));
    $petfood_image_id = (int) get_theme_mod('avin_home_petfood_image', 0);
    $petfood_cta_text = get_theme_mod('avin_home_petfood_cta_text', '');
    if ($petfood_heading || $petfood_copy || !empty($petfood_highlights)) :
        $petfood_group = get_term_by('slug', 'pet-food', 'business_line');
        $petfood_cta_link = get_theme_mod('avin_home_petfood_cta_link', '') ?: ($petfood_group ? avin_business_line_url($petfood_group) : (string) get_post_type_archive_link('product'));
        ?>
		<section class="section section-tinted" aria-labelledby="petfood-heading">
			<div class="container split-layout<?php echo $petfood_image_id ? '' : ' no-media'; ?>">
				<div class="split-layout-body">
					<?php if ($petfood_heading) : ?><h2 id="petfood-heading"><?php echo esc_html($petfood_heading); ?></h2><?php endif; ?>
					<?php if ($petfood_copy) : ?><p class="section-lede"><?php echo esc_html($petfood_copy); ?></p><?php endif; ?>
					<?php if (!empty($petfood_highlights)) : ?>
						<ul class="value-point-list">
							<?php foreach ($petfood_highlights as $h) : ?>
								<li><?php echo avin_icon('check'); ?> <?php echo esc_html($h['text']); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ($petfood_cta_text) : ?>
						<a href="<?php echo esc_url($petfood_cta_link); ?>" class="btn btn-primary"><?php echo esc_html($petfood_cta_text); ?> <?php echo avin_icon('arrow-end'); ?></a>
					<?php endif; ?>
				</div>
				<?php if ($petfood_image_id) : ?>
					<div class="split-layout-media"><?php echo wp_get_attachment_image($petfood_image_id, 'avin-hero'); ?></div>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   06 — Featured Products
   ==================================================================== */
if (get_theme_mod('avin_home_featured_enabled', true)) :
    $featured_heading = get_theme_mod('avin_home_featured_heading', '');
    $featured_cta_text = get_theme_mod('avin_home_featured_cta_text', '');
    $featured_cards = [];
    foreach (avin_repeater('avin_home_featured_products') as $row) {
        $product_id = (int) ($row['product'] ?? 0);
        if (!$product_id || get_post_status($product_id) !== 'publish') {
            continue;
        }
        $override_image_id = (int) ($row['image_override'] ?? 0);
        $featured_cards[] = [
            'title' => $row['name_override'] ?: get_the_title($product_id),
            'description' => $row['description_override'] ?: get_the_excerpt($product_id),
            'link' => $row['link_override'] ?: get_permalink($product_id),
            'image_id' => $override_image_id ?: get_post_thumbnail_id($product_id),
        ];
    }
    if (!empty($featured_cards)) :
        ?>
		<section class="section" aria-labelledby="featured-heading">
			<div class="container">
				<?php if ($featured_heading) : ?>
					<div class="section-head"><h2 id="featured-heading"><?php echo esc_html($featured_heading); ?></h2></div>
				<?php endif; ?>
				<div class="product-grid">
					<?php foreach ($featured_cards as $card) : ?>
						<article class="product-card">
							<a href="<?php echo esc_url($card['link']); ?>" class="product-card-media">
								<?php if ($card['image_id']) : ?>
									<?php echo wp_get_attachment_image($card['image_id'], 'avin-card', false, ['loading' => 'lazy']); ?>
								<?php else : ?>
									<span class="product-card-media-placeholder" aria-hidden="true"><?php echo avin_icon('single-ingredient'); ?></span>
								<?php endif; ?>
							</a>
							<div class="product-card-body">
								<h3 class="product-card-title"><a href="<?php echo esc_url($card['link']); ?>"><?php echo esc_html($card['title']); ?></a></h3>
								<?php if ($card['description']) : ?>
									<p class="product-card-ingredient"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($card['description']), 16)); ?></p>
								<?php endif; ?>
								<a href="<?php echo esc_url($card['link']); ?>" class="product-card-link"><?php esc_html_e('View Product', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<?php if ($featured_cta_text) : ?>
					<div class="section-footer-cta">
						<a href="<?php echo esc_url((string) get_post_type_archive_link('product')); ?>" class="btn btn-secondary"><?php echo esc_html($featured_cta_text); ?> <?php echo avin_icon('arrow-end'); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   07 — Private Label
   ==================================================================== */
if (get_theme_mod('avin_home_private_label_enabled', true)) :
    $pl_heading = get_theme_mod('avin_home_private_label_heading', '');
    $pl_copy = get_theme_mod('avin_home_private_label_copy', '');
    $pl_steps = array_values(array_filter(avin_repeater('avin_home_private_label_steps'), fn ($s) => !empty($s['label'])));
    $pl_cta_text = get_theme_mod('avin_home_private_label_cta_text', '');
    if ($pl_heading || $pl_copy || !empty($pl_steps)) :
        $pl_cta_link = get_theme_mod('avin_home_private_label_cta_link', '') ?: home_url('/contact/');
        ?>
		<section class="section section-tinted" aria-labelledby="private-label-heading">
			<div class="container">
				<div class="section-head">
					<?php if ($pl_heading) : ?><h2 id="private-label-heading"><?php echo esc_html($pl_heading); ?></h2><?php endif; ?>
					<?php if ($pl_copy) : ?><p class="section-lede"><?php echo esc_html($pl_copy); ?></p><?php endif; ?>
				</div>
				<?php if (!empty($pl_steps)) : ?>
					<ol class="process-steps">
						<?php foreach ($pl_steps as $i => $step) : ?>
							<li class="process-step">
								<span class="process-step-number"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
								<span class="process-step-label"><?php echo esc_html($step['label']); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
				<?php if ($pl_cta_text) : ?>
					<a href="<?php echo esc_url($pl_cta_link); ?>" class="btn btn-primary"><?php echo esc_html($pl_cta_text); ?> <?php echo avin_icon('arrow-end'); ?></a>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   08 — How We Work
   ==================================================================== */
if (get_theme_mod('avin_home_how_we_work_enabled', true)) :
    $how_heading = get_theme_mod('avin_home_how_we_work_heading', '');
    $how_steps = array_values(array_filter(avin_repeater('avin_home_how_we_work_steps'), fn ($s) => !empty($s['label'])));
    if (!empty($how_steps)) :
        ?>
		<section class="section" aria-labelledby="how-we-work-heading">
			<div class="container">
				<?php if ($how_heading) : ?>
					<div class="section-head"><h2 id="how-we-work-heading"><?php echo esc_html($how_heading); ?></h2></div>
				<?php endif; ?>
				<div class="numbered-step-grid">
					<?php foreach ($how_steps as $step) : ?>
						<div class="numbered-step">
							<?php if (!empty($step['number'])) : ?>
								<span class="numbered-step-number"><?php echo esc_html($step['number']); ?></span>
							<?php endif; ?>
							<h3><?php echo esc_html($step['label']); ?></h3>
							<?php if (!empty($step['description'])) : ?>
								<p><?php echo esc_html($step['description']); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   09 — Quality
   ==================================================================== */
if (get_theme_mod('avin_home_quality_enabled', true)) :
    $quality_heading = get_theme_mod('avin_home_quality_heading', '');
    $quality_points = array_values(array_filter(avin_repeater('avin_home_quality_points'), fn ($p) => !empty($p['text'])));
    $quality_badges = array_values(array_filter(avin_repeater('avin_home_quality_badges'), fn ($b) => !empty($b['image'])));
    if ($quality_heading || !empty($quality_points) || !empty($quality_badges)) :
        ?>
		<section class="section section-tinted" aria-labelledby="quality-heading">
			<div class="container">
				<?php if ($quality_heading) : ?>
					<div class="section-head"><h2 id="quality-heading"><?php echo esc_html($quality_heading); ?></h2></div>
				<?php endif; ?>
				<?php if (!empty($quality_points)) : ?>
					<ul class="value-point-list value-point-list-inline">
						<?php foreach ($quality_points as $point) : ?>
							<li><?php echo avin_icon('check'); ?> <?php echo esc_html($point['text']); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if (!empty($quality_badges)) : ?>
					<div class="badge-row">
						<?php foreach ($quality_badges as $badge) : ?>
							<div class="badge-item">
								<?php echo wp_get_attachment_image((int) $badge['image'], [96, 96]); ?>
								<?php if (!empty($badge['title'])) : ?><span><?php echo esc_html($badge['title']); ?></span><?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
    endif;
endif;

/* ====================================================================
   10 — B2B CTA / RFQ
   ==================================================================== */
if (get_theme_mod('avin_home_cta_enabled', true)) :
    $rfq_heading = get_theme_mod('avin_home_cta_heading', '');
    $rfq_copy = get_theme_mod('avin_home_cta_copy', '');
    $rfq_primary_text = get_theme_mod('avin_home_cta_primary_text', '');
    $rfq_secondary_text = get_theme_mod('avin_home_cta_secondary_text', '');
    if ($rfq_heading || $rfq_primary_text) :
        $rfq_primary_link = get_theme_mod('avin_home_cta_primary_link', '') ?: home_url('/contact/');
        $rfq_secondary_link = get_theme_mod('avin_home_cta_secondary_link', '') ?: home_url('/contact/');
        ?>
		<section class="cta-band">
			<div class="container cta-band-inner">
				<div>
					<?php if ($rfq_heading) : ?><h2><?php echo esc_html($rfq_heading); ?></h2><?php endif; ?>
					<?php if ($rfq_copy) : ?><p><?php echo esc_html($rfq_copy); ?></p><?php endif; ?>
				</div>
				<div class="cta-band-actions">
					<?php if ($rfq_primary_text) : ?>
						<a href="<?php echo esc_url($rfq_primary_link); ?>" class="btn btn-primary btn-lg"><?php echo esc_html($rfq_primary_text); ?></a>
					<?php endif; ?>
					<?php if ($rfq_secondary_text) : ?>
						<a href="<?php echo esc_url($rfq_secondary_link); ?>" class="btn btn-secondary btn-lg"><?php echo esc_html($rfq_secondary_text); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
    endif;
endif;

get_footer();
