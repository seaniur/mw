<?php
/**
 * Services page. Matches a WordPress Page with slug "services".
 */
get_header();

$services = [
    ['icon' => 'package-search', 'index' => '01', 'title' => 'Sourcing & Supplier Network', 'description' => 'Access a pre-vetted network of pet food and pet care manufacturers, matched to your product spec, volume, and budget, without running your own supplier search from scratch.', 'capabilities' => [
        'Pre-vetted manufacturer network across 12+ facilities',
        'Supplier audits and capability matching',
        'Ingredient and raw material sourcing',
        'Volume and cost benchmarking',
    ]],
    ['icon' => 'factory', 'index' => '02', 'title' => 'Manufacturing & Private Label', 'description' => 'From first trial batch to full-scale production, we manage private label and co-manufacturing programs with the same team from start to finish.', 'capabilities' => [
        'Private label production programs',
        'Formulation support and recipe development',
        'Small-batch trial runs before full production',
        'Co-packing and contract manufacturing',
    ]],
    ['icon' => 'flask-conical', 'index' => '03', 'title' => 'R&D & Formulation', 'description' => 'Our formulation team develops kibble, treats, and supplement recipes that hold up nutritionally, sensorially, and on the shelf.', 'capabilities' => [
        'Nutritional formulation for kibble, treats & supplements',
        'Ingredient sourcing for novel and functional formats',
        'Shelf-life and stability testing',
        'Regulatory-ready documentation',
    ]],
    ['icon' => 'shield-check', 'index' => '04', 'title' => 'Quality & Compliance', 'description' => 'Every batch is held to the same bar, whether it ships to one country or twenty, with documentation ready before regulators ask.', 'capabilities' => [
        'Batch-level lab testing and certificates of analysis',
        'Facility audits against food-safety standards',
        'Labeling and regulatory review by market',
        'Recall-readiness protocols',
    ]],
    ['icon' => 'boxes', 'index' => '05', 'title' => 'Packaging & Branding', 'description' => 'We manage packaging from structural design through print production, so what ships matches what your customers expect on shelf and online.', 'capabilities' => [
        'Structural and sustainable packaging design',
        'Multi-market labeling and compliance artwork',
        'Print production management',
        'Retail-ready and e-commerce packaging formats',
    ]],
    ['icon' => 'truck', 'index' => '06', 'title' => 'Logistics & Fulfillment', 'description' => 'Freight, customs, warehousing, and fulfillment, coordinated so product moves from factory floor to retail shelf without gaps in visibility.', 'capabilities' => [
        'Freight, customs, and duties coordination',
        'Warehousing and inventory management',
        'Direct-to-retail and DTC fulfillment',
        'Real-time shipment visibility',
    ]],
];
?>

<!-- Hero -->
<section class="relative overflow-hidden pt-16 pb-16 sm:pt-20 sm:pb-20">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/services/hero-bg.jpg'); ?>" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-paper/85"></div>
    </div>

    <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6 sm:px-8">
        <div class="reveal"><?php metwiser_eyebrow('Services'); ?></div>
        <div class="reveal" style="--reveal-delay:0.05s">
            <h1 class="font-display max-w-3xl text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl">Capabilities built for pet brands at scale.</h1>
        </div>
        <div class="reveal" style="--reveal-delay:0.1s">
            <p class="max-w-2xl text-[0.95rem] leading-relaxed text-body">
                Every engagement draws from the same six capabilities, mixed
                and matched to where you are today, from first production run
                to multi-country distribution.
            </p>
        </div>
    </div>
</section>

<!-- Services list -->
<div class="border-t border-hairline">
    <?php foreach ($services as $i => $service) :
        $is_odd = $i % 2 === 1;
        ?>
        <section class="border-b border-hairline py-16 sm:py-20 <?php echo $is_odd ? 'bg-paper-soft' : ''; ?>">
            <div class="mx-auto w-full max-w-6xl px-6 sm:px-8">
                <div class="grid items-start gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16 <?php echo $is_odd ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
                    <div class="reveal flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="brand-gradient inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-paper"><?php echo metwiser_icon($service['icon'], 20); ?></span>
                            <span class="font-display brand-gradient-text text-xl font-bold"><?php echo esc_html($service['index']); ?></span>
                        </div>
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl"><?php echo esc_html($service['title']); ?></h2>
                        <div class="gradient-rule w-16"></div>
                        <p class="max-w-md text-[0.95rem] leading-relaxed text-body"><?php echo esc_html($service['description']); ?></p>
                    </div>

                    <div class="reveal" style="--reveal-delay:0.1s">
                        <ul class="grid gap-3 sm:grid-cols-2">
                            <?php foreach ($service['capabilities'] as $item) : ?>
                                <li class="flex items-start gap-3 rounded-xl border border-hairline bg-paper p-4">
                                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full gradient-dot"></span>
                                    <span class="text-sm leading-relaxed text-ink"><?php echo esc_html($item); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<?php get_template_part('template-parts/final-cta', null, [
    'title' => 'Not sure which service you need?',
    'description' => "Tell us about your product and timeline. We'll recommend the right starting point across sourcing, manufacturing, or logistics.",
    'button_label' => 'Talk to Our Team',
]); ?>

<?php get_footer(); ?>
