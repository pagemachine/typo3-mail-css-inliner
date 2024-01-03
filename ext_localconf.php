<?php

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\FluidEmail::class]['className'] = \Pagemachine\MailCssInliner\Mail\FluidEmail::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\Mailer::class]['className'] = \Pagemachine\MailCssInliner\Mail\Mailer::class;
