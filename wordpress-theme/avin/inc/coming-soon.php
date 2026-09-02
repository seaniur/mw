<?php
/**
 * Coming Soon mode: a site-wide gate, toggled from Appearance → Customize
 * → Coming Soon Mode, that shows a branded holding page — with a "Notify
 * Me" email opt-in — to every logged-out visitor while the real site is
 * still being built. Any logged-in WordPress account (the whole team,
 * any role) always sees and can edit the real site normally; nothing
 * here touches wp-admin, REST, or login.
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
    // and admin-post.php — the inquiry/notify handlers — use entirely
    // separate request flows), so this can't lock the team out of
    // wp-admin or block form submissions. Any logged-in account bypasses,
    // regardless of role — everyone else sees the holding page.
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
    $notify_status = isset($_GET['avin_notify']) ? sanitize_key(wp_unslash($_GET['avin_notify'])) : '';

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
			--color-success: #2f7a4f;
			--color-error: #b3261e;
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
		.divider { margin: 36px auto; max-width: 320px; border: 0; border-top: 1px solid var(--color-hairline); }
		.notify-label { font-size: 13px; font-weight: 700; margin: 0 0 12px; }
		.notify-form { display: flex; gap: 8px; max-width: 380px; margin: 0 auto; }
		.notify-form input[type="email"] {
			flex: 1;
			padding: 0.75em 1em;
			border: 1px solid var(--color-hairline);
			border-radius: 999px;
			background: #fff;
			font-size: 0.9rem;
			min-width: 0;
		}
		.notify-form button {
			padding: 0.75em 1.4em;
			border: 0;
			border-radius: 999px;
			background: var(--color-ink);
			color: #fff;
			font-weight: 700;
			font-size: 0.9rem;
			cursor: pointer;
			flex-shrink: 0;
		}
		.notify-hp { position: absolute; opacity: 0; height: 0; width: 0; overflow: hidden; pointer-events: none; }
		.notify-notice { font-size: 13px; font-weight: 600; margin: 0 0 14px; }
		.notify-notice.is-success { color: var(--color-success); }
		.notify-notice.is-error { color: var(--color-error); }
		@media (max-width: 420px) {
			.notify-form { flex-direction: column; }
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

		<hr class="divider">

		<?php if ($notify_status === 'success') : ?>
			<p class="notify-notice is-success" role="status"><?php esc_html_e('Thanks — we\'ll email you the moment the new site is live.', 'avin'); ?></p>
		<?php elseif ($notify_status === 'duplicate') : ?>
			<p class="notify-notice is-success" role="status"><?php esc_html_e('You\'re already on the list — we\'ll be in touch at launch.', 'avin'); ?></p>
		<?php elseif ($notify_status === 'error') : ?>
			<p class="notify-notice is-error" role="alert"><?php esc_html_e('Please enter a valid email address.', 'avin'); ?></p>
		<?php endif; ?>

		<p class="notify-label"><?php esc_html_e('Get notified when we launch', 'avin'); ?></p>
		<form class="notify-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
			<input type="hidden" name="action" value="avin_notify">
			<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>">
			<input type="hidden" name="avin_notify_ts" value="<?php echo esc_attr(time()); ?>">
			<?php wp_nonce_field('avin_notify_form', 'avin_notify_nonce'); ?>
			<span class="notify-hp" aria-hidden="true">
				<label for="avin-notify-hp"><?php esc_html_e('Leave this field empty', 'avin'); ?></label>
				<input type="text" id="avin-notify-hp" name="hp_field" tabindex="-1" autocomplete="off">
			</span>
			<label class="screen-reader-text" for="avin-notify-email"><?php esc_html_e('Email address', 'avin'); ?></label>
			<input type="email" id="avin-notify-email" name="email" placeholder="<?php esc_attr_e('you@company.com', 'avin'); ?>" required>
			<button type="submit"><?php esc_html_e('Notify Me', 'avin'); ?></button>
		</form>

		<p class="footer">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'avin'); ?></p>
	</main>
</body>
</html>
	<?php
}

/* -------------------------------------------------------------------- */
/* "Notify Me" signups: storage + submission handler                    */
/* -------------------------------------------------------------------- */

function avin_register_subscriber_cpt(): void
{
    register_post_type('subscriber', [
        'labels' => [
            'name' => __('Notify Me Signups', 'avin'),
            'singular_name' => __('Signup', 'avin'),
            'all_items' => __('Notify Me Signups', 'avin'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email',
        'supports' => ['title'],
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
    ]);
}
add_action('init', 'avin_register_subscriber_cpt');

function avin_subscriber_columns(array $columns): array
{
    return [
        'cb' => $columns['cb'],
        'title' => __('Email', 'avin'),
        'date' => $columns['date'],
    ];
}
add_filter('manage_subscriber_posts_columns', 'avin_subscriber_columns');

/**
 * Handles the "Notify Me" form. Three bot defenses, same soft-fail
 * pattern as the inquiry form (a bot is told "success" either way, so it
 * gets no signal about which defense caught it):
 *   - honeypot field real visitors never see or fill
 *   - a nonce, so the form can't be replayed from elsewhere
 *   - a minimum time-on-page (submissions faster than 3s are almost
 *     always scripted, not a human reading the page first)
 */
function avin_handle_notify_signup(): void
{
    if (!isset($_POST['avin_notify_nonce']) || !wp_verify_nonce($_POST['avin_notify_nonce'], 'avin_notify_form')) {
        wp_die(esc_html__('Security check failed. Please go back and try again.', 'avin'), '', ['response' => 403]);
    }

    $redirect_to = isset($_POST['redirect_to']) ? esc_url_raw(wp_unslash($_POST['redirect_to'])) : home_url('/');
    $redirect_to = remove_query_arg('avin_notify', $redirect_to);

    $submitted_at = absint($_POST['avin_notify_ts'] ?? 0);
    $too_fast = $submitted_at && (time() - $submitted_at) < 3;

    if (!empty($_POST['hp_field']) || $too_fast) {
        wp_safe_redirect(add_query_arg('avin_notify', 'success', $redirect_to));
        exit;
    }

    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    if ($email === '' || !is_email($email)) {
        wp_safe_redirect(add_query_arg('avin_notify', 'error', $redirect_to));
        exit;
    }

    $existing = get_posts([
        'post_type' => 'subscriber',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'meta_key' => '_avin_email',
        'meta_value' => $email,
        'fields' => 'ids',
        'no_found_rows' => true,
    ]);

    if (!empty($existing)) {
        wp_safe_redirect(add_query_arg('avin_notify', 'duplicate', $redirect_to));
        exit;
    }

    $subscriber_id = wp_insert_post([
        'post_type' => 'subscriber',
        'post_title' => $email,
        'post_status' => 'publish',
    ]);

    if (!is_wp_error($subscriber_id) && $subscriber_id) {
        update_post_meta($subscriber_id, '_avin_email', $email);
    }

    $recipient = get_theme_mod('avin_inquiry_recipient') ?: get_theme_mod('avin_contact_email', get_option('admin_email'));
    $recipient = apply_filters('avin_notify_recipient', $recipient);
    wp_mail(
        $recipient,
        sprintf('[%s] New "Notify Me" signup', get_bloginfo('name')),
        sprintf("%s just signed up to be notified when the new site launches.\n\nTotal signups so far: %d", $email, (int) wp_count_posts('subscriber')->publish)
    );

    wp_safe_redirect(add_query_arg('avin_notify', 'success', $redirect_to));
    exit;
}
add_action('admin_post_avin_notify', 'avin_handle_notify_signup');
add_action('admin_post_nopriv_avin_notify', 'avin_handle_notify_signup');
