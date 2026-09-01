<?php
/**
 * B2B inquiry form — the single form used both stand-alone (Contact page)
 * and embedded at the bottom of every product page. Pass ['product_id' =>
 * $id] to preselect that product; the field stays a real <select> either
 * way so a buyer can ask about a different or additional product without
 * leaving the page.
 *
 * Submits to admin-post.php (see inc/inquiry.php for avin_handle_inquiry())
 * so it works with JavaScript disabled; assets/js/main.js progressively
 * enhances the two hero CTAs ("Request a Quote" / "Request a Sample") to
 * preselect the matching radio instead of just scrolling to the form.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** @var int|null $product_id */
$product_id = $product_id ?? null;

$products = get_posts([
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'no_found_rows' => true,
]);

$submitted = isset($_GET['avin_inquiry']) ? sanitize_key(wp_unslash($_GET['avin_inquiry'])) : '';
?>

<?php if ($submitted === 'success') : ?>
	<div class="form-notice form-notice-success" role="status">
		<?php esc_html_e('Thank you — your inquiry has been received. Our team will follow up shortly.', 'avin'); ?>
	</div>
<?php elseif ($submitted === 'error') : ?>
	<div class="form-notice form-notice-error" role="alert">
		<?php esc_html_e('Something went wrong sending your inquiry. Please check the required fields and try again, or email us directly.', 'avin'); ?>
	</div>
<?php endif; ?>

<form class="inquiry-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" data-inquiry-form>
	<input type="hidden" name="action" value="avin_inquiry">
	<input type="hidden" name="redirect_to" value="<?php echo esc_url(get_permalink() ?: home_url('/')); ?>">
	<?php wp_nonce_field('avin_inquiry_form', 'avin_inquiry_nonce'); ?>

	<div class="form-row-hp" aria-hidden="true">
		<label for="avin-hp-field"><?php esc_html_e('Leave this field empty', 'avin'); ?></label>
		<input type="text" id="avin-hp-field" name="hp_field" tabindex="-1" autocomplete="off">
	</div>

	<fieldset class="request-type" data-request-type>
		<legend><?php esc_html_e('I would like to', 'avin'); ?></legend>
		<label><input type="radio" name="request_type" value="quote" checked> <?php esc_html_e('Request a Quote', 'avin'); ?></label>
		<label><input type="radio" name="request_type" value="sample"> <?php esc_html_e('Request a Sample', 'avin'); ?></label>
	</fieldset>

	<div class="form-grid">
		<p class="form-field">
			<label for="avin-inq-name"><?php esc_html_e('Name', 'avin'); ?> <span class="required">*</span></label>
			<input type="text" id="avin-inq-name" name="name" required autocomplete="name">
		</p>
		<p class="form-field">
			<label for="avin-inq-company"><?php esc_html_e('Company', 'avin'); ?></label>
			<input type="text" id="avin-inq-company" name="company" autocomplete="organization">
		</p>
		<p class="form-field">
			<label for="avin-inq-country"><?php esc_html_e('Country', 'avin'); ?></label>
			<input type="text" id="avin-inq-country" name="country" autocomplete="country-name">
		</p>
		<p class="form-field">
			<label for="avin-inq-email"><?php esc_html_e('Email', 'avin'); ?> <span class="required">*</span></label>
			<input type="email" id="avin-inq-email" name="email" required autocomplete="email">
		</p>
		<p class="form-field">
			<label for="avin-inq-whatsapp"><?php esc_html_e('WhatsApp', 'avin'); ?></label>
			<input type="text" id="avin-inq-whatsapp" name="whatsapp" autocomplete="tel">
		</p>
		<p class="form-field">
			<label for="avin-inq-product"><?php esc_html_e('Product', 'avin'); ?></label>
			<select id="avin-inq-product" name="product_id">
				<option value=""><?php esc_html_e('General inquiry (no specific product)', 'avin'); ?></option>
				<?php foreach ($products as $product) : ?>
					<option value="<?php echo esc_attr($product->ID); ?>" <?php selected($product_id, $product->ID); ?>>
						<?php echo esc_html($product->post_title); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p class="form-field">
			<label for="avin-inq-quantity"><?php esc_html_e('Required Quantity', 'avin'); ?></label>
			<input type="text" id="avin-inq-quantity" name="quantity" placeholder="<?php esc_attr_e('e.g. 1 x 20ft container / month', 'avin'); ?>">
		</p>
		<p class="form-field">
			<label for="avin-inq-application"><?php esc_html_e('Application', 'avin'); ?></label>
			<input type="text" id="avin-inq-application" name="application" placeholder="<?php esc_attr_e('e.g. Pet Treat Manufacturing', 'avin'); ?>">
		</p>
	</div>

	<p class="form-field">
		<label for="avin-inq-message"><?php esc_html_e('Message', 'avin'); ?> <span class="required">*</span></label>
		<textarea id="avin-inq-message" name="message" rows="4" required></textarea>
	</p>

	<button type="submit" class="btn btn-primary"><?php esc_html_e('Submit Inquiry', 'avin'); ?></button>
</form>
