import type { Metadata } from "next";
import {
  Boxes,
  Factory,
  FlaskConical,
  PackageSearch,
  ShieldCheck,
  Truck,
  type LucideIcon,
} from "lucide-react";
import { Container, Eyebrow, GradientRule } from "@/components/ui";
import { Reveal } from "@/components/motion";
import { FinalCta } from "@/components/sections/final-cta";
import { cn } from "@/lib/utils";

export const metadata: Metadata = {
  title: "Services | Metwiser",
  description:
    "Sourcing, manufacturing, R&D, quality, packaging, and logistics services for pet brands and retailers, delivered by one accountable partner.",
};

type Service = {
  icon: LucideIcon;
  index: string;
  title: string;
  description: string;
  capabilities: string[];
};

const SERVICES: Service[] = [
  {
    icon: PackageSearch,
    index: "01",
    title: "Sourcing & Supplier Network",
    description:
      "Access a pre-vetted network of pet food and pet care manufacturers, matched to your product spec, volume, and budget, without running your own supplier search from scratch.",
    capabilities: [
      "Pre-vetted manufacturer network across 12+ facilities",
      "Supplier audits and capability matching",
      "Ingredient and raw material sourcing",
      "Volume and cost benchmarking",
    ],
  },
  {
    icon: Factory,
    index: "02",
    title: "Manufacturing & Private Label",
    description:
      "From first trial batch to full-scale production, we manage private label and co-manufacturing programs with the same team from start to finish.",
    capabilities: [
      "Private label production programs",
      "Formulation support and recipe development",
      "Small-batch trial runs before full production",
      "Co-packing and contract manufacturing",
    ],
  },
  {
    icon: FlaskConical,
    index: "03",
    title: "R&D & Formulation",
    description:
      "Our formulation team develops kibble, treats, and supplement recipes that hold up nutritionally, sensorially, and on the shelf.",
    capabilities: [
      "Nutritional formulation for kibble, treats & supplements",
      "Ingredient sourcing for novel and functional formats",
      "Shelf-life and stability testing",
      "Regulatory-ready documentation",
    ],
  },
  {
    icon: ShieldCheck,
    index: "04",
    title: "Quality & Compliance",
    description:
      "Every batch is held to the same bar, whether it ships to one country or twenty, with documentation ready before regulators ask.",
    capabilities: [
      "Batch-level lab testing and certificates of analysis",
      "Facility audits against food-safety standards",
      "Labeling and regulatory review by market",
      "Recall-readiness protocols",
    ],
  },
  {
    icon: Boxes,
    index: "05",
    title: "Packaging & Branding",
    description:
      "We manage packaging from structural design through print production, so what ships matches what your customers expect on shelf and online.",
    capabilities: [
      "Structural and sustainable packaging design",
      "Multi-market labeling and compliance artwork",
      "Print production management",
      "Retail-ready and e-commerce packaging formats",
    ],
  },
  {
    icon: Truck,
    index: "06",
    title: "Logistics & Fulfillment",
    description:
      "Freight, customs, warehousing, and fulfillment, coordinated so product moves from factory floor to retail shelf without gaps in visibility.",
    capabilities: [
      "Freight, customs, and duties coordination",
      "Warehousing and inventory management",
      "Direct-to-retail and DTC fulfillment",
      "Real-time shipment visibility",
    ],
  },
];

export default function ServicesPage() {
  return (
    <>
      {/* Hero */}
      <section className="pt-16 pb-16 sm:pt-20 sm:pb-20">
        <Container className="flex flex-col gap-6">
          <Reveal>
            <Eyebrow>Services</Eyebrow>
          </Reveal>
          <Reveal delay={0.05}>
            <h1 className="font-display max-w-3xl text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl">
              Capabilities built for pet brands at scale.
            </h1>
          </Reveal>
          <Reveal delay={0.1}>
            <p className="max-w-2xl text-[0.95rem] leading-relaxed text-body">
              Every engagement draws from the same six capabilities, mixed
              and matched to where you are today, from first production run
              to multi-country distribution.
            </p>
          </Reveal>
        </Container>
      </section>

      {/* Services list */}
      <div className="border-t border-hairline">
        {SERVICES.map((service, i) => (
          <section
            key={service.title}
            className={cn(
              "border-b border-hairline py-16 sm:py-20",
              i % 2 === 1 && "bg-paper-soft",
            )}
          >
            <Container>
              <div
                className={cn(
                  "grid items-start gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16",
                  i % 2 === 1 && "lg:[&>*:first-child]:order-2",
                )}
              >
                <Reveal className="flex flex-col gap-4">
                  <div className="flex items-center gap-3">
                    <span className="brand-gradient inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-paper">
                      <service.icon size={20} />
                    </span>
                    <span className="font-display brand-gradient-text text-xl font-bold">
                      {service.index}
                    </span>
                  </div>
                  <h2 className="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    {service.title}
                  </h2>
                  <GradientRule />
                  <p className="max-w-md text-[0.95rem] leading-relaxed text-body">
                    {service.description}
                  </p>
                </Reveal>

                <Reveal delay={0.1}>
                  <ul className="grid gap-3 sm:grid-cols-2">
                    {service.capabilities.map((item) => (
                      <li
                        key={item}
                        className="flex items-start gap-3 rounded-xl border border-hairline bg-paper p-4"
                      >
                        <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full gradient-dot" />
                        <span className="text-sm leading-relaxed text-ink">
                          {item}
                        </span>
                      </li>
                    ))}
                  </ul>
                </Reveal>
              </div>
            </Container>
          </section>
        ))}
      </div>

      <FinalCta
        title="Not sure which service you need?"
        description="Tell us about your product and timeline. We'll recommend the right starting point across sourcing, manufacturing, or logistics."
        buttonLabel="Talk to Our Team"
      />
    </>
  );
}
