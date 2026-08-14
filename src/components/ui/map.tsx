"use client";

import { useMemo, useState, type CSSProperties } from "react";
import { motion, AnimatePresence, type Transition } from "framer-motion";
import DottedMap from "dotted-map";
import Image from "next/image";
import { useTheme } from "next-themes";

interface MapPoint {
  lat: number;
  lng: number;
  label?: string;
  /** Which side of the marker to draw the label on — use this to keep labels
   * from overlapping when two points sit close together. Defaults to "bottom". */
  labelSide?: "top" | "bottom";
}

interface MapDot {
  start: MapPoint;
  end: MapPoint;
}

interface MapProps {
  dots?: MapDot[];
  lineColor?: string;
  dotColor?: string;
  showLabels?: boolean;
  animationDuration?: number;
  loop?: boolean;
}

function projectPoint(lat: number, lng: number) {
  const x = (lng + 180) * (800 / 360);
  const y = (90 - lat) * (400 / 180);
  return { x, y };
}

function createCurvedPath(
  start: { x: number; y: number },
  end: { x: number; y: number },
) {
  const midX = (start.x + end.x) / 2;
  const midY = Math.min(start.y, end.y) - 50;
  return `M ${start.x} ${start.y} Q ${midX} ${midY} ${end.x} ${end.y}`;
}

export function WorldMap({
  dots = [],
  lineColor = "var(--color-orange)",
  dotColor,
  showLabels = true,
  animationDuration = 2,
  loop = true,
}: MapProps) {
  const [hoveredLocation, setHoveredLocation] = useState<string | null>(null);
  const { resolvedTheme } = useTheme();
  const isDark = resolvedTheme === "dark";

  const map = useMemo(
    () => new DottedMap({ height: 100, grid: "diagonal" }),
    [],
  );

  const svgMap = useMemo(
    () =>
      map.getSVG({
        radius: 0.22,
        color: dotColor ?? (isDark ? "#EBE4DC33" : "#211C1833"),
        shape: "circle",
        backgroundColor: "transparent",
      }),
    [map, isDark, dotColor],
  );

  const uniquePoints = useMemo(() => {
    const seen = new Map<string, MapPoint>();
    for (const dot of dots) {
      for (const point of [dot.start, dot.end]) {
        const key = point.label ?? `${point.lat},${point.lng}`;
        if (!seen.has(key)) seen.set(key, point);
      }
    }
    return Array.from(seen.values());
  }, [dots]);

  const staggerDelay = 0.3;
  const totalAnimationTime = dots.length * staggerDelay + animationDuration;
  const pauseTime = 2;
  const fullCycleDuration = totalAnimationTime + pauseTime;

  return (
    <div className="relative aspect-[2/1] w-full overflow-hidden">
      <Image
        src={`data:image/svg+xml;utf8,${encodeURIComponent(svgMap)}`}
        className="pointer-events-none absolute inset-0 h-full w-full object-cover select-none [mask-image:linear-gradient(to_bottom,transparent,white_10%,white_90%,transparent)]"
        alt=""
        height={495}
        width={1056}
        draggable={false}
        priority
      />
      <svg
        viewBox="0 0 800 400"
        className="pointer-events-auto absolute inset-0 h-full w-full select-none"
        preserveAspectRatio="xMidYMid meet"
        role="img"
        aria-label="World map showing Metwiser's markets"
      >
        <defs>
          <linearGradient id="mw-map-path" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stopColor={lineColor} stopOpacity="0" />
            <stop offset="5%" stopColor={lineColor} stopOpacity="1" />
            <stop offset="95%" stopColor={lineColor} stopOpacity="1" />
            <stop offset="100%" stopColor={lineColor} stopOpacity="0" />
          </linearGradient>
        </defs>

        {dots.map((dot, i) => {
          const startPoint = projectPoint(dot.start.lat, dot.start.lng);
          const endPoint = projectPoint(dot.end.lat, dot.end.lng);
          const path = createCurvedPath(startPoint, endPoint);

          const startTime = (i * staggerDelay) / fullCycleDuration;
          const endTime =
            (i * staggerDelay + animationDuration) / fullCycleDuration;
          const resetTime = totalAnimationTime / fullCycleDuration;

          const loopTransition: Transition = {
            duration: fullCycleDuration,
            times: [0, startTime, endTime, resetTime, 1],
            ease: "easeInOut",
            repeat: Infinity,
            repeatDelay: 0,
          };

          return (
            <g key={`path-${dot.start.label}-${dot.end.label}`}>
              <motion.path
                d={path}
                fill="none"
                stroke="url(#mw-map-path)"
                strokeWidth="1.6"
                initial={{ pathLength: 0 }}
                animate={
                  loop ? { pathLength: [0, 0, 1, 1, 0] } : { pathLength: 1 }
                }
                transition={
                  loop
                    ? loopTransition
                    : {
                        duration: animationDuration,
                        delay: i * staggerDelay,
                        ease: "easeInOut",
                      }
                }
              />
              {loop ? (
                <motion.circle
                  r="3.5"
                  fill={lineColor}
                  initial={{ opacity: 0 }}
                  animate={{ opacity: [0, 0, 1, 0, 0] }}
                  transition={loopTransition}
                  style={
                    { offsetPath: `path('${path}')` } as CSSProperties
                  }
                />
              ) : null}
            </g>
          );
        })}

        {uniquePoints.map((point) => {
          const { x, y } = projectPoint(point.lat, point.lng);
          const labelY = point.labelSide === "top" ? y - 68 : y + 20;
          return (
            <g key={point.label ?? `${point.lat},${point.lng}`}>
              <motion.g
                onHoverStart={() => setHoveredLocation(point.label ?? null)}
                onHoverEnd={() => setHoveredLocation(null)}
                className="cursor-pointer"
                whileHover={{ scale: 1.2 }}
                transition={{ type: "spring", stiffness: 400, damping: 10 }}
                style={{ transformOrigin: `${x}px ${y}px` }}
              >
                <circle cx={x} cy={y} r="4" fill={lineColor} />
                <circle cx={x} cy={y} r="4" fill={lineColor} opacity="0.5">
                  <animate
                    attributeName="r"
                    from="4"
                    to="13"
                    dur="2s"
                    repeatCount="indefinite"
                  />
                  <animate
                    attributeName="opacity"
                    from="0.5"
                    to="0"
                    dur="2s"
                    repeatCount="indefinite"
                  />
                </circle>
              </motion.g>

              {showLabels && point.label ? (
                <foreignObject
                  x={x - 90}
                  y={labelY}
                  width="180"
                  height="22"
                  overflow="visible"
                >
                  <div className="flex justify-center">
                    <span className="bg-paper inline-block px-2 py-1 font-mono text-[0.95rem] font-medium tracking-[0.04em] whitespace-nowrap text-ink uppercase">
                      {point.label}
                    </span>
                  </div>
                </foreignObject>
              ) : null}
            </g>
          );
        })}
      </svg>

      <AnimatePresence>
        {hoveredLocation ? (
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 10 }}
            className="absolute bottom-3 left-3 rounded-md border border-hairline bg-paper/95 px-3 py-1.5 text-xs font-medium text-ink backdrop-blur-sm sm:hidden"
          >
            {hoveredLocation}
          </motion.div>
        ) : null}
      </AnimatePresence>
    </div>
  );
}
