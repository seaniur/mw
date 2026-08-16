import Link from "next/link";
import {
  ArrowUpRight,
  Factory,
  Globe2,
  PackageSearch,
  ShieldCheck,
  Truck,
  Zap,
} from "lucide-react";
import { Container, Eyebrow, ButtonLink, SectionHeading } from "@/components/ui";
import { Reveal, Stagger, StaggerItem } from "@/components/motion";
import { RouteGraphic } from "@/components/route-graphic";
import { AnimatedCounter } from "@/components/animated-counter";
import { FinalCta } from "@/components/sections/final-cta";

const STATS = [
  { value: 4, prefix: "", suffix: "", label: "Countries served" },
  { value: 100, prefix: "+", suffix: "", label: "SKUs developed" },
  { value: 8, prefix: "", suffix: "", label: "Retail stores" },
  { value: 2, prefix: "", suffix: "", label: "Manufacturing partners" },
];

const PROCESS = [
  {
    index: "01",
    title: "Source",
    description:
      "We vet and qualify manufacturing partners across our global network, matched to your product spec, volume, and budget.",
  },
  {
    index: "02",
    title: "Formulate & Manufacture",
    description:
      "Our team manages formulation, private label production, and packaging, with full transparency at every stage.",
  },
  {
    index: "03",
    title: "Quality & Compliance",
    description:
      "Every batch is tested against food-safety, labeling, and regulatory standards for your target markets.",
  },
  {
    index: "04",
    title: "Deliver to Market",
    description:
      "We coordinate logistics and fulfillment so your products land on shelf, on time, wherever your customers are.",
  },
];

const SERVICES = [
  {
    icon: PackageSearch,
    title: "Sourcing & Supplier Network",
    description:
      "Access a pre-vetted network of pet food and pet care manufacturers across our global partner base.",
  },
  {
    icon: Factory,
    title: "Manufacturing & Private Label",
    description:
      "Formulation, production, and packaging for private label and co-manufactured pet product lines.",
  },
  {
    icon: ShieldCheck,
    title: "Quality & Compliance",
    description:
      "Batch testing, audits, and regulatory guidance across food-safety and labeling requirements by market.",
  },
  {
    icon: Truck,
    title: "Logistics & Fulfillment",
    description:
      "End-to-end freight, customs, and fulfillment coordination from factory floor to retail shelf.",
  },
];

const VALUES = [
  {
    icon: Globe2,
    title: "Global network",
    description:
      "Manufacturing and logistics partners across North America, Europe, and Asia-Pacific.",
  },
  {
    icon: ShieldCheck,
    title: "Rigorous quality",
    description:
      "Every partner and batch is held to the same food-safety and compliance bar, market by market.",
  },
  {
    icon: Zap,
    title: "Speed to market",
    description:
      "One accountable team managing sourcing, production, and delivery, so timelines don't slip between vendors.",
  },
];

export default function Home() {
  return (
    <>
      {/* Hero */}
      <section className="relative overflow-hidden pt-16 pb-20 sm:pt-20 sm:pb-28">
        <Container className="grid items-center gap-14 lg:grid-cols-[1.05fr_0.95fr] lg:gap-10">
          <div className="flex flex-col gap-6">
            <Reveal>
              <Eyebrow>Global Pet Partner</Eyebrow>
            </Reveal>
            <Reveal delay={0.05}>
              <h1 className="font-display text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl lg:text-6xl">
                Pet solutions,
                <br />
                <span className="brand-gradient-text">from source to market.</span>
              </h1>
            </Reveal>
            <Reveal delay={0.1}>
              <p className="max-w-lg text-[0.95rem] leading-relaxed text-body">
                Metwiser sources, manufactures, and delivers pet food and pet
                care products for brands and retailers in 20+ countries, one
                accountable partner across the entire supply chain.
              </p>
            </Reveal>
            <Reveal delay={0.15}>
              <div className="flex flex-wrap items-center gap-4 pt-2">
                <ButtonLink href="/contact">Start a Conversation</ButtonLink>
                <ButtonLink href="/services" variant="outline">
                  Explore Services
                </ButtonLink>
              </div>
            </Reveal>
          </div>

          <Reveal delay={0.2} y={30}>
            <div className="relative flex items-center justify-center rounded-2xl border border-hairline bg-paper-soft p-8 sm:p-12">
              <RouteGraphic className="w-full max-w-md" />
            </div>
          </Reveal>
        </Container>
      </section>

      {/* Stats */}
      <section className="border-y border-hairline bg-paper-soft">
        <Container className="grid grid-cols-2 divide-x divide-y divide-hairline lg:grid-cols-4 lg:divide-y-0">
          {STATS.map((stat) => (
            <div
              key={stat.label}
              className="flex flex-col items-center gap-1 px-4 py-10 text-center"
            >
              <span className="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                <AnimatedCounter
                  value={stat.value}
                  prefix={stat.prefix}
                  suffix={stat.suffix}
                />
              </span>
              <span className="text-[0.68rem] font-semibold tracking-[0.12em] text-muted uppercase">
                {stat.label}
              </span>
            </div>
          ))}
        </Container>
      </section>

      {/* Process */}
      <section className="py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <SectionHeading
            eyebrow="How it works"
            title="From source to market, one accountable path."
            description="Four stages, one partner. We stay engaged from the first supplier call through the final delivery."
          />

          <Stagger className="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            {PROCESS.map((step, i) => (
              <StaggerItem key={step.index} className="relative flex flex-col gap-3">
                <span className="font-display brand-gradient-text text-2xl font-bold">
                  {step.index}
                </span>
                <h3 className="font-display text-lg font-bold text-ink">
                  {step.title}
                </h3>
                <p className="text-sm leading-relaxed text-body">
                  {step.description}
                </p>
                {i < PROCESS.length - 1 ? (
                  <span
                    className="gradient-rule absolute top-3 -right-4 hidden w-8 lg:block"
                    aria-hidden="true"
                  />
                ) : null}
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Services preview */}
      <section className="border-t border-hairline bg-paper-soft py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <div className="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
            <SectionHeading
              eyebrow="What we do"
              title="Capabilities built for pet brands at scale."
            />
            <Link
              href="/services"
              className="group inline-flex shrink-0 cursor-pointer items-center gap-1.5 text-sm font-medium text-orange"
            >
              View all services
              <ArrowUpRight
                size={16}
                className="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
              />
            </Link>
          </div>

          <Stagger className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {SERVICES.map((service) => (
              <StaggerItem key={service.title}>
                <Link
                  href="/services"
                  className="group flex h-full flex-col gap-4 rounded-2xl border border-hairline bg-paper p-6 transition-all duration-200 hover:-translate-y-1 hover:border-orange/40 hover:shadow-[0_16px_32px_-16px_rgba(226,124,57,0.35)]"
                >
                  <span className="brand-gradient inline-flex h-11 w-11 items-center justify-center rounded-xl text-paper">
                    <service.icon size={20} />
                  </span>
                  <h3 className="font-display text-base font-bold text-ink">
                    {service.title}
                  </h3>
                  <p className="text-sm leading-relaxed text-body">
                    {service.description}
                  </p>
                </Link>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Values */}
      <section className="py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <SectionHeading
            eyebrow="Why Metwiser"
            title="Built for brands that can't afford supply-chain surprises."
            align="center"
          />

          <Stagger className="grid gap-10 sm:grid-cols-3">
            {VALUES.map((value) => (
              <StaggerItem
                key={value.title}
                className="flex flex-col items-center gap-3 text-center"
              >
                <span className="inline-flex h-12 w-12 items-center justify-center rounded-full border border-hairline text-orange">
                  <value.icon size={20} />
                </span>
                <h3 className="font-display text-base font-bold text-ink">
                  {value.title}
                </h3>
                <p className="max-w-xs text-sm leading-relaxed text-body">
                  {value.description}
                </p>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Quote */}
      <section className="border-t border-hairline bg-paper-soft py-20 sm:py-28">
        <Container>
          <Reveal>
            <div className="mx-auto flex max-w-2xl flex-col items-center gap-6 text-center">
              <span className="font-display brand-gradient-text text-5xl leading-none font-bold">
                &ldquo;
              </span>
              <p className="font-display text-xl leading-snug font-bold tracking-tight text-ink sm:text-2xl">
                Metwiser cut our supplier qualification time in half and gave
                us one team to call instead of five.
              </p>
              <div className="flex flex-col items-center gap-0.5">
                <span className="font-display text-sm font-bold text-ink">
                  VP of Sourcing
                </span>
                <span className="text-[0.7rem] font-medium tracking-[0.12em] text-gold uppercase">
                  National Pet Retail Chain
                </span>
              </div>
            </div>
          </Reveal>
        </Container>
      </section>

      <FinalCta
        title="Let's build your pet product pipeline."
        description="Tell us what you're sourcing or manufacturing. We'll map the fastest accountable path from source to market."
      />
    </>
  );
}
