<?php
/**
 * Desktop Products mega menu panel. Triggered from header.php's button
 * (data-mega-trigger); toggling, outside-click, and Escape handling live
 * in assets/js/main.js. Every link here is a real, crawlable <a href>,
 * not a JS-only affordance, per the brief's SEO requirement.
 */

if (!defined('ABSPATH')) {
    exit;
}

$avin_mega_columns = avin_get_mega_menu_columns();
if (empty($avin_mega_columns)) {
    return;
}
?>
<div id="products-mega-menu" class="mega-menu" data-mega-panel hidden>
	<div class="container mega-menu-inner">
		<div class="mega-menu-columns">
			<?php foreach ($avin_mega_columns as $column) : ?>
				<div class="mega-menu-column">
					<p class="mega-menu-heading"><?php echo esc_html($column['heading']); ?></p>
					<ul>
						<?php foreach ($column['lines'] as $line) : ?>
							<li class="mega-menu-item<?php echo $line['featured'] ? ' is-featured' : ''; ?>">
								<a href="<?php echo esc_url($line['url']); ?>">
									<?php echo avin_icon($line['icon'], 'mega-menu-icon'); ?>
									<span class="mega-menu-item-body">
										<span class="mega-menu-item-name"><?php echo esc_html($line['name']); ?></span>
										<?php if ($line['description']) : ?>
											<span class="mega-menu-item-desc"><?php echo esc_html($line['description']); ?></span>
										<?php endif; ?>
										<span class="mega-menu-item-cta"><?php esc_html_e('Explore', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></span>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="mega-menu-footer">
			<a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="mega-menu-view-all">
				<?php esc_html_e('View All Products', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?>
			</a>
		</div>
	</div>
</div>
