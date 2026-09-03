=== Avin Tejarat Parto ===
A custom, hand-coded (no page builder, no ACF) WordPress theme for Avin
Tejarat Parto's B2B website, built from the project brief: mobile-first,
SEO/AEO-first, with a fully dynamic product architecture (business lines,
categories, technical specifications, documents, packaging — all editable
from wp-admin, nothing hard-coded in a template) and FA/EN/RU multilingual
+ RTL readiness.

== Installation ==

1. Zip the `avin` theme folder (this folder) and upload it via
   Appearance -> Themes -> Add New -> Upload Theme, then Activate. (Or
   upload the folder to wp-content/themes/ over FTP/cPanel File Manager
   and activate from Appearance -> Themes.)

2. On activation the theme automatically:
   - creates the Pages it needs (Home, About, Contact, Coming Soon, Blog
     — see "Coming Soon mode" and "Blog" below — at /about/, /contact/,
     /coming-soon/, /blog/; leave their content empty and the templates
     render everything, or write real copy into them and it's used
     instead). Home and Blog also get wired up under Settings -> Reading
     as the site's front page / posts page, which is what makes /blog/
     work as an actual paginated post index rather than an ordinary Page.
   - seeds the Business Lines taxonomy with its 3 groups (Food, Pet Food,
     Feed) and 5 lines nested under them (see "Product architecture"
     below), with the names/descriptions from the brief, so the mega menu
     and site aren't empty on first activation. This seeding re-checks on
     every wp-admin visit (cheap, idempotent), so it also picks up new
     terms after a theme update without needing a reactivation.
   - switches "Plain" permalinks to pretty ones if needed, and flushes
     rewrite rules once after an update that changes the URL structure

   If an earlier copy of the theme was already active, just visit
   wp-admin once and the same check runs — no deactivate/reactivate
   needed. This also covers updating an already-live site to a newer
   copy of these files: any Page the theme needs that doesn't exist yet
   (e.g. Coming Soon, if this is an update to a site that predates that
   feature) is created the next time anyone opens wp-admin, automatically.

3. Go to Settings -> Permalinks and click Save once. This isn't optional
   for a fresh install — WordPress needs an explicit rewrite-rule flush
   the first time a plugin/theme registers new URL patterns (business
   line landing pages, /products/), and while activation already does
   this once, if a hosting environment caches rewrite rules do this
   manually if a business-line or product URL 404s.

4. Set the real logo under Appearance -> Customize -> Site Identity
   (Custom Logo) — it appears next to the company name in the header and
   footer (see "Logo" below). The three brand colors in assets/css/
   main.css (--brand-red / --brand-orange / --brand-amber, top of the
   file) were read off the submitted logo mark; nudge them to the exact
   brand hex values once final brand files are confirmed — nothing else
   in the theme needs to change, every accent color derives from those
   three variables.

== Logo ==

The header/footer show the uploaded logo image and the site name side by
side (Appearance -> Customize -> Site Identity sets both the logo and
the "Site Title" text used here). Two things worth knowing:

- **SVG uploads are enabled** for Administrators only (Settings ->
  General mime-type allowlist is otherwise restricted for security — an
  SVG can carry embedded scripts, so this is deliberately not opened up
  to lower roles). Upload a vector logo there for a crisp mark at any
  size instead of a raster PNG/JPG. See the comment above
  avin_allow_svg_uploads() in inc/setup.php if editors below
  Administrator ever need this too — that needs a real SVG sanitizer
  added first, not just widening the capability check.
- The logo's display size is controlled by CSS (assets/css/main.css,
  .site-logo-mark, 52px tall / 40px in the header's compact scrolled
  state) — not by the uploaded file's own dimensions, so any reasonably
  sized source image works.

== Coming Soon mode ==

While the real site is still being built, turn on Appearance -> Customize
-> Coming Soon Mode -> "Show the Coming Soon page to visitors". Once
enabled:

- Every logged-out visitor sees a single branded holding page (your logo,
  an editable grid of boxes, and a "Get in Touch" mailto button) instead
  of the real site — no navigation into pages that aren't ready.
- Anyone logged into wp-admin (your whole team, any role) keeps seeing
  and editing the real site completely normally — nothing is hidden from
  you, only from the public. To check what a visitor actually sees, log
  out or open the site in a private/incognito window. The Customizer's
  live preview also always shows the real site so you can style it while
  Coming Soon Mode is on.
- The **Coming Soon Page Logo** control right under the toggle uploads a
  logo used only on this holding page (handy if it should differ from
  the main site logo); leave it empty and it falls back to Customize ->
  Site Identity's logo, then to a plain initial if neither is set.
  The **Coming Soon** entry under Pages in wp-admin still exists and
  sets the browser tab title, but its body content isn't shown on the
  page anymore.
- **Boxes** are fully editable under Appearance -> Customize -> Coming
  Soon Mode -> Boxes: add/remove/reorder as many as you like, each with
  its own icon (upload any image), title, and description — nothing
  hard-coded. **Columns per row** are set independently for Desktop,
  Tablet, and Mobile (a number field each), so the grid can be e.g. 4
  columns on desktop, 2 on tablet, 1 on mobile, or any combination you
  choose.
- Turn the toggle off the moment the real site is ready — everything
  reverts instantly, nothing to undo elsewhere.

The gate only affects front-end page views; wp-admin, REST requests, and
the inquiry/notify form handlers are on separate request paths WordPress
never runs it against, so none of those are ever blocked by this.

**Notify Me signups**: the holding page includes an email opt-in ("Get
notified when we launch"). Submissions are stored under **Notify Me
Signups** in the wp-admin sidebar (one per email, duplicates are silently
deduped) and a short email is sent to the Inquiry Notification Email
(falls back to Contact Email) each time someone new signs up, so you'll
know before you even check wp-admin. When the real site is ready, that
list is your launch-announcement audience — export it however you like
(e.g. a CSV/export plugin, or just copy the emails from the list table).

Bot handling on that form is three-layered, all server-side, no
third-party CAPTCHA/script: a honeypot field real visitors never see or
fill, a nonce, and a minimum 3-second time-on-page before a submission is
accepted. A bot tripping any of these is shown the same "success" message
a real visitor gets — it's just quietly never stored — so scripted
submitters get no signal about what caught them.

== Navigation & Mega Menu ==

The primary nav (Home / Products / Blog / About / Contact) is fixed —
hard-coded in avin_primary_nav_items() (inc/setup.php), not an
Appearance -> Menus wp_nav_menu, on purpose: "Products" has to stay
wired to the mega menu trigger, so letting it be freely renamed/removed/
reordered from a generic menu editor would silently break that. Home/
Blog/About/Contact's URLs still update automatically if you change which
Pages are assigned as the front page / posts page.

The Products mega menu is driven directly by the **Business Lines**
taxonomy (wp-admin -> Products -> Business Lines) described in "Product
architecture" below — there's no separate mega-menu content type to keep
in sync:

- The 3 top-level **group** terms (Food, Pet Food, Feed) are column 1's
  fixed selectors.
- Each group's child **line** terms (e.g. "Chicken Feet Products" under
  Food) are the cards that column's panel reveals. Open any line's term
  edit screen to set its **Card Image** (optional — cards without one get
  a clean minimal treatment instead, per the brief), **Description**, and
  **Menu Order**; the card always links to that line's own landing page
  (see "Product architecture"), so there's no separate link field to keep
  pointed at the right place.

On desktop, clicking "Products" opens all three groups' panels at once in
the DOM (every link is always real and crawlable — nothing is fetched or
swapped in via JS) and only toggles which one is visible; hovering or
clicking a group (Food/Pet Food/Feed) switches instantly with no reload,
and the same switching also works via click/keyboard alone, so it's never
hover-only. On mobile/tablet the same data renders as a nested accordion
(Products -> group -> lines) instead of attempting the desktop's
multi-column hover layout on a touch screen.

== Blog ==

A standard WordPress blog: write posts under Posts -> Add New as usual.
home.php is the listing template (card grid with featured image, excerpt,
date), single.php is the single-post template — both styled to match the
rest of the theme. "Blog" in the nav and the mega menu's footer link
both resolve via avin_blog_url() (inc/setup.php), which reads the live
Settings -> Reading -> "Posts page" value rather than assuming a slug,
so it stays correct even if that's later pointed at a different Page.

== Product architecture ==

Everything under "Products" in wp-admin is one custom post type,
`product`, with two taxonomies:

- **Business Lines** (Products -> Business Lines) — hierarchical, two
  levels deep. The 3 top-level **group** terms (Food, Pet Food, Feed) exist
  purely to organize the site's URL structure and mega menu; the 5
  **line** terms nest under them (Chicken Feet Products and others under
  Food, Freeze-Dried Products etc. under Pet Food, the powder lines under
  Feed). A line's archive *is* its landing page, nested under its group:

      domain.com/food/chicken-feet-products/
      domain.com/pet-food/freeze-dried-products/

  and each product's own URL nests one level further under its line:

      domain.com/food/chicken-feet-products/some-product-name/
      domain.com/pet-food/freeze-dried-products/another-product/

  A group term's own archive (e.g. /food/) shows a simple grid of its
  child lines rather than a product grid. Edit any term (group or line) to
  set its **Card Image**/icon, **Description**, hero subtitle, CTA labels,
  menu order, and (lines only) which one gets the "featured" treatment in
  the mega menu and homepage grid — under Quick Edit these don't show;
  use the term edit screen's custom fields.

  **If you're updating from an older version of this theme** where lines
  had flat URLs (e.g. /freeze-dried-pet-food/) or products lived at
  /products/{slug}/: those old URLs keep working. The theme 301-redirects
  every legacy business-line and product URL to its new nested location
  automatically (inc/cpt-product.php, `avin_redirect_legacy_urls()`) —
  nothing to configure, and nothing breaks for anyone who bookmarked or
  linked the old URLs, including search engines.

- **Category Groups** (invisible taxonomy, no admin menu item of its
  own — managed via the Product Category Groups metabox on each product)
  — light grouping terms like Poultry / Marine / Fruits & Vegetables,
  used only to section a landing page's product grid into subheadings.
  Per the brief these deliberately do NOT get their own pages/URLs.

Every other product field (Ingredient, Origin, Grade, Application,
Processing Method, Format, Key Features, Ingredients list, Technical
Specifications table, Packaging, Shelf Life & Storage, MOQ/Supply
Capacity, Certifications, Quality Standards, Documents, Gallery,
Packaging/Process images, Related Products, SEO title/description) is a
meta box on the product edit screen — see "Dynamic Product Fields" in the
brief; every one of those fields is implemented, none are hard-coded.
Technical Specifications, Certifications, and Documents are repeaters —
click "+ Add Row" for as many parameters/certs/files as a product needs.

Adding a new business line: Products -> Business Lines -> Add New, and
set its **Parent** to Food / Pet Food / Feed (or leave it top-level to add
a fourth group). It appears in the mega menu and gets a working landing
page automatically at the matching nested URL (grouped by whichever
Category Groups you tag its products with, or a flat grid if you don't
add any) — no template changes needed.

== Multilingual (FA / EN / RU) ==

The theme itself is fully translation-ready: every UI string goes through
`__()`/`_e()` with the `avin` text domain, and every piece of content that
needs a language (product fields, business-line copy, page content) lives
on its own post/term rather than being hard-coded into a template — so
each language gets independent content, not a machine-translated layer
over English. Layout uses CSS logical properties throughout (not
left/right), so Farsi's RTL direction mirrors the header, mega menu,
mobile drawer, forms, and product pages correctly with no separate
"RTL build" — see assets/css/rtl.css for the handful of things logical
properties don't cover (letter-spacing, directional icons).

Running three actual languages needs a translation-management plugin to
store and route the per-language content — that's a hosting/content
decision, not something a theme should hard-code. Recommended setup:

1. Install **Polylang** (free) or **WPML** (paid, more turnkey for a
   commercial B2B site with a translation workflow).
2. Add Persian (fa, RTL), English (en), Russian (ru) as site languages.
3. Both plugins register their language switcher through the same hooks
   this theme already checks (`pll_the_languages()` for Polylang,
   `icl_get_languages()` for WPML) — template-parts/language-switcher.php
   picks whichever is active automatically and renders it in the header
   and footer. Nothing else to wire up.
4. Translate each business-line term and each product; the taxonomy/CPT
   structure (business_line, product_category, product) is translated
   per-plugin's own UI, and URLs get the plugin's language prefix
   (e.g. /fa/food/chicken-feet-products/) automatically.
5. `is_rtl()` (WordPress core) reflects the active language once
   Polylang/WPML sets it, which is what triggers assets/css/rtl.css.

== SEO / AEO ==

- Semantic HTML5 throughout (header/nav/main/article/section/footer,
  proper heading order, real `<a href>` links in the mega menu — nothing
  JS-only that a crawler can't follow).
- Schema.org JSON-LD on every page (inc/schema.php): Organization +
  WebSite site-wide, Product (with every Technical Specification as an
  `additionalProperty`, so AI answer engines can read grade/protein/
  moisture etc. directly) on product pages, BreadcrumbList wherever a
  trail exists.
- Editable per-product SEO Title / Meta Description fields, with
  Open Graph + Twitter Card tags generated from them (inc/seo.php) —
  automatically disabled if Yoast/Rank Math/AIOSEO/SEO Framework is later
  installed, so the theme never fights a real SEO plugin.
- Clean, descriptive, hierarchical URLs — /food/chicken-feet-products/,
  /pet-food/freeze-dried-products/ etc. for each business line's landing
  page, and /food/chicken-feet-products/{product-slug}/ for its products
  (see "Product architecture" above) — with automatic 301 redirects from
  any older flat URL, so search-engine rankings and inbound links carry
  over rather than 404ing after an update.
- WordPress core's native XML sitemap (wp-sitemap.xml, built in since 5.5)
  picks up the product CPT and business_line taxonomy automatically since
  both are public/show_in_rest — no sitemap plugin required.
- Not yet built: per-product FAQ schema (the brief mentions this only for
  the Chicken Feet & Paws family's SEO fields, section 9) — straightforward
  to add as one more repeater field in inc/meta-boxes.php
  (`avin_product_field_groups()`) plus a FAQPage entry in
  inc/schema.php's `avin_product_schema()` when that content exists.

== Inquiries ==

The inquiry form (template-parts/inquiry-form.php — embedded on every
product page and standalone on the Contact page) posts to
admin-post.php, is stored as an `inquiry` post (Products -> Inquiries in
wp-admin — From/Type/Product/Email columns, full details in a meta box)
and emails the team via `wp_mail()`. Works with JavaScript entirely
disabled (progressive enhancement only adds the "Request a Sample"
button preselecting that radio).

Set the notification address under Appearance -> Customize -> Contact
Details (defaults to the Contact Email if left blank). `wp_mail()` uses
PHP's `mail()` by default, which is unreliable on shared hosting and
often lands in spam — install an SMTP plugin (e.g. "WP Mail SMTP") for
real deliverability; no theme changes needed.

== Homepage builder ==

The homepage (front-page.php) is a fully CMS-driven page builder — every
section, and every heading/copy/image/link inside it, is edited under
Appearance -> Customize -> Homepage, nothing is hard-coded in the
template. The panel has 10 sections, each with its own "Show this
section" checkbox so it can be turned off independently:

  01 Hero Slider          Repeater: one or more slides, each with a
                           background image, heading, description, and
                           an optional CTA button. One slide renders as a
                           static hero (no arrows/dots); 2+ slides get a
                           full prev/next + dot slider that auto-rotates
                           every 6s, pauses on hover/focus, and never
                           auto-advances under prefers-reduced-motion.
  02 Company Value/Trust   Heading, copy, and a repeater of short trust
                           points (e.g. "ISO 22000 certified").
  03 Product Categories    Reads live from the Business Lines taxonomy's
                           3 groups (Food/Pet Food/Feed) — nothing to
                           configure here beyond the section heading; edit
                           each group's card image/description on its own
                           term edit screen (Products -> Business Lines).
  04 Sourcing (Food)       Heading, copy, a repeater of numbered process
                           steps (each with an optional icon image), and
                           a CTA button.
  05 Pet Food              Heading, copy, a repeater of highlight bullets,
     Manufacturing          an optional large photo (two-column layout;
                           drops to one column if no photo is set), and a
                           CTA button.
  06 Featured Products     Repeater: pick any published product per row,
                           with optional name/description/image/link
                           overrides if you want the card to read
                           differently here than on its own page.
  07 Private Label         Same shape as Sourcing: heading, copy, numbered
                           steps, CTA button.
  08 How We Work           Heading and a repeater of numbered steps, each
                           with its own number label, title, and
                           description.
  09 Quality               Heading, a repeater of inline value points, and
                           an optional repeater of certification badge
                           images.
  10 B2B CTA / RFQ         Heading, copy, and up to two CTA buttons
                           (primary + secondary), each with its own link.

Every section also has its own emptiness check: leave it fully blank and
it renders nothing at all rather than an empty heading/shell, so turning
a section "on" with no content configured yet never leaves a gap on the
page.

== Where to edit things ==

  front-page.php               Homepage — 10-section CMS-driven page builder (see above)
  page-about.php                About page (brand positioning fallback content)
  page-contact.php              Contact page (inquiry form + contact info)
  taxonomy-business_line.php    Business-line pages — group terms show a line grid, leaf
                                 terms (all 5 lines) share the product-grid landing layout
  single-product.php            Product detail page (every section from the brief)
  archive-product.php           "All Products" catalog with business-line filter pills
  home.php                      Blog listing (WordPress's "Posts page" template)
  single.php                    Single blog post
  header.php / footer.php       Site chrome, logo+name, nav, footer columns
  template-parts/               mega-menu, mobile-nav, language-switcher, product-card, inquiry-form
  inc/cpt-product.php            Product CPT, hierarchical Business Lines taxonomy + nested
                                 rewrite/permalinks, legacy URL redirects, seed data, query helpers
  inc/meta-boxes.php             Every dynamic product field (add a field here, it just works)
  inc/inquiry.php                Inquiry form handler + wp-admin list/detail view
  inc/schema.php                 JSON-LD structured data
  inc/seo.php                    Meta title/description, Open Graph/Twitter
  inc/customizer.php             Contact Details + Coming Soon Mode Customizer sections
  inc/customizer-homepage.php    Homepage builder's 10 Customizer sections (see above)
  inc/customizer-repeater.php    Shared repeater/setting helpers used by every Customizer field
  inc/class-avin-customize-repeater-control.php  The repeater control class itself
  inc/coming-soon.php            Coming Soon mode gate + holding-page markup + boxes builder
  assets/css/main.css            All front-end styling + design tokens (top of file)
  assets/css/rtl.css             RTL-only overrides (loaded when is_rtl())
  assets/css/admin.css           Product edit screen (meta box) styling
  assets/css/customizer-repeater.css  Repeater control styling inside the Customizer sidebar
  assets/js/main.js              Sticky header, mega menu, mobile nav, search toggle, hero slider
  assets/js/admin-meta-boxes.js  Repeater rows + wp.media pickers for the meta boxes
  assets/js/customizer-repeater.js  Add/remove/reorder rows + wp.media pickers for Customizer repeaters

== Notes ==

- No build step, no Node.js, no Composer required to run the theme —
  plain PHP + CSS + vanilla JS on any standard WordPress/PHP host.
- No third-party fonts or scripts are loaded — the system font stack
  (with a Farsi fallback to Vazirmatn if that font is installed/enqueued
  separately) keeps the site fast with zero external requests, matching
  the brief's Core Web Vitals requirement.
- Every repeater/media/gallery field in the admin (Technical
  Specifications, Documents, Certifications, Gallery) is hand-built with
  wp.media + vanilla JS — there is no ACF or other plugin dependency.
