# Alpha Labs Utility Pack

A WordPress plugin providing common utilities and functionality for Alpha Labs projects. This plugin is included in all websites we create and those we adopt from other developers, providing a consistent set of tools and features across our portfolio.

## Contents

### Frontend Features
- **Automatic Button Tracking** `[Both]` - GA4 tracking for phone, email, WhatsApp, and meeting links
- **CookieYes Banner Delay** `[Both]` - 3-second delay for cookie consent banners

### Admin Features
- **Comments Disabled** `[Both]` - Complete removal of WordPress comments functionality
- **Exception Notifications** `[Both]` - Automatic email alerts for uncaught exceptions and fatal errors

### Development Helpers
- **Custom Post Type Helper** `[Both]` - Rapid CPT registration with auto-generated labels
- **Custom Post Type Fields Helper** `[Both]` - Meta boxes with automatic save handling

### Bedrock Integration
- **Environment-Based Configuration** `[Bedrock Only]` - Custom .env variables and IP-based debugging
- **Digital Ocean Spaces Integration** `[Bedrock Only]` - Auto-configuration for WP Offload Media

### Theme Compatibility
- **Astra Theme Compatibility** `[Both]` - Reserved for theme-specific adjustments
- **GeneratePress Theme Compatibility** `[Both]` - Reserved for theme-specific adjustments

---

## Overview

This plugin is designed to be lightweight and compatible with both traditional WordPress installations and modern Bedrock-based projects. It's intentionally kept simple to minimize compatibility issues while providing essential utilities that support our development workflow.

## Features

### 🎯 Frontend Features

#### Automatic Button Tracking `[Both]`
**Location:** `lib/FrontEnd/Buttons.php`

Automatically tracks user interactions with common call-to-action buttons using Google Analytics 4 (GA4):
- **Phone links** (`tel:`) - Tracked as `phone_click`
- **Email links** (`mailto:`) - Tracked as `email_click`
- **WhatsApp links** - Tracked as `whatsapp_click`
- **Meeting links** (Calendly, Microsoft Meetings) - Tracked as `meeting_click`

No manual GA4 event setup required. Only fires if GA4 (`gtag`) is already loaded on the page.

#### CookieYes Banner Delay `[Both]`
**Location:** `lib/FrontEnd/Cookies.php`

Delays the appearance of CookieYes cookie consent banners by 3 seconds to improve user experience and reduce immediate popup fatigue. Only activates when CookieYes plugin is installed.

---

### 🔧 Admin Features

#### Comments Disabled `[Both]`
**Location:** `lib/Admin/Customise.php`

Completely disables WordPress comments functionality:
- Removes comment support from all post types
- Removes comments from admin menu
- Removes comments from admin bar

#### Exception Notifications `[Both]`
**Location:** `lib/Admin/ExceptionHandler.php`

Automatically sends email notifications when uncaught exceptions or fatal errors occur:
- **Dual Notification**: Sends to both WordPress admin email and `admin@alphalabs.net`
- **Exception Handling**: Catches uncaught exceptions with full stack traces
- **Fatal Error Detection**: Captures PHP fatal errors via shutdown handler
- **Detailed Reports**: HTML-formatted emails with error details, request context, and stack traces
- **Debug Log Integration**: Logs to WordPress debug log when `WP_DEBUG_LOG` is enabled

Helps ensure no critical errors go unnoticed across all Alpha Labs sites.

---

### 🏗️ Development Helpers

#### Custom Post Type Helper `[Both]`
**Location:** `lib/Helpers/CustomPostType.php`

Rapidly register custom post types with auto-generated labels. Eliminates boilerplate code.

**Usage:**
```php
new \AlphaLabsUtilityPack\Helpers\CustomPostType(
    'project',           // Post type slug
    ['public' => true],  // register_post_type() args
    'Project',           // Singular label
    'Projects'           // Plural label
);
```

#### Custom Post Type Fields Helper `[Both]`
**Location:** `lib/Helpers/CustomPostTypeFields.php`

Add meta boxes with custom fields to post types with automatic save handling, nonce verification, and capability checks.

**Supported field types:** `text`, `textarea`, `number`, `datetime`

**Usage:**
```php
new \AlphaLabsUtilityPack\Helpers\CustomPostTypeFields(
    'project',                    // Post type
    'project_details',            // Meta box ID
    'Project Details',            // Meta box title
    [                             // Fields array
        ['id' => 'project_url', 'label' => 'Project URL', 'type' => 'text'],
        ['id' => 'launch_date', 'label' => 'Launch Date', 'type' => 'datetime'],
        ['id' => 'description', 'label' => 'Description', 'type' => 'textarea']
    ]
);
```

---

### 🌱 Bedrock Integration

#### Environment-Based Configuration `[Bedrock Only]`
**Location:** `lib/Bedrock/CustomConfig.php`

Loads custom environment variables from `.env` files via Roots WPConfig.

**Custom Environment Variables:**
- `AL4_DEBUG_IP_ADDRESS` - IP-based remote debugging
- `AL4_AS3CF_KEY` - WP Offload Media (S3) access key for Digital Ocean Spaces
- `AL4_AS3CF_SECRET` - WP Offload Media (S3) secret key

**Features:**
- **IP-Based Remote Debugging**: Automatically enables `WP_DEBUG_DISPLAY`, `WP_DEBUG_LOG`, and `SCRIPT_DEBUG` when visitor IP matches `AL4_DEBUG_IP_ADDRESS`
- **Digital Ocean Spaces Integration**: Auto-configures WP Offload Media plugin for Digital Ocean when credentials are provided

---

### 🎨 Theme Compatibility

#### Astra Theme Compatibility `[Both]`
**Location:** `lib/Compatability/Astra.php`

Reserved for Astra theme-specific compatibility adjustments.

#### GeneratePress Theme Compatibility `[Both]`
**Location:** `lib/Compatability/GeneratePress.php`

Reserved for GeneratePress theme-specific compatibility adjustments.

---

## Legend

- `[Both]` - Works with both WordPress Classic and Bedrock
- `[Bedrock Only]` - Requires Roots Bedrock environment
- `[WP Classic]` - Traditional WordPress only

## Installation

This plugin is deployed via GitHub and supports automatic updates through GitHub Updater.

**GitHub Repository:** [josephdsouza86/alphalabs-utility-pack](https://github.com/josephdsouza86/alphalabs-utility-pack)

## Version Management

Current Version: **1.0.2.12**

Follows semantic versioning: `MAJOR.MINOR.PATCH.BUILD`

When updating, ensure both the plugin header and `ALPHALABS_UTILITY_PACK_VERSION` constant are updated in `alphalabs-utility-pack.php`.

## Development Notes

- No build process required - PHP files deployed directly
- Uses PSR-4 autoloading for modern PHP classes
- Built on WordPress Plugin Boilerplate architecture
- See `.github/copilot-instructions.md` for detailed development guidelines