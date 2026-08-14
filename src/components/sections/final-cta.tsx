import { Reveal } from "@/components/motion";
import { Container } from "@/components/ui";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";

export function FinalCta({
  title,
  description,
  buttonLabel = "Start a Conversation",
  href = "/contact",
}: {
  title: string;
  description: string;
  buttonLabel?: string;
  href?: string;
}) {
  return (
    <section className="brand-gradient relative overflow-hidden py-20 sm:py-28">
      <div
        className="pointer-events-none absolute inset-0 opacity-[0.08]"
        style={{
          backgroundImage:
            "radial-gradient(circle at 1px 1px, #fff 1px, transparent 0)",
          backgroundSize: "22px 22px",
        }}
        aria-hidden="true"
      />
      <Container className="relative flex flex-col items-center gap-6 text-center">
        <Reveal>
          <h2 className="font-display max-w-2xl text-3xl font-bold tracking-tight text-paper sm:text-4xl">
            {title}
          </h2>
        </Reveal>
        <Reveal delay={0.1}>
          <p className="max-w-xl text-sm leading-relaxed text-paper/90 sm:text-base">
            {description}
          </p>
        </Reveal>
        <Reveal delay={0.2}>
          <Link
            href={href}
            className="group mt-2 inline-flex cursor-pointer items-center gap-2 rounded-full bg-paper px-7 py-3.5 text-sm font-medium text-ink transition-transform duration-200 hover:-translate-y-0.5"
          >
            {buttonLabel}
            <ArrowUpRight
              size={16}
              className="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
            />
          </Link>
        </Reveal>
      </Container>
    </section>
  );
}
