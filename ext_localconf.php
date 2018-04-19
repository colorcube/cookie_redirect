<?php
defined('TYPO3_MODE') or die();

$boot = function () {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
        'cookie_redirect', 'Classes/Controller/Plugin.php', '_pi1', 'list_type', 0);


    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:cookie_redirect/Configuration/TSconfig/ContentElementWizard.t3s">');

};

$boot();
unset($boot);