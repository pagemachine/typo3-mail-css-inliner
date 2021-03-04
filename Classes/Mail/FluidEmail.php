<?php
declare(strict_types=1);

namespace Pagemachine\MailCssInliner\Mail;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use TYPO3\CMS\Core\Mail\FluidEmail as CoreFluidEmail;

class FluidEmail extends CoreFluidEmail
{
    protected function renderContent(string $format): string
    {
        $content = parent::renderContent($format);

        if ($format === 'html') {
            $converter = new CssToInlineStyles();
            return $converter->convert($content);
        }

        return $content;
    }
}
