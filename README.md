![Bootstrap Version](https://img.shields.io/badge/Bootstrap-6.0.0--Alpha-7952B3?logo=bootstrap&logoColor=white)

# Understrap Child Theme

A modernised **Understrap Child Theme** with updated dependencies, Bootstrap 6 integration, and compatibility updates for PHP 8.3 and newer.

This repository provides an updated starting point for WordPress projects built on Understrap, with refreshed development tooling and a more current foundation for ongoing theme development.

## Features

- **Understrap-based** — built on the Understrap parent theme.
- **Bootstrap 6** — updated to use Bootstrap 6.
- **Updated Node.js dependencies** — refreshed npm packages and development tooling.
- **PHP 8.3+ compatibility** — updated theme code for newer PHP versions.
- **SCSS workflow** — compile and manage theme stylesheets using Sass.
- **Customisable** — intended as a foundation for project-specific WordPress theme development.

## Requirements

- WordPress
- Understrap parent theme
- PHP 8.3 or newer
- Node.js and npm
- A compatible Sass build environment

## Installation

1. Clone or download this repository into your WordPress installation's `wp-content/themes/` directory.
2. Install and activate the required Understrap parent theme.
3. Install the child theme's Node.js dependencies:

   ```bash
   npm install
   ```

4. Activate the child theme through **WordPress Admin → Appearance → Themes**.
5. Use the available npm scripts to compile stylesheets and run the development workflow.

## Development

After installing the dependencies, check `package.json` for the available npm scripts.

Run the relevant build or watch command to compile SCSS and other theme assets during development.

```bash
npm run
```

This lists the available npm scripts.

## Compatibility

| Component | Version / support |
|---|---|
| WordPress | Requires a compatible WordPress installation |
| Understrap | Understrap parent theme |
| Bootstrap | 6 |
| PHP | 8.3+ |
| Node.js | ^24.15.0 or >=26.11.1 |

## Purpose

This project aims to provide a more up-to-date Understrap child theme foundation, reducing the need to repeat dependency updates and PHP compatibility adjustments when starting new WordPress projects.

## Disclaimer

This is a child theme intended for development and customisation. Review compatibility with your specific WordPress, Understrap, PHP, and Node.js environment before deploying it to a production site.

## Licence

Refer to the repository and its dependency licences for applicable licensing terms. Understrap, Bootstrap, and other bundled dependencies remain subject to their respective licences.
