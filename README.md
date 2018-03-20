# CSS inliner for the TYPO3 mailer [![Build Status](https://travis-ci.org/pagemachine/typo3-mail-css-inliner.svg)](https://travis-ci.org/pagemachine/typo3-mail-css-inliner) [![Latest Stable Version](https://poser.pugx.org/pagemachine/typo3-mail-css-inliner/v/stable)](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner) [![Total Downloads](https://poser.pugx.org/pagemachine/typo3-mail-css-inliner/downloads)](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner) [![Latest Unstable Version](https://poser.pugx.org/pagemachine/typo3-mail-css-inliner/v/unstable)](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner) [![License](https://poser.pugx.org/pagemachine/typo3-mail-css-inliner/license)](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner)

This extension integrates a [CSS inliner](https://packagist.org/packages/tijsverkoyen/css-to-inline-styles) into the TYPO3 core mailer.

## Installation

This extension is installable from various sources:

1. Via [Composer](https://packagist.org/packages/pagemachine/typo3-mail-css-inliner):

        composer require pagemachine/typo3-mail-css-inliner

2. From the [TYPO3 Extension Repository](https://extensions.typo3.org/extension/mail_css_inliner/)

After installing the extension registers itself as Swiftmailer plugin, no further configuration is necessary.

## Purpose

Designing mails is hard. Especially requirements like table layouts and inline styles are complicated to handle and take a lot of time to get right. This extension takes one burden off your shoulders and takes care of turning a regular stylesheet to inline styles.

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
