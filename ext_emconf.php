<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Mail CSS Inliner',
    'description' => 'Inlines CSS in all outgoing TYPO3 mails.',
    'category' => 'misc',
    'author' => 'Mathias Brodala',
    'author_email' => 'mbrodala@pagemachine.de',
    'author_company' => 'Pagemachine AG',
    'state' => 'stable',
    'version' => '1.0.3',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.99.99',
            'typo3' => '7.6.0-8.7.99',
        ],
    ],
];
