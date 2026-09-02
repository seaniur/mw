<?php
/**
 * Coming Soon mode: a site-wide gate, toggled from Appearance → Customize
 * → Coming Soon Mode, that shows a branded holding page to every logged-
 * out visitor while the real site is still being built. Logged-in team
 * members (any wp-admin account) always see and can edit the real site
 * normally — nothing here touches wp-admin, REST, or login.
 *
 * The holding page's headline/body text is the "Coming Soon" Page's own
 * title/content (Pages → Coming Soon in wp-admin) — a normal WordPress
 * Page anyone with edit access can open and rewrite, not something baked
 * into this file. This is intentionally a full stand-alone document (its
 * own <html>/<head>), not the theme's header/footer — a maintenance page
 * shouldn't show navigation into a site that isn't finished yet.
 */

if (!defined('ABSPATH')) {
    exit;
}

function avin_maybe_show_coming_soon(): void
{
    // template_redirect only ever fires on the front end (wp-admin, REST,
    // and admin-post.php — the inquiry form's handler — use entirely
    // separate request flows), so this can't lock the team out of
    // wp-admin or block form submissions.
    if (is_user_logged_in()) {
        return;
    }
    if (!get_theme_mod('avin_coming_soon_enabled', false)) {
        return;
    }

    avin_render_coming_soon_page();
    exit;
}
add_action('template_redirect', 'avin_maybe_show_coming_soon', 0);

function avin_render_coming_soon_page(): void
{
    $page = get_page_by_path('coming-soon');
    $title = $page ? get_the_title($page) : __('Coming Soon', 'avin');
    $content = $page ? apply_filters('the_content', $page->post_content) : '';
    $email = get_theme_mod('avin_contact_email', 'sales@avinparto.com');

    nocache_headers();
    ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html($title . ' — ' . get_bloginfo('name')); ?></title>
	<meta name="robots" content="noindex, follow">
	<?php if (has_site_icon()) : ?>
		<link rel="icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
	<?php endif; ?>
	<style>
		:root {
			--brand-red: #c8452f;
			--brand-orange: #e2793a;
			--brand-amber: #eba23c;
			--brand-gradient: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-orange) 55%, var(--brand-amber) 100%);
			--color-paper: #fbf9f6;
			--color-ink: #221f1c;
			--color-ink-soft: #55504a;
			--color-hairline: #e6ded3;
		}
		* { box-sizing: border-box; }
		html, body { height: 100%; margin: 0; }
		body {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100svh;
			padding: 24px;
			background: var(--color-paper);
			color: var(--color-ink);
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
			text-align: center;
		}
		.card { max-width: 560px; width: 100%; }
		.badge {
			width: 72px; height: 72px;
			margin: 0 auto 28px;
			border-radius: 18px;
			background: var(--brand-gradient);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #fff;
			font-weight: 800;
			font-size: 32px;
			box-shadow: 0 12px 32px rgba(200, 69, 47, 0.28);
			overflow: hidden;
		}
		.badge img { max-width: 100%; max-height: 100%; object-fit: contain; }
		.eyebrow {
			font-size: 12px; font-weight: 700; letter-spacing: 0.12em;
			text-transform: uppercase; color: var(--brand-red);
			margin: 0 0 14px;
		}
		h1 { margin: 0 0 12px; font-size: clamp(1.8rem, 1.4rem + 2vw, 2.5rem); letter-spacing: -0.01em; }
		.lede { margin: 0 auto 28px; max-width: 440px; font-size: 1.05rem; line-height: 1.6; color: var(--color-ink-soft); }
		.lede p { margin: 0 0 1em; }
		.lede p:last-child { margin-bottom: 0; }
		.cta {
			display: inline-flex; align-items: center; gap: 8px;
			padding: 0.9em 1.8em;
			border-radius: 999px;
			background: var(--brand-gradient);
			color: #fff;
			font-weight: 700;
			font-size: 0.95rem;
			text-decoration: none;
			box-shadow: 0 8px 24px rgba(200, 69, 47, 0.22);
		}
		.footer { margin-top: 40px; font-size: 12px; color: var(--color-ink-soft); opacity: 0.75; }
	</style>
</head>
<body>
	<main class="card">
		<div class="badge" aria-hidden="true">
			<?php if (has_custom_logo()) : ?>
				<?php echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'thumbnail'); ?>
			<?php else : ?>
				<?php echo esc_html(mb_substr(get_bloginfo('name'), 0, 1)); ?>
			<?php endif; ?>
		</div>
		<p class="eyebrow"><?php esc_html_e('International B2B Sourcing & Supply Partner', 'avin'); ?></p>
		<h1><?php echo esc_html(get_bloginfo('name') ?: $title); ?></h1>
		<?php if ($content) : ?>
			<div class="lede"><?php echo wp_kses_post($content); ?></div>
		<?php endif; ?>
		<?php if ($email) : ?>
			<a class="cta" href="mailto:<?php echo esc_attr($email); ?>"><?php esc_html_e('Get in Touch', 'avin'); ?> →</a>
		<?php endif; ?>
		<p class="footer">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'avin'); ?></p>
	</main>
</body>
</html>
	<?php
}
