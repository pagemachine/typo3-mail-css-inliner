<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Functional;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for processing of Symfony Mail messages
 */
final class SymfonyMailMessageTest extends AbstractMailTest
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/mail_css_inliner',
    ];

    /**
     * @return void
     */
    protected function setUp()
    {
        if (is_subclass_of(MailMessage::class, \Swift_Message::class)) {
            $this->markTestSkipped('Not using Symfony Mail');
        }

        parent::setUp();
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
}
