<?php
/**
 * Site footer: brand blurb, nav, contact details, WhatsApp link, legal bar.
 */
?>
    <footer class="border-t border-hairline bg-paper-soft">
        <div class="mx-auto grid w-full max-w-6xl gap-12 px-6 py-16 sm:grid-cols-2 sm:px-8 lg:grid-cols-[1.3fr_0.7fr_1fr]">
            <div class="flex flex-col gap-4">
                <?php get_template_part('template-parts/logo'); ?>
                <div class="gradient-rule w-16"></div>
                <p class="max-w-xs text-sm leading-relaxed text-body">
                    Pet Solutions, From Source to Market. A global partner for
                    sourcing, manufacturing, and delivering pet products at scale.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">Navigate</span>
                <?php foreach (metwiser_nav_links() as $link) : ?>
                    <a href="<?php echo esc_url($link['href']); ?>" class="text-sm text-ink transition-colors hover:text-orange">
                        <?php echo esc_html($link['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col">
                <span class="mb-1 text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">Contact</span>
                <div class="divide-y divide-hairline">
                    <?php get_template_part('template-parts/data-row', null, [
                        'label' => 'Tel',
                        'value' => '+90 537 503 14 93',
                        'href' => 'tel:+905375031493',
                        'trailing' => metwiser_whatsapp_link('905375031493'),
                    ]); ?>
                    <?php get_template_part('template-parts/data-row', null, [
                        'label' => 'Email',
                        'value' => 'hello@metwiser.com',
                        'href' => 'mailto:hello@metwiser.com',
                    ]); ?>
                    <?php get_template_part('template-parts/data-row', null, [
                        'label' => 'HQ',
                        'value' => 'Istanbul, Türkiye',
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="border-t border-hairline">
            <div class="mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-2 px-6 py-6 text-xs text-muted sm:flex-row sm:px-8">
                <span>&copy; <?php echo esc_html(date('Y')); ?> Metwiser. All rights reserved.</span>
                <span class="tracking-wide">Pet Solutions, From Source to Market</span>
            </div>
        </div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>
