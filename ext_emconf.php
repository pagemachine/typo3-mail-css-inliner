<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Mail CSS Inliner',
    'description' => 'Inlines CSS in all outgoing TYPO3 mails.',
    'category' => 'misc',
    'author' => 'Mathias Brodala',
    'author_email' => 'mbrodala@pagemachine.de',
    'author_company' => 'Pagemachine AG',
    'state' => 'stable',
    'version' => '3.1.1',
    'constraints' => [
        'depends' => [
            'php' => '8.0.0-8.99.99',
            'typo3' => '11.5.0-12.4.99',
        ],
    ],
];
