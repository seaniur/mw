import Link from "next/link";
import { Logo } from "@/components/logo";
import { Container, DataRow, GradientRule } from "@/components/ui";

const NAV_LINKS = [
  { href: "/", label: "Home" },
  { href: "/about", label: "About" },
  { href: "/services", label: "Services" },
  { href: "/brands", label: "Brands" },
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
          <div className="mt-2 flex items-center gap-4">
            <a
              href="https://linkedin.com"
              target="_blank"
              rel="noreferrer noopener"
              aria-label="Metwiser on LinkedIn"
              className="text-muted transition-colors hover:text-orange"
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.03-1.85-3.03-1.85 0-2.14 1.45-2.14 2.94v5.66H9.36V9h3.41v1.56h.05c.48-.9 1.63-1.85 3.36-1.85 3.59 0 4.25 2.36 4.25 5.44v6.3zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45z" />
              </svg>
            </a>
            <a
              href="https://x.com"
              target="_blank"
              rel="noreferrer noopener"
              aria-label="Metwiser on X"
              className="text-muted transition-colors hover:text-orange"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M18.9 2H22l-7.6 8.7L23.3 22h-7.2l-5.6-6.9L4 22H0.9l8.1-9.3L0.7 2h7.4l5.1 6.4L18.9 2Zm-1.3 18h1.8L6.5 4H4.6l13 16Z" />
              </svg>
            </a>
          </div>
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
            <DataRow label="Tel" value="+1 (415) 555-0148" href="tel:+14155550148" />
            <DataRow
              label="Email"
              value="hello@metwiser.com"
              href="mailto:hello@metwiser.com"
            />
            <DataRow label="HQ" value="San Francisco, CA" />
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
