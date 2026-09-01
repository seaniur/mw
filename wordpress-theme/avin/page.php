<?php
/**
 * Generic Page fallback (used for any Page besides About/Contact, which
 * have their own dedicated templates).
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) :
    the_post();
    ?>
	<article class="container section page-content">
		<?php avin_breadcrumbs(); ?>
		<h1><?php the_title(); ?></h1>
		<div class="prose"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;
get_footer();
