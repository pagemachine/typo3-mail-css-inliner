<?php
defined('TYPO3_MODE') or die();

(function () {
    if (version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version(), '10', '>=')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\Mailer::class]['className'] = \Pagemachine\MailCssInliner\Mail\Mailer::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\FluidEmail::class]['className'] = \Pagemachine\MailCssInliner\Mail\FluidEmail::class;
    } else {
        /** @var TYPO3\CMS\Extbase\SignalSlot\Dispatcher */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        $signalSlotDispatcher->connect(
            \TYPO3\CMS\Core\Mail\Mailer::class,
            'postInitializeMailer',
            \Pagemachine\MailCssInliner\Slots\MailerSlot::class,
            'registerPlugin'
        );
    }
})();
