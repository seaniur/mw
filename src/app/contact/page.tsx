import type { Metadata } from "next";
import { Clock } from "lucide-react";
import { Container, Eyebrow, DataRow, GradientRule } from "@/components/ui";
import { Reveal } from "@/components/motion";
import { ContactForm } from "@/components/contact-form";
import { WhatsAppLink } from "@/components/whatsapp-link";

export const metadata: Metadata = {
  title: "Contact | Metwiser",
  description:
    "Get in touch with the Metwiser team to start sourcing, manufacturing, or shipping your pet product line.",
};

const REGIONS = [
  { label: "North America", value: "Response within 1 business day" },
  { label: "Europe", value: "Response within 1 business day" },
  { label: "APAC", value: "Response within 2 business days" },
];

export default function ContactPage() {
  return (
    <section className="pt-16 pb-20 sm:pt-20 sm:pb-28">
      <Container>
        <div className="mb-14 flex flex-col gap-6">
          <Reveal>
            <Eyebrow>Contact</Eyebrow>
          </Reveal>
          <Reveal delay={0.05}>
            <h1 className="font-display max-w-2xl text-4xl leading-[1.08] font-bold tracking-tight text-ink sm:text-5xl">
              Let&apos;s build your pet product pipeline.
            </h1>
          </Reveal>
          <Reveal delay={0.1}>
            <p className="max-w-xl text-[0.95rem] leading-relaxed text-body">
              Tell us about your product, timeline, and target markets. A
              member of our team will follow up to map the fastest
              accountable path from source to market.
            </p>
          </Reveal>
        </div>

        <div className="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
          <Reveal delay={0.1}>
            <ContactForm />
          </Reveal>

          <Reveal delay={0.15} className="flex flex-col gap-8">
            <div className="rounded-2xl border border-hairline bg-paper-soft p-8">
              <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                Direct contact
              </span>
              <GradientRule className="mt-3 mb-1" />
              <div className="divide-y divide-hairline">
                <DataRow
                  label="Tel"
                  value="+90 537 503 14 93"
                  href="tel:+905375031493"
                  trailing={<WhatsAppLink phone="905375031493" />}
                />
                <DataRow
                  label="Email"
                  value="hello@metwiser.com"
                  href="mailto:hello@metwiser.com"
                />
                <DataRow label="HQ" value="Istanbul, Türkiye" />
              </div>
            </div>

            <div className="rounded-2xl border border-hairline bg-paper p-8">
              <div className="mb-4 flex items-center gap-2">
                <Clock size={16} className="text-orange" />
                <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
                  Response times by region
                </span>
              </div>
              <div className="divide-y divide-hairline">
                {REGIONS.map((region) => (
                  <DataRow
                    key={region.label}
                    label={region.label}
                    value={region.value}
                  />
                ))}
              </div>
            </div>
          </Reveal>
        </div>
      </Container>
    </section>
  );
}
