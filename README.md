# Metwiser

Marketing site for Metwiser — "Pet Solutions, From Source to Market." Built with Next.js (App Router), Tailwind CSS v4, and Framer Motion.

Content is placeholder copy pending real brand copy; the contact form is a local-only stub pending the headless WordPress/API integration (see `src/components/contact-form.tsx`).

## Getting started

```bash
npm install
npm run dev
```

Open [http://localhost:3000](http://localhost:3000).

## Structure

- `src/app/` — routes: home, `/about`, `/services`, `/contact`
- `src/components/` — shared layout (`site-header`, `site-footer`), the brand logo (`logo.tsx`), motion helpers (`motion.tsx`), and UI primitives (`ui.tsx`)
- `src/app/globals.css` — brand design tokens (colors, gradient) and Tailwind theme

## Brand

Typography: Space Grotesk (display/headlines) + IBM Plex Mono (everything else), per the Metwiser style guide. Color tokens and the brand gradient live in `globals.css` under `@theme inline`.

The logo mark in `src/components/logo.tsx` is a recreation from the style guide's gradient spec — swap in the source logo asset when available.
