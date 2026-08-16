<?php
/**
 * About page. Matches the WordPress Page with slug "about" (create a page
 * titled/slugged "about" in wp-admin — this template is picked up
 * automatically by WordPress's page-{slug}.php convention).
 */
get_header();

$locations = [
    ['flags' => '🇨🇦', 'name' => 'Canada', 'category' => 'Business Development Center', 'description' => 'A platform for developing the next stage of our pet business.'],
    ['flags' => '🇹🇷', 'name' => 'Türkiye', 'category' => 'Chain Store, E-Commerce & Distribution', 'description' => 'A growing presence in premium pet retail and distribution.'],
    ['flags' => '🇬🇧', 'name' => 'United Kingdom', 'category' => 'Distribution & E-Commerce', 'description' => 'Premium pet product distribution and e-commerce.'],
    ['flags' => '🇺🇸', 'name' => 'United States', 'category' => 'Distribution & E-Commerce', 'description' => 'Premium pet products through distribution and e-commerce channels.'],
];

$uk = ['lat' => 51.5074, 'lng' => -0.1278, 'label' => 'United Kingdom', 'label_side' => 'top'];
$usa = ['lat' => 40.7128, 'lng' => -74.006, 'label' => 'United States', 'label_side' => 'bottom'];
$turkiye = ['lat' => 41.0082, 'lng' => 28.9784, 'label' => 'Türkiye', 'label_side' => 'bottom'];
$canada = ['lat' => 43.6532, 'lng' => -79.3832, 'label' => 'Canada', 'label_side' => 'top'];

$map_dots = [
    ['start' => $uk, 'end' => $usa],
    ['start' => $usa, 'end' => $turkiye],
    ['start' => $turkiye, 'end' => $canada],
    ['start' => $canada, 'end' => $uk],
];

$global_scale = [
    ['value' => '2022', 'label' => 'Founded', 'sub' => 'From Scratch', 'highlight' => false],
    ['value' => '03', 'label' => 'International Markets', 'sub' => 'UK, US & Türkiye', 'highlight' => true],
    ['value' => '03', 'label' => 'Core Channels', 'sub' => 'Retail, Distribution & E-Commerce', 'highlight' => false],
];

$partners = [
    ['name' => 'Pawfect', 'file' => 'pawfect.png'],
    ['name' => 'Finest Pet Food', 'file' => 'finest-pet-food.png'],
];
?>

<!-- 01 — Hero -->
<section class="pt-16 pb-24 sm:pt-20 sm:pb-32">
    <div class="mx-auto grid w-full max-w-6xl items-center gap-16 px-6 sm:px-8 lg:grid-cols-[1.4fr_1fr] lg:gap-8">
        <div class="flex flex-col gap-7">
            <div class="reveal"><?php metwiser_eyebrow('About Us'); ?></div>
            <div class="reveal" style="--reveal-delay:0.05s">
                <h1 class="font-display leading-[1.05] font-bold tracking-tight text-ink uppercase">
                    <span class="block text-4xl sm:text-5xl lg:text-6xl">Built on</span>
                    <span class="brand-gradient-text block text-5xl sm:text-6xl lg:text-7xl">+40 years</span>
                    <span class="block text-4xl sm:text-5xl lg:text-6xl">of successful business.</span>
                </h1>
            </div>
            <div class="reveal" style="--reveal-delay:0.1s">
                <p class="max-w-md text-[0.95rem] leading-relaxed text-body">
                    Founded in 2022, we began with a clear ambition: to build a
                    modern, international business in the pet industry.
                </p>
            </div>
        </div>

        <div class="reveal lg:-my-10 lg:-mr-10" style="--reveal-delay:0.15s; --reveal-y:30px">
            <img
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/about/here.png'); ?>"
                alt="Two hands passing a young tree growing from soil, with a city skyline in the background"
                width="800"
                height="1000"
                class="about-hero-photo aspect-[4/5] w-full object-cover"
            >
        </div>
    </div>
</section>

<!-- 02 — The New Beginning -->
<section class="relative border-t border-hairline py-24 sm:py-32">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/about/beginning-bg.jpg'); ?>" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0" style="background-image:linear-gradient(to right, #ffffff 0%, #ffffff 38%, rgba(255,255,255,0) 72%);"></div>
    </div>

    <div class="mx-auto w-full max-w-6xl px-6 sm:px-8">
        <div class="reveal flex max-w-md flex-col gap-5">
            <?php metwiser_eyebrow('2022'); ?>
            <h2 class="font-display text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
                Starting a pet business.<br>With a global mindset.
            </h2>
            <p class="text-[0.95rem] leading-relaxed text-body">
                What started in 2022 as a new venture has grown into an
                expanding international presence across the pet industry.
            </p>
            <p class="text-[0.95rem] leading-relaxed text-body">
                Built on decades of business experience and driven by a new
                generation, we approach the pet industry with an entrepreneurial
                mindset, combining quality, operational excellence, long-term
                partnerships and a clear international vision.
            </p>
        </div>
    </div>
</section>

<!-- 04 — Global Footprint -->
<section class="border-t border-hairline py-24 sm:py-32">
    <div class="mx-auto flex w-full max-w-6xl flex-col items-center gap-14 px-6 text-center sm:px-8">
        <div class="flex flex-col items-center gap-4">
            <div class="reveal"><?php metwiser_eyebrow('Global Footprint'); ?></div>
            <div class="reveal" style="--reveal-delay:0.05s">
                <h2 class="font-display max-w-xl text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">Our global footprint</h2>
            </div>
            <div class="reveal" style="--reveal-delay:0.1s">
                <p class="max-w-lg text-[0.95rem] leading-relaxed text-body">
                    From premium distribution to retail and e-commerce, our
                    presence continues to grow across key international markets.
                </p>
            </div>
        </div>

        <div class="reveal w-full max-w-2xl" style="--reveal-delay:0.1s">
            <?php get_template_part('template-parts/world-map', null, ['dots' => $map_dots]); ?>
        </div>

        <div class="grid w-full gap-6 sm:grid-cols-2">
            <?php foreach ($locations as $i => $location) : ?>
                <div class="reveal" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <div class="flex h-full flex-col items-center gap-2 rounded-2xl bg-paper px-8 py-10 text-center shadow-[0_20px_40px_-20px_rgba(33,28,24,0.2)]">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl leading-none"><?php echo esc_html($location['flags']); ?></span>
                            <span class="font-display text-lg font-bold tracking-tight text-ink sm:text-xl"><?php echo esc_html($location['name']); ?></span>
                        </div>
                        <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-gold uppercase"><?php echo esc_html($location['category']); ?></span>
                        <span class="max-w-sm text-sm leading-relaxed text-body"><?php echo esc_html($location['description']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 05 — Global Scale -->
<section class="border-t border-hairline py-24 sm:py-32" style="background-color:#f8f6ea">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-14 px-6 sm:px-8">
        <div class="reveal">
            <h2 class="font-display leading-[1.02] font-bold tracking-tight text-ink uppercase">
                <span class="block text-6xl sm:text-7xl lg:text-8xl">Metwiser</span>
                <span class="brand-gradient-text block text-2xl sm:text-3xl">From one idea</span>
                <span class="block text-2xl sm:text-3xl">to a growing global presence.</span>
            </h2>
        </div>

        <div class="grid items-stretch gap-6 sm:grid-cols-3">
            <?php foreach ($global_scale as $i => $stat) : ?>
                <div class="reveal h-full" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <div class="flex h-full flex-col gap-2 rounded-2xl px-8 py-10 shadow-[0_20px_40px_-20px_rgba(33,28,24,0.2)] <?php echo $stat['highlight'] ? 'brand-gradient' : 'bg-paper'; ?>" <?php echo $stat['highlight'] ? 'style="box-shadow:0 20px 40px -20px rgba(226,124,57,0.45)"' : ''; ?>>
                        <span class="font-display text-4xl font-bold tracking-tight sm:text-5xl <?php echo $stat['highlight'] ? 'text-paper' : 'text-ink'; ?>"><?php echo esc_html($stat['value']); ?></span>
                        <span class="text-[0.68rem] font-semibold tracking-[0.14em] uppercase <?php echo $stat['highlight'] ? 'text-paper/85' : 'text-muted'; ?>"><?php echo esc_html($stat['label']); ?></span>
                        <?php if (!empty($stat['sub'])) : ?>
                            <span class="text-[0.62rem] font-medium tracking-[0.1em] uppercase <?php echo $stat['highlight'] ? 'text-paper/70' : 'text-muted/80'; ?>"><?php echo esc_html($stat['sub']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Our Partners -->
<section class="border-t border-hairline py-20 sm:py-28">
    <div class="mx-auto flex w-full max-w-6xl flex-col items-center gap-12 px-6 sm:px-8">
        <div class="reveal"><?php metwiser_eyebrow('Our Partners'); ?></div>
        <div class="flex flex-wrap items-center justify-center gap-x-16 gap-y-10">
            <?php foreach ($partners as $i => $partner) : ?>
                <div class="reveal" style="--reveal-delay:<?php echo esc_attr(0.05 + $i * 0.08); ?>s">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/partners/' . $partner['file']); ?>"
                        alt="<?php echo esc_attr($partner['name']); ?>"
                        width="220"
                        height="90"
                        class="h-16 w-auto object-contain opacity-70 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0 sm:h-20"
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 06 — Vision + 07 — CTA -->
<section class="border-t border-hairline py-24 sm:py-32">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-16 px-6 sm:px-8">
        <div class="flex flex-col gap-6">
            <div class="reveal"><?php metwiser_eyebrow('Our Next Chapter'); ?></div>
            <div class="reveal" style="--reveal-delay:0.05s">
                <h2 class="font-display max-w-2xl text-3xl leading-[1.1] font-bold tracking-tight text-ink uppercase sm:text-4xl">Building more than a pet business.</h2>
            </div>
            <div class="reveal" style="--reveal-delay:0.1s">
                <p class="max-w-xl text-[0.95rem] leading-relaxed text-body">
                    We are building a global platform for the pet industry,
                    connecting premium products, distribution, retail and
                    e-commerce across markets.
                </p>
            </div>
        </div>

        <div class="gradient-rule w-full"></div>

        <div class="reveal" style="--reveal-delay:0.1s">
            <div class="flex flex-col items-center gap-4 text-center">
                <h3 class="font-display text-2xl font-bold tracking-tight text-ink uppercase sm:text-3xl">Looking for a pet partner?</h3>
                <p class="max-w-sm text-sm leading-relaxed text-body">Let's explore what we can build together.</p>
                <?php metwiser_arrow_link(home_url('/contact/'), 'Start a Conversation'); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
