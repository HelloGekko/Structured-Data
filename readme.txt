=== Structured data for WordPress ===
Contributors: hellogekko
Tags: schema, structured data, json-ld, seo, rich results, acf
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build perfect, schema.org-compliant structured data (JSON-LD) through a visual wizard — plus an internal-link cockpit, Search Console insight, instant indexing and AI-readable output.

== Description ==

Structured Data lets you output clean, valid JSON-LD for the schema types that matter, configured through a three-step wizard:

1. **Schema Type** — pick the schema.org type.
2. **Display Conditions** — decide exactly where the schema is output.
3. **Modify Schema Output** — add properties and map each one to a WordPress field, an ACF field, or custom text.

Beyond the schema wizard it adds an orchestration layer that lays *over* your existing SEO plugin (Rank Math or Yoast) without competing with it: one Cockpit to inspect and tune your site structure, keep Google in sync, and make your content readable by AI assistants.

= Supported schema types =

* Article
* BlogPosting
* Book
* FAQ (FAQPage)
* NewsArticle
* WebPage
* ItemPage
* Event
* Local Business
* Product
* Service
* Person
* Organization
* Job posting (JobPosting)

= Display conditions =

Show globally, homepage, post type (with equal / not equal operators), specific post or page, post category, taxonomy (tag), post format, page template, author, author name, URL parameter and date — combined with include/exclude logic.

= Property mapping =

For every schema property you can choose the value source:

* **WordPress** — title, content, excerpt, dates, permalink, featured image, author and site data.
* **ACF** — any Advanced Custom Fields field (when ACF is active).
* **Custom text** — a manual value, with merge tags such as `{{title}}` and `{{site_name}}`.

= FAQ automation =

The FAQ type adds a **Choose Method** option:

* **Manual** — enter question/answer pairs by hand.
* **Automatic** — link an ACF repeater field and map its question and answer sub-fields. The FAQ is then generated automatically from the repeater rows on each page.

ACF is optional: the plugin works without it, and unlocks ACF field selection and the automatic FAQ when Advanced Custom Fields (Pro) is active.

= The Cockpit =

One overview of every public page with its entity type, incoming/outgoing internal links, click-depth, cornerstone, canonical and robots state — editable in a side panel that writes *through* the active SEO plugin's own fields, never emitting competing tags. It flags orphans, deep pages, missing links and other issues as a dismissible tip list, draws a dependency-free cluster graph, and links related entities in your JSON-LD through @id references. It also reads the front-end links injected by the Internal Link Builder plugin so counts and orphan detection stay accurate.

= Conflict handling =

Detects other plugins that already output structured data and lets you either remove only the duplicate types or all foreign JSON-LD — preferring each SEO plugin's own disable filters so nothing breaks.

= Search Console & instant indexing =

Connect Google Search Console (OAuth) to see how Google actually indexed each page — coverage, chosen canonical, last crawl — right in the Cockpit. The same connection powers instant indexing: new and updated pages are submitted to Google's Indexing API automatically on publish, or on demand with a button, within a daily quota.

= AI-readable output =

Serves a clean Markdown version of each page (with content negotiation on the `Accept` header) and an `/llms.txt` index, so AI assistants can read your content — duplicate-content safe with noindex and canonical headers.

= Reviews =

Pull in Google reviews (Places API, Business Profile API or manual entry) as aggregate rating markup, synced and cached on a schedule, on the schema types Google accepts.

= Updates =

Delivered straight from GitHub Releases: your site shows the standard update notice and updates with one click, with no third-party service in between.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate **Structured Data** through the *Plugins* menu.
3. Go to **Structured Data → Add New Schema** and follow the wizard.

== Changelog ==

= 1.1.0 =
* Keyword cannibalisation detection: the Cockpit flags pages that compete for the same term, from two signals — the focus keywords set in Rank Math or Yoast (read through the SEO adapter, so it works whichever plugin a site uses) and the real queries where Search Console shows several of your pages ranking. Fix it in one place with the canonical field the cockpit already offers.
* Cockpit "Re-scan" button: re-checks the currently flagged pages on demand so issues you just fixed drop off the tip list immediately.
* AI readability audit: the Cockpit flags pages that are hard for AI agents to parse (images without alt text, non-descriptive link text, broken heading order) and offers an on-demand full-page check for the H1. Focused on machine parseability, not WCAG compliance.
* New schema type: JobPosting (vacancy), generated from the official schema.org v30.0 vocabulary, with curated fields for hiring organisation, job location, salary range and remote work — pairs with instant indexing (Google's Indexing API officially supports JobPosting).
* Automatic BreadcrumbList: outputs a Home → parent/category → page trail as JSON-LD, so breadcrumb structured data is present even when this plugin is your only source. Toggle, home label and post types under Structured Data → Breadcrumbs.
* New display condition: show a schema based on the value of an ACF field, with a value control that adapts to the field type (text, boolean, number, select/radio/checkbox).
* Search Console tips now recognise pages you already submitted for indexing: the "not indexed" warning softens to an informational note until Google re-crawls.
* The cluster graph now includes links injected by Internal Link Builder, so it matches the incoming/outgoing link counts.
* The cluster graph legend now explains the node colours (this page, cornerstone, orphan).

= 1.0.0 =
* Initial release.
* Visual three-step wizard for 13 schema types with schema.org v30.0 field catalog, object-valued properties and a live JSON-LD preview.
* Display conditions and property mapping to WordPress, ACF or custom values, including automatic FAQ from an ACF repeater.
* Cockpit: internal-link index, click-depth and orphan detection, cornerstone/canonical/robots editing through Rank Math or Yoast, entity relations via @id, a cluster graph and a dismissible tip list.
* Internal Link Builder integration so front-end-injected links are reflected in link counts and orphan detection.
* Conflict detection and overrule (remove duplicates only, or all foreign JSON-LD) via each SEO plugin's own filters.
* Google Search Console connection (URL inspection) surfaced per page in the Cockpit.
* Instant indexing: automatic and on-demand submission to the Google Indexing API, quota-aware.
* AI-readable output: per-page Markdown, content negotiation and an /llms.txt index.
* Google reviews as aggregate rating markup (Places API, Business Profile API or manual), synced and cached.
* Automatic updates from GitHub Releases.
