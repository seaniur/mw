=== Metwiser ===
A hand-coded classic PHP theme for the Metwiser marketing site, ported
from the original Next.js build. Content (copy, brand list, locations,
stats) is hardcoded directly in the template files, not editable from
wp-admin — edit the .php files listed below to change anything.

== Installation ==

1. Zip the `metwiser` theme folder (this folder) and upload it via
   Appearance -> Themes -> Add New -> Upload Theme, then Activate.
   (Or upload the folder directly to wp-content/themes/ over FTP/cPanel
   File Manager, then activate it from Appearance -> Themes.)

2. On activation the theme automatically creates the four Pages it
   needs (About, Services, Brands, Contact, at slugs about/services/
   brands/contact) and switches "Plain" permalinks to pretty ones if
   your install was still on the WordPress default — the nav and every
   internal link assume /about/-style URLs. You don't need to create
   these Pages yourself; leave their content empty, the templates do
   all the rendering.

   If you installed an earlier copy of this theme before this existed,
   just visit wp-admin once (Pages -> All Pages) and any missing page
   will be created automatically — no need to deactivate/reactivate.

3. That's it. No build step, no Node.js, no Composer — this is a plain
   PHP + compiled CSS + vanilla JS theme that runs on any standard
   WordPress/PHP host, including shared cPanel hosting.

== Still-missing images ==

These files are referenced by the templates but aren't included yet —
upload them with these exact filenames and they'll appear
automatically, no code changes needed:

  wp-content/themes/metwiser/assets/images/brands/
    - catz-and-dogz-photo.jpg   (poster frame behind the video before it loads)
    - petwiser-product-range.jpg

  wp-content/themes/metwiser/assets/images/home/
    - hero-bg.jpg   (homepage hero section background)

  wp-content/themes/metwiser/assets/images/services/
    - hero-bg.jpg   (services page hero section background)

== Contact form ==

The form posts via AJAX to /wp-admin/admin-ajax.php and sends through
wp_mail() (see metwiser_handle_contact_form() in functions.php). By
default wp_mail() uses PHP's mail(), which is unreliable on some hosts
and often lands in spam. For real deliverability, install an SMTP
plugin such as "WP Mail SMTP" and connect it to a transactional mail
provider (or your host's SMTP credentials) — no theme changes needed,
wp_mail() will automatically route through it.

The recipient address (hello@metwiser.com) can be changed via the
`metwiser_contact_recipient` filter in functions.php, or just edit the
$recipient line in metwiser_handle_contact_form() directly.

== Where to edit things ==

  front-page.php     Homepage (hero, stats, process, services, values, quote)
  page-about.php      About page (hero, history, world map, stats, partners)
  page-services.php   Services page (6 service rows)
  page-brands.php     Brands page (Catz and Dogz, Pawfect, Petwiser)
  page-contact.php    Contact page layout (form is in template-parts/contact-form.php)
  header.php / footer.php   Site nav, logo, footer columns, contact details
  template-parts/      Reusable pieces (logo, data-row, world-map, route-graphic, final-cta, contact-form)
  inc/helpers.php      Small markup helper functions (eyebrow, arrow-link, whatsapp link, map math)
  inc/icons.php         Inline SVG icon set (no icon-library dependency)
  assets/css/input.css  Tailwind source — edit this, then rebuild (see below)
  assets/css/main.css   Compiled CSS actually loaded by the site — don't hand-edit
  assets/js/main.js     Header scroll state, mobile menu, scroll-reveal
                        animations, animated counters, contact form submit

== Rebuilding the compiled CSS ==

If you edit any class names in the .php templates or in
assets/css/input.css, the compiled assets/css/main.css needs to be
regenerated. From the theme folder, with Node.js installed:

  npx @tailwindcss/cli -i assets/css/input.css -o assets/css/main.css

(No other build step is required — the theme ships with main.css
already compiled, so this is only needed if you change styling.)

== Notes ==

- Fonts (Space Grotesk, IBM Plex Mono) load from Google Fonts via a
  CDN <link> in functions.php. Self-host them there instead if you'd
  rather avoid the external request.
- The animated world map and homepage route illustration are pure
  SVG + CSS/SMIL, no JS animation library — they work even with
  JavaScript disabled (just without the scroll-reveal fade-in).
- The header, footer, nav links, and contact details are shared across
  every page via metwiser_nav_links() and inc/helpers.php.
