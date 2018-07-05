<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Unit\Mail\Plugin;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pagemachine\MailCssInliner\Mail\Plugin\CssInlinerPlugin;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for Pagemachine\MailCssInliner\Mail\Plugin\CssInlinerPlugin
 */
class CssInlinerPluginTest extends UnitTestCase
{
    /**
     * @var CssInlinerPlugin
     */
    protected $cssInlinerPlugin;

    /**
     * @var CssToInlineStyles|\Prophecy\Prophecy\ObjectProphecy
     */
    protected $converter;

    /**
     * Set up this testcase
     */
    public function setUp()
    {
        $this->converter = $this->prophesize(CssToInlineStyles::class);
        $this->cssInlinerPlugin = new CssInlinerPlugin($this->converter->reveal());
    }

    /**
     * @test
     */
    public function processesHtmlInMessageBody()
    {
        /** @var MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setBody('<p>before</p>', 'text/html');
        /** @var \Swift_Events_SendEvent|\Prophecy\Prophecy\ObjectProphecy */
        $event = $this->prophesize(\Swift_Events_SendEvent::class);
        $event->getMessage()->willReturn($message);

        $this->converter->convert('<p>before</p>')->willReturn('<p>after</p>');

        $this->cssInlinerPlugin->beforeSendPerformed($event->reveal());

        $this->assertEquals('<p>after</p>', $message->getBody());
    }

    /**
     * @test
     */
    public function processesHtmlInMessagePart()
    {
        /** @var MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message
            ->addPart('before', 'text/plain')
            ->addPart('<p>before</p>', 'text/html');
        /** @var \Swift_Events_SendEvent|\Prophecy\Prophecy\ObjectProphecy */
        $event = $this->prophesize(\Swift_Events_SendEvent::class);
        $event->getMessage()->willReturn($message);

        $this->converter->convert('<p>before</p>')->willReturn('<p>after</p>');

        $this->cssInlinerPlugin->beforeSendPerformed($event->reveal());

        $this->assertEquals('<p>after</p>', $message->getChildren()[1]->getBody());
    }

    /**
     * @test
     */
    public function ignoresMessageAttachments()
    {
        /** @var MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message
            ->setBody('<p>before</p>', 'text/html')
            ->attach(new \Swift_Attachment('TEST', 'test.pdf', 'application/pdf'));
        /** @var \Swift_Events_SendEvent|\Prophecy\Prophecy\ObjectProphecy */
        $event = $this->prophesize(\Swift_Events_SendEvent::class);
        $event->getMessage()->willReturn($message);

        $this->converter->convert('<p>before</p>')->willReturn('<p>after</p>');

        $this->cssInlinerPlugin->beforeSendPerformed($event->reveal());

        $this->assertEquals('<p>after</p>', $message->getBody());
    }

    /**
     * @test
     */
    public function handlesMessagesWithPartsAndAttachmentsOnly()
    {
        /** @var MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message
            ->addPart('<p>before</p>', 'text/html')
            ->attach(new \Swift_Attachment('TEST', 'test.pdf', 'application/pdf'));
        /** @var \Swift_Events_SendEvent|\Prophecy\Prophecy\ObjectProphecy */
        $event = $this->prophesize(\Swift_Events_SendEvent::class);
        $event->getMessage()->willReturn($message);

        $this->converter->convert('<p>before</p>')->willReturn('<p>after</p>');

        $this->cssInlinerPlugin->beforeSendPerformed($event->reveal());

        $this->assertEquals('<p>after</p>', $message->getChildren()[0]->getBody());
    }
}
