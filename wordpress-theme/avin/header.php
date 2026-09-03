<?php
/**
 * Site header: [logo + company name] — [Home / Products (mega menu) /
 * Blog / About / Contact] — [search / language switcher / "Send an
 * Inquiry" CTA / mobile nav trigger], sticky with a compact state on
 * scroll (assets/css/main.css .is-compact, toggled in assets/js/main.js).
 * Markup avoids anything that depends on :hover alone — every interactive
 * piece works by click/tap and keyboard too.
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'avin'); ?></a>

<header class="site-header" data-site-header>
	<div class="site-header-bar">
		<div class="container site-header-inner">
			<?php avin_site_logo(); ?>

			<nav class="main-nav" aria-label="<?php esc_attr_e('Primary', 'avin'); ?>">
				<ul class="main-nav-list">
					<?php foreach (avin_primary_nav_items() as $nav_item) : ?>
						<?php if (!empty($nav_item['mega'])) : ?>
							<li class="main-nav-item has-mega">
								<button
									type="button"
									class="main-nav-trigger"
									aria-expanded="false"
									aria-controls="products-mega-menu"
									data-mega-trigger
								>
									<?php echo esc_html($nav_item['label']); ?>
									<?php echo avin_icon('chevron-down'); ?>
								</button>
							</li>
						<?php else : ?>
							<li class="main-nav-item">
								<a href="<?php echo esc_url($nav_item['url']); ?>"<?php echo avin_nav_is_current($nav_item['url']) ? ' aria-current="page"' : ''; ?>>
									<?php echo esc_html($nav_item['label']); ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</nav>

			<div class="site-header-actions">
				<button type="button" class="icon-button" data-search-toggle aria-expanded="false" aria-controls="site-search">
					<?php echo avin_icon('search'); ?>
					<span class="screen-reader-text"><?php esc_html_e('Search', 'avin'); ?></span>
				</button>

				<?php get_template_part('template-parts/language-switcher'); ?>

				<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-sm">
					<?php esc_html_e('Send an Inquiry', 'avin'); ?>
				</a>

				<button type="button" class="icon-button mobile-nav-toggle" data-mobile-nav-toggle aria-expanded="false" aria-controls="mobile-nav">
					<?php echo avin_icon('menu'); ?>
					<span class="screen-reader-text"><?php esc_html_e('Open menu', 'avin'); ?></span>
				</button>
			</div>
		</div>

		<div id="site-search" class="site-search" data-search-panel hidden>
			<form class="container site-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
				<label class="screen-reader-text" for="avin-search-input"><?php esc_html_e('Search products', 'avin'); ?></label>
				<input type="search" id="avin-search-input" name="s" placeholder="<?php esc_attr_e('Search products, categories, ingredients…', 'avin'); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" class="btn btn-secondary btn-sm"><?php esc_html_e('Search', 'avin'); ?></button>
			</form>
		</div>
	</div>

	<?php get_template_part('template-parts/mega-menu'); ?>
</header>

<?php get_template_part('template-parts/mobile-nav'); ?>

<main id="main" class="site-main">
