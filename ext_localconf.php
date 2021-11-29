<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\Mailer::class]['className'] = \Pagemachine\MailCssInliner\Mail\Mailer::class;
