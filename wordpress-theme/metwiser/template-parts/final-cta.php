<?php
/**
 * Full-width gradient closing CTA banner used at the bottom of most pages.
 * $args: title, description, button_label (optional), href (optional)
 */
$title = $args['title'] ?? '';
$description = $args['description'] ?? '';
$button_label = $args['button_label'] ?? 'Start a Conversation';
$href = $args['href'] ?? home_url('/contact/');
?>
<section class="brand-gradient relative overflow-hidden py-20 sm:py-28">
    <div class="final-cta-dots pointer-events-none absolute inset-0 opacity-[0.08]" aria-hidden="true"></div>
    <div class="relative mx-auto flex w-full max-w-6xl flex-col items-center gap-6 px-6 text-center sm:px-8">
        <div class="reveal">
            <h2 class="font-display max-w-2xl text-3xl font-bold tracking-tight text-paper sm:text-4xl"><?php echo esc_html($title); ?></h2>
        </div>
        <div class="reveal" style="--reveal-delay:0.1s">
            <p class="max-w-xl text-sm leading-relaxed text-paper/90 sm:text-base"><?php echo esc_html($description); ?></p>
        </div>
        <div class="reveal" style="--reveal-delay:0.2s">
            <a href="<?php echo esc_url($href); ?>" class="group mt-2 inline-flex cursor-pointer items-center gap-2 rounded-full bg-paper px-7 py-3.5 text-sm font-medium text-ink transition-transform duration-200 hover:-translate-y-0.5">
                <?php echo esc_html($button_label); ?>
                <?php echo metwiser_icon('arrow-up-right', 16, 'transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5'); ?>
            </a>
        </div>
    </div>
</section>
