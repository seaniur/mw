import type { Metadata } from "next";
import { Container, Eyebrow, SectionHeading, DataRow, GradientRule } from "@/components/ui";
import { Reveal, Stagger, StaggerItem } from "@/components/motion";
import { AnimatedCounter } from "@/components/animated-counter";
import { FinalCta } from "@/components/sections/final-cta";

export const metadata: Metadata = {
  title: "About — Metwiser",
  description:
    "Metwiser is the operating partner behind pet product lines sold in over 20 countries — spanning sourcing, manufacturing, quality, and logistics.",
};

const VALUES = [
  {
    index: "01",
    title: "Accountability",
    description:
      "One team owns the outcome end to end — no finger-pointing between vendors when something slips.",
  },
  {
    index: "02",
    title: "Transparency",
    description:
      "You see supplier status, quality results, and shipment timelines as they happen, not after the fact.",
  },
  {
    index: "03",
    title: "Speed",
    description:
      "Every process is built to compress timelines — from supplier match to shelf — without cutting corners.",
  },
  {
    index: "04",
    title: "Quality",
    description:
      "The same compliance bar applies whether a batch ships to one country or twenty.",
  },
];

const PRESENCE = [
  { value: 8, suffix: "+", label: "Years in operation" },
  { value: 60, suffix: "+", label: "Team members" },
  { value: 12, suffix: "", label: "Manufacturing partners" },
  { value: 20, suffix: "+", label: "Countries served" },
];

const LEADERSHIP = [
  { name: "Alex Rivera", title: "Co-Founder & CEO" },
  { name: "Jordan Lee", title: "Co-Founder & COO" },
  { name: "Priya Nandan", title: "Head of Quality & Compliance" },
  { name: "Marcus Webb", title: "Head of Logistics" },
];

function initials(name: string) {
  return name
    .split(" ")
    .map((part) => part[0])
    .join("");
}

export default function AboutPage() {
  return (
    <>
      {/* Hero */}
      <section className="pt-16 pb-16 sm:pt-20 sm:pb-20">
        <Container className="flex flex-col gap-6">
          <Reveal>
            <Eyebrow>About Metwiser</Eyebrow>
          </Reveal>
          <Reveal delay={0.05}>
            <h1 className="font-display max-w-3xl text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl">
              Built to move pet products further, faster.
            </h1>
          </Reveal>
          <Reveal delay={0.1}>
            <p className="max-w-2xl text-[0.95rem] leading-relaxed text-body">
              Metwiser began as a sourcing desk for a handful of independent
              pet brands. Today we&apos;re the operating partner behind pet
              product lines sold in over 20 countries — without losing the
              hands-on approach we started with.
            </p>
          </Reveal>
        </Container>
      </section>

      {/* Story */}
      <section className="border-t border-hairline py-20 sm:py-28">
        <Container className="grid gap-12 lg:grid-cols-2 lg:gap-16">
          <Reveal className="flex flex-col gap-5">
            <Eyebrow>Our story</Eyebrow>
            <h2 className="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
              Pet brands don&apos;t fail because the product is wrong.
            </h2>
            <p className="text-[0.95rem] leading-relaxed text-body">
              They fail because the supply chain behind it is slow, opaque,
              or scattered across too many vendors. Metwiser exists to fix
              that — a single operating layer spanning sourcing,
              manufacturing, quality, and logistics, so pet brands can focus
              on the product and the customer instead of managing eleven
              different vendors in between.
            </p>
            <p className="text-[0.95rem] leading-relaxed text-body">
              We work with independent pet brands and national retailers
              alike, applying the same rigor to a first production run as we
              do to a multi-country rollout.
            </p>
          </Reveal>

          <Reveal delay={0.1}>
            <div className="rounded-2xl border border-hairline bg-paper-soft p-8">
              <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                At a glance
              </span>
              <GradientRule className="mt-3 mb-1" />
              <div className="divide-y divide-hairline">
                <DataRow label="Founded" value="2016" />
                <DataRow label="HQ" value="San Francisco, CA" />
                <DataRow label="Team" value="60+ specialists" />
                <DataRow label="Markets" value="20+ countries" />
              </div>
            </div>
          </Reveal>
        </Container>
      </section>

      {/* Values */}
      <section className="border-t border-hairline bg-paper-soft py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <SectionHeading
            eyebrow="What we stand for"
            title="Four principles that don't flex under pressure."
          />
          <Stagger className="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            {VALUES.map((value) => (
              <StaggerItem key={value.index} className="flex flex-col gap-3">
                <span className="font-display brand-gradient-text text-2xl font-bold">
                  {value.index}
                </span>
                <h3 className="font-display text-lg font-bold text-ink">
                  {value.title}
                </h3>
                <p className="text-sm leading-relaxed text-body">
                  {value.description}
                </p>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Presence stats */}
      <section className="py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <SectionHeading
            eyebrow="Global presence"
            title="A team spread across the markets we serve."
            align="center"
          />
          <div className="grid grid-cols-2 divide-x divide-y divide-hairline rounded-2xl border border-hairline lg:grid-cols-4 lg:divide-y-0">
            {PRESENCE.map((stat) => (
              <div
                key={stat.label}
                className="flex flex-col items-center gap-1 px-4 py-10 text-center"
              >
                <span className="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                  <AnimatedCounter value={stat.value} suffix={stat.suffix} />
                </span>
                <span className="text-[0.68rem] font-semibold tracking-[0.12em] text-muted uppercase">
                  {stat.label}
                </span>
              </div>
            ))}
          </div>
        </Container>
      </section>

      {/* Leadership */}
      <section className="border-t border-hairline bg-paper-soft py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <SectionHeading
            eyebrow="Leadership"
            title="The team steering source to market."
          />
          <Stagger className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {LEADERSHIP.map((person) => (
              <StaggerItem
                key={person.name}
                className="flex flex-col items-center gap-4 rounded-2xl border border-hairline bg-paper p-7 text-center"
              >
                <span className="brand-gradient font-display inline-flex h-16 w-16 items-center justify-center rounded-full text-lg font-bold text-paper">
                  {initials(person.name)}
                </span>
                <div className="flex flex-col gap-1">
                  <span className="font-display text-base font-bold text-ink">
                    {person.name}
                  </span>
                  <span className="text-[0.68rem] font-medium tracking-[0.12em] text-gold uppercase">
                    {person.title}
                  </span>
                </div>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      <FinalCta
        title="Meet the team behind your next launch."
        description="Whether you're validating a first product or scaling into new regions, we'll walk you through how we work."
      />
    </>
  );
}
