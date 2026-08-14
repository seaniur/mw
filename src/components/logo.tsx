import Image from "next/image";
import { cn } from "@/lib/utils";

export function LogoMark({ className }: { className?: string }) {
  return (
    <Image
      src="/brand/logo.png"
      alt="Metwiser"
      width={64}
      height={64}
      className={cn("h-8 w-8 object-contain", className)}
      priority
    />
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
