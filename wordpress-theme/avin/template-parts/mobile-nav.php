<?php
/**
 * Mobile navigation drawer — a first-class experience per the brief, not
 * a desktop menu crammed into a hamburger. Slides in from the logical
 * inline-start edge (assets/css/main.css uses inset-inline-start, not
 * left/right, so this mirrors correctly under RTL Farsi without a
 * separate RTL rule). Business lines are listed flat inside one
 * "Products" accordion, matching the brief's recommended mobile structure.
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
				<li class="mobile-nav-accordion">
					<button type="button" class="mobile-nav-accordion-trigger" aria-expanded="false" aria-controls="mobile-products-panel" data-accordion-trigger>
						<?php esc_html_e('Products', 'avin'); ?>
						<?php echo avin_icon('chevron-down'); ?>
					</button>
					<div id="mobile-products-panel" class="mobile-nav-submenu" data-accordion-panel hidden>
						<ul>
							<?php foreach (avin_get_business_lines() as $line) : ?>
								<li>
									<a href="<?php echo esc_url(avin_business_line_url($line)); ?>">
										<?php echo esc_html($line->name); ?>
										<?php echo avin_icon('chevron-end'); ?>
									</a>
								</li>
							<?php endforeach; ?>
							<li>
								<a class="mobile-nav-viewall" href="<?php echo esc_url(get_post_type_archive_link('product')); ?>">
									<?php esc_html_e('View All Products', 'avin'); ?>
									<?php echo avin_icon('arrow-end'); ?>
								</a>
							</li>
						</ul>
					</div>
				</li>

				<?php if (has_nav_menu('primary')) : ?>
					<?php
					wp_nav_menu([
						'theme_location' => 'primary',
						'container' => false,
						'items_wrap' => '%3$s',
					]);
					?>
				<?php else : ?>
					<li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About', 'avin'); ?></a></li>
				<?php endif; ?>
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
