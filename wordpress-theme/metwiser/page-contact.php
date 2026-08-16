<?php
/**
 * Contact page. Matches a WordPress Page with slug "contact". The form
 * posts via AJAX to admin-ajax.php (see metwiser_handle_contact_form()
 * in functions.php and the submit handler in assets/js/main.js).
 */
get_header();

$regions = [
    ['label' => 'North America', 'value' => 'Response within 1 business day'],
    ['label' => 'Europe', 'value' => 'Response within 1 business day'],
    ['label' => 'APAC', 'value' => 'Response within 2 business days'],
];
?>

<section class="pt-16 pb-20 sm:pt-20 sm:pb-28">
    <div class="mx-auto w-full max-w-6xl px-6 sm:px-8">
        <div class="mb-14 flex flex-col gap-6">
            <div class="reveal"><?php metwiser_eyebrow('Contact'); ?></div>
            <div class="reveal" style="--reveal-delay:0.05s">
                <h1 class="font-display max-w-2xl text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl">Let's build your pet product pipeline.</h1>
            </div>
            <div class="reveal" style="--reveal-delay:0.1s">
                <p class="max-w-xl text-[0.95rem] leading-relaxed text-body">
                    Tell us about your product, timeline, and target markets. A
                    member of our team will follow up to map the fastest
                    accountable path from source to market.
                </p>
            </div>
        </div>

        <div class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
            <div class="reveal" style="--reveal-delay:0.1s">
                <?php get_template_part('template-parts/contact-form'); ?>
            </div>

            <div class="reveal flex flex-col gap-8" style="--reveal-delay:0.15s">
                <div class="rounded-2xl border border-hairline bg-paper-soft p-8">
                    <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">Direct contact</span>
                    <div class="gradient-rule mt-3 mb-1 w-16"></div>
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

                <div class="rounded-2xl border border-hairline bg-paper p-8">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="text-orange"><?php echo metwiser_icon('clock', 16); ?></span>
                        <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">Response times by region</span>
                    </div>
                    <div class="divide-y divide-hairline">
                        <?php foreach ($regions as $region) : ?>
                            <?php get_template_part('template-parts/data-row', null, [
                                'label' => $region['label'],
                                'value' => $region['value'],
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
