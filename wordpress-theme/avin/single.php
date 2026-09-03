<?php
/**
 * Single blog post.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) :
    the_post();
    ?>
	<article <?php post_class(); ?>>
		<section class="page-hero">
			<div class="container">
				<?php avin_breadcrumbs(); ?>
				<h1><?php the_title(); ?></h1>
				<p class="post-meta">
					<?php echo esc_html(get_the_date()); ?>
					<?php if (get_the_author()) : ?>
						&middot; <?php esc_html_e('By', 'avin'); ?> <?php the_author(); ?>
					<?php endif; ?>
				</p>
			</div>
		</section>

		<?php if (has_post_thumbnail()) : ?>
			<div class="container post-featured-image">
				<?php the_post_thumbnail('avin-hero', ['loading' => 'eager']); ?>
			</div>
		<?php endif; ?>

		<div class="container section post-body">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>

		<div class="container post-footer">
			<a href="<?php echo esc_url(avin_blog_url()); ?>" class="btn btn-secondary">
				<?php esc_html_e('← Back to Blog', 'avin'); ?>
			</a>
		</div>
	</article>
<?php
endwhile;
get_footer();
