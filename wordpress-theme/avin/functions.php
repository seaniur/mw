<?php
/**
 * Avin Tejarat Parto theme bootstrap.
 *
 * Loads the theme's building blocks from inc/. Each file owns one concern
 * (setup/enqueue, the Product CPT + taxonomies, admin meta boxes, the mega
 * menu, inquiries, SEO/schema) so the pieces stay easy to find and extend
 * as the product portfolio grows.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AVIN_VERSION', '1.0.0');
define('AVIN_DIR', get_template_directory());
define('AVIN_URI', get_template_directory_uri());

require AVIN_DIR . '/inc/helpers.php';
require AVIN_DIR . '/inc/setup.php';
require AVIN_DIR . '/inc/customizer.php';
require AVIN_DIR . '/inc/cpt-product.php';
require AVIN_DIR . '/inc/meta-boxes.php';
require AVIN_DIR . '/inc/mega-menu.php';
require AVIN_DIR . '/inc/inquiry.php';
require AVIN_DIR . '/inc/schema.php';
require AVIN_DIR . '/inc/seo.php';
