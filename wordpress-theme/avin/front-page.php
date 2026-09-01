<?php
/**
 * Home page: hero, the five business lines (Freeze-Dried Pet Food
 * featured), the brand's four positioning pillars, and an inquiry CTA
 * band. Editable copy (hero heading/subtitle) reads from the Front Page
 * WordPress Page's own content when one is set (Settings → Reading →
 * "A static page"), falling back to the brief's own copy otherwise so the
 * homepage never ships empty.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$front_page_id = (int) get_option('page_on_front');
$has_editable_content = $front_page_id && get_post($front_page_id) && trim(get_post_field('post_content', $front_page_id)) !== '';

$pillars = [
    ['title' => __('Reliable', 'avin'), 'body' => __('A dependable partner capable of maintaining consistent quality, supply, and communication throughout the business relationship.', 'avin')],
    ['title' => __('Expert', 'avin'), 'body' => __('Practical knowledge of sourcing, processing, product specifications, quality requirements, and the needs of the pet food industry.', 'avin')],
    ['title' => __('Capable', 'avin'), 'body' => __('Access to supply networks, production capabilities, quality control, and the operational infrastructure required to handle B2B and international orders.', 'avin')],
    ['title' => __('International', 'avin'), 'body' => __('Structured and presented according to the expectations of international buyers, with transparent product information and global sourcing understanding.', 'avin')],
];
?>

<section class="hero">
	<div class="container hero-inner">
		<p class="eyebrow"><?php esc_html_e('International B2B Sourcing & Supply Partner', 'avin'); ?></p>
		<h1><?php esc_html_e('Reliable pet food ingredients, sourced and supplied from Iran to the world.', 'avin'); ?></h1>
		<p class="hero-lede">
			<?php esc_html_e('Avin Tejarat Parto supplies freeze-dried and air-dried proteins, chicken feet & paws, freeze-dried fruits & vegetables, and animal protein ingredients to pet food manufacturers and distributors worldwide.', 'avin'); ?>
		</p>
		<div class="hero-actions">
			<a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-primary"><?php esc_html_e('Explore Products', 'avin'); ?></a>
			<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-secondary"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a>
		</div>
	</div>
</section>

<?php if ($has_editable_content) : ?>
	<section class="section container prose">
		<?php echo apply_filters('the_content', get_post_field('post_content', $front_page_id)); ?>
	</section>
<?php endif; ?>

<section class="section" aria-labelledby="business-lines-heading">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e('What We Supply', 'avin'); ?></p>
			<h2 id="business-lines-heading"><?php esc_html_e('Five business lines, one dependable partner', 'avin'); ?></h2>
		</div>
		<div class="business-line-grid">
			<?php foreach (avin_get_business_lines() as $line) :
                $featured = (bool) get_term_meta($line->term_id, 'avin_featured', true);
                $icon = get_term_meta($line->term_id, 'avin_icon', true) ?: 'single-ingredient';
                $description = get_term_meta($line->term_id, 'avin_mega_description', true) ?: $line->description;
                ?>
				<a href="<?php echo esc_url(avin_business_line_url($line)); ?>" class="business-line-card<?php echo $featured ? ' is-featured' : ''; ?>">
					<span class="business-line-card-icon"><?php echo avin_icon($icon); ?></span>
					<h3><?php echo esc_html($line->name); ?></h3>
					<p><?php echo esc_html(avin_trim($description, 120)); ?></p>
					<span class="business-line-card-cta"><?php esc_html_e('Explore', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section-tinted" aria-labelledby="positioning-heading">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e('Why Avin Tejarat Parto', 'avin'); ?></p>
			<h2 id="positioning-heading"><?php esc_html_e('A long-term partner across the supply chain', 'avin'); ?></h2>
			<p class="section-lede">
				<?php esc_html_e('Avin Tejarat Parto is a professional and dependable partner that understands the pet food supply chain and has the capabilities to deliver the products and quality standards required by international B2B customers.', 'avin'); ?>
			</p>
		</div>
		<div class="pillar-grid">
			<?php foreach ($pillars as $pillar) : ?>
				<div class="pillar-card">
					<h3><?php echo esc_html($pillar['title']); ?></h3>
					<p><?php echo esc_html($pillar['body']); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="cta-band">
	<div class="container cta-band-inner">
		<div>
			<h2><?php esc_html_e('Sourcing a specific product or grade?', 'avin'); ?></h2>
			<p><?php esc_html_e('Tell us your requirements — quantity, application, and target markets — and our team will follow up with specifications and pricing.', 'avin'); ?></p>
		</div>
		<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-lg"><?php esc_html_e('Send an Inquiry', 'avin'); ?></a>
	</div>
</section>

<?php get_footer(); ?>
