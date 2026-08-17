import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";
import { Container, Eyebrow } from "@/components/ui";
import { Reveal, Stagger, StaggerItem } from "@/components/motion";

export const metadata: Metadata = {
  title: "Brands | Metwiser",
  description:
    "The brands under the Metwiser umbrella: Catz and Dogz, Pawfect, and the soon-to-launch Petwiser.",
};

const PAWFECT_CATEGORIES = [
  {
    name: "Nature's Munch",
    subtitle: "Freeze-Dried Fruits",
    species: "Dogs",
  },
  {
    name: "Finest Himalayan",
    subtitle: "Cheese Bars",
    species: "Dogs",
  },
  {
    name: "Cheesecuits",
    subtitle: "Cheese Biscuits",
    species: "Dogs",
  },
  {
    name: "Woofur",
    subtitle: "Air-Dried Meat Pieces",
    species: "Dogs",
  },
  {
    name: "Pixie",
    subtitle: "Air-Dried Meat Pieces",
    species: "Cats",
  },
  {
    name: "Nature's Feast",
    subtitle: "Freeze-Dried Meat",
    species: "Dogs & Cats",
  },
];

export default function BrandsPage() {
  return (
    <>
      {/* Hero */}
      <section className="relative py-24 sm:py-32">
        <div className="absolute inset-0 -z-10 overflow-hidden">
          <Image
            src="/brands/hero.jpg"
            alt=""
            fill
            className="object-cover object-[center_62%]"
            priority
          />
          <div className="absolute inset-0 bg-paper/20" />
        </div>

        <Container>
          <Reveal className="flex max-w-md flex-col gap-5">
            <Eyebrow>Our Brands</Eyebrow>
            <h1 className="font-display text-4xl leading-[1.05] font-bold tracking-tight text-ink uppercase sm:text-5xl">
              The brands we&apos;re building.
            </h1>
            <p className="text-[0.95rem] leading-relaxed text-body">
              From retail on the ground to products on the shelf, each brand
              in the Metwiser family tackles a different part of the pet
              industry.
            </p>
          </Reveal>
        </Container>
      </section>

      {/* Catz and Dogz */}
      <section className="border-t border-hairline py-20 sm:py-28">
        <Container className="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
          <Reveal className="overflow-hidden rounded-2xl">
            <video
              src="/brands/catz-and-dogz-video.mp4"
              poster="/brands/catz-and-dogz-photo.jpg"
              className="aspect-[4/5] w-full object-cover"
              autoPlay
              muted
              loop
              playsInline
              controls
            />
          </Reveal>

          <Reveal delay={0.1} className="flex flex-col items-start gap-6">
            <Image
              src="/brands/catz-and-dogz-logo.png"
              alt="Catz and Dogz"
              width={200}
              height={64}
              className="h-[3.25rem] w-auto self-start object-contain"
            />
            <h2 className="font-display text-3xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-4xl">
              Catz and Dogz
            </h2>
            <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
              A growing chain of pet stores across Türkiye, bringing premium
              pet products directly into local communities, with a new
              branch opening on a regular basis.
            </p>
            <div className="flex items-center gap-5 pt-2">
              <span className="font-display brand-gradient-text text-6xl leading-none font-bold tracking-tight sm:text-7xl">
                08
              </span>
              <div className="flex flex-col gap-1">
                <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                  Active Branches
                </span>
                <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-gold uppercase">
                  🇹🇷 Türkiye · Still Expanding
                </span>
              </div>
            </div>
            <Link
              href="https://catz-dogz.com/"
              target="_blank"
              rel="noreferrer noopener"
              className="group inline-flex w-fit cursor-pointer items-center gap-2 text-sm font-medium tracking-tight text-ink transition-colors hover:text-orange"
            >
              Visit catz-dogz.com
              <ArrowUpRight
                size={16}
                className="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
              />
            </Link>
          </Reveal>
        </Container>
      </section>

      {/* Pawfect */}
      <section className="border-t border-hairline bg-paper-soft py-20 sm:py-28">
        <Container className="flex flex-col gap-14">
          <Reveal className="flex flex-col gap-6 lg:max-w-2xl">
            <div className="flex items-center gap-4">
              <Image
                src="/partners/pawfect.png"
                alt="Pawfect"
                width={120}
                height={120}
                className="h-[5.2rem] w-[5.2rem] object-contain sm:h-[6.5rem] sm:w-[6.5rem]"
              />
              <h2 className="font-display text-3xl leading-tight font-bold tracking-tight text-ink uppercase sm:text-4xl">
                Pawfect
              </h2>
            </div>
            <p className="text-[0.95rem] leading-relaxed text-body">
              The Pawfect range consists of various natural snacks to pamper
              pets. From freeze-dried fruit to crunchy cheese treats and from
              a cheesy chew to freeze-dried meat, there is something
              delicious and nutritious for every dog and cat.
            </p>
          </Reveal>

          <Stagger className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {PAWFECT_CATEGORIES.map((category) => (
              <StaggerItem key={category.name}>
                <div className="flex h-full flex-col gap-1.5 rounded-2xl bg-paper px-6 py-7 shadow-[0_20px_40px_-24px_rgba(33,28,24,0.2)]">
                  <span className="font-display text-lg font-bold tracking-tight text-ink">
                    {category.name}
                  </span>
                  <span className="text-sm text-body">{category.subtitle}</span>
                  <span className="mt-1 text-[0.65rem] font-semibold tracking-[0.14em] text-gold uppercase">
                    {category.species}
                  </span>
                </div>
              </StaggerItem>
            ))}
          </Stagger>
        </Container>
      </section>

      {/* Petwiser */}
      <section className="border-t border-hairline py-16 sm:py-20">
        <Container className="flex flex-col items-center gap-3 text-center">
          <Reveal>
            <Eyebrow>Coming Soon</Eyebrow>
          </Reveal>
          <Reveal delay={0.05}>
            <h2 className="font-display text-3xl font-bold tracking-tight text-ink uppercase sm:text-4xl">
              Petwiser
            </h2>
          </Reveal>
          <Reveal delay={0.1}>
            <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
              A new brand for people who truly care about their pet&apos;s
              diet. Launching soon.
            </p>
          </Reveal>
          <Reveal delay={0.15} className="mt-6 w-full max-w-2xl">
            <Image
              src="/brands/petwiser-product-range.jpg"
              alt="A preview of the Petwiser product range"
              width={900}
              height={600}
              className="w-full rounded-2xl object-cover"
            />
          </Reveal>
        </Container>
      </section>

      {/* Closing CTA */}
      <section className="border-t border-hairline py-16 sm:py-20">
        <Container className="flex flex-col items-center gap-3 text-center">
          <Reveal>
            <p className="text-[0.95rem] text-body">
              Want to work with one of our brands?
            </p>
          </Reveal>
          <Reveal delay={0.05}>
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
          </Reveal>
        </Container>
      </section>
    </>
  );
}
