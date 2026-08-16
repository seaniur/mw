import Link from "next/link";
import type { ReactNode } from "react";
import { cn } from "@/lib/utils";

export function Container({
  children,
  className,
}: {
  children: ReactNode;
  className?: string;
}) {
  return (
    <div className={cn("mx-auto w-full max-w-6xl px-6 sm:px-8", className)}>
      {children}
    </div>
  );
}

export function Eyebrow({
  children,
  className,
}: {
  children: ReactNode;
  className?: string;
}) {
  return (
    <span
      className={cn(
        "inline-flex items-center gap-2 text-[0.7rem] font-semibold tracking-[0.18em] text-gold uppercase",
        className,
      )}
    >
      <span className="h-1.5 w-1.5 rounded-full gradient-dot" />
      {children}
    </span>
  );
}

export function GradientRule({ className }: { className?: string }) {
  return <div className={cn("gradient-rule w-16", className)} />;
}

export function DataRow({
  label,
  value,
  href,
  trailing,
}: {
  label: string;
  value: string;
  href?: string;
  trailing?: ReactNode;
}) {
  const valueEl = href ? (
    <Link href={href} className="hover:text-orange transition-colors">
      {value}
    </Link>
  ) : (
    value
  );

  return (
    <div className="flex items-baseline gap-3 py-2.5">
      <span className="h-1.5 w-1.5 shrink-0 rounded-full gradient-dot" />
      <span className="w-20 shrink-0 text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
        {label}
      </span>
      <span className="text-sm text-ink">{valueEl}</span>
      {trailing}
    </div>
  );
}

const buttonBase =
  "inline-flex cursor-pointer items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-medium tracking-tight transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange";

export function ButtonLink({
  href,
  children,
  variant = "primary",
  className,
  onClick,
}: {
  href: string;
  children: ReactNode;
  variant?: "primary" | "outline" | "ghost";
  className?: string;
  onClick?: () => void;
}) {
  const styles = {
    primary:
      "brand-gradient text-paper shadow-[0_8px_24px_-8px_rgba(226,124,57,0.55)] hover:shadow-[0_10px_28px_-6px_rgba(226,124,57,0.65)] hover:-translate-y-0.5",
    outline:
      "border border-ink/15 text-ink hover:border-orange/60 hover:text-orange",
    ghost: "text-ink hover:text-orange",
  } as const;

  return (
    <Link
      href={href}
      onClick={onClick}
      className={cn(buttonBase, styles[variant], className)}
    >
      {children}
    </Link>
  );
}

export function SectionHeading({
  eyebrow,
  title,
  description,
  align = "left",
}: {
  eyebrow: string;
  title: ReactNode;
  description?: string;
  align?: "left" | "center";
}) {
  return (
    <div
      className={cn(
        "flex max-w-2xl flex-col gap-4",
        align === "center" && "mx-auto items-center text-center",
      )}
    >
      <Eyebrow>{eyebrow}</Eyebrow>
      <h2 className="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
        {title}
      </h2>
      {description ? (
        <p className="text-[0.95rem] leading-relaxed text-body">
          {description}
        </p>
      ) : null}
    </div>
  );
}
