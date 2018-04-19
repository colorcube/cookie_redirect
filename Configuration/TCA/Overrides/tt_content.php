<?php
defined('TYPO3_MODE') || die();

/**
 * Register Plugin and flexform
 */


$pluginSignature = 'cookie_redirect_pi1';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
    'LLL:EXT:cookie_redirect/Resources/Private/Language/locallang.xlf:tt_content.list_type_pi1',
    $pluginSignature,
    'EXT:cookie_redirect/ext_icon.png'
),'list_type', 'cookie_redirect');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature,
    'FILE:EXT:cookie_redirect/Configuration/FlexForms/PluginFlexform.xml');