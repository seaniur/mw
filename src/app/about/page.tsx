import type { Metadata } from "next";
import { ArrowUpRight } from "lucide-react";
import Link from "next/link";
import Image from "next/image";
import { Container, Eyebrow, GradientRule } from "@/components/ui";
import { Reveal, Stagger, StaggerItem } from "@/components/motion";
import { WorldMap } from "@/components/ui/map";
import { QrCode } from "@/components/qr-code";
import { cn } from "@/lib/utils";

export const metadata: Metadata = {
  title: "About — Metwiser",
  description:
    "Built on decades of business experience, backed by a business ecosystem generating over USD 5 billion in annual turnover, and driven by a new generation with a global vision.",
};

const LOCATIONS = [
  {
    country: "United Kingdom",
    category: "Premium Distribution",
    description: "Premium pet product distribution.",
  },
  {
    country: "United States",
    category: "Distribution + E-Commerce",
    description:
      "Premium pet products through distribution and e-commerce channels.",
  },
  {
    country: "Türkiye",
    category: "Retail + Distribution",
    description:
      "A growing presence in premium pet retail and distribution.",
    emphasis: { value: "08", label: "Active Stores" },
  },
  {
    country: "Canada",
    category: "Business Development",
    description: "A platform for developing the next stage of our pet business.",
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
  { value: "2021", label: "Founded" },
  { value: "08", label: "Active Pet Stores" },
  { value: "04", label: "International Markets" },
  { value: "03", label: "Core Channels", sub: "Distribution · Retail · E-Commerce" },
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
                Founded in 2021, we began with a clear ambition: to build a
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
          <Image
            src="/about/beginning-bg.jpg"
            alt=""
            fill
            className="object-cover opacity-70"
          />
          <div className="absolute inset-0 bg-paper/10" />
        </div>

        <Container className="grid items-center gap-12 lg:grid-cols-[1fr_0.9fr] lg:gap-16">
          <Reveal className="flex flex-col gap-5 lg:order-1">
            <h2 className="font-display text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
              Starting from zero again.
              <br />
              Thinking global.
            </h2>
            <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
              What started in 2021 as a new venture has grown into an
              expanding international presence across the pet industry.
            </p>
            <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
              Built on decades of business experience and driven by a new
              generation, we approach the pet industry with an entrepreneurial
              mindset — combining quality, operational excellence, long-term
              partnerships and a clear international vision.
            </p>
          </Reveal>

          <Reveal delay={0.1} className="flex items-center gap-5 lg:order-2 lg:justify-end">
            <GradientRule className="h-24 w-px shrink-0 sm:h-32" />
            <span className="font-display text-[5.5rem] leading-none font-bold tracking-tight text-ink sm:text-[7rem] lg:text-[8.5rem]">
              2021
            </span>
          </Reveal>
        </Container>
      </section>

      {/* 03 — Experience */}
      <section className="relative border-t border-hairline py-24 sm:py-32">
        <div className="absolute inset-0 -z-10 overflow-hidden">
          <Image
            src="/about/factory.jpg"
            alt=""
            fill
            className="object-cover"
          />
          <div className="absolute inset-0 bg-paper/82" />
        </div>

        <Container className="flex flex-col gap-14">
          <Reveal>
            <h2 className="font-display max-w-lg text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
              Experience that shapes how we build.
            </h2>
          </Reveal>

          <Reveal delay={0.05}>
            <p className="max-w-lg text-[0.95rem] leading-relaxed text-body">
              Decades of experience in building and scaling businesses have
              shaped the way we operate today — with a focus on quality,
              reliability, operational discipline and long-term growth.
            </p>
          </Reveal>

          <div className="grid gap-10 border-t border-hairline pt-10 sm:grid-cols-2 sm:gap-16">
            <Reveal delay={0.1} className="flex flex-col gap-2">
              <span className="brand-gradient-text font-display text-6xl leading-none font-bold tracking-tight sm:text-7xl">
                USD 5B+
              </span>
              <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                Annual ecosystem turnover
              </span>
            </Reveal>

            <Reveal delay={0.15} className="flex flex-col gap-2">
              <span className="font-display text-ink text-6xl leading-none font-bold tracking-tight sm:text-7xl">
                100,000+
              </span>
              <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                Tons of products produced annually
              </span>
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
        <Container className="flex flex-col gap-14">
          <div className="flex flex-col gap-4">
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

          <Reveal delay={0.1}>
            <WorldMap dots={MAP_DOTS} />
          </Reveal>

          <div className="divide-y divide-hairline border-t border-hairline">
            {LOCATIONS.map((location) => (
              <div
                key={location.country}
                className="grid gap-3 py-7 sm:grid-cols-[1fr_1.4fr] sm:items-baseline sm:gap-8"
              >
                <span className="font-display text-xl font-bold tracking-tight text-ink sm:text-2xl">
                  {location.country}
                </span>
                <div className="flex flex-col gap-1.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                  <div className="flex flex-col gap-1.5">
                    <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-gold uppercase">
                      {location.category}
                    </span>
                    <span className="max-w-sm text-sm leading-relaxed text-body">
                      {location.description}
                    </span>
                  </div>
                  {location.emphasis ? (
                    <div className="flex shrink-0 items-baseline gap-2">
                      <span className="font-display text-3xl font-bold text-ink">
                        {location.emphasis.value}
                      </span>
                      <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                        {location.emphasis.label}
                      </span>
                    </div>
                  ) : null}
                </div>
              </div>
            ))}
          </div>
        </Container>
      </section>

      {/* 05 — Global Scale */}
      <section className="border-t border-hairline py-24 sm:py-32">
        <Container className="flex flex-col gap-14">
          <Reveal>
            <h2 className="font-display max-w-2xl text-2xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-3xl">
              From one idea to a growing global presence.
            </h2>
          </Reveal>

          <Stagger className="grid grid-cols-2 divide-x divide-y divide-hairline border-y border-hairline lg:grid-cols-4 lg:divide-y-0">
            {GLOBAL_SCALE.map((stat) => (
              <StaggerItem
                key={stat.label}
                className="flex flex-col gap-1.5 px-2 py-10 sm:px-6"
              >
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
            <div className="flex flex-col items-start justify-between gap-10 sm:flex-row sm:items-center">
              <div className="flex flex-col gap-4">
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

              <div className="flex shrink-0 flex-col items-center gap-2">
                <QrCode value="https://metwiser.com/contact" className="h-24 w-24" />
                <span className="text-[0.6rem] font-semibold tracking-[0.14em] text-muted uppercase">
                  Scan to Connect
                </span>
                <span className="text-[0.62rem] tracking-wide text-muted">
                  metwiser.com
                </span>
              </div>
            </div>
          </Reveal>
        </Container>
      </section>
    </>
  );
}
