<?php
/**
 * Generic WordPress Page fallback (used for any page whose slug doesn't
 * match a dedicated page-*.php template, e.g. one created later in wp-admin).
 */
get_header();
?>

<main class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6 py-24 sm:px-8">
    <?php while (have_posts()) : the_post(); ?>
        <article>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink"><?php the_title(); ?></h1>
            <div class="prose mt-6 max-w-2xl text-body"><?php the_content(); ?></div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
