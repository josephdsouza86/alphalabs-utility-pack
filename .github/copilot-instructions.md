# Alpha Labs Utility Pack - Copilot Instructions

## Project Overview
WordPress plugin providing common utilities and functionality for Alpha Labs projects. This plugin is included in **all** Alpha Labs websites (internal and subcontractor work) via Composer and provides shared utilities that work across both traditional WordPress and Bedrock-based projects.

Built using the WordPress Plugin Boilerplate architecture with PSR-4 autoloading for modern PHP classes.

### Integration with Alpha Labs Standards
This plugin complements the Alpha Labs WordPress Coding Standards (v2.0) by:
- Providing utilities that work in both Bedrock and Classic WordPress environments
- Offering helper classes that simplify common patterns (CPTs, meta boxes)
- Integrating with Bedrock's environment-based configuration
- Supporting Alpha Labs' presentation-only theme philosophy (business logic stays in `mu-plugins/al-logic`)

**Important**: This plugin is for **shared utilities** only. Project-specific business logic belongs in `mu-plugins/al-logic/`, not here.

## Architecture

### Bootstrap & Initialization
- **Entry point**: `alphalabs-utility-pack.php` - defines constants, registers activation/deactivation hooks, and instantiates the core plugin class
- **Core class**: `lib/includes/class-alphalabs-utility-pack.php` - orchestrates plugin initialization, autoloading, and hook registration
- **Hook loader**: `lib/includes/class-alphalabs-utility-pack-loader.php` - maintains arrays of actions/filters and registers them with WordPress

### Namespace & Autoloading
- **Root namespace**: `AlphaLabsUtilityPack`
- **PSR-4 autoloader**: `lib/Psr4AutoLoader.php` maps namespace to `lib/` directory
- All new utility classes go in `lib/` subdirectories and use the `AlphaLabsUtilityPack\[Subdirectory]` namespace

### Module Organization
```
lib/
├── Admin/          - WordPress admin customizations (e.g., Customise.php disables comments)
│                     ExceptionHandler.php sends errors to admin@alphalabs.net
├── FrontEnd/       - Public-facing features (Buttons.php for GA4 tracking, Cookies.php for CookieYes)
├── Compatability/  - Theme compatibility layers (Astra, GeneratePress)
├── Bedrock/        - Roots Bedrock integration (CustomConfig.php for env-based config)
├── Helpers/        - Reusable utilities (CustomPostType, CustomPostTypeFields)
└── includes/       - Legacy WordPress boilerplate classes (use underscore naming)
```

## Adding New Features

### Creating a New Utility Class
1. Create file in appropriate `lib/` subdirectory with PSR-4 namespace
2. Instantiate in either:
   - `Alphalabs_Utility_Pack::init_global_load()` for always-on features
   - `Alphalabs_Utility_Pack_Public::__construct()` for frontend features
   - `Alphalabs_Utility_Pack_Admin::init_customiser()` for admin features

Example:
```php
// lib/FrontEnd/NewFeature.php
namespace AlphaLabsUtilityPack\FrontEnd;

class NewFeature {
    public function __construct() {
        add_action('wp_footer', array($this, 'someMethod'));
    }
}

// Then in class-alphalabs-utility-pack-public.php constructor:
new \AlphaLabsUtilityPack\FrontEnd\NewFeature();
```

### Using Helper Classes

**CustomPostType** - Register custom post types with auto-generated labels:
```php
new \AlphaLabsUtilityPack\Helpers\CustomPostType(
    'project',           // Post type slug
    ['public' => true],  // register_post_type() args
    'Project',           // Singular label
    'Projects'           // Plural label
);
```

**Note**: For Alpha Labs Bedrock projects, CPTs should be registered in `mu-plugins/al-logic/src/Bootstrap/PostTypes.php` using this helper. For non-Bedrock or simpler sites, this helper can be used directly in the plugin context.

**CustomPostTypeFields** - Add meta boxes with fields:
```php
new \AlphaLabsUtilityPack\Helpers\CustomPostTypeFields(
    'project',                    // Post type
    'project_details',            // Meta box ID
    'Project Details',            // Meta box title
    [                             // Fields array
        ['id' => 'project_url', 'label' => 'Project URL', 'type' => 'text'],
        ['id' => 'launch_date', 'label' => 'Launch Date', 'type' => 'datetime']
    ]
);
```

**Note**: For full Alpha Labs projects, prefer ACF field groups defined in `mu-plugins/al-logic/acf-json/`. Use this helper for lightweight sites or quick prototypes.

## Key Patterns

### Hook Registration
- Never use `add_action()`/`add_filter()` directly in feature classes
- Always use `array($this, 'methodName')` callback format for class methods
- The loader pattern ensures hooks fire at correct lifecycle points

### Bedrock Integration
- `lib/Bedrock/CustomConfig.php` loads environment variables from `.env` via Roots WPConfig
- Custom env keys: `AL4_DEBUG_IP_ADDRESS`, `AL4_AS3CF_KEY`, `AL4_AS3CF_SECRET`
- Remote debugging enabled when `AL4_DEBUG_IP_ADDRESS` matches visitor IP

### Frontend Tracking
- `lib/FrontEnd/Buttons.php` auto-tracks clicks on tel:, mailto:, WhatsApp, and meeting links via GA4
- Uses `gtag()` JavaScript API - only fires if GA4 is loaded

### Constants
- `ALPHALABS_UTILITY_PACK_VERSION` - plugin version (update in main file header and constant)
- `ALPHALABS_UTILITY_PACK_SLUG` - plugin slug for WordPress
- `ALPHALABS_UTILITY_PACK_PLUGIN_PATH` - absolute path to plugin directory

## Conventions

### Naming
- **Legacy classes** (in `includes/`, `admin/`, `public/`): WordPress boilerplate style with underscores (e.g., `Alphalabs_Utility_Pack_Admin`)
- **Modern classes** (in `lib/` subdirectories): PSR-4 with namespaces and PascalCase (e.g., `AlphaLabsUtilityPack\FrontEnd\Buttons`)
- **Methods**: camelCase for new code, snake_case for WordPress hooks

### Code Style
- Escape output: `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitize input: `sanitize_text_field()`, `sanitize_textarea_field()`
- Check capabilities before saving: `current_user_can('edit_post', $post_id)`
- Verify nonces: `wp_verify_nonce()` before processing form data
- **PHP 8.2 target** (minimum 7.4 for legacy compatibility)
- Use strict types where possible: `declare(strict_types=1);`

### Alpha Labs Context
When developing features for this plugin:
1. **Keep it simple** - This plugin is intentionally lightweight to minimize compatibility issues
2. **Work everywhere** - Most features should work in both Bedrock and Classic WordPress
3. **No project-specific code** - This is a shared utility; project logic goes in `al-logic` MU-plugin
4. **Tag compatibility** - Clearly document if a feature is `[Bedrock Only]`, `[WP Classic]`, or `[Both]`

## Version Management
When updating plugin version:
1. Update version in `alphalabs-utility-pack.php` plugin header comment
2. Update `ALPHALABS_UTILITY_PACK_VERSION` constant in same file
3. Follow semantic versioning: `MAJOR.MINOR.PATCH.BUILD`

## Deployment
- GitHub repository: `josephdsouza86/alphalabs-utility-pack`
- Includes GitHub Updater support (see plugin header `GitHub Plugin URI`)
- No build process - PHP files deployed directly
