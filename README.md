# Structured Data

A premium WordPress plugin to add perfect, [schema.org](https://schema.org)-compliant
structured data (JSON-LD) to your site through a visual wizard.

## Features

- **3-step wizard**: pick a schema type → configure display conditions → modify the schema output.
- **12 schema types**: Article, BlogPosting, Book, FAQ, NewsArticle, WebPage, ItemPage,
  Event, Local Business, Product, Person, Organization.
- **Display conditions** with include/exclude logic: show globally, homepage, post type
  (equal / not equal), post, page, post category, taxonomy (tag), post format, page template,
  author, author name, URL parameter and date.
- **Property mapping**: map every schema property to a WordPress field, an ACF field, or
  custom text (with `{{merge_tags}}`).
- **FAQ automation**: choose between *Manual* question/answer pairs or *Automatic* mode that
  links an ACF repeater field and maps its question/answer sub-fields.
- **ACF optional**: works without ACF, and unlocks ACF mapping + automatic FAQ when
  Advanced Custom Fields (Pro) is active.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- (Optional) Advanced Custom Fields / ACF Pro for ACF mapping and automatic FAQ.

## Architecture

```
structured-data.php          Bootstrap, constants, hooks
uninstall.php                Removes all schema definitions on uninstall
includes/
  Autoloader.php             PSR-4 autoloader for HelloGekko\StructuredData
  Plugin.php                 Singleton wiring everything together
  PostType.php               Registers the hgsd_schema CPT
  SchemaDefinition.php       Read/write wrapper around a definition's post meta
  Schema/
    SchemaRegistry.php       Registry of all schema types
    AbstractSchemaType.php   Base: property definitions + JSON-LD builder
    Types/                   The 12 schema type implementations
  Display/
    DisplayConditions.php    Evaluates where a schema is shown
  Output/
    FrontendOutput.php       Prints JSON-LD in wp_head
    PropertyResolver.php     Resolves WP / ACF / custom values
  Admin/
    Admin.php                Meta boxes, save, AJAX, list columns
    AcfFields.php            Reads ACF fields / repeaters / sub-fields
    views/                   Wizard + status meta box markup
assets/
  css/admin.css              Wizard styling
  js/admin.js                Wizard interactivity
```

## Extending

Register custom schema types via the `hgsd_register_schema_types` action, and filter the
final output via the `hgsd_output_nodes` filter.
