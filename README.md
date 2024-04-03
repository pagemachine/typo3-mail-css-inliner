# CSS inliner for the TYPO3 mailer ![CI](https://github.com/pagemachine/typo3-mail-css-inliner/workflows/CI/badge.svg)

This extension integrates a [CSS inliner](https://packagist.org/packages/tijsverkoyen/css-to-inline-styles) into the TYPO3 core mailer.

## Installation

This extension is installable from various sources:

1. Via [Composer](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner):

        composer require pagemachine/typo3-mail-css-inliner

2. From the [TYPO3 Extension Repository](https://extensions.typo3.org/extension/mail_css_inliner/)

After installing the extension registers itself automatically, no further configuration is necessary.

## Purpose

Designing mails is hard. Especially requirements like table layouts and inline styles are complicated to handle and take a lot of time to get right. This extension takes one burden off your shoulders and takes care of turning a regular stylesheet to inline styles. See our blog post about [Mail styling in TYPO3 now easier](https://www.pagemachine.de/blog/mail-styling-typo3/).

Before:

```html
<!doctype html>
<html>
    <head>
        <title>CSS Inline Test</title>
        <style>
            body {
                color: #333;
            }
            h1 {
                font-size: 36px;
            }
            a {
                color: #337ab7;
            }
        </style>
    </head>
    <body>
        <h1>Headline</h1>
        <p>Content with <a href="https://example.org">link</a>.</p>
    </body>
</html>
```

After:

```html
<html>
  <head>
  <title>CSS Inline Test</title>
  <style>
        body {
            color: #333;
        }
        h1 {
            font-size: 36px;
        }
        a {
            color: #337ab7;
        }
    </style>
    </head>
    <body style="color: #333;">
        <h1 style="font-size: 36px;">Headline</h1>
        <p>Content with <a href="https://example.org" style="color: #337ab7;" target="_blank">link</a>.</p>
    </body>
</html>
```

Currently only `<style>` elements are supported, external styles via `<link>` are not imported.

## Testing

All tests can be executed with the shipped Docker Compose definition:

    docker compose run --rm app composer build
