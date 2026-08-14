import type { Metadata } from "next";
import { ArrowUpRight } from "lucide-react";
import Link from "next/link";
import Image from "next/image";
import { Container, Eyebrow, GradientRule } from "@/components/ui";
import { Reveal, Stagger, StaggerItem } from "@/components/motion";
import { AnimatedCounter } from "@/components/animated-counter";
import { WorldMap } from "@/components/ui/map";
import { cn } from "@/lib/utils";

export const metadata: Metadata = {
  title: "About — Metwiser",
  description:
    "Built on decades of business experience, backed by a business ecosystem generating over USD 5 billion in annual turnover, and driven by a new generation with a global vision.",
};

const LOCATIONS = [
  {
    flags: ["🇨🇦"],
    name: "Canada",
    category: "Business Development Center",
    description: "A platform for developing the next stage of our pet business.",
  },
  {
    flags: ["🇹🇷"],
    name: "Türkiye",
    category: "Chain Store, E-Commerce & Distribution",
    description: "A growing presence in premium pet retail and distribution.",
  },
  {
    flags: ["🇬🇧", "🇺🇸"],
    name: "United Kingdom & United States",
    category: "Distribution & E-Commerce",
    description:
      "Premium pet products delivered through distribution and e-commerce channels.",
  },
];

const UK = { lat: 51.5074, lng: -0.1278, label: "United Kingdom", labelSide: "top" as const };
const USA = { lat: 40.7128, lng: -74.006, label: "United States", labelSide: "bottom" as const };
const TURKIYE = { lat: 41.0082, lng: 28.9784, label: "Türkiye", labelSide: "bottom" as const };
const CANADA = { lat: 43.6532, lng: -79.3832, label: "Canada", labelSide: "top" as const };

const MAP_DOTS = [
  { start: UK, end: USA },
  { start: USA, end: TURKIYE },
  { start: TURKIYE, end: CANADA },
  { start: CANADA, end: UK },
];

const GLOBAL_SCALE = [
  { value: "2022", label: "Founded", sub: "From Scratch" },
  { value: "03", label: "International Markets", sub: "UK, US & Türkiye" },
  {
    value: "03",
    label: "Core Channels",
    sub: "Retail, Distribution & E-Commerce",
  },
];

const PARTNERS = [
  { name: "Pawfect", file: "pawfect.png" },
  { name: "Pixie", file: "pixie.png" },
  { name: "Finest Pet Food", file: "finest-pet-food.png" },
];

export default function AboutPage() {
  return (
    <>
      {/* 01 — Hero */}
      <section className="pt-16 pb-24 sm:pt-20 sm:pb-32">
        <Container className="grid items-center gap-16 lg:grid-cols-[1.4fr_1fr] lg:gap-8">
          <div className="flex flex-col gap-7">
            <Reveal>
              <Eyebrow>About Us</Eyebrow>
            </Reveal>
            <Reveal delay={0.05}>
              <h1 className="font-display leading-[1.05] font-bold tracking-tight text-ink uppercase">
                <span className="block text-4xl sm:text-5xl lg:text-6xl">
                  Built on experience.
                </span>
                <span className="block text-4xl sm:text-5xl lg:text-6xl">
                  Driven by a
                </span>
                <span className="brand-gradient-text block text-5xl sm:text-6xl lg:text-7xl">
                  new generation.
                </span>
              </h1>
            </Reveal>
            <Reveal delay={0.1}>
              <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
                Founded in 2022, we began with a clear ambition: to build a
                modern, international business in the pet industry.
              </p>
            </Reveal>
          </div>

          <Reveal delay={0.15} y={30} className="lg:-my-10 lg:-mr-10">
            <Image
              src="/about/here.png"
              alt="Two hands passing a young tree growing from soil, with a city skyline in the background"
              width={800}
              height={1000}
              className={cn(
                "aspect-[4/5] w-full object-cover",
                "mask-radial-[75%_65%] mask-radial-from-45% mask-radial-to-100% mask-radial-at-center",
                "mask-t-from-50% mask-t-to-100% mask-b-from-50% mask-b-to-100%",
                "mask-x-from-65% mask-x-to-100%",
              )}
              priority
            />
          </Reveal>
        </Container>
      </section>

      {/* 02 — The New Beginning */}
      <section className="relative border-t border-hairline py-24 sm:py-32">
        <div className="absolute inset-0 -z-10 overflow-hidden">
          <Image src="/about/beginning-bg.jpg" alt="" fill className="object-cover" />
          <div className="absolute inset-0 bg-paper/20" />
        </div>

        <Container>
          <Reveal className="flex max-w-md flex-col gap-5">
            <h2 className="font-display text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
              Starting from zero again.
              <br />
              Thinking global.
            </h2>
            <p className="text-[0.95rem] leading-relaxed text-body">
              What started in 2022 as a new venture has grown into an
              expanding international presence across the pet industry.
            </p>
            <p className="text-[0.95rem] leading-relaxed text-body">
              Built on decades of business experience and driven by a new
              generation, we approach the pet industry with an entrepreneurial
              mindset — combining quality, operational excellence, long-term
              partnerships and a clear international vision.
            </p>
            <div className="flex items-center gap-5 pt-2">
              <GradientRule className="h-16 w-px shrink-0 sm:h-20" />
              <span className="font-display text-6xl leading-none font-bold tracking-tight text-ink sm:text-7xl">
                2022
              </span>
            </div>
          </Reveal>
        </Container>
      </section>

      {/* 03 — Experience */}
      <section className="relative border-t border-hairline py-16 sm:py-20">
        <div className="absolute inset-0 -z-10 overflow-hidden">
          <Image
            src="/about/factory.jpg"
            alt=""
            fill
            className="scale-105 object-cover opacity-50 blur-[2px]"
          />
          <div className="absolute inset-0 bg-paper/88" />
        </div>

        <Container className="flex flex-col items-center gap-10 text-center">
          <Reveal className="flex flex-col items-center gap-5">
            <h2 className="font-display max-w-lg text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
              Experience that shapes how we build.
            </h2>
            <p className="max-w-lg text-[0.95rem] leading-relaxed text-body">
              Decades of experience in building and scaling businesses have
              shaped the way we operate today — with a focus on quality,
              reliability, operational discipline and long-term growth.
            </p>
          </Reveal>

          <div className="grid w-full max-w-2xl items-stretch gap-6 sm:grid-cols-2">
            <Reveal delay={0.1} className="h-full">
              <div className="brand-gradient flex h-full flex-col items-center justify-center gap-2 rounded-2xl px-8 py-10 shadow-[0_20px_40px_-20px_rgba(226,124,57,0.45)]">
                <span className="font-display text-5xl leading-none font-bold tracking-tight text-paper sm:text-6xl">
                  <AnimatedCounter value={5000} prefix="$" suffix="M+" format />
                </span>
                <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-paper/85 uppercase">
                  Annual ecosystem turnover
                </span>
              </div>
            </Reveal>

            <Reveal delay={0.15} className="h-full">
              <div className="flex h-full flex-col items-center justify-center gap-2 rounded-2xl bg-paper px-8 py-10 shadow-[0_20px_40px_-20px_rgba(33,28,24,0.25)]">
                <span className="font-display text-5xl leading-none font-bold tracking-tight text-ink sm:text-6xl">
                  <AnimatedCounter value={100000} suffix="+" format />
                </span>
                <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                  Tons of products produced annually
                </span>
              </div>
            </Reveal>
          </div>

          <Reveal delay={0.2}>
            <p className="max-w-lg text-xs text-muted">
              Across our broader business ecosystem, spanning multiple
              industries.
            </p>
          </Reveal>
        </Container>
      </section>

      {/* 04 — Global Footprint */}
      <section className="border-t border-hairline py-24 sm:py-32">
        <Container className="flex flex-col items-center gap-14 text-center">
          <div className="flex flex-col items-center gap-4">
            <Reveal>
              <Eyebrow>Global Footprint</Eyebrow>
            </Reveal>
            <Reveal delay={0.05}>
              <h2 className="font-display max-w-xl text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
                Our global footprint
              </h2>
            </Reveal>
            <Reveal delay={0.1}>
              <p className="max-w-lg text-[0.95rem] leading-relaxed text-body">
                From premium distribution to retail and e-commerce, our
                presence continues to grow across key international markets.
              </p>
            </Reveal>
          </div>

          <Reveal delay={0.1} className="w-full max-w-2xl">
            <WorldMap dots={MAP_DOTS} />
          </Reveal>

          <Stagger className="grid w-full gap-6 sm:grid-cols-2">
            {LOCATIONS.map((location) => (
              <StaggerItem key={location.name}>
                <div className="flex h-full flex-col items-center gap-2 rounded-2xl bg-paper px-8 py-10 text-center shadow-[0_20px_40px_-20px_rgba(33,28,24,0.2)]">
                  <div className="flex items-center gap-2.5">
                    <span className="text-xl leading-none">
                      {location.flags.join(" ")}
                    </span>
                    <span className="font-display text-lg font-bold tracking-tight text-ink sm:text-xl">
                      {location.name}
                    </span>
                  </div>
                  <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-gold uppercase">
                    {location.category}
                  </span>
                  <span className="max-w-sm text-sm leading-relaxed text-body">
                    {location.description}
                  </span>
                </div>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* 05 — Global Scale */}
      <section className="border-t border-hairline bg-[#f8f6ea] py-24 sm:py-32">
        <Container className="flex flex-col gap-14">
          <Reveal>
            <h2 className="font-display leading-[1.05] font-bold tracking-tight text-ink uppercase">
              <span className="block text-5xl sm:text-6xl lg:text-7xl">
                Metwiser
              </span>
              <span className="brand-gradient-text block text-2xl sm:text-3xl">
                From one idea
              </span>
              <span className="block text-2xl sm:text-3xl">
                to a growing global presence.
              </span>
            </h2>
          </Reveal>

          <Stagger className="grid gap-6 sm:grid-cols-3">
            {GLOBAL_SCALE.map((stat) => (
              <StaggerItem key={stat.label}>
                <div className="flex h-full flex-col gap-2 rounded-2xl bg-paper px-8 py-10 shadow-[0_20px_40px_-20px_rgba(33,28,24,0.2)]">
                  <span className="font-display text-4xl font-bold tracking-tight text-ink sm:text-5xl">
                    {stat.value}
                  </span>
                  <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                    {stat.label}
                  </span>
                  {stat.sub ? (
                    <span className="text-[0.62rem] font-medium tracking-[0.1em] text-muted/80 uppercase">
                      {stat.sub}
                    </span>
                  ) : null}
                </div>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Our Partners */}
      <section className="border-t border-hairline py-20 sm:py-28">
        <Container className="flex flex-col items-center gap-12">
          <Reveal>
            <Eyebrow>Our Partners</Eyebrow>
          </Reveal>
          <Stagger className="flex flex-wrap items-center justify-center gap-x-16 gap-y-10">
            {PARTNERS.map((partner) => (
              <StaggerItem key={partner.name}>
                <Image
                  src={`/partners/${partner.file}`}
                  alt={partner.name}
                  width={160}
                  height={64}
                  className="h-10 w-auto object-contain opacity-70 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0 sm:h-12"
                />
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* 06 — Vision + 07 — CTA */}
      <section className="border-t border-hairline py-24 sm:py-32">
        <Container className="flex flex-col gap-16">
          <div className="flex flex-col gap-6">
            <Reveal>
              <Eyebrow>Our Next Chapter</Eyebrow>
            </Reveal>
            <Reveal delay={0.05}>
              <h2 className="font-display max-w-2xl text-3xl leading-[1.1] font-bold tracking-tight text-ink uppercase sm:text-4xl">
                Building more than a pet business.
              </h2>
            </Reveal>
            <Reveal delay={0.1}>
              <p className="max-w-xl text-[0.95rem] leading-relaxed text-body">
                We are building a global platform for the pet industry —
                connecting premium products, distribution, retail and
                e-commerce across markets.
              </p>
            </Reveal>
          </div>

          <GradientRule className="w-full" />

          <Reveal delay={0.1}>
            <div className="flex flex-col items-center gap-4 text-center">
              <h3 className="font-display text-2xl font-bold tracking-tight text-ink uppercase sm:text-3xl">
                Looking for a pet partner?
              </h3>
              <p className="max-w-sm text-sm leading-relaxed text-body">
                Let&apos;s explore what we can build together.
              </p>
              <Link
                href="/contact"
                className="group inline-flex w-fit cursor-pointer items-center gap-2 text-sm font-medium tracking-tight text-ink transition-colors hover:text-orange"
              >
                Start a Conversation
                <ArrowUpRight
                  size={16}
                  className="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                />
              </Link>
            </div>
          </Reveal>
        </Container>
      </section>
    </>
  );
}
