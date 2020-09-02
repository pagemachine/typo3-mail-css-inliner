<?php
declare(strict_types = 1);

namespace Pagemachine\MailCssInliner\Tests\Functional;

/*
 * This file is part of the Pagemachine Mail CSS Inliner project.
 */

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for mail processing
 */
final class MailTest extends AbstractMailTest
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/mail_css_inliner',
    ];

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
            ->setSubject('Mail CSS Inliner Test')
            ->setTo('test@example.org')
            ->setBody($htmlBody, 'text/html')
            ->send();

        $expectedSubstring = <<<HTML
<p style="color: red;">Test</p>
HTML
        ;

        $this->assertLastMessageBodyContains($expectedSubstring);
    }
}
