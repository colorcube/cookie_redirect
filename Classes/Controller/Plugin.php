<?php
namespace Colorcube\CookieRedirect\Controller;


/**
 * This file is part of the "cookie_redirect" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;


/**
 * Plugin for the 'cookie_redirect' extension.
 *
 * @author René Fritz <r.fritz@colorcube.de>
 */
class Plugin {

    /**
     * The current cObject
     *
     * Note: This must be public cause it is set by ContentObjectRenderer::callUserFunction()
     *
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    public $cObj;


    /**
     * @var array TypoScript configuration array
     */
    protected $conf = [];

    protected $defaultCookiePrefix = 'tx_cookieredirect_pi1';


	function main($content, $conf) 
    {
        $this->conf = $conf;

		$this->pi_initPIflexForm ();

        $cookieName = $this->getConfig('cookiePrefix', 'cookiePrefix', $this->defaultCookiePrefix);
        $cookieName = $cookieName.'-'.$GLOBALS['TSFE']->id;

        // get cookie end date from Flexform data
        $cookieLifetime = (int)($this->getConfig('', 'cookie_lifetime'));
        $cookieLifetime = time()<$cookieLifetime ? $cookieLifetime : 0;
        // set cookie end date from TS if not already set
        $cookieLifetime = $cookieLifetime ? $cookieLifetime : time()+(int)($this->getConfig('cookieLifetime', '', 40000000));


        $redirectPID = false;
        if($_COOKIE[$cookieName]) {
            // cookie is already set - get the default redirect
            $redirectPID = (int)($this->getConfig('defaultPID', 'shortcut_default'));
        } else {
            // first visit - get the redirect PID and set cookie
            $redirectPID = (int)($this->getConfig('shortcutPID', 'shortcut'));
            if ($redirectPID) {
                setcookie($cookieName, true, $cookieLifetime, '/');
            }
        }

        // fetch redirect page from configured PID - and finally: redirect
        if ($redirectPID) {
            $page = $GLOBALS['TSFE']->getPageShortcut($redirectPID, '', $GLOBALS['TSFE']->id);
            $redirectUrl = $this->cObj->getTypoLink_URL($page['uid']);
            header('Location: '.GeneralUtility::locationHeaderUrl($redirectUrl));
            exit;
        }

        // no redirect PID - do nothing
	}




    /*******************************
     *
     * FlexForms related functions
     *
     *******************************/


    /**
     * Get configuration values from TypoScript and Flexform data.
     *
     * @param	string		TypoScript key to get a value from ($this->conf[$tsKey]') with stdWrap
     * @param	string		Flexform data key
     * @param	string		Default value which will be used if no value was found
     * @return	string		configuration value
     */
    private function getConfig($tsKey, $ffKey, $default='') {
        list ($ffKey, $ffSheet) = explode(':', $ffKey);
        if($ffKey AND $this->cObj->data['pi_flexform']) {
            $config = (string)$this->pi_getFFvalue($this->cObj->data['pi_flexform'], $ffKey, ($ffSheet?$ffSheet:'sDEF'));
        }
        if($tsKey AND $config=='') {
            $config = $this->cObj->stdWrap($this->conf[$tsKey], $this->conf[$tsKey.'.']);
        }
        $config = $config!='' ? $config : $default;
        return $config;
    }


    /**
     * Converts $this->cObj->data['pi_flexform'] from XML string to flexForm array.
     *
     * @param string $field Field name to convert
     */
    public function pi_initPIflexForm($field = 'pi_flexform')
    {
        // Converting flexform data into array:
        if (!is_array($this->cObj->data[$field]) && $this->cObj->data[$field]) {
            $this->cObj->data[$field] = GeneralUtility::xml2array($this->cObj->data[$field]);
            if (!is_array($this->cObj->data[$field])) {
                $this->cObj->data[$field] = [];
            }
        }
    }

    /**
     * Return value from somewhere inside a FlexForm structure
     *
     * @param array $T3FlexForm_array FlexForm data
     * @param string $fieldName Field name to extract. Can be given like "test/el/2/test/el/field_templateObject" where each part will dig a level deeper in the FlexForm data.
     * @param string $sheet Sheet pointer, eg. "sDEF
     * @param string $lang Language pointer, eg. "lDEF
     * @param string $value Value pointer, eg. "vDEF
     * @return string|NULL The content.
     */
    public function pi_getFFvalue($T3FlexForm_array, $fieldName, $sheet = 'sDEF', $lang = 'lDEF', $value = 'vDEF')
    {
        $sheetArray = is_array($T3FlexForm_array) ? $T3FlexForm_array['data'][$sheet][$lang] : '';
        if (is_array($sheetArray)) {
            return $this->pi_getFFvalueFromSheetArray($sheetArray, explode('/', $fieldName), $value);
        }
        return null;
    }

    /**
     * Returns part of $sheetArray pointed to by the keys in $fieldNameArray
     *
     * @param array $sheetArray Multidimensiona array, typically FlexForm contents
     * @param array $fieldNameArr Array where each value points to a key in the FlexForms content - the input array will have the value returned pointed to by these keys. All integer keys will not take their integer counterparts, but rather traverse the current position in the array an return element number X (whether this is right behavior is not settled yet...)
     * @param string $value Value for outermost key, typ. "vDEF" depending on language.
     * @return mixed The value, typ. string.
     * @access private
     * @see pi_getFFvalue()
     */
    public function pi_getFFvalueFromSheetArray($sheetArray, $fieldNameArr, $value)
    {
        $tempArr = $sheetArray;
        foreach ($fieldNameArr as $k => $v) {
            if (MathUtility::canBeInterpretedAsInteger($v)) {
                if (is_array($tempArr)) {
                    $c = 0;
                    foreach ($tempArr as $values) {
                        if ($c == $v) {
                            $tempArr = $values;
                            break;
                        }
                        $c++;
                    }
                }
            } else {
                $tempArr = $tempArr[$v];
            }
        }
        return $tempArr[$value];
    }
}
