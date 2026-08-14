import { cn } from "@/lib/utils";

/**
 * Recreated from the Metwiser style guide (gradient + geometry approximated —
 * swap for the source logo file when available).
 */
export function LogoMark({ className }: { className?: string }) {
  return (
    <svg
      viewBox="0 0 120 100"
      fill="none"
      className={cn("h-8 w-auto", className)}
      aria-hidden="true"
    >
      <defs>
        <linearGradient id="mw-grad-a" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0%" stopColor="var(--color-amber)" />
          <stop offset="55%" stopColor="var(--color-orange)" />
          <stop offset="100%" stopColor="var(--color-terracotta)" />
        </linearGradient>
        <linearGradient id="mw-grad-b" x1="0" y1="1" x2="1" y2="0">
          <stop offset="0%" stopColor="var(--color-terracotta)" />
          <stop offset="55%" stopColor="var(--color-orange)" />
          <stop offset="100%" stopColor="var(--color-amber)" />
        </linearGradient>
      </defs>
      <polyline
        points="18,87 44,19 64,59 84,19 110,87"
        stroke="url(#mw-grad-b)"
        strokeWidth="14"
        strokeLinecap="round"
        strokeLinejoin="round"
        opacity="0.85"
        style={{ mixBlendMode: "multiply" }}
      />
      <polyline
        points="14,84 40,16 60,56 80,16 106,84"
        stroke="url(#mw-grad-a)"
        strokeWidth="14"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}

export function Wordmark({ className }: { className?: string }) {
  return (
    <span
      className={cn(
        "font-display text-2xl font-bold tracking-tight lowercase",
        className,
      )}
    >
      <span className="text-ink">met</span>
      <span className="text-orange">wiser</span>
    </span>
  );
}

export function Logo({ className }: { className?: string }) {
  return (
    <span className={cn("inline-flex items-center gap-2.5", className)}>
      <LogoMark />
      <Wordmark />
    </span>
  );
}
