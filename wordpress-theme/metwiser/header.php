<?php
/**
 * Site header: logo, primary nav, "Get in Touch" CTA, mobile menu toggle.
 * Scroll shadow + mobile menu behavior live in assets/js/main.js.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-full antialiased">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('flex min-h-full flex-col bg-paper text-ink'); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="sticky top-0 z-50 transition-all duration-300 bg-transparent">
    <div class="brand-gradient absolute inset-x-0 bottom-0 h-[2px]"></div>
    <div class="mx-auto flex h-18 w-full max-w-6xl items-center justify-between px-6 py-4 sm:px-8">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0">
            <?php get_template_part('template-parts/logo'); ?>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <?php foreach (metwiser_nav_links() as $link) : ?>
                <a
                    href="<?php echo esc_url($link['href']); ?>"
                    class="text-[0.72rem] font-medium tracking-[0.14em] uppercase transition-colors <?php echo metwiser_nav_is_active($link['href']) ? 'text-orange' : 'text-body hover:text-ink'; ?>"
                >
                    <?php echo esc_html($link['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="hidden md:block">
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary py-2.5 text-xs">
                Get in Touch
            </a>
        </div>

        <button
            type="button"
            id="mobile-menu-toggle"
            class="inline-flex h-10 w-10 cursor-pointer items-center justify-center text-ink md:hidden"
            aria-label="Open menu"
            aria-expanded="false"
        >
            <svg id="icon-menu" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
            <svg id="icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <div id="mobile-menu" class="mobile-menu overflow-hidden border-t border-hairline bg-paper md:hidden">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-1 px-6 py-4 sm:px-8">
            <?php foreach (metwiser_nav_links() as $link) : ?>
                <a href="<?php echo esc_url($link['href']); ?>" class="py-3 text-sm font-medium tracking-wide text-ink">
                    <?php echo esc_html($link['label']); ?>
                </a>
            <?php endforeach; ?>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary mt-3 w-full">
                Get in Touch
            </a>
        </div>
    </div>
</header>
