<?php
/**
 * Contact / "Send an Inquiry" page — the stand-alone version of the same
 * inquiry-form template part embedded on every product page.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) :
    the_post();
    $email = get_theme_mod('avin_contact_email', 'sales@avinparto.com');
    $whatsapp = get_theme_mod('avin_whatsapp_number', '');
    ?>

	<section class="page-hero">
		<div class="container">
			<?php avin_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<p class="hero-lede"><?php esc_html_e('Tell us what you\'re sourcing and our team will follow up with specifications, samples, and pricing.', 'avin'); ?></p>
		</div>
	</section>

	<section class="section container contact-layout">
		<div class="contact-form-col">
			<?php if (get_the_content()) : ?>
				<div class="prose"><?php the_content(); ?></div>
			<?php endif; ?>
			<?php get_template_part('template-parts/inquiry-form'); ?>
		</div>
		<aside class="contact-info-col">
			<h2><?php esc_html_e('Direct Contact', 'avin'); ?></h2>
			<ul class="contact-info-list">
				<li>
					<span class="contact-info-label"><?php esc_html_e('Email', 'avin'); ?></span>
					<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
				</li>
				<?php if ($whatsapp) : ?>
					<li>
						<span class="contact-info-label"><?php esc_html_e('WhatsApp', 'avin'); ?></span>
						<a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>"><?php echo avin_icon('whatsapp'); ?> <?php echo esc_html($whatsapp); ?></a>
					</li>
				<?php endif; ?>
			</ul>
			<div class="contact-info-lines">
				<h3><?php esc_html_e('Business Lines', 'avin'); ?></h3>
				<ul>
					<?php foreach (avin_get_business_lines() as $line) : ?>
						<li><a href="<?php echo esc_url(avin_business_line_url($line)); ?>"><?php echo esc_html($line->name); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</aside>
	</section>

<?php
endwhile;
get_footer();
