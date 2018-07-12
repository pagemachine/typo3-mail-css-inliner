<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    /** @var TYPO3\CMS\Extbase\SignalSlot\Dispatcher */
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Core\Mail\Mailer::class,
        'postInitializeMailer',
        \Pagemachine\MailCssInliner\Slots\MailerSlot::class,
        'registerPlugin'
    );
});
