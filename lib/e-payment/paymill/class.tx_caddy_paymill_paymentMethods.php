<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
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

require_once( PATH_tslib . 'class.tslib_pibase.php' );

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  103: class tx_caddy_paymill_paymentMethods extends tslib_pibase
 *
 *              SECTION: Main
 *  149:     public function main( $paymentId=null, $pid=null )
 *
 *              SECTION: Init
 *  242:     private function init( $paymentId=null, $pid=null )
 *  258:     private function initDrs( )
 *  271:     private function initInstances( )
 *  286:     private function initInstancesDrs( )
 *  304:     private function initInstancesDynamicMarkers( )
 *  323:     private function initInstancesPaymillLib( )
 *  345:     private function initVars( $paymentId, $pid )
 *  368:     private function initVarsPid( $pid=null )
 *
 *              SECTION: Payment Methods
 *  411:     private function paymentMethods( )
 *  474:     private function paymentMethodsCreditcard( )
 *  503:     private function paymentMethodsElv( )
 *  525:     private function paymentMethodsElvIban( )
 *  549:     private function paymentMethodsUndefined( )
 *  563:     private function paymentWiPowermail( )
 *
 *              SECTION: Powermail
 *  584:     private function powermailInAction( )
 *
 *              SECTION: Requirements
 *  621:     private function requirements( )
 *  647:     private function requirementsDie( )
 *  667:     private function requirementsToken( )
 *  701:     private function requirementsWiPowermail( )
 *
 *              SECTION: Prompts
 *  732:     private function serverPrompt( )
 *
 *              SECTION: Session
 *  774:     private function session( )
 *  800:     private function sessionDataAdd( )
 *  825:     private function sessionDataRemove( $force=false )
 *
 *              SECTION: Setting methods
 *  884:     public function setParentObject( $pObj )
 *
 *              SECTION: Template
 *  933:     private function template( $subpart )
 * 1026:     private function templateMode( )
 * 1052:     private function templateModeLive( )
 * 1066:     private function templateModeTest( )
 * 1090:     private function templateModeTestData( )
 * 1117:     private function templateModeTestDataCreditcard( )
 * 1138:     private function templateModeTestDataElv( )
 * 1157:     private function templateModeTestDataElvIban( )
 * 1176:     private function templateModeTestDataWiPowermail( )
 * 1190:     private function templateSectionActive( $template )
 *
 * TOTAL FUNCTIONS: 35
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Get the HTML template with the payment methods
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @internal    #53678
 * @version     4.0.6
 * @since       4.0.6
 */
class tx_caddy_paymill_paymentMethods extends tslib_pibase
{
    public $extKey        = 'caddy';
    public $prefixId      = 'tx_caddy_paymill_paymentMethods';
    public $scriptRelPath = 'lib/e-payment/paymill/class.tx_caddy_paymill_paymentMethods.php';

    public  $conf           = null;       // array    : current TypoScript configuration
    private $content        = null;       // string   : content. Will returned by main( )
    public  $drs            = null;       // object   : instance of drs class.
    private $dynamicMarkers = null;       // object   : instance of dynamicMarkers class.
    public  $local_cObj     = null;
    private $paymentId      = null;       // integer  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
    private $pObj           = null;       // object   : parent object
    private $pid            = null;       // integer  : pid of the current page
    private $prompts        = array( );   // array    : prompts

    private $paymillClientResponse  = null;       // object   : Paymill payment response object
    private $paymillPaymentResponse = null;       // object   : Paymill payment response object

    private $transactionAmount      = '0.00';
    private $transactionCurrency    = 'EUR';
    private $transactionDescription = 'Transaction by TYPO3 Caddy';
    private $transactionClientEmail = 'default@typo3-caddy.de';
    private $transactionClientName  = 'TYPO3 Caddy';






  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * main( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @param	integer		$pid        : ...
  * @return	array		$payment    : true, if payment was successful
  * @access public
  * @version     4.0.6
  * @since       4.0.6
  */
  public function main( $paymentId=null, $pid=null )
  {
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( );
//$prompt = str_replace( '//' , PHP_EOL . '//' , $prompt );
//var_dump( __METHOD__, __LINE__, $prompt);

    $arrReturn = array(
      'content' => null,
      'payment' => false
    );

    $this->init( $paymentId, $pid );

    $this->requirementsDie( );

    $this->content = $this->template( '###CONTENT###' );
    $this->content = $this->templateSectionRemove( $this->content );
    $this->content = $this->templateSectionActive( $this->content );

    $this->prompts = $this->paymillLib->paymillInit( );
    if( $this->prompts )
    {
//var_dump( __METHOD__, __LINE__, $this->prompts );
      $this->sessionDataRemove( );
      $this->serverPrompt( );
      $this->templateMode( );
      $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
      $this->content = $this->dynamicMarkers->main( $this->content, $this );

      $arrReturn = array(
        'content' => $this->content,
        'payment' => false
      );
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
      return $arrReturn;
    }

    $this->templateMode( );


    if( ! $this->requirements( ) )
    {
      $this->sessionDataRemove( );
      $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
      $this->content = $this->dynamicMarkers->main( $this->content, $this );
      $arrReturn = array(
        'content' => $this->content,
        'payment' => false
      );
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
      return $arrReturn;
    }

      // Insert payment data into content
    $payment = $this->paymentMethods( );
      // Insert prompt into content
    $this->serverPrompt( );
      // Insert
    $this->session( );

    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
    $this->content = $this->dynamicMarkers->main( $this->content, $this );

    $arrReturn = array(
      'content' => $this->content,
      'payment' => $payment
    );
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    return $arrReturn;
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @param	[type]		$pid: ...
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function init( $paymentId=null, $pid=null )
  {
    $this->initVars( $paymentId, $pid );
    $this->initInstances( );
    $this->initDrs( );
  }


 /**
  * initDrs( )
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function initDrs( )
  {
    $this->drs->init( );
  }

 /**
  * initInstances( ):
  *
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function initInstances( )
  {
    $this->initInstancesDrs( );
    $this->initInstancesDynamicMarkers( );
    $this->initInstancesPaymillLib( );
  }

 /**
  * initInstancesDrs( ):
  *
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function initInstancesDrs( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;
  }

 /**
  * initInstancesDynamicMarkers( ):
  *
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function initInstancesDynamicMarkers( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );
    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
  }

 /**
  * initInstancesPaymillLib( ) :  Requires and initiate the class of the current e-payment provider.
  *                               If no e-payment provider is enabled, nothing will happen.
  *
  * @return	void
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.6
  */
  private function initInstancesPaymillLib( )
  {
    $path2paymillLib  = t3lib_extMgm::extPath( 'caddy' ) . 'lib/e-payment/paymill/class.tx_caddy_paymill_lib.php';

      // Initiate the provider class
    require_once( $path2paymillLib );
    $this->paymillLib = t3lib_div::makeInstance( 'tx_caddy_paymill_lib' );
    $this->paymillLib->setParentObject( $this               );
    $this->paymillLib->setPiVars(       $this->pObj->piVars );
    $this->paymillLib->setPid(          $this->pid    );
  }

 /**
  * initVars( )
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @param	[type]		$pid: ...
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function initVars( $paymentId, $pid )
  {
    $this->conf         = $this->pObj->conf;
    $this->local_cObj   = $GLOBALS['TSFE']->cObj;
    $this->paymentId    = $paymentId;



    $this->initVarsPid( $pid );
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    //$this->pi_initPIflexForm( );
  }

/**
 * initPid( )  : Returns the globlas tsfe id, if the given pid is null
 *
 * @param	integer		$pid  : given pid (may be null)
 * @return	integer		$pid  : id of the page with the caddy plugin
 * @internal    #54634
 * @version     4.0.3
 * @since       4.0.6
 */
  private function initVarsPid( $pid=null )
  {
    if( $pid !== null )
    {
      $this->pid = $pid;
      return;
    }

    if( ( int ) $this->conf['userFunc.']['caddyPid'] )
    {
      $pid = ( int ) $this->conf['userFunc.']['caddyPid'];
    }

    if( $pid === null || $pid === '' )
    {
      if( $this->drs->drsError || $this->drsUserfunc )
      {
        $prompt = 'Given pid of the Caddy is empty!';
        t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
      }
      $pid = $GLOBALS["TSFE"]->id;
    }

    $this->pid = $pid;
  }



  /***********************************************
  *
  * Payment Methods
  *
  **********************************************/

 /**
  * paymentMethods( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	boolean		$paymill  : true, if paymill was successful
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentMethods( )
  {
    $paymill            = false;
    $followTheWorkflow  = false;

    switch( true )
    {
      case( $this->paymillLib->getPaymillToken( ) ):
      case( $this->paymentWiPowermail( ) ):
//var_dump( __METHOD__, __LINE__ );
        $followTheWorkflow = true;
        break;
      default:
//var_dump( __METHOD__, __LINE__ );
        $followTheWorkflow = false;
        break;
    }
    if( ! $followTheWorkflow )
    {
//var_dump( __METHOD__, __LINE__ );
      $paymill = false;
      return $paymill;
    }

//    $errors = $this->paymillLib->paymillInit( );
//    if( $errors )
//    {
//var_dump( __METHOD__, __LINE__, $errors );
//      $this->sessionDataRemove( );
//      $paymill = false;
//      return $paymill;
//    }

    switch( $this->paymentId )
    {
      case( 4 ):
        $this->paymentMethodsCreditcard( );
        break;
      case( 5 ):
        $this->paymentMethodsElv( );
        break;
      case( 6 ):
        $this->paymentMethodsElvIban( );
        break;
      default:
        $this->paymentMethodsUndefined( );
        break;
    }

//var_dump( __METHOD__, __LINE__ );
    $paymill = true;
    return $paymill;
  }

 /**
  * paymentMethodsCreditcard( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentMethodsCreditcard( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-creditcard-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-creditcard' );
//    var_dump( __FILE__, __LINE__,
//            $this->paymillLib->paymillPaymentResponse
////            $this->paymillLib->paymillPaymentResponse->getCode( ),
////            $this->paymillLib->paymillPaymentResponse->getAccount( ),
////            $this->paymillLib->paymillPaymentResponse->getHolder( )
//            );
    $expiry = $this->paymillLib->getCardMonth( ) . '/' . $this->paymillLib->getCardYear( );
    $marker['###VALUE_CREDITCARD-NUMBER###']  = '**** **** **** ' . $this->paymillLib->getCardNumber( );
    $marker['###VALUE_CREDITCARD-EXPIRY###']  = $expiry;
    $marker['###VALUE_CREDITCARD-HOLDER###']  = $this->paymillLib->getCardHolder( );
    $marker['###VALUE_CREDITCARD-CVC###']     = '****';

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentMethodsElv( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentMethodsElv( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-elv-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-elv' );

    $marker['###VALUE_ELV-ACCOUNT###']  = $this->paymillLib->getBankAccount( );
    $marker['###VALUE_ELV-BANKCODE###'] = $this->paymillLib->getBankCode( );
    $marker['###VALUE_ELV-HOLDER###']   = $this->paymillLib->getBankHolder( );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentMethodsElvIban( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentMethodsElvIban( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-elv-iban-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-elv-iban' );

    //$iban = '****' . $this->paymillLib->paymillPaymentResponse->getCode( ) . $this->paymillLib->paymillPaymentResponse->getAccount( );
    $iban = '****' . $this->paymillLib->getBankCode( ) . $this->paymillLib->getBankAccount( );
    $marker['###VALUE_ELV-IBAN###']         = $iban;
    $marker['###VALUE_ELV-BIC###']          = '**********';
    $marker['###VALUE_ELV-IBAN-HOLDER###']  = $this->paymillLib->getBankHolder( );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentMethodsUndefined( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentMethodsUndefined( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|'     . $this->pi_getLL( 'prompt-paywith-undefined' );
  }

 /**
  * paymentWiPowermail( ):
  *
  * @return	boolean
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function paymentWiPowermail( )
  {
    return $this->powermailInAction( );
  }



  /***********************************************
  *
  * Powermail
  *
  **********************************************/

 /**
  * powermailInAction( ):
  *
  * @return	boolean
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function powermailInAction( )
  {
    $GP = t3lib_div::_GET( )
        + t3lib_div::_POST( )
        ;

    switch( true )
    {
      case( $GP['tx_powermail_pi1']['action'] == 'confirmation' ):
      case( $GP['tx_powermail_pi1']['action'] == 'create' ):
      case( $GP['tx_powermail_pi1']['action'] == 'form' ):
        return true;
        break;
      default:
        return false;
        break;
    }

    unset( $GP );
  }



  /***********************************************
  *
  * Requirements
  *
  **********************************************/

 /**
  * requirements( ):
  *
  * @return	boolean
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function requirements( )
  {
    $requirementsMatched = false;

    switch( true )
    {
      case( $this->requirementsToken( ) ):
      case( $this->requirementsWiPowermail( ) ):
        $requirementsMatched = true;
        break;
      default:
        $requirementsMatched = false;
        break;
    }

    return $requirementsMatched;
  }

 /**
  * requirementsDie( ) :
  *
  * @return	void
  * @access public
  * @version     4.0.6
  * @since       4.0.6
  */
  private function requirementsDie( )
  {
    if( ! is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no parent object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
  }

 /**
  * requirementsToken( ): Returns false, if token isn't part of the URL params
  *
  * @return	boolen
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function requirementsToken( )
  {
//      // Token is proper
//    if( $this->paymillLib->paymillTokenByPivar( ) )
//    {
//      return true;
//    }
//
//      // Token isn't set, e-payment form is called the first time
//    if( ! $this->paymillLib->paymillTokenByPivar( ) )
//    {
//      return false;
//    }

    if( $this->paymillLib->getPaymillToken( ) )
    {
      return true;
    }

    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|alert|' . $this->pi_getLL( 'prompt-token-error' );
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART||' . $this->pi_getLL( 'prompt-token-info1' );
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART||' . $this->pi_getLL( 'prompt-token-info2' );

    return false;
  }

 /**
  * requirementsWiPowermail( ) :
  *
  * @return	boolean
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function requirementsWiPowermail( )
  {
    return $this->powermailInAction( );
  }



  /***********************************************
  *
  * Prompts
  *
  **********************************************/

 /**
  * serverPrompt( ):  Prompts the items of $prompts below the submit buttons.
  *                   An item has the format: marker|type|prompt
  *                   * marker  :
  *                               * SERVER_PROMPT_WICLOSE_SUBPART
  *                               * SERVER_PROMPT_WOCLOSE_SUBPART
  *                   * type    :
  *                               * secondary (grey)
  *                               * [empty!]  (blue)
  *                               * success   (green)
  *                               * error     (red)
  *                   Example: SERVER_PROMPT_WOCLOSE_SUBPART|alert|message
  *
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function serverPrompt( )
  {

    $marker         = array( );
    $server_prompt  = null;

    if( empty( $this->prompts ) )
    {
      return;
    }

    foreach( $this->prompts as $prompt )
    {
      list( $subpartMarker, $marker['###TYPE###'], $marker['###PROMPT###'] ) = explode( '|', $prompt );
      $subpart = $this->template( '###' . $subpartMarker . '###' );
      $subpart = $this->pObj->cObj->substituteMarkerArray( $subpart, $marker );
      $server_prompt  = $server_prompt
                      . $subpart;
    }

    unset( $marker );
    $marker['###SERVER_PROMPT###'] = $server_prompt;
    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }



  /***********************************************
  *
  * Session
  *
  **********************************************/

 /**
  * session( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function session( )
  {

    switch( true )
    {
      case( $this->paymillLib->getPaymillToken( ) ):
//var_dump( __METHOD__, __LINE__ );
        $this->sessionDataAdd( );
        break;
      case( ! $this->paymillLib->getPaymillToken( ) ):
      default:
var_dump( __METHOD__, __LINE__ );
        $this->sessionDataRemove( );
        break;
    }
  }

 /**
  * sessionDataAdd( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function sessionDataAdd( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );

    unset( $sesArray['e-payment']['paymill'] );

    $sesArray['e-payment']['paymill']['token']          = $this->paymillLib->getPaymillToken( );
    $sesArray['e-payment']['paymill']['client']['id']   = $this->paymillLib->getPaymillClientId( );
    $sesArray['e-payment']['paymill']['payment']['id']  = $this->paymillLib->getPaymillPaymentId( );

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
//    var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
  }

 /**
  * sessionDataRemove( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function sessionDataRemove( $force=false )
  {
    $sessionDataRemove = false;

    switch( true )
    {
      case( $force ):
        $sessionDataRemove = true;
        break;
      case( $this->requirementsToken( ) ):
      case( $this->requirementsWiPowermail( ) ):
        $sessionDataRemove = false;
        break;
      default:
        $sessionDataRemove = true;
        break;
    }

    unset( $force );

    if( ! $sessionDataRemove )
    {
      return;
    }
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( );
//var_dump( __METHOD__, __LINE__, $prompt);

    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
      // :TODO: Recalculte option costs (option costs without payment)
      // Remove paymill data
    unset( $sesArray['e-payment']['paymill'] );
      // Remove payment id
    unset( $sesArray['payment'] );
      // Remove options payment
    unset( $sesArray['options']['payment'] );

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
//var_dump( __FILE__, __LINE__, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
  }



  /***********************************************
  *
  * Setting methods
  *
  **********************************************/

 /**
  * setParentObject( )  : Returns a caddy with HTML form and HTML options among others
  *
  * @param	[type]		$$pObj: ...
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function setParentObject( $pObj )
  {
    if( ! is_object( $pObj ) )
    {
      $prompt = 'ERROR: no parent object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->pObj = $pObj;

//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//echo $prompt;

    if( ! is_object( $pObj->drs ) )
    {
      $prompt = 'ERROR: no DRS object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
//                'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->drs = $pObj->drs;

  }



 /***********************************************
  *
  * Template
  *
  **********************************************/

 /**
  * template( ): Returns the template
  *
  * @param	string		$subpart  :
  * @return	string		$template : HTML template
  * @access private
  * @version     4.0.6
  * @since       4.0.6
  */
  private function template( $subpart )
  {
    $cObj     = $this->pObj->cObj;
    $conf     = $this->pObj->conf;
    $template = $cObj->fileResource( $conf['api.']['e-payment.']['provider.']['paymill.']['files.']['html'] );

      // Die if there isn't any HTML template
    if( empty ( $template ) )
    {
      $prompt = '
        <div style="border:1em solid red;color:red;padding:1em;text-align:center">
          <h1>
            ERROR: No HTML Template
          </h1>
          <p>
            There isn\'t any HTML template.
          </p>
          <p>
            Please check your template configuration. See
          </p>
          <ul>
            <li>
              TypoScript Constant Editor: [CADDY - E-PAYMENT - PAYMILL FILES]
            </li>
            <li>
              TypoScript Object Browser: plugin.tx_caddy_pi1.api.e-payment.provider.paymill.files.html
            </li>
          </ul>
          <p>
            ' . __METHOD__ . ' (' . __LINE__ . ')
          </p>
          <p>
            Sorry for the trouble.<br 7>
            Caddy - the TYPO3 Shopping Cart
          </p>
        </div>
        ';
      die( $prompt );
    }

    $template = $cObj->getSubpart( $template, $subpart );

      // Die if there isn't any HTML template
    if( empty ( $template ) )
    {
$debugTrail = 'debug trail: ' . t3lib_utility_Debug::debugTrail( );
$debugTrail = str_replace( '//', '<br />' . PHP_EOL . '//', $debugTrail );
//var_dump( __METHOD__, __LINE__, $prompt);
      $prompt = '
        <div style="border:1em solid red;color:red;padding:1em;text-align:center">
          <h1>
            ERROR: HTML Template without subpart ' . $subpart . '
          </h1>
          <p>
            HTML template doesn\'t contain the subpart ' . $subpart . '
          </p>
          <p>
            Please check your template configuration. See
          </p>
          <ul>
            <li>
              TypoScript Constant Editor: [CADDY - E-PAYMENT - PAYMILL FILES]
            </li>
            <li>
              TypoScript Object Browser: plugin.tx_caddy_pi1.api.e-payment.provider.paymill.files.html
            </li>
          </ul>
          <p>
            ' . __METHOD__ . ' (' . __LINE__ . ')
          </p>
          <p>
            Sorry for the trouble.<br 7>
            Caddy - the TYPO3 Shopping Cart
          </p>
          <p>
            debug trial: ' . $debugTrail . ')
          </p>
        </div>
        ';
      die( $prompt );
    }

    return $template;
  }

 /**
  * templateMode( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateMode( )
  {
    //plugin.tx_caddy_pi1.api.e-payment.provider.paymill.mode
    $mode = $this->conf['api.']['e-payment.']['provider.']['paymill.']['mode'];

    switch( $mode )
    {
      case( 'live' ):
        $this->templateModeLive( );
        break;
      case( 'test' ):
      default:
        $this->templateModeTest( );
        break;

    }
  }

 /**
  * templateModeLive( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeLive( )
  {
    $this->content = str_replace( '###TEST_OR_LIVE_MODE###', 'live', $this->content );
    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, '###PAYMILL_TEST_MODE###', null );
  }

 /**
  * templateModeTest( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTest( )
  {
    $this->content = str_replace( '###TEST_OR_LIVE_MODE###', 'test', $this->content );

    $marker = array(
      '###PAYMILL_LIVE_MODE_PROMPT###' => $this->pi_getLL( 'paymill-live-mode' ),
      '###PAYMILL_TEST_MODE_PROMPT###' => $this->pi_getLL( 'paymill-test-mode' )
    );
    $subpart = $this->template( '###PAYMILL_TEST_MODE###' );
    $subpart = $this->pObj->cObj->substituteMarkerArray( $subpart, $marker );

    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, '###PAYMILL_TEST_MODE###', $subpart );

    $this->templateModeTestData( );
  }

 /**
  * templateModeTestData( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTestData( )
  {
    if( $this->paymillLib->getPaymillToken( ) )
    {
//var_dump( __METHOD__, __LINE__ );
      return;
    }

    if( $this->templateModeTestDataWiPowermail( ) )
    {
//var_dump( __METHOD__, __LINE__ );
      return;
    }
    $this->templateModeTestDataCreditcard( );
    $this->templateModeTestDataElv( );
    $this->templateModeTestDataElvIban( );
//var_dump( __METHOD__, __LINE__ );
  }

 /**
  * templateModeTestDataCreditcard( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTestDataCreditcard( )
  {

    $marker = array(
      '###VALUE_CREDITCARD-NUMBER###' => '30000000000004',
      '###VALUE_CREDITCARD-EXPIRY###' => strftime( '%m/%g', strtotime( "+2 years" ) ),
      '###VALUE_CREDITCARD-HOLDER###' => $this->pi_getLL( 'prompt-guineapig' ),
      '###VALUE_CREDITCARD-CVC###'    => '123'
    );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * templateModeTestDataElv( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTestDataElv( )
  {
    $marker = array(
      '###VALUE_ELV-ACCOUNT###' => '12345678',
      '###VALUE_ELV-BANKCODE###' => '10050000',
      '###VALUE_ELV-HOLDER###' => $this->pi_getLL( 'prompt-guineapig' )
    );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * templateModeTestDataElvIban( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTestDataElvIban( )
  {
    $marker = array(
      '###VALUE_ELV-IBAN###'        => 'DE10100500001234567890',
      '###VALUE_ELV-BIC###'         => 'BELADEBEXXX',
      '###VALUE_ELV-IBAN-HOLDER###' => $this->pi_getLL( 'prompt-guineapig' ),
    );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * templateModeTestDataWiPowermail( ) :
  *
  * @return	boolean
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateModeTestDataWiPowermail( )
  {
    return $this->powermailInAction( );
  }

 /**
  * templateSectionActive( ) : Replace the ###ACTIVE### marker
  *
  * @param	string		$template : HTML template
  * @return	string		$template : HTML template
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function templateSectionActive( $template )
  {
      // Set default value;
    $active = 1; // 1: credit card, 2: elv. 3: sepa (elv-iban).
    if( $this->paymentId !== null )
    {
      $active = $this->paymentId;
    }

    $markers = array(
      0 => '###CADDY_PAYMENT_SECTION_'  . $active . '###',
      1 => '###CADDY_PAYMENT_TYPE_'     . $active . '###'
    );

    $values = array(
      0 => 'active',
      1 => 'payment-type-active'
    );

    foreach( $markers as $key => $marker )
    {
      $value = $values[$key];
      $template = str_replace( $marker, ' ' . $value . ' ', $template );
      $template = str_replace( '  ' . $value, ' ' . $value, $template );
      $template = str_replace( $value . '  ', $value . ' ', $template );
      $template = str_replace( '" ' . $value, '"' . $value, $template );
      $template = str_replace( $value . ' "', $value . '"', $template );
    }

    return $template;
  }

 /**
  * templateSectionRemove( ) :  Remove the subpart ###SECTION_X###, if correspond payment method is disabled.
  *                             X is the key of the payment method.
  *
  * @param	string		$template : HTML template
  * @return	string		$template : HTML template
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function templateSectionRemove( $template )
  {
    $conf           = $this->pObj->conf;
    $paymentOptions = $conf['api.']['options.']['payment.']['options.'];

    foreach( array_keys( ( array ) $paymentOptions ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

      $enabled = $paymentOptions[$key . '.']['enabled'];

      if( $enabled )
      {
        continue;
      }

      $section  = '###SECTION_' . $key . '###';
      $template = $this->pObj->cObj->substituteSubpart( $template, $section, null );
    }

    return $template;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_paymill_paymentMethods.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_paymill_paymentMethods.php']);
}
?>