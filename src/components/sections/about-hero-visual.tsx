import { cn } from "@/lib/utils";

/**
 * Placeholder for the hero photograph specified in the About Us art
 * direction brief (§08 — editorial shot of a dog and cat moving through
 * contemporary architecture). No brand photoshoot asset exists yet, so this
 * renders an abstract stand-in built from the same architectural / motion
 * cues, sized and cropped the way the real photograph should be. Swap the
 * <svg> below for an <Image> of the real asset when it's shot.
 */
export function AboutHeroVisual({ className }: { className?: string }) {
  return (
    <div
      className={cn(
        "relative aspect-[4/5] w-full overflow-hidden bg-paper-soft",
        className,
      )}
    >
      <svg
        viewBox="0 0 400 500"
        className="h-full w-full"
        preserveAspectRatio="xMidYMid slice"
        aria-hidden="true"
      >
        <defs>
          <linearGradient id="ahv-sweep" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stopColor="var(--color-amber)" stopOpacity="0" />
            <stop offset="35%" stopColor="var(--color-amber)" stopOpacity="0.55" />
            <stop offset="65%" stopColor="var(--color-orange)" stopOpacity="0.55" />
            <stop offset="100%" stopColor="var(--color-terracotta)" stopOpacity="0" />
          </linearGradient>
        </defs>

        {/* abstracted architectural elevation lines */}
        {[40, 95, 150, 205, 260, 315].map((x, i) => (
          <line
            key={x}
            x1={x}
            y1={40 + (i % 3) * 18}
            x2={x}
            y2={500}
            stroke="var(--color-ink)"
            strokeOpacity="0.08"
            strokeWidth="1"
          />
        ))}
        <line x1="0" y1="120" x2="400" y2="88" stroke="var(--color-ink)" strokeOpacity="0.1" strokeWidth="1" />
        <line x1="0" y1="340" x2="400" y2="368" stroke="var(--color-ink)" strokeOpacity="0.1" strokeWidth="1" />

        {/* motion sweep — the sparing gradient accent */}
        <rect x="-40" y="0" width="180" height="620" fill="url(#ahv-sweep)" transform="rotate(18 200 250)" />

        {/* dotted-map echo, ties visually to the footprint section below */}
        {Array.from({ length: 8 }).map((_, row) =>
          Array.from({ length: 6 }).map((_, col) => (
            <circle
              key={`${row}-${col}`}
              cx={60 + col * 55 + (row % 2 === 0 ? 0 : 27)}
              cy={60 + row * 52}
              r="1.6"
              fill="var(--color-ink)"
              fillOpacity="0.12"
            />
          )),
        )}

        <circle cx="205" cy="250" r="3" fill="var(--color-terracotta)" />
      </svg>
    </div>
  );
}
