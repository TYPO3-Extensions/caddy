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

require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_div.php'); // file for div functions

/**
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy_powermail
 * @version     2.0.0
 * @since       1.4.6
 */
class tx_caddy_powermail_146 extends tslib_pibase {

	/**
	 * Don't show powermail form if session is empty
	 *
	 * @param	string			$content: html content from powermail
	 * @param	array			$piVars: piVars from powermail
	 * @param	object			$pObj: piVars from powermail
	 * @return	void
	 */
	public function PM_MainContentAfterHook($content, $piVars, &$pObj) {
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
		$piVars = t3lib_div::_GP('tx_powermail_pi1');

		if ($piVars['mailID'] > 0 || $piVars['sendNow'] > 0)
		{
			return false; // stop
		}

		if ($conf['powermailContent.']['uid'] > 0 && intval($conf['powermailContent.']['uid']) == $pObj->cObj->data['uid'])
		{ // if powermail uid isset and fits to current CE
			$div = t3lib_div::makeInstance('tx_caddy_div'); // Create new instance for div functions
			$products = $div->getProductsFromSession(); // get products from session

			if (!is_array($products) || count($products) == 0)
			{ // if there are no products in the session
				$pObj->content = ''; // clear content
			}

			$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', 'caddy_cart_' . $GLOBALS["TSFE"]->id);
			$cartmin = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['cart.']['cartmin.'];
			if ((floatval($sesArray['productsGross']) < floatval($cartmin['value'])) && ($cartmin['hideifnotreached.']['powermail']))
			{
				$pObj->content = ''; // clear content
			}
		}
	}
	
	public function PM_MandatoryHookBefore($error, &$markerArray, &$sessionfields, &$obj)
	{
		$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', 'caddy_cart_' . $GLOBALS["TSFE"]->id);

		$min = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['cart.']['cartmin.']['value'];
		if (floatval($sesArray['productsGross']) < floatval($min))
		{
			$obj->error = 1;
			// ToDo: better error handling, localized and beautiful
			$sessionfields['ERROR']['cartmin'][] = 'ERROR: min cart not reached';
		}
	}

	public function PM_MandatoryHook($error, &$markerArray, &$innerMarkerArray, &$sessionfields, &$obj)
	{
		$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', 'caddy_cart_' . $GLOBALS["TSFE"]->id);

		// if ordernumber in session is empty, than create a new ordernumber; should be empty, when first product added to cart
		if (!isset($sesArray['ordernumber']) || !$sesArray['ordernumber'])
		{
			$registry =  t3lib_div::makeInstance('t3lib_Registry');
			$ordernumber =  $registry->get('tx_caddy', 'lastOrder_'.$GLOBALS["TSFE"]->id);
			if ($ordernumber)
			{
				$ordernumber += 1;
				$registry =  t3lib_div::makeInstance('t3lib_Registry');
				$registry->set('tx_caddy', 'lastOrder_'.$GLOBALS["TSFE"]->id,  $ordernumber);
			} else {
				$ordernumber = 1;
				$registry =  t3lib_div::makeInstance('t3lib_Registry');
				$registry->set('tx_caddy', 'lastOrder_'.$GLOBALS["TSFE"]->id,  $ordernumber);
			}
			$sesArray['ordernumber'] = $ordernumber;
		}

		$GLOBALS['TSFE']->fe_user->setKey('ses', 'caddy_cart_' . $GLOBALS["TSFE"]->id, $sesArray);
	}

	public function PM_SubmitEmailHook($subpart, &$maildata, &$sessiondata, &$markerArray, $obj)
	{
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['settings.']['overall.'];
		$ordernumber = $GLOBALS['TSFE']->cObj->cObjGetSingle($conf['ordernumber'], $conf['ordernumber.']);
		
		$maildata['subject'] = str_replace('###ORDERNUMBER###', $ordernumber, $maildata['subject']);
	}

  public function PM_SubmitEmailHook2( &$subpart, &$htmlMail, &$parent )
  {
    //ensure that both UIDxx and Name vals are replaced ....
    //$this->prepareMarkerArray($parent);
    //make mailer accessable
    $this->htmlMail     = $htmlMail;
    //make parent accessable
    $this->parent       = $parent;
    //make subpart accessable
    $this->subpart      = $subpart;

    $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', 'caddy_cart_' . $GLOBALS["TSFE"]->id);

    $conf               = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['settings.']['overall.'];
    $ordernumber        = $GLOBALS['TSFE']->cObj->cObjGetSingle($conf['ordernumber'], $conf['ordernumber.']);
    $packinglistnumber  = $GLOBALS['TSFE']->cObj->cObjGetSingle($conf['packinglistnumber'], $conf['packinglistnumber.']);

    // HOOK for ATTACHMENTS
    $checkError = 0;
    if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['caddy']['addAttachment'])) 
    {
      foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['caddy']['addAttachment'] as $userFunc) 
      {
        $params = array(
          'subpart'           => $subpart,
          'ordernumber'       => $ordernumber,
          'packinglistnumber' => $packinglistnumber
        );
        $checkError = $checkError + t3lib_div::callUserFunction($userFunc, $params, $sesArray);
      }
    }
    if ($checkError > 0) {
      // TODO: do some error handling
      return -1;
    }

    foreach( $sesArray['files'] as $filename => $filepath )
    {
      $this->attachFile( $filepath, $filename );
    }
  }


	/**
	 * Clear cart after submit
	 *
	 * @param	string			$content: html content from powermail
	 * @param	array			$conf: TypoScript from powermail
	 * @param	array			$session: Values in session
	 * @param	boolean			$ok: if captcha not failed
	 * @param	object			$pObj: Parent object
	 * @return	void
	 */
	public function PM_SubmitLastOneHook($content, $conf, $session, $ok, $pObj)
	{
		$piVars = t3lib_div::_GPmerged('tx_powermail_pi1');
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];

		if ($piVars['mailID'] == $conf['powermailContent.']['uid'])
		{ // current content uid fits to given uid in constants
			$div = t3lib_div::makeInstance('tx_caddy_div'); // Create new instance for div functions
			$products = $div->removeAllProductsFromSession(); // clear complete cart
		}
	}
	/**
	 * Add specified file in $path to the mail as $name    
	 *
	 * $path string path to the file
	 * $name string alias of the file in the mail               
	 */
	private function attachFile($path,$name) {
		//add file to mail
		if(is_a($this->htmlMail,'t3lib_mail_Message')) {
			//new way of adding a attachment
			$attachment = Swift_Attachment::newInstance();
			//use it with markers ;)
			$attachment->setFilename($name);
			$attachment->setBody(file_get_contents($path));
			$this->htmlMail->attach($attachment);
		} elseif(is_a($this->htmlMail,'t3lib_htmlmail')) {
			//old mail api as fallback
			#$this->htmlMail->addAttachment($this->outputFile);
			$this->htmlMail->theParts['attach'][] = array(
				'content'       => file_get_contents($path),
				'content_type'  => 'application/octet-stream',
				'filename'      => $name,
				);
		} elseif($this->htmlMail === null) {
			//do nothing if no mailobject is attached
		} else {
			throw new Exception('Unknown mail object type');
		}
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_powermail_146.php'])
{
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_powermail_146.php']);
}
?>
