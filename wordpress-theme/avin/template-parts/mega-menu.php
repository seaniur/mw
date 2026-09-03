<?php
/**
 * Desktop Products mega menu: column 1 is the fixed FOOD / PET FOOD /
 * FEED category selector, columns 2-4 are that category's items (image,
 * subtitle, Explore link). Every category's panel is rendered into the
 * DOM up front — switching category only toggles which panel is visible
 * (via assets/js/main.js) rather than swapping content in with JS/AJAX —
 * so every item stays a real, crawlable <a href> regardless of which
 * category is active when a crawler (or a JS-disabled visitor) sees the
 * page, per the brief's SEO requirement.
 */

if (!defined('ABSPATH')) {
    exit;
}

$avin_mega_categories = avin_get_business_line_groups();
if (empty($avin_mega_categories)) {
    return;
}
?>
<div id="products-mega-menu" class="mega-menu" data-mega-panel hidden>
	<div class="container mega-menu-inner">
		<div class="mega-menu-grid" role="tablist" aria-label="<?php esc_attr_e('Product categories', 'avin'); ?>">
			<div class="mega-menu-col-1">
				<ul class="mega-category-list">
					<?php foreach ($avin_mega_categories as $i => $category) : ?>
						<li>
							<button
								type="button"
								class="mega-category-btn<?php echo $i === 0 ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
								aria-controls="mega-panel-<?php echo esc_attr($category->term_id); ?>"
								data-mega-category-trigger
								data-mega-category="<?php echo esc_attr($category->slug); ?>"
							>
								<?php echo esc_html($category->name); ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="mega-menu-col-panels">
				<?php foreach ($avin_mega_categories as $i => $category) :
                    $items = avin_get_business_line_children($category->term_id);
                    ?>
					<div
						id="mega-panel-<?php echo esc_attr($category->term_id); ?>"
						class="mega-item-panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
						role="tabpanel"
						data-mega-panel-for="<?php echo esc_attr($category->slug); ?>"
						data-count="<?php echo esc_attr(min(count($items), 3)); ?>"
						<?php echo $i === 0 ? '' : 'hidden'; ?>
					>
						<?php if (empty($items)) : ?>
							<p class="mega-item-empty"><?php esc_html_e('Products in this category are being added soon.', 'avin'); ?></p>
						<?php else : ?>
							<?php foreach ($items as $item) :
                                $subtitle = get_term_meta($item->term_id, 'avin_mega_description', true) ?: $item->description;
                                $image_id = (int) get_term_meta($item->term_id, 'avin_image', true);
                                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'avin-card') : '';
                                ?>
								<a
									class="mega-item-card<?php echo $image_url ? ' has-image' : ''; ?>"
									href="<?php echo esc_url(avin_business_line_url($item)); ?>"
									<?php if ($image_url) : ?>style="--mega-item-image: url('<?php echo esc_url($image_url); ?>');"<?php endif; ?>
								>
									<span class="mega-item-body">
										<span class="mega-item-title"><?php echo esc_html($item->name); ?></span>
										<?php if ($subtitle) : ?>
											<span class="mega-item-subtitle"><?php echo esc_html($subtitle); ?></span>
										<?php endif; ?>
										<span class="mega-item-cta"><?php esc_html_e('Explore', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></span>
									</span>
								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="mega-menu-footer">
			<a href="<?php echo esc_url((string) get_post_type_archive_link('product')); ?>" class="mega-menu-view-all">
				<?php esc_html_e('View All Products', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?>
			</a>
		</div>
	</div>
</div>
