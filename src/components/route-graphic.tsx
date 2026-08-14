"use client";

import { motion, useReducedMotion } from "framer-motion";

const PATH =
  "M 10 170 C 90 170, 110 110, 170 100 C 230 90, 250 40, 330 30 C 360 26, 375 24, 390 20";

const NODES = [
  { cx: 10, cy: 170 },
  { cx: 170, cy: 100 },
  { cx: 330, cy: 30 },
  { cx: 390, cy: 20 },
];

export function RouteGraphic({ className }: { className?: string }) {
  const reduced = useReducedMotion();

  return (
    <svg
      viewBox="0 0 400 200"
      fill="none"
      className={className}
      role="img"
      aria-label="Illustration of a route rising from source to market"
    >
      <defs>
        <linearGradient id="route-grad" x1="0" y1="1" x2="1" y2="0">
          <stop offset="0%" stopColor="var(--color-amber)" />
          <stop offset="55%" stopColor="var(--color-orange)" />
          <stop offset="100%" stopColor="var(--color-terracotta)" />
        </linearGradient>
      </defs>

      <path
        d={PATH}
        stroke="var(--color-hairline)"
        strokeWidth="2"
        strokeLinecap="round"
      />

      <motion.path
        d={PATH}
        stroke="url(#route-grad)"
        strokeWidth="2.5"
        strokeLinecap="round"
        initial={reduced ? undefined : { pathLength: 0 }}
        animate={{ pathLength: 1 }}
        transition={{ duration: 1.6, ease: [0.16, 1, 0.3, 1], delay: 0.3 }}
      />

      {NODES.map((node, i) => (
        <motion.circle
          key={`${node.cx}-${node.cy}`}
          cx={node.cx}
          cy={node.cy}
          r={i === NODES.length - 1 ? 7 : 5}
          fill={i === NODES.length - 1 ? "var(--color-terracotta)" : "var(--color-paper)"}
          stroke="url(#route-grad)"
          strokeWidth="2.5"
          initial={reduced ? undefined : { opacity: 0, scale: 0.4 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{
            duration: 0.4,
            delay: reduced ? 0 : 0.4 + i * 0.35,
            ease: [0.16, 1, 0.3, 1],
          }}
        />
      ))}
    </svg>
  );
}
