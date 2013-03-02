<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  Caddy is a fork of wt_cart (version 1.4.6)
 *  (c) wt_cart 2010-2012 - wt_cart Development Team <info@wt-cart.com>
 * 
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_session.php'); // file for div functions
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_dynamicmarkers.php'); // file for dynamicmarker functions

/**
 * Plugin 'Form to Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       1.4.6
 */
class tx_caddy_pi2 extends tslib_pibase
{
	public $prefixId = 'tx_caddy_pi2';

	// same as class name
	public $scriptRelPath = 'pi2/class.tx_caddy_pi2.php';

	// path to this script relative to the extension dir.
	public $extKey = 'caddy';

	/**
	* the main method of the PlugIn
	*
	* @param string    $content: The PlugIn content
	* @param array   $conf: The PlugIn configuration
	* @return  The content that is displayed on the website
	*/	
	public function main($content, $conf)
	{
		// config
		global $TSFE;
		$local_cObj = $TSFE->cObj; // cObject
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->session = t3lib_div::makeInstance('tx_caddy_session'); // Create new instance for div functions
                $this->session->pObj    = $this;
		$this->dynamicMarkers = t3lib_div::makeInstance('tx_caddy_dynamicmarkers', $this->scriptRelPath); // Create new instance for dynamicmarker function
		$this->tmpl['form'] = $this->cObj->getSubpart($this->cObj->fileResource($this->conf['main.']['template']), '###CADDY_FORM###'); // Load FORM HTML Template

		// build product from FlexForm
		$product = array();
		$this->pi_initPIflexForm();
		foreach ($this->conf['flexfields.'] as $key => $val)
		{
			// get all product data which are not attributes
			if (!stristr($key, '.'))
			{
				$product[$val] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $val);
			}
			// get attribute data
			else
			{
				$rows = explode("\n", $this->pi_getFFvalue($this->cObj->data['pi_flexform'], rtrim($key, '.')));
				$attributes = array();
				foreach ($rows as $rowval)
				{
					$rowArr = explode('==', $rowval);
					$attributes[$rowArr[0]] = $rowArr[1];
				}
				foreach ($this->conf['flexfields.'][$key] as $subkey => $subval)
				{
					if (!stristr($subkey, '.'))
					{
						$product[$subval] = $attributes[$subkey];
					}
				}
			}
		}
		$local_cObj->start($product, $this->conf['flexfields.']);
		$local_cObj->start($product, $this->conf['flexfields.']['attributes.']);

		$formMarkerArray = array();

		// get marker for all fields defined in plugin pi from caddy
		$conf_pi1 = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
		$conf_pi1_fields = $conf_pi1['settings.']['powermailCart.']['fields.'];
		foreach ((array) $conf_pi1_fields as $key => $value)
		{
			if (!stristr($key, '.'))
			{ 
				$productOut[$key] = $local_cObj->cObjGetSingle($conf_pi1_fields[$key], $conf_pi1_fields[$key . '.']); // write to marker
				$formMarkerArray['###' . strtoupper($key) . '###'] = $productOut[$key]; // write to marker
			}
		}

		$formMarkerArray['###CADDY_FORM_ACTION_PID###'] = $this->conf['main.']['pid'];
		$formMarkerArray['###CADDY_FORM_ACTION###'] = $this->pi_getPageLink($this->conf['main.']['pid']);
		$formMarkerArray['###CADDY_FORM_CONTENTUID###'] = $this->cObj->data['uid'];

		$this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['form'], null, $formMarkerArray); // Get html template
		$this->content = $this->dynamicMarkers->main($this->content, $this); // Fill dynamic locallang or typoscript markers
		$this->content = preg_replace('|###.*?###|i', '', $this->content); // Finally clear not filled markers
		return $this->pi_wrapInBaseClass($this->content);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi2.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi2.php']);
}
?>