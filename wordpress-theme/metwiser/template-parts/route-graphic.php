<?php
/**
 * Decorative "source to market" route illustration used in the homepage
 * hero. Animates in once, driven by the parent .reveal's is-visible class
 * (see assets/css/main.css) — no JS required beyond the shared reveal
 * IntersectionObserver in main.js.
 */
$path = 'M 10 170 C 90 170, 110 110, 170 100 C 230 90, 250 40, 330 30 C 360 26, 375 24, 390 20';
$nodes = [
    ['cx' => 10, 'cy' => 170],
    ['cx' => 170, 'cy' => 100],
    ['cx' => 330, 'cy' => 30],
    ['cx' => 390, 'cy' => 20],
];
$class = $args['class'] ?? '';
?>
<svg viewBox="0 0 400 200" fill="none" class="route-graphic <?php echo esc_attr($class); ?>" role="img" aria-label="Illustration of a route rising from source to market">
    <defs>
        <linearGradient id="route-grad" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0%" stop-color="var(--color-amber)"></stop>
            <stop offset="55%" stop-color="var(--color-orange)"></stop>
            <stop offset="100%" stop-color="var(--color-terracotta)"></stop>
        </linearGradient>
    </defs>

    <path d="<?php echo esc_attr($path); ?>" stroke="var(--color-hairline)" stroke-width="2" stroke-linecap="round"></path>

    <path
        d="<?php echo esc_attr($path); ?>"
        pathLength="1"
        stroke="url(#route-grad)"
        stroke-width="2.5"
        stroke-linecap="round"
        class="route-graphic-path"
    ></path>

    <?php foreach ($nodes as $i => $node) :
        $is_last = $i === count($nodes) - 1;
        ?>
        <circle
            cx="<?php echo esc_attr($node['cx']); ?>"
            cy="<?php echo esc_attr($node['cy']); ?>"
            r="<?php echo $is_last ? 7 : 5; ?>"
            fill="<?php echo $is_last ? 'var(--color-terracotta)' : 'var(--color-paper)'; ?>"
            stroke="url(#route-grad)"
            stroke-width="2.5"
            class="route-graphic-node"
            style="animation-delay: <?php echo esc_attr(0.4 + $i * 0.35); ?>s;"
        ></circle>
    <?php endforeach; ?>
</svg>
