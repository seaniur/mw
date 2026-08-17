import Link from "next/link";
import { Logo } from "@/components/logo";
import { Container, DataRow, GradientRule } from "@/components/ui";
import { WhatsAppLink } from "@/components/whatsapp-link";

const NAV_LINKS = [
  { href: "/", label: "Home" },
  { href: "/brands", label: "Brands" },
  { href: "/services", label: "Services" },
  { href: "/about", label: "About" },
  { href: "/contact", label: "Contact" },
];

export function SiteFooter() {
  const year = new Date().getFullYear();

  return (
    <footer className="border-t border-hairline bg-paper-soft">
      <Container className="grid gap-12 py-16 sm:grid-cols-2 lg:grid-cols-[1.3fr_0.7fr_1fr]">
        <div className="flex flex-col gap-4">
          <Logo />
          <GradientRule />
          <p className="max-w-xs text-sm leading-relaxed text-body">
            Pet Solutions, From Source to Market. A global partner for
            sourcing, manufacturing, and delivering pet products at scale.
          </p>
        </div>

        <div className="flex flex-col gap-3">
          <span className="text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
            Navigate
          </span>
          {NAV_LINKS.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="text-sm text-ink transition-colors hover:text-orange"
            >
              {link.label}
            </Link>
          ))}
        </div>

        <div className="flex flex-col">
          <span className="mb-1 text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase">
            Contact
          </span>
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
      </Container>

      <div className="border-t border-hairline">
        <Container className="flex flex-col items-center justify-between gap-2 py-6 text-xs text-muted sm:flex-row">
          <span>© {year} Metwiser. All rights reserved.</span>
          <span className="tracking-wide">Pet Solutions, From Source to Market</span>
        </Container>
      </div>
    </footer>
  );
}
