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
     * @param \Swift_Events_SendEvent $event
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $event)
    {
        $message = $event->getMessage();

        $this->processHtmlParts($message);
    }

    /**
     * @param \Swift_Events_SendEvent $event
     * @codeCoverageIgnore
     */
    public function sendPerformed(\Swift_Events_SendEvent $event)
    {
        // Nothing to do
    }

    /**
     * Recursively processes all HTML parts of a MIME entity
     */
    protected function processHtmlParts(\Swift_Mime_MimeEntity $entity): void
    {
        // Only process HTML parts
        if ($entity instanceof \Swift_Mime_MimePart && ($this->isHtmlPart($entity) || $this->looksLikeHtmlPart($entity))) {
            $entity->setBody($this->converter->convert($entity->getBody()));
        }

        foreach ($entity->getChildren() as $childEntity) {
            $this->processHtmlParts($childEntity);
        }
    }

    /**
     * Returns whether a given entity is an HTML part
     */
    private function isHtmlPart(\Swift_Mime_MimePart $entity): bool
    {
        return $entity->getContentType() === 'text/html';
    }

    /**
     * Returns whether a given entity is a mixed multipart which looks like an HTML part
     *
     * If HTML was set as body and something was attached, \Swift_Mime_SimpleMimeEntity::setChildren()
     * overwrites the content type of the part to "multipart/mixed" without any way to retrieve the
     * original content type, thus use a simple heuristic to check for possible HTML content.
     */
    private function looksLikeHtmlPart(\Swift_Mime_MimePart $entity): bool
    {
        return in_array($entity->getContentType(), ['multipart/mixed', 'multipart/alternative'], true)
            && !empty($entity->getBody())
            && strpos($entity->getBody(), '<') !== false;
    }
}
