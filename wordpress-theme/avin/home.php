<?php
/**
 * Blog index — WordPress uses this template automatically for the
 * "Posts page" (Settings → Reading), which avin_create_missing_pages()
 * in inc/setup.php wires up to the auto-created Blog Page. front-page.php
 * still owns "/" regardless, so this only ever renders /blog/.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="page-hero">
	<div class="container">
		<?php avin_breadcrumbs(); ?>
		<h1><?php single_post_title(); ?></h1>
		<p class="hero-lede"><?php esc_html_e('News, sourcing insights, and updates from Avin Tejarat Parto.', 'avin'); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="post-grid">
				<?php
                while (have_posts()) :
                    the_post();
                    ?>
					<article <?php post_class('post-card'); ?>>
						<a href="<?php the_permalink(); ?>" class="post-card-media">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('avin-card', ['loading' => 'lazy']); ?>
							<?php else : ?>
								<span class="post-card-media-placeholder" aria-hidden="true"><?php echo avin_icon('document'); ?></span>
							<?php endif; ?>
						</a>
						<div class="post-card-body">
							<p class="post-card-date"><?php echo esc_html(get_the_date()); ?></p>
							<h2 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="post-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
							<a href="<?php the_permalink(); ?>" class="post-card-link">
								<?php esc_html_e('Read More', 'avin'); ?> <?php echo avin_icon('arrow-end'); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="pagination">
				<?php
                echo paginate_links([
                    'prev_text' => __('← Previous', 'avin'),
                    'next_text' => __('Next →', 'avin'),
                ]);
                ?>
			</div>
		<?php else : ?>
			<p class="empty-state"><?php esc_html_e('No posts yet — check back soon.', 'avin'); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
