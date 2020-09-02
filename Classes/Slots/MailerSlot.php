<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Slots;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Pagemachine\MailCssInliner\Mail\Plugin\CssInlinerPlugin;
use TYPO3\CMS\Core\Mail\Mailer;

/**
 * Slot for TYPO3 mailer signals
 */
class MailerSlot
{
    /**
     * Registers the CSS inliner plugin
     */
    public function registerPlugin(Mailer $mailer): void
    {
        $plugin = new CssInlinerPlugin();
        // @phpstan-ignore-next-line
        $mailer->registerPlugin($plugin);
    }
}
