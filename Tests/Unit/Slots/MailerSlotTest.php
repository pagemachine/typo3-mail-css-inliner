<?php
namespace Pagemachine\MailCssInliner\Tests\Unit\Slots;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pagemachine\MailCssInliner\Mail\Plugin\CssInlinerPlugin;
use Pagemachine\MailCssInliner\Slots\MailerSlot;
use Prophecy\Argument;
use TYPO3\CMS\Core\Mail\Mailer;

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
    public function setUp()
    {
        $this->mailerSlot = new MailerSlot();
    }

    /**
     * @test
     */
    public function registersCssInlinerPlugin()
    {
        /** @var Mailer|\Prophecy\Prophecy\ObjectProphecy */
        $mailer = $this->prophesize(Mailer::class);
        $mailer->registerPlugin(Argument::type(CssInlinerPlugin::class))->shouldBeCalled();

        $this->mailerSlot->registerPlugin($mailer->reveal());
    }
}
