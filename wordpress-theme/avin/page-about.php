<?php
/**
 * About page. Editable via wp-admin like any Page — the brand-positioning
 * copy below is seed content (from the brief) shown only until an admin
 * writes real content into the page editor, so the URL is never empty.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) :
    the_post();
    $has_content = trim(get_the_content()) !== '';
    ?>

	<section class="page-hero">
		<div class="container">
			<?php avin_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<p class="hero-lede"><?php esc_html_e('A reliable, capable, and internationally oriented B2B sourcing and supply partner for the pet food industry.', 'avin'); ?></p>
		</div>
	</section>

	<?php if ($has_content) : ?>
		<article class="container section prose">
			<?php the_content(); ?>
		</article>
	<?php else : ?>
		<section class="section container prose">
			<p>
				<?php esc_html_e('Avin Tejarat Parto communicates a strong understanding of products, sourcing, processing, quality, and supply requirements, while demonstrating the capability to work with professional buyers, manufacturers, distributors, and international partners.', 'avin'); ?>
			</p>
			<p>
				<?php esc_html_e('We position ourselves as more than a trading company or product supplier — a long-term business partner supporting customers across the supply chain, from sourcing and raw materials to processing, quality control, production, and supply.', 'avin'); ?>
			</p>
		</section>

		<section class="section section-tinted">
			<div class="container pillar-grid">
				<?php
                $pillars = [
                    ['title' => __('Reliable', 'avin'), 'body' => __('A dependable partner capable of maintaining consistent quality, supply, and communication throughout the business relationship.', 'avin')],
                    ['title' => __('Expert', 'avin'), 'body' => __('Practical knowledge of sourcing, processing, product specifications, quality requirements, and the needs of the pet food industry.', 'avin')],
                    ['title' => __('Capable', 'avin'), 'body' => __('Access to supply networks, production capabilities, quality control, and the operational infrastructure required to handle B2B and international orders.', 'avin')],
                    ['title' => __('International', 'avin'), 'body' => __('Structured and presented according to the expectations of international buyers, with transparent product information and global sourcing understanding.', 'avin')],
                ];
                foreach ($pillars as $pillar) :
                    ?>
					<div class="pillar-card">
						<h3><?php echo esc_html($pillar['title']); ?></h3>
						<p><?php echo esc_html($pillar['body']); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="cta-band">
		<div class="container cta-band-inner">
			<div>
				<h2><?php esc_html_e('Let\'s discuss your sourcing requirements', 'avin'); ?></h2>
				<p><?php esc_html_e('Our team will follow up with specifications, samples, and pricing.', 'avin'); ?></p>
			</div>
			<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-lg"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a>
		</div>
	</section>

<?php
endwhile;
get_footer();
