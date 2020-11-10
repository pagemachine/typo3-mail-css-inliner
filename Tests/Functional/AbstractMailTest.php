<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Functional;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use Bnf\Typo3HttpFactory\ResponseFactory;
use Bnf\Typo3HttpFactory\StreamFactory;
use Http\Client\Curl\Client as HttpClient;
use Http\Message\Guzzle\RequestFactory;
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
    protected function setUp()
    {
        parent::setUp();

        $this->purgeMailMessages();
    }

    protected function assertLastMessageBodyContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        $this->assertContains($substring, $messageBody);
    }

    protected function assertLastMessageBodyNotContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        $this->assertNotContains($substring, $messageBody);
    }

    protected function purgeMailMessages(): void
    {
        $this->getMailHogClient()->purgeMessages();
    }

    protected function getMailHogClient(): MailhogClient
    {
        $mailHogClient = new MailhogClient(
            new HttpClient(
                new ResponseFactory(),
                new StreamFactory()
            ),
            new RequestFactory(),
            'http://mail:8025'
        );

        return $mailHogClient;
    }
}
