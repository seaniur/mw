<?php
/**
 * Homepage. Content is hardcoded here (STATS/PROCESS/SERVICES/VALUES),
 * mirroring the original React homepage 1:1 — edit the arrays and markup
 * below directly to change copy.
 */
get_header();

$stats = [
    ['value' => 4, 'prefix' => '', 'suffix' => '', 'label' => 'Countries served'],
    ['value' => 100, 'prefix' => '+', 'suffix' => '', 'label' => 'SKUs developed'],
    ['value' => 8, 'prefix' => '', 'suffix' => '', 'label' => 'Retail stores'],
    ['value' => 2, 'prefix' => '', 'suffix' => '', 'label' => 'Manufacturing partners'],
];

$process = [
    ['index' => '01', 'title' => 'Source', 'description' => 'We vet and qualify manufacturing partners across our global network, matched to your product spec, volume, and budget.'],
    ['index' => '02', 'title' => 'Formulate & Manufacture', 'description' => 'Our team manages formulation, private label production, and packaging, with full transparency at every stage.'],
    ['index' => '03', 'title' => 'Quality & Compliance', 'description' => 'Every batch is tested against food-safety, labeling, and regulatory standards for your target markets.'],
    ['index' => '04', 'title' => 'Deliver to Market', 'description' => 'We coordinate logistics and fulfillment so your products land on shelf, on time, wherever your customers are.'],
];

$services = [
    ['icon' => 'package-search', 'title' => 'Sourcing & Supplier Network', 'description' => 'Access a pre-vetted network of pet food and pet care manufacturers across our global partner base.'],
    ['icon' => 'factory', 'title' => 'Manufacturing & Private Label', 'description' => 'Formulation, production, and packaging for private label and co-manufactured pet product lines.'],
    ['icon' => 'shield-check', 'title' => 'Quality & Compliance', 'description' => 'Batch testing, audits, and regulatory guidance across food-safety and labeling requirements by market.'],
    ['icon' => 'truck', 'title' => 'Logistics & Fulfillment', 'description' => 'End-to-end freight, customs, and fulfillment coordination from factory floor to retail shelf.'],
];

$values = [
    ['icon' => 'globe', 'title' => 'Global network', 'description' => 'Manufacturing and logistics partners across North America, Europe, and Asia-Pacific.'],
    ['icon' => 'shield-check', 'title' => 'Rigorous quality', 'description' => 'Every partner and batch is held to the same food-safety and compliance bar, market by market.'],
    ['icon' => 'zap', 'title' => 'Speed to market', 'description' => "One accountable team managing sourcing, production, and delivery, so timelines don't slip between vendors."],
];
?>

<!-- Hero -->
<section class="relative overflow-hidden pt-16 pb-20 sm:pt-20 sm:pb-28">
    <div class="mx-auto grid w-full max-w-6xl items-center gap-14 px-6 sm:px-8 lg:grid-cols-[1.05fr_0.95fr] lg:gap-10">
        <div class="flex flex-col gap-6">
            <div class="reveal"><?php metwiser_eyebrow('Global Pet Partner'); ?></div>
            <div class="reveal" style="--reveal-delay:0.05s">
                <h1 class="font-display text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl lg:text-6xl">
                    Pet solutions,<br>
                    <span class="brand-gradient-text">from source to market.</span>
                </h1>
            </div>
            <div class="reveal" style="--reveal-delay:0.1s">
                <p class="max-w-lg text-[0.95rem] leading-relaxed text-body">
                    Metwiser sources, manufactures, and delivers pet food and pet
                    care products for brands and retailers in 20+ countries, one
                    accountable partner across the entire supply chain.
                </p>
            </div>
            <div class="reveal" style="--reveal-delay:0.15s">
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary">Start a Conversation</a>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-outline">Explore Services</a>
                </div>
            </div>
        </div>

        <div class="reveal" style="--reveal-delay:0.2s; --reveal-y:30px">
            <div class="relative flex items-center justify-center rounded-2xl border border-hairline bg-paper-soft p-8 sm:p-12">
                <?php get_template_part('template-parts/route-graphic', null, ['class' => 'w-full max-w-md']); ?>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="border-y border-hairline bg-paper-soft">
    <div class="mx-auto grid w-full max-w-6xl grid-cols-2 divide-x divide-y divide-hairline px-6 sm:px-8 lg:grid-cols-4 lg:divide-y-0">
        <?php foreach ($stats as $stat) : ?>
            <div class="flex flex-col items-center gap-1 px-4 py-10 text-center">
                <span class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    <span class="counter" data-value="<?php echo esc_attr($stat['value']); ?>" data-prefix="<?php echo esc_attr($stat['prefix']); ?>" data-suffix="<?php echo esc_attr($stat['suffix']); ?>"><?php echo esc_html($stat['prefix']); ?>0<?php echo esc_html($stat['suffix']); ?></span>
                </span>
                <span class="text-[0.68rem] font-semibold tracking-[0.12em] text-muted uppercase"><?php echo esc_html($stat['label']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Process -->
<section class="py-20 sm:py-28">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-14 px-6 sm:px-8">
        <div class="reveal flex max-w-2xl flex-col gap-4">
            <?php metwiser_eyebrow('How it works'); ?>
            <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">From source to market, one accountable path.</h2>
            <p class="text-[0.95rem] leading-relaxed text-body">Four stages, one partner. We stay engaged from the first supplier call through the final delivery.</p>
        </div>

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            <?php foreach ($process as $i => $step) : ?>
                <div class="reveal relative flex flex-col gap-3" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <span class="font-display brand-gradient-text text-2xl font-bold"><?php echo esc_html($step['index']); ?></span>
                    <h3 class="font-display text-lg font-bold text-ink"><?php echo esc_html($step['title']); ?></h3>
                    <p class="text-sm leading-relaxed text-body"><?php echo esc_html($step['description']); ?></p>
                    <?php if ($i < count($process) - 1) : ?>
                        <span class="gradient-rule absolute top-3 -right-4 hidden w-8 lg:block" aria-hidden="true"></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services preview -->
<section class="border-t border-hairline bg-paper-soft py-20 sm:py-28">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-14 px-6 sm:px-8">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
            <div class="reveal flex max-w-2xl flex-col gap-4">
                <?php metwiser_eyebrow('What we do'); ?>
                <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Capabilities built for pet brands at scale.</h2>
            </div>
            <a href="<?php echo esc_url(home_url('/services/')); ?>" class="group inline-flex shrink-0 cursor-pointer items-center gap-1.5 text-sm font-medium text-orange">
                View all services
                <?php echo metwiser_icon('arrow-up-right', 16, 'transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5'); ?>
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($services as $i => $service) : ?>
                <div class="reveal" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="group flex h-full flex-col gap-4 rounded-2xl border border-hairline bg-paper p-6 transition-all duration-200 hover:-translate-y-1 hover:border-orange/40 hover:shadow-[0_16px_32px_-16px_rgba(226,124,57,0.35)]">
                        <span class="brand-gradient inline-flex h-11 w-11 items-center justify-center rounded-xl text-paper"><?php echo metwiser_icon($service['icon'], 20); ?></span>
                        <h3 class="font-display text-base font-bold text-ink"><?php echo esc_html($service['title']); ?></h3>
                        <p class="text-sm leading-relaxed text-body"><?php echo esc_html($service['description']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-20 sm:py-28">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-14 px-6 sm:px-8">
        <div class="reveal mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
            <?php metwiser_eyebrow('Why Metwiser'); ?>
            <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Built for brands that can't afford supply-chain surprises.</h2>
        </div>

        <div class="grid gap-10 sm:grid-cols-3">
            <?php foreach ($values as $i => $value) : ?>
                <div class="reveal flex flex-col items-center gap-3 text-center" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-hairline text-orange"><?php echo metwiser_icon($value['icon'], 20); ?></span>
                    <h3 class="font-display text-base font-bold text-ink"><?php echo esc_html($value['title']); ?></h3>
                    <p class="max-w-xs text-sm leading-relaxed text-body"><?php echo esc_html($value['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quote -->
<section class="border-t border-hairline bg-paper-soft py-20 sm:py-28">
    <div class="mx-auto w-full max-w-6xl px-6 sm:px-8">
        <div class="reveal mx-auto flex max-w-2xl flex-col items-center gap-6 text-center">
            <span class="font-display brand-gradient-text text-5xl leading-none font-bold">&ldquo;</span>
            <p class="font-display text-xl leading-snug font-bold tracking-tight text-ink sm:text-2xl">
                Metwiser cut our supplier qualification time in half and gave us one team to call instead of five.
            </p>
            <div class="flex flex-col items-center gap-0.5">
                <span class="font-display text-sm font-bold text-ink">VP of Sourcing</span>
                <span class="text-[0.7rem] font-medium tracking-[0.12em] text-gold uppercase">National Pet Retail Chain</span>
            </div>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/final-cta', null, [
    'title' => "Let's build your pet product pipeline.",
    'description' => "Tell us what you're sourcing or manufacturing. We'll map the fastest accountable path from source to market.",
]); ?>

<?php get_footer(); ?>
