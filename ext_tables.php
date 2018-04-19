<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {

    $boot = function () {

        $icons = [
            'ext-cookie-redirect-wizard-icon' => 'plugin_wizard.svg',
        ];
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        foreach ($icons as $identifier => $path) {
            $iconRegistry->registerIcon(
                $identifier,
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:cookie_redirect/Resources/Public/Icons/' . $path]
            );
        }
    };

    $boot();
    unset($boot);

}