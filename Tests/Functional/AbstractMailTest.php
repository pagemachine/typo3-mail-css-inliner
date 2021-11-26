<?php

declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Functional;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Http\Client\Curl\Client as HttpCurlClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use rpkamp\Mailhog\MailhogClient;

abstract class AbstractMailTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $configurationToUseInTestInstance = [
        'MAIL' => [
            'transport' => 'smtp',
            'transport_smtp_server' => 'mail:1025',
        ],
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->purgeMailMessages();
    }

    protected function assertLastMessageBodyContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        $this->assertStringContainsString($substring, $messageBody);
    }

    protected function assertLastMessageBodyNotContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        $this->assertStringNotContainsString($substring, $messageBody);
    }

    protected function purgeMailMessages(): void
    {
        $this->getMailHogClient()->purgeMessages();
    }

    protected function getMailHogClient(): MailhogClient
    {
        $mailHogClient = new MailhogClient(
            new HttpCurlClient(),
            new GuzzleMessageFactory(),
            'http://mail:8025'
        );

        return $mailHogClient;
    }
}
