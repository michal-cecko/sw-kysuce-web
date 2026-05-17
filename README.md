# Streetworkout Slovakia — WordPress theme

Custom WordPress theme for **Streetworkout Slovakia**, a club site with events, playgrounds, blog and Mailchimp newsletter signups.

> **Site is no longer live.** Kept as a portfolio reference for the build approach.

## Stack

- **WordPress** + **PHP**
- **ACF** for content fields
- **JS + jQuery** front-end (Vue not used here)
- **Sass** styles, **Gulp 4** asset pipeline
- **Composer** for `phpoffice/phpspreadsheet` (event participant export)
- **MailChimp PHP wrapper** (vendored under `_mailchimp_api/`)
- **phpqrcode** (vendored under `qrcodes/phpqrcode/`) for printable event QR codes

## Layout

```
├── front-page.php           # homepage
├── header.php / footer.php
├── functions/               # split function files
│   ├── functions_theme.php  # asset enqueue, theme cleanup
│   ├── functions_helper.php # date/event helpers, reCAPTCHA verify
│   └── …
├── functions.php            # require_once for all of the above
├── template-*.php           # page templates (about, blog, contact, events, playgrounds)
├── single.php / single-event.php
├── template_parts/          # partials
├── custom_plugins/          # bundled in-theme plugins
│   ├── madelo_starterpack/  # admin pages (incl. reCAPTCHA settings UI)
│   └── custom-gutenberg-blocks/
├── assets/                  # sass / js / images / fonts
├── _mailchimp_api/          # vendored MailChimp PHP lib
└── qrcodes/phpqrcode/       # vendored QR code generator
```

## Build

```bash
npm install
npx gulp production          # full sass + scripts build → /dist
npx gulp assets:watch        # watch sass + js during dev
```

## Required `wp_options` (set via wp-admin under "Madelo Starterpack")

| Option | Purpose |
|---|---|
| `recaptcha_site_key` | reCAPTCHA v3 site key (used by enqueue + JS) |
| `recaptcha_secret_key` | reCAPTCHA v3 secret (used by `checkCaptcha` helper) |

The theme reads these via `get_option()` — no hardcoded keys.

## License

[MIT](LICENSE) © Michal Čečko
