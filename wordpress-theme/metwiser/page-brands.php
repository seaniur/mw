<?php
/**
 * Brands page. Matches a WordPress Page with slug "brands".
 */
get_header();

$pawfect_categories = [
    ['name' => "Nature's Munch", 'subtitle' => 'Freeze-Dried Fruits', 'species' => 'Dogs'],
    ['name' => 'Finest Himalayan', 'subtitle' => 'Cheese Bars', 'species' => 'Dogs'],
    ['name' => 'Cheesecuits', 'subtitle' => 'Cheese Biscuits', 'species' => 'Dogs'],
    ['name' => 'Woofur', 'subtitle' => 'Air-Dried Meat Pieces', 'species' => 'Dogs'],
    ['name' => 'Pixie', 'subtitle' => 'Air-Dried Meat Pieces', 'species' => 'Cats'],
    ['name' => "Nature's Feast", 'subtitle' => 'Freeze-Dried Meat', 'species' => 'Dogs & Cats'],
];
?>

<!-- Hero -->
<section class="relative py-24 sm:py-32">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <img
            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brands/hero.jpg'); ?>"
            alt=""
            class="h-full w-full object-cover"
            style="object-position: center 62%;"
        >
        <div class="absolute inset-0 bg-paper/20"></div>
    </div>

    <div class="mx-auto w-full max-w-6xl px-6 sm:px-8">
        <div class="reveal flex max-w-md flex-col gap-5">
            <?php metwiser_eyebrow('Our Brands'); ?>
            <h1 class="font-display text-4xl leading-[1.05] font-bold tracking-tight text-ink uppercase sm:text-5xl">The brands we're building.</h1>
            <p class="text-[0.95rem] leading-relaxed text-body">
                From retail on the ground to products on the shelf, each brand
                in the Metwiser family tackles a different part of the pet
                industry.
            </p>
        </div>
    </div>
</section>

<!-- Catz and Dogz -->
<section class="border-t border-hairline py-20 sm:py-28">
    <div class="mx-auto grid w-full max-w-6xl items-center gap-12 px-6 sm:px-8 lg:grid-cols-2 lg:gap-16">
        <div class="reveal overflow-hidden rounded-2xl">
            <video
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brands/catz-and-dogz-video.mp4'); ?>"
                poster="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brands/catz-and-dogz-photo.jpg'); ?>"
                class="aspect-[4/5] w-full object-cover"
                autoplay
                muted
                loop
                playsinline
                controls
            ></video>
        </div>

        <div class="reveal flex flex-col items-start gap-6" style="--reveal-delay:0.1s">
            <img
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brands/catz-and-dogz-logo.png'); ?>"
                alt="Catz and Dogz"
                width="200"
                height="64"
                class="h-[3.25rem] w-auto self-start object-contain"
            >
            <h2 class="font-display text-3xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-4xl">Catz and Dogz</h2>
            <p class="max-w-md text-[0.95rem] leading-relaxed text-body">
                A growing chain of pet stores across Türkiye, bringing premium
                pet products directly into local communities, with a new
                branch opening on a regular basis.
            </p>
            <div class="flex items-center gap-5 pt-2">
                <span class="font-display brand-gradient-text text-6xl leading-none font-bold tracking-tight sm:text-7xl">08</span>
                <div class="flex flex-col gap-1">
                    <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">Active Branches</span>
                    <span class="text-[0.68rem] font-semibold tracking-[0.14em] text-gold uppercase">🇹🇷 Türkiye · Still Expanding</span>
                </div>
            </div>
            <?php metwiser_arrow_link('https://catz-dogz.com/', 'Visit catz-dogz.com', '', '_blank'); ?>
        </div>
    </div>
</section>

<!-- Pawfect -->
<section class="border-t border-hairline bg-paper-soft py-20 sm:py-28">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-14 px-6 sm:px-8">
        <div class="reveal flex flex-col gap-6 lg:max-w-2xl">
            <div class="flex items-center gap-4">
                <img
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/partners/pawfect.png'); ?>"
                    alt="Pawfect"
                    width="120"
                    height="120"
                    class="h-[5.2rem] w-[5.2rem] object-contain sm:h-[6.5rem] sm:w-[6.5rem]"
                >
                <h2 class="font-display text-3xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-4xl">Pawfect</h2>
            </div>
            <p class="text-[0.95rem] leading-relaxed text-body">
                The Pawfect range consists of various natural snacks to pamper
                pets. From freeze-dried fruit to crunchy cheese treats and from
                a cheesy chew to freeze-dried meat, there is something
                delicious and nutritious for every dog and cat.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($pawfect_categories as $i => $category) : ?>
                <div class="reveal" style="--reveal-delay:<?php echo esc_attr(0.04 + $i * 0.06); ?>s">
                    <div class="flex h-full flex-col gap-1.5 rounded-2xl bg-paper px-6 py-7 shadow-[0_20px_40px_-24px_rgba(33,28,24,0.2)]">
                        <span class="font-display text-lg font-bold tracking-tight text-ink"><?php echo esc_html($category['name']); ?></span>
                        <span class="text-sm text-body"><?php echo esc_html($category['subtitle']); ?></span>
                        <span class="mt-1 text-[0.65rem] font-semibold tracking-[0.14em] text-gold uppercase"><?php echo esc_html($category['species']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Petwiser -->
<section class="border-t border-hairline py-16 sm:py-20">
    <div class="mx-auto flex w-full max-w-6xl flex-col items-center gap-3 px-6 text-center sm:px-8">
        <div class="reveal"><?php metwiser_eyebrow('Coming Soon'); ?></div>
        <div class="reveal" style="--reveal-delay:0.05s">
            <h2 class="font-display text-3xl font-bold tracking-tight text-ink uppercase sm:text-4xl">Petwiser</h2>
        </div>
        <div class="reveal" style="--reveal-delay:0.1s">
            <p class="max-w-md text-[0.95rem] leading-relaxed text-body">A new brand for people who truly care about their pet's diet. Launching soon.</p>
        </div>
        <div class="reveal mt-6 w-full max-w-2xl" style="--reveal-delay:0.15s">
            <img
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/brands/petwiser-product-range.jpg'); ?>"
                alt="A preview of the Petwiser product range"
                width="900"
                height="600"
                class="w-full rounded-2xl object-cover"
            >
        </div>
    </div>
</section>

<!-- Closing CTA -->
<section class="border-t border-hairline py-16 sm:py-20">
    <div class="mx-auto flex w-full max-w-6xl flex-col items-center gap-3 px-6 text-center sm:px-8">
        <div class="reveal">
            <p class="text-[0.95rem] text-body">Want to work with one of our brands?</p>
        </div>
        <div class="reveal" style="--reveal-delay:0.05s">
            <?php metwiser_arrow_link(home_url('/contact/'), 'Start a Conversation'); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
