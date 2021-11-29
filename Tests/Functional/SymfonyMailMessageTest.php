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
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for processing of Symfony Mail messages
 */
final class SymfonyMailMessageTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/mail_css_inliner',
    ];
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

    /**
     * @test
     */
    public function injectsInlineStyles(): void
    {
        $htmlBody = <<<HTML
<html>
    <head>
        <title></title>
        <style>
            p {
                color:red
            }
        </style>
    </head>
    <body>
        <p>Test</p>
    </body>
</html>
HTML
        ;
        GeneralUtility::makeInstance(MailMessage::class)
            ->subject('Mail CSS Inliner Test')
            ->to('test@example.org')
            ->html($htmlBody)
            ->send();

        $expectedSubstring = <<<HTML
<p style="color: red;">Test</p>
HTML
        ;

        $this->assertLastMessageBodyContains($expectedSubstring);
    }

    /**
     * @test
     */
    public function skipsMailWithoutHtmlBody(): void
    {
        GeneralUtility::makeInstance(MailMessage::class)
            ->subject('Mail CSS Inliner Test')
            ->to('test@example.org')
            ->text('Test')
            ->send();

        $this->assertLastMessageBodyNotContains('<');
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
