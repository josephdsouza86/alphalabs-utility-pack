# CLAUDE.md — Alpha Labs Utility Pack

This file provides guidance for AI assistants (Claude, Copilot, etc.) working in this repository.

---

## Project Overview

**Alpha Labs Utility Pack** is a lightweight WordPress plugin that provides shared utility functionality for all Alpha Labs projects. It is designed to work across both traditional WordPress and Roots Bedrock installations.

- **Plugin Name:** Utility Pack
- **Current Version:** 1.0.2.12
- **License:** GPL-2.0+
- **GitHub:** https://github.com/josephdsouza86/alphalabs-utility-pack
- **Auto-updates via:** GitHub Updater WordPress plugin

---

## Technology Stack

- **Language:** PHP (minimum 7.4; target 8.2)
- **Platform:** WordPress (3.0.1+ compatible)
- **Architecture:** WordPress Plugin Boilerplate + PSR-4 namespaced classes
- **Autoloading:** Custom PSR-4 autoloader (`lib/Psr4AutoLoader.php`)
- **Build process:** None — PHP files are deployed directly
- **No Composer, no npm, no frontend build pipeline**

---

## Directory Structure

```
alphalabs-utility-pack/
├── alphalabs-utility-pack.php       # Plugin entry point — constants, activation hooks, bootstrap
├── uninstall.php                    # Cleanup on plugin uninstall
├── index.php                        # Empty directory protection file
├── LICENSE.txt
├── README.md                        # User-facing documentation
│
├── lib/                             # Modern PSR-4 classes (namespace: AlphaLabsUtilityPack)
│   ├── Psr4AutoLoader.php           # PSR-4 autoloader
│   ├── Admin/
│   │   ├── Customise.php            # Disables WordPress comments globally
│   │   └── ExceptionHandler.php    # Fatal error/exception email notifications
│   ├── FrontEnd/
│   │   └── Buttons.php             # GA4 event tracking for CTAs
│   ├── Compatability/
│   │   ├── Astra.php               # Astra theme compatibility (placeholder)
│   │   └── GeneratePress.php       # GeneratePress + Spectra blocks fix
│   ├── Helpers/
│   │   ├── CustomPostType.php       # Simplifies CPT registration with auto-labels
│   │   └── CustomPostTypeFields.php # Meta box/field helper (text, textarea, number, datetime)
│   ├── bedrock/
│   │   └── CustomConfig.php         # Bedrock-only: env-based WP config overrides
│   └── includes/                    # Legacy WordPress Plugin Boilerplate classes
│       ├── class-alphalabs-utility-pack.php           # Core plugin class
│       ├── class-alphalabs-utility-pack-loader.php    # Hook registration system
│       ├── class-alphalabs-utility-pack-i18n.php      # i18n / translations
│       ├── class-alphalabs-utility-pack-activator.php # Plugin activation
│       └── class-alphalabs-utility-pack-deactivator.php
│
├── admin/                           # Legacy admin boilerplate (mostly unused scaffolding)
│   ├── class-alphalabs-utility-pack-admin.php
│   ├── css/alphalabs-utility-pack-admin.css
│   ├── js/alphalabs-utility-pack-admin.js
│   └── partials/alphalabs-utility-pack-admin-display.php
│
├── public/                          # Legacy frontend boilerplate (mostly unused scaffolding)
│   ├── class-alphalabs-utility-pack-public.php
│   ├── css/alphalabs-utility-pack-public.css
│   ├── js/alphalabs-utility-pack-public.js
│   └── partials/alphalabs-utility-pack-public-display.php
│
├── languages/
│   └── alphalabs-utility-pack.pot   # Translation template
│
└── .github/
    └── copilot-instructions.md      # AI assistant guidelines
```

---

## Plugin Constants

Defined in `alphalabs-utility-pack.php`:

| Constant | Value |
|---|---|
| `ALPHALABS_UTILITY_PACK_VERSION` | `'1.0.2.12'` |
| `ALPHALABS_UTILITY_PACK_SLUG` | `'alphalabs-utility-pack'` |
| `ALPHALABS_UTILITY_PACK_PLUGIN_PATH` | Absolute path to plugin root dir |

---

## Architecture & Bootstrap Flow

### Initialization sequence

1. `alphalabs-utility-pack.php` — defines constants, registers activation/deactivation hooks, calls `run_alphalabs_utility_pack()`
2. `run_alphalabs_utility_pack()` — instantiates `Alphalabs_Utility_Pack` (core class) and calls `->run()`
3. `Alphalabs_Utility_Pack::__construct()` — loads dependencies, sets locale, defines admin/public hooks, calls `init_global_load()`
4. `Alphalabs_Utility_Pack::load_dependencies()` — registers PSR-4 autoloader mapping `AlphaLabsUtilityPack` → `lib/`
5. `Alphalabs_Utility_Pack::run()` — executes all queued hooks via the loader

### Where to instantiate new features

| Feature type | Instantiation point |
|---|---|
| Always-on (admin + frontend) | `Alphalabs_Utility_Pack::init_global_load()` |
| Admin-only | `Alphalabs_Utility_Pack_Admin::init_customiser()` |
| Frontend-only | `Alphalabs_Utility_Pack_Public::__construct()` |

### Hook Loader Pattern

Never call `add_action()` or `add_filter()` directly. Use the loader:

```php
// In the core class or admin/public class:
$this->loader->add_action('wp_footer', $instance, 'method_name');
$this->loader->add_filter('the_content', $instance, 'method_name');
```

The loader batches all hook registrations and fires them during `run()`.

---

## Naming Conventions

### Legacy classes (WordPress Boilerplate style)
- Located in: `includes/`, `admin/`, `public/`
- Naming: `Alphalabs_Utility_Pack_[Suffix]` (Snake_Case with plugin prefix)
- File names: `class-alphalabs-utility-pack-[suffix].php`

### Modern classes (PSR-4 namespaced)
- Located in: `lib/[Subdirectory]/`
- Namespace: `AlphaLabsUtilityPack\[Subdirectory]\[ClassName]`
- File names: `ClassName.php` (PascalCase, one class per file)
- Example: `AlphaLabsUtilityPack\Helpers\CustomPostType`

### Methods
- Public methods: camelCase (e.g., `addCustomButtonTrackingScript()`)
- Private methods: underscore-prefixed camelCase (e.g., `_sendFatalErrorNotification()`)
- WordPress hook callbacks: snake_case matching WP convention where applicable

---

## Code Style & Security Requirements

All code must follow WordPress coding standards and security practices:

```php
// Always escape output
esc_html( $value );
esc_attr( $value );
esc_url( $value );

// Always sanitize input
sanitize_text_field( $_POST['field'] );
sanitize_textarea_field( $_POST['textarea'] );

// Always verify nonces before saving
if ( ! wp_verify_nonce( $_POST['nonce'], 'action_name' ) ) { return; }

// Always check capabilities before save
if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

// Use strict types in modern classes
declare(strict_types=1);
```

---

## Adding a New Feature

1. Create a class in the appropriate `lib/` subdirectory:
   ```php
   <?php
   declare(strict_types=1);
   namespace AlphaLabsUtilityPack\FrontEnd;

   class MyFeature {
       public function __construct() {
           add_action('wp_footer', [$this, 'render']);
       }

       public function render(): void {
           // implementation
       }
   }
   ```

2. Instantiate it in the correct initialization method (see table above):
   ```php
   // In init_global_load(), init_customiser(), or __construct():
   new \AlphaLabsUtilityPack\FrontEnd\MyFeature();
   ```

3. No registration or autoloader config needed — PSR-4 handles class loading automatically.

---

## Feature Reference

### `lib/FrontEnd/Buttons.php` — GA4 Button Tracking
- Injects JavaScript in `wp_footer`
- Automatically tracks: `tel:` links (phone_click), `mailto:` links (email_click), WhatsApp links (whatsapp_click), Calendly/Microsoft Meetings links (meeting_click)
- Only fires if `gtag` is present on the page

### `lib/Admin/Customise.php` — Remove Comments
- Completely removes WordPress comments functionality site-wide
- Removes comment support from all post types, admin menu, and admin bar

### `lib/Admin/ExceptionHandler.php` — Fatal Error Notifications
- Registers a PHP shutdown function to catch fatal errors
- Sends HTML notification emails to WordPress admin email and `admin@alphalabs.net`
- Also hooks into `wp_php_error_message` for WordPress recovery mode
- Writes to debug log when `WP_DEBUG_LOG` is enabled

### `lib/Helpers/CustomPostType.php` — CPT Registration Helper
- Usage: `new CustomPostType('project', $args, 'Project', 'Projects')`
- Auto-generates all standard WordPress CPT labels from singular/plural names

### `lib/Helpers/CustomPostTypeFields.php` — CPT Meta Box Helper
- Adds meta boxes with fields to post types
- Handles nonce, capability checks, sanitization, and escaping automatically
- Supported field types: `text`, `textarea`, `number`, `datetime`

### `lib/bedrock/CustomConfig.php` — Bedrock Environment Config
- **Bedrock only** — reads `.env` variables for WP configuration overrides
- Custom env keys: `AL4_DEBUG_IP_ADDRESS`, `AL4_AS3CF_KEY`, `AL4_AS3CF_SECRET`
- Enables WP_DEBUG_DISPLAY/LOG/SCRIPT_DEBUG for matching IP address
- Configures WP Offload Media for Digital Ocean Spaces when credentials provided

### `lib/Compatability/GeneratePress.php` — GeneratePress Compatibility
- Fixes Spectra/UAGB block CSS loading inside GeneratePress Post Elements

---

## Version Management

Version follows `MAJOR.MINOR.PATCH.BUILD` format.

When releasing a new version, update **both**:
1. Plugin header comment in `alphalabs-utility-pack.php`:
   ```php
   * Version: 1.0.2.13
   ```
2. The constant definition:
   ```php
   define( 'ALPHALABS_UTILITY_PACK_VERSION', '1.0.2.13' );
   ```

GitHub Updater reads the plugin header to detect available updates.

---

## Environment Compatibility Tags

Use these in comments and documentation to indicate compatibility:

| Tag | Meaning |
|---|---|
| `[Both]` | Works in traditional WordPress and Bedrock |
| `[Bedrock Only]` | Requires Roots Bedrock environment |
| `[WP Classic]` | Traditional WordPress only |

---

## Deployment

- No build step required — PHP files are deployed as-is
- Auto-updates are handled by the [GitHub Updater](https://github.com/afragen/github-updater) WordPress plugin
- Incrementing the plugin version number triggers the update mechanism
- Project-specific logic does **not** belong here — use `mu-plugins/al-logic` instead

---

## What This Plugin Is NOT

- Not a project-specific plugin — contains only shared, reusable utilities
- Not a theme — no templates, no styling
- Not a full-featured plugin framework — intentionally lightweight to minimize compatibility risk
