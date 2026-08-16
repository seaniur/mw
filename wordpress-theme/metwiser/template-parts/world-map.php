<?php
/**
 * Animated world map: dotted background image + gradient curved routes
 * between markers, ported from the original React <WorldMap> component.
 *
 * $args['dots']: array of ['start' => ['lat','lng','label','label_side'], 'end' => [...]]
 * Line-draw animation is pure CSS (assets/css/main.css); the moving dot
 * and marker pulse use native SVG SMIL <animate>, so none of it needs JS.
 */
$dots = $args['dots'] ?? [];
$line_color = '#E27C39';
$stagger_delay = 0.3; // seconds between each route starting to draw
$anim_duration = 2;   // seconds each route takes to fully draw
$pause_time = 2;      // seconds all routes stay fully drawn before erasing
$total_anim_time = count($dots) * $stagger_delay + $anim_duration;
$full_cycle = $total_anim_time + $pause_time;

// De-dupe endpoints so shared cities (e.g. a hub visited by two routes)
// only get one marker + label.
$points = [];
foreach ($dots as $dot) {
    foreach (['start', 'end'] as $side) {
        $point = $dot[$side];
        $key = $point['label'] ?? ($point['lat'] . ',' . $point['lng']);
        if (!isset($points[$key])) {
            $points[$key] = $point;
        }
    }
}
?>
<div class="world-map relative aspect-[2/1] w-full overflow-hidden">
    <img
        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/world-dots.svg'); ?>"
        alt=""
        class="map-bg pointer-events-none absolute inset-0 h-full w-full object-cover select-none"
        draggable="false"
    >
    <svg
        viewBox="0 0 800 400"
        class="pointer-events-auto absolute inset-0 h-full w-full select-none"
        preserveAspectRatio="xMidYMid meet"
        role="img"
        aria-label="World map showing Metwiser's markets"
        xmlns:xlink="http://www.w3.org/1999/xlink"
    >
        <defs>
            <linearGradient id="mw-map-path" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="<?php echo esc_attr($line_color); ?>" stop-opacity="0"></stop>
                <stop offset="5%" stop-color="<?php echo esc_attr($line_color); ?>" stop-opacity="1"></stop>
                <stop offset="95%" stop-color="<?php echo esc_attr($line_color); ?>" stop-opacity="1"></stop>
                <stop offset="100%" stop-color="<?php echo esc_attr($line_color); ?>" stop-opacity="0"></stop>
            </linearGradient>
        </defs>

        <?php foreach ($dots as $i => $dot) :
            $start = metwiser_map_project_point($dot['start']['lat'], $dot['start']['lng']);
            $end = metwiser_map_project_point($dot['end']['lat'], $dot['end']['lng']);
            $path = metwiser_map_curved_path($start, $end);
            $path_id = 'mw-map-path-' . $i;
            $delay = $i * $stagger_delay;
            $begin_fraction = $delay / $full_cycle;
            $end_fraction = ($delay + $anim_duration) / $full_cycle;
            $reset_fraction = $total_anim_time / $full_cycle;
            $key_times = '0;' . round($begin_fraction, 4) . ';' . round($end_fraction, 4) . ';' . round($reset_fraction, 4) . ';1';
            ?>
            <g>
                <path
                    id="<?php echo esc_attr($path_id); ?>"
                    d="<?php echo esc_attr($path); ?>"
                    pathLength="1"
                    fill="none"
                    stroke="url(#mw-map-path)"
                    stroke-width="1.6"
                    class="map-path"
                    style="animation-delay: <?php echo esc_attr(round($delay, 2)); ?>s; animation-duration: <?php echo esc_attr(round($full_cycle, 2)); ?>s;"
                ></path>
                <circle r="3.5" fill="<?php echo esc_attr($line_color); ?>" opacity="0">
                    <animateMotion
                        dur="<?php echo esc_attr(round($full_cycle, 2)); ?>s"
                        repeatCount="indefinite"
                        keyPoints="0;0;1;1;0"
                        keyTimes="<?php echo esc_attr($key_times); ?>"
                        calcMode="linear"
                    >
                        <mpath href="#<?php echo esc_attr($path_id); ?>" xlink:href="#<?php echo esc_attr($path_id); ?>"></mpath>
                    </animateMotion>
                    <animate
                        attributeName="opacity"
                        values="0;0;1;1;0;0"
                        keyTimes="<?php echo esc_attr($key_times); ?>"
                        dur="<?php echo esc_attr(round($full_cycle, 2)); ?>s"
                        repeatCount="indefinite"
                    ></animate>
                </circle>
            </g>
        <?php endforeach; ?>

        <?php foreach ($points as $point) :
            $xy = metwiser_map_project_point($point['lat'], $point['lng']);
            $label_y = ($point['label_side'] ?? 'bottom') === 'top' ? $xy['y'] - 68 : $xy['y'] + 20;
            ?>
            <g class="map-marker" tabindex="0" data-label="<?php echo esc_attr($point['label'] ?? ''); ?>" style="transform-origin: <?php echo esc_attr($xy['x']); ?>px <?php echo esc_attr($xy['y']); ?>px;">
                <circle cx="<?php echo esc_attr($xy['x']); ?>" cy="<?php echo esc_attr($xy['y']); ?>" r="4" fill="<?php echo esc_attr($line_color); ?>"></circle>
                <circle cx="<?php echo esc_attr($xy['x']); ?>" cy="<?php echo esc_attr($xy['y']); ?>" r="4" fill="<?php echo esc_attr($line_color); ?>" opacity="0.5">
                    <animate attributeName="r" from="4" to="13" dur="2s" repeatCount="indefinite"></animate>
                    <animate attributeName="opacity" from="0.5" to="0" dur="2s" repeatCount="indefinite"></animate>
                </circle>
                <?php if (!empty($point['label'])) : ?>
                    <foreignObject x="<?php echo esc_attr($xy['x'] - 90); ?>" y="<?php echo esc_attr($label_y); ?>" width="180" height="22" overflow="visible">
                        <div xmlns="http://www.w3.org/1999/xhtml" class="flex justify-center">
                            <span class="inline-block bg-paper px-2 py-1 font-mono text-[0.95rem] font-medium tracking-[0.04em] whitespace-nowrap text-ink uppercase"><?php echo esc_html($point['label']); ?></span>
                        </div>
                    </foreignObject>
                <?php endif; ?>
            </g>
        <?php endforeach; ?>
    </svg>

    <div id="map-tooltip" class="pointer-events-none absolute bottom-3 left-3 hidden rounded-md border border-hairline bg-paper/95 px-3 py-1.5 text-xs font-medium text-ink backdrop-blur-sm sm:hidden"></div>
</div>
