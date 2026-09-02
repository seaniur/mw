<?php
/**
 * Closes #main, then the site footer: company summary, business-line
 * sitemap (helps both users and crawlers reach every landing page),
 * quick links, and contact/inquiry CTA.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
</main>

<footer class="site-footer">
	<div class="container footer-top">
		<div class="footer-brand">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
				<?php if (has_custom_logo()) : the_custom_logo(); else : ?>
					<span class="site-logo-text"><?php bloginfo('name'); ?></span>
				<?php endif; ?>
			</a>
			<p class="footer-tagline">
				<?php esc_html_e('A reliable, expert, and internationally oriented B2B sourcing and supply partner for the pet food industry.', 'avin'); ?>
			</p>
			<?php get_template_part('template-parts/language-switcher'); ?>
		</div>

		<nav class="footer-col" aria-label="<?php esc_attr_e('Business lines', 'avin'); ?>">
			<p class="footer-col-heading"><?php esc_html_e('Business Lines', 'avin'); ?></p>
			<ul>
				<?php foreach (avin_get_business_lines() as $line) : ?>
					<li><a href="<?php echo esc_url(avin_business_line_url($line)); ?>"><?php echo esc_html($line->name); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<nav class="footer-col" aria-label="<?php esc_attr_e('Company', 'avin'); ?>">
			<p class="footer-col-heading"><?php esc_html_e('Company', 'avin'); ?></p>
			<ul>
				<li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About Us', 'avin'); ?></a></li>
				<li><a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>"><?php esc_html_e('All Products', 'avin'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a></li>
			</ul>
		</nav>

		<div class="footer-col">
			<p class="footer-col-heading"><?php esc_html_e('Get in Touch', 'avin'); ?></p>
			<ul class="footer-contact">
				<?php $email = get_theme_mod('avin_contact_email', 'sales@avinparto.com'); ?>
				<li><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
				<?php $whatsapp = get_theme_mod('avin_whatsapp_number', ''); ?>
				<?php if ($whatsapp) : ?>
					<li><a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>"><?php echo avin_icon('whatsapp'); ?> <?php echo esc_html($whatsapp); ?></a></li>
				<?php endif; ?>
			</ul>
			<a class="btn btn-primary btn-sm" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a>
		</div>
	</div>

	<div class="container footer-bottom">
		<p>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'avin'); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
