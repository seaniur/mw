<?php
/**
 * Fallback template (required by WordPress). Metwiser's real pages use the
 * dedicated front-page.php / page-*.php templates; this only renders for
 * URLs that don't match one of those (e.g. an unrecognized page slug).
 */
get_header();
?>

<main class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6 py-24 sm:px-8">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink"><?php the_title(); ?></h1>
            <div class="prose mt-6 max-w-2xl text-body"><?php the_content(); ?></div>
        </article>
    <?php endwhile; else : ?>
        <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Nothing found</h1>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
