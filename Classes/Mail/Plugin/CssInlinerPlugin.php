<?php
declare(strict_types = 1);
namespace Pagemachine\MailCssInliner\Mail\Plugin;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Swiftmailer CSS inline plugin
 */
class CssInlinerPlugin implements \Swift_Events_SendListener
{
    /**
     * @var CssToInlineStyles
     */
    protected $converter;

    /**
     * @param CssToInlineStyles|null $converter
     */
    public function __construct(CssToInlineStyles $converter = null)
    {
        $this->converter = $converter ?: new CssToInlineStyles();
    }

    /**
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $event)
    {
        $message = $event->getMessage();

        $this->processHtmlParts($message);
    }

    /**
     * @param Swift_Events_SendEvent $evt
     * @codeCoverageIgnore
     */
    public function sendPerformed(\Swift_Events_SendEvent $event)
    {
        // Nothing to do
    }

    /**
     * Recursively processes all HTML parts of a MIME entity
     *
     * @param \Swift_Mime_MimeEntity $entity
     * @return void
     */
    protected function processHtmlParts(\Swift_Mime_MimeEntity $entity)
    {
        // Only process HTML parts
        if ($entity instanceof \Swift_Mime_MimePart && $entity->getContentType() === 'text/html') {
            $entity->setBody($this->converter->convert($entity->getBody()));
        }

        foreach ($entity->getChildren() as $childEntity) {
            $this->processHtmlParts($childEntity);
        }
    }
}
