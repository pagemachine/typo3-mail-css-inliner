<?php

declare(strict_types=1);

namespace Pagemachine\MailCssInliner\Mail;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use TYPO3\CMS\Core\Mail\FluidEmail as CoreFluidEmail;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

class FluidEmail extends CoreFluidEmail
{
    /**
     * @param resource|string|null $body
     *
     * @return $this
     */
    public function html($body, string $charset = 'utf-8'): static
    {
        if (!empty($body)) {
            $converter = new CssToInlineStyles();
            $body = $converter->convert($body);
        }

        return parent::html($body, $charset);
    }
}
