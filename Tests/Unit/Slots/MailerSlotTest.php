<?php

declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Unit\Slots;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pagemachine\MailCssInliner\Mail\Plugin\CssInlinerPlugin;
use Pagemachine\MailCssInliner\Slots\MailerSlot;
use Prophecy\Argument;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Core\Mail\MailMessage;

/**
 * Testcase for MailerSlot
 */
class MailerSlotTest extends UnitTestCase
{
    /**
     * @var MailerSlot
     */
    protected $mailerSlot;

    /**
     * Set up this testcase
     */
    public function setUp(): void
    {
        if (!is_subclass_of(MailMessage::class, \Swift_Message::class)) {
            $this->markTestSkipped('Not using Swiftmailer');
        }

        $this->mailerSlot = new MailerSlot();
    }

    /**
     * @test
     */
    public function registersCssInlinerPlugin(): void
    {
        /** @var Mailer|\Prophecy\Prophecy\ObjectProphecy */
        $mailer = $this->prophesize(Mailer::class);
        /** @var CssInlinerPlugin|Argument\Token\TypeToken */
        $plugin = Argument::type(CssInlinerPlugin::class);
        $mailer->registerPlugin($plugin)->shouldBeCalled();

        $this->mailerSlot->registerPlugin($mailer->reveal());
    }
}
