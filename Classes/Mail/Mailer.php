<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Mail;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use TYPO3\CMS\Core\Mail\Mailer as CoreMailer;

final class Mailer extends CoreMailer
{
    /**
     * @inheritdoc
     */
    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        if ($message instanceof Email && !empty($message->getHtmlBody())) {
            $converter = new CssToInlineStyles();
            $message->html($converter->convert($message->getHtmlBody()));
        }

        parent::send($message, $envelope);
    }
}
