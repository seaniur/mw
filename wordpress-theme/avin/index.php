<?php
/**
 * Fallback template — required by WordPress, rarely hit directly since
 * front-page.php, single-product.php, taxonomy-business_line.php, page.php
 * and archive-product.php cover every URL the theme generates.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<div class="container section">
	<?php if (have_posts()) : ?>
		<div class="stack-lg">
			<?php while (have_posts()) : the_post(); ?>
				<article <?php post_class('post-summary'); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="post-summary-excerpt"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e('Nothing found.', 'avin'); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
