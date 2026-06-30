=== Structured data for WordPress ===
Contributors: hellogekko
Tags: schema, structured data, json-ld, seo, rich results, acf
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build perfect, schema.org-compliant structured data (JSON-LD) through a visual wizard, with display conditions and flexible property mapping.

== Description ==

Structured Data lets you output clean, valid JSON-LD for the schema types that matter, configured through a three-step wizard:

1. **Schema Type** — pick the schema.org type.
2. **Display Conditions** — decide exactly where the schema is output.
3. **Modify Schema Output** — add properties and map each one to a WordPress field, an ACF field, or custom text.

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

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate **Structured Data** through the *Plugins* menu.
3. Go to **Structured Data → Add New Schema** and follow the wizard.

== Changelog ==

= 1.0.0 =
* Initial release.
