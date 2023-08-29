<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Mail CSS Inliner',
    'description' => 'Inlines CSS in all outgoing TYPO3 mails.',
    'category' => 'misc',
    'author' => 'Mathias Brodala',
    'author_email' => 'mbrodala@pagemachine.de',
    'author_company' => 'Pagemachine AG',
    'state' => 'stable',
    'version' => '3.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
        ],
    ],
];
