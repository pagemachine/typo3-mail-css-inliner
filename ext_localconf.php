<?php

use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][FluidEmail::class]['className'] = \Pagemachine\MailCssInliner\Mail\FluidEmail::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][Mailer::class]['className'] = \Pagemachine\MailCssInliner\Mail\Mailer::class;
