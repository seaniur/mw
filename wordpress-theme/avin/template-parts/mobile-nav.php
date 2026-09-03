<?php
/**
 * Mobile navigation drawer — a first-class experience per the brief, not
 * a desktop menu crammed into a hamburger. Slides in from the logical
 * inline-start edge (assets/css/main.css uses inset-inline-start, not
 * left/right, so this mirrors correctly under RTL Farsi without a
 * separate RTL rule). Mirrors avin_primary_nav_items() (Home/Products/
 * Blog/About/Contact); Products expands into the same FOOD/PET FOOD/FEED
 * categories as the desktop mega menu, each expanding further into its
 * items — a nested accordion rather than attempting the desktop's
 * multi-column hover layout on a touch screen.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="mobile-nav" class="mobile-nav" data-mobile-nav hidden>
	<button type="button" class="mobile-nav-overlay" data-mobile-nav-close aria-label="<?php esc_attr_e('Close menu', 'avin'); ?>"></button>
	<div class="mobile-nav-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Menu', 'avin'); ?>">
		<div class="mobile-nav-head">
			<span class="mobile-nav-title"><?php esc_html_e('Menu', 'avin'); ?></span>
			<button type="button" class="icon-button" data-mobile-nav-close aria-label="<?php esc_attr_e('Close menu', 'avin'); ?>">
				<?php echo avin_icon('close'); ?>
			</button>
		</div>

		<nav class="mobile-nav-nav" aria-label="<?php esc_attr_e('Mobile', 'avin'); ?>">
		<ul class="mobile-nav-list">
				<?php foreach (avin_primary_nav_items() as $nav_item) : ?>
					<?php if (!empty($nav_item['mega'])) : ?>
						<li class="mobile-nav-accordion">
							<button type="button" class="mobile-nav-accordion-trigger" aria-expanded="false" aria-controls="mobile-products-panel" data-accordion-trigger>
								<?php echo esc_html($nav_item['label']); ?>
								<?php echo avin_icon('chevron-down'); ?>
							</button>
							<div id="mobile-products-panel" class="mobile-nav-submenu" data-accordion-panel hidden>
								<ul class="mobile-nav-categories">
									<?php foreach (avin_get_mega_categories() as $category) :
                                        $panel_id = 'mobile-mega-' . $category->term_id;
                                        $items = avin_get_mega_items($category->term_id);
                                        ?>
										<li class="mobile-nav-subaccordion">
											<button type="button" class="mobile-nav-subaccordion-trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>" data-accordion-trigger>
												<?php echo esc_html($category->name); ?>
												<?php echo avin_icon('chevron-down'); ?>
											</button>
											<div id="<?php echo esc_attr($panel_id); ?>" class="mobile-nav-subsubmenu" data-accordion-panel hidden>
												<ul>
													<?php foreach ($items as $item) : ?>
														<li>
															<a href="<?php echo esc_url(avin_mega_item_url($item)); ?>">
																<?php echo esc_html($item->name); ?>
																<?php echo avin_icon('chevron-end'); ?>
															</a>
														</li>
													<?php endforeach; ?>
												</ul>
											</div>
										</li>
									<?php endforeach; ?>
									<li>
										<a class="mobile-nav-viewall" href="<?php echo esc_url((string) get_post_type_archive_link('product')); ?>">
											<?php esc_html_e('View All Products', 'avin'); ?>
											<?php echo avin_icon('arrow-end'); ?>
										</a>
									</li>
								</ul>
							</div>
						</li>
					<?php else : ?>
						<li><a href="<?php echo esc_url($nav_item['url']); ?>"><?php echo esc_html($nav_item['label']); ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="mobile-nav-actions">
			<?php get_template_part('template-parts/language-switcher'); ?>
			<a class="btn btn-primary btn-block" href="<?php echo esc_url(home_url('/contact/')); ?>">
				<?php esc_html_e('Send an Inquiry', 'avin'); ?>
			</a>
		</div>
	</div>
</div>
