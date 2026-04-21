<?php

declare(strict_types=1);

namespace Pagemachine\MailCssInliner\Tests\Functional;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */
use Http\Client\Curl\Client as HttpCurlClient;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use PHPUnit\Framework\Attributes\Test;
use rpkamp\Mailhog\MailhogClient;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Testcase for processing of Symfony Mail messages
 */
final class SymfonyMailMessageTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/mail_css_inliner',
    ];

    protected array $configurationToUseInTestInstance = [
        'MAIL' => [
            'transport' => 'smtp',
            'transport_smtp_server' => 'mail:1025',
            'templateRootPaths' => [
                101 => 'EXT:mail_css_inliner/Tests/Functional/Fixtures/Templates/Email',
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->purgeMailMessages();
    }

    #[Test]
    public function injectsInlineStylesIntoMailMessage(): void
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

        $mail = GeneralUtility::makeInstance(MailMessage::class)
            ->subject('Mail CSS Inliner Test')
            ->to('test@example.org')
            ->html($htmlBody);
        $this->get(MailerInterface::class)->send($mail);

        $expectedSubstring = <<<HTML
<p style="color: red;">Test</p>
HTML
        ;

        $this->assertLastMessageBodyContains($expectedSubstring);
    }

    #[Test]
    public function injectsInlineStylesIntoFluidEmail(): void
    {
        $mail = GeneralUtility::makeInstance(FluidEmail::class)
            ->subject('Mail CSS Inliner Test')
            ->to('test@example.org')
            ->setTemplate('InlineStyles')
            ->assign('content', 'Test')
        ;
        $this->get(MailerInterface::class)->send($mail);

        $expectedSubstring = <<<HTML
<p style="color: red;">Test</p>
HTML
        ;

        $this->assertLastMessageBodyContains($expectedSubstring);
    }

    #[Test]
    public function skipsMailWithoutHtmlBody(): void
    {
        $mail = GeneralUtility::makeInstance(MailMessage::class)
            ->subject('Mail CSS Inliner Test')
            ->to('test@example.org')
            ->text('Test');
        $this->get(MailerInterface::class)->send($mail);

        $this->assertLastMessageBodyNotContains('<');
    }

    protected function assertLastMessageBodyContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        self::assertStringContainsString($substring, $messageBody);
    }

    protected function assertLastMessageBodyNotContains(string $substring)
    {
        $messageBody = $this->getMailHogClient()->getLastMessage()->body;

        self::assertStringNotContainsString($substring, $messageBody);
    }

    protected function purgeMailMessages(): void
    {
        $this->getMailHogClient()->purgeMessages();
    }

    protected function getMailHogClient(): MailhogClient
    {
        $mailHogClient = new MailhogClient(
            new HttpCurlClient(),
            new RequestFactory(),
            new StreamFactory(),
            'http://mail:8025'
        );

        return $mailHogClient;
    }
}
