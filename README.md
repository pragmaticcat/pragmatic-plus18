# Pragmatic +18

Craft CMS 5 plugin scaffold for a Pragmatic +18 control panel section, with a two-tab CP interface ready to extend.

## Features
- CP section labeled `Pragmatic` with subnavigation item: `+18`
- +18 section entry point redirects to `General`
- Two CP tabs: `General` (`/pragmatic-plus18/general`) and `Opciones` (`/pragmatic-plus18/options`)
- Base Twig layout for +18 pages: `pragmatic-plus18/_layout`
- Plugin registered as `pragmatic-plus18` for Craft CMS 5 projects

## Requirements
- Craft CMS `^5.0`
- PHP `>=8.2`

## Installation
1. Add the plugin to your Craft project and run `composer install`.
2. Install the plugin from the Craft Control Panel.
3. Run migrations when prompted.

## Usage
### CP
- Go to `Pragmatic > +18`.
- Use the **General** tab for global +18 settings (page scaffold ready).
- Use the **Opciones** tab for additional configuration (page scaffold ready).

## Project structure
```
src/
  PragmaticPlus18.php
  controllers/
    DefaultController.php
  templates/
    _layout.twig
    general.twig
    options.twig
```

## Notes
- This repository currently provides the control panel structure and routing scaffold.
- Business logic, settings models, and persistence can be added incrementally on top of this base.
