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
   - seeds the 5 business lines and their category groups (see "Product
     architecture" below), and the mega menu's 3 categories + 8 items
     (see "Navigation & Mega Menu" below), with the names/descriptions
     from the brief, so the mega menu and site aren't empty on first
     activation
   - switches "Plain" permalinks to pretty ones if needed

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
  four category boxes — Freeze-Dried Pet Food, Air-Dried Pet Food,
  Chicken Feet Products, Animal Protein Ingredients — and a "Get in
  Touch" mailto button) instead of the real site — no navigation into
  pages that aren't ready.
- Anyone logged into wp-admin (your whole team, any role) keeps seeing
  and editing the real site completely normally — nothing is hidden from
  you, only from the public. To check what a visitor actually sees, log
  out or open the site in a private/incognito window.
- The **Coming Soon Page Logo** control right under the toggle uploads a
  logo used only on this holding page (handy if it should differ from
  the main site logo); leave it empty and it falls back to Customize ->
  Site Identity's logo, then to a plain initial if neither is set.
  The **Coming Soon** entry under Pages in wp-admin still exists and
  sets the browser tab title, but its body content isn't shown on the
  page anymore — it was replaced by the four category boxes above.
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

The Products mega menu is its own small content type, separate from the
Products/business-line catalog described below, because it needs an
image + subtitle + link per entry that products/business lines don't
carry:

- **Mega Menu Categories** (wp-admin -> Products -> Mega Categories) —
  the three fixed column-1 selectors: Food, Pet Food, Feed. Just a name
  and an order (seeded 1/2/3 on activation); there's no order field in
  the UI for this one since there are only ever 3 of them — add one the
  same way Mega Items has theirs if that changes.
- **Mega Menu Items** (wp-admin -> Products -> Mega Items) — the cards
  each category reveals (e.g. "Chicken Feet Products" under Food). Open
  any item to edit its **Mega Category** (which of the 3 columns it
  appears under), **Subtitle**, **Background Image** (optional — cards
  without one get a clean minimal treatment instead, per the brief),
  **Explore Link URL** (where the card's "Explore ->" goes — leave empty
  and it falls back to the All Products archive), and **Order**.

Seeded on activation with the 8 items from the approved structure —
Chicken Feet Products / Extracts / Freeze-Dried Snacks under Food;
Freeze-Dried Products / Air-Dried Products / Paste Products under Pet
Food; Chicken Powders / Marine Powders under Feed — with Explore links
pre-filled to the matching existing business-line landing page for the 4
items that have one (Chicken Feet Products, Freeze-Dried Products,
Air-Dried Products, and both powder items point at Ingredients &
Solutions); the other 4 (Extracts, Freeze-Dried Snacks, Paste Products)
don't have a dedicated landing page yet, so point at All Products until
one exists — update their Explore Link URL once it does.

On desktop, clicking "Products" opens all three categories' item panels
at once in the DOM (every link is always real and crawlable — nothing
is fetched or swapped in via JS) and only toggles which one is visible;
hovering or clicking a category (Food/Pet Food/Feed) switches instantly
with no reload. On mobile/tablet the same data renders as a nested
accordion (Products -> category -> items) instead of attempting the
desktop's multi-column hover layout on a touch screen.

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

- **Business Lines** (Products -> Business Lines) — the 5 lines from the
  brief: Freeze-Dried Pet Food, Air-Dried Pet Food, Chicken Feet & Paws,
  Freeze-Dried Fruits & Vegetables, Animal Protein Ingredients. Each
  term's archive *is* its landing page (/freeze-dried-pet-food/ etc.).
  Edit a term to change its mega-menu description, hero subtitle, CTA
  labels, menu order, and which one gets the "featured" (Freeze-Dried)
  treatment in the mega menu and homepage grid — under Quick Edit these
  don't show; use the term edit screen's custom fields.

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

Adding a new business line: Products -> Business Lines -> Add New. It
appears in the mega menu and gets a working landing page automatically
(grouped by whichever Category Groups you tag its products with, or a
flat grid if you don't add any) — no template changes needed. To change
which mega-menu column a line's card appears under, filter
`avin_mega_menu_column_map` in a child theme/mu-plugin.

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
   (e.g. /fa/freeze-dried-pet-food/) automatically.
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
- Clean, descriptive URLs matching the brief's SEO architecture exactly:
  /freeze-dried-pet-food/, /air-dried-pet-food/, /chicken-feet-products/,
  /freeze-dried-human-food/, /ingredients-solutions/ for the five
  business lines; /products/{product-slug}/ for individual products (flat
  on purpose — a product's business line can be reclassified later
  without ever breaking or redirecting its canonical URL).
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

== Where to edit things ==

  front-page.php              Homepage (hero, business-line grid, brand pillars, CTA)
  page-about.php               About page (brand positioning fallback content)
  page-contact.php             Contact page (inquiry form + contact info)
  taxonomy-business_line.php   Business-line landing pages (all 5 lines share this)
  single-product.php           Product detail page (every section from the brief)
  archive-product.php          "All Products" catalog with business-line filter pills
  home.php                     Blog listing (WordPress's "Posts page" template)
  single.php                   Single blog post
  header.php / footer.php      Site chrome, logo+name, nav, footer columns
  template-parts/              mega-menu, mobile-nav, language-switcher, product-card, inquiry-form
  inc/cpt-product.php           Product CPT, taxonomies, seed data, query helpers
  inc/meta-boxes.php            Every dynamic product field (add a field here, it just works)
  inc/mega-menu.php             Mega Categories/Items taxonomies, admin fields, seed data, query helpers
  inc/inquiry.php               Inquiry form handler + wp-admin list/detail view
  inc/schema.php                JSON-LD structured data
  inc/seo.php                   Meta title/description, Open Graph/Twitter
  inc/customizer.php            Contact Details + Coming Soon Mode Customizer sections
  inc/coming-soon.php           Coming Soon mode gate + holding-page markup
  assets/css/main.css           All front-end styling + design tokens (top of file)
  assets/css/rtl.css            RTL-only overrides (loaded when is_rtl())
  assets/css/admin.css          Product edit screen (meta box) styling
  assets/js/main.js             Sticky header, mega menu, mobile nav, search toggle
  assets/js/admin-meta-boxes.js Repeater rows + wp.media pickers for the meta boxes

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
