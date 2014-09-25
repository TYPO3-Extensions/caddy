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

// #61634, 140916, dwildt, 1-
//require_once(PATH_tslib . 'class.tslib_pibase.php');
// #61634, 140916, dwildt, +
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
if ( $version < 6002002 )
{
  require_once(PATH_tslib . 'class.tslib_pibase.php');
}
// #61634, 140916, dwildt, +

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   51: class tx_caddy_template
 *   70:     public function main( )
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Paymill e-payment modul for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @internal    #53678
 * @version     6.0.0
 * @since       4.0.5
 */
class tx_caddy_epayment_paymill extends tslib_pibase
{
    public $extKey        = 'caddy';
    public $prefixId      = 'tx_caddy_epayment_paymill';
    public $scriptRelPath = 'lib/e-payment/paymill/class.tx_caddy_epayment_paymill.php';

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




 /**
  * main( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	string		$template   : HTML template
  * @access   public
  * @version     4.0.5
  * @since       4.0.5
  */
  public function main( $paymentId=null, $pid=null )
  {
var_dump( __METHOD__, __LINE__ );

    $this->init( $paymentId, $pid );

    $this->requirementsDie( );

    $this->content = $this->template( '###CONTENT###' );
    $this->content = $this->templateSectionRemove( $this->content );
    $this->content = $this->templateSectionActive( $this->content );
    $this->templateMode( );


    if( ! $this->requirements( ) )
    {
//var_dump( __METHOD__, __LINE__ );
      $this->sessionDataRemove( );
      $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
      $this->content = $this->dynamicMarkers->main( $this->content, $this );
      return $this->content;
    }

      // Insert payment data into content
    $this->payment( );
      // Insert prompt into content
    $this->serverPrompt( );
      // Insert
    $this->session( );

    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
    $this->content = $this->dynamicMarkers->main( $this->content, $this );

    return $this->content;

  }

 /**
  * init( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
  */
  private function initDrs( )
  {
    $this->drs->init( );
  }

 /**
  * initInstances( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function initInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );
    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;

//    require_once( $path2lib . 'class.tx_caddy_session.php' );
//    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );
//    $this->session->setParentObject( $this );
  }


/**
 * initPid( )  : Returns the globlas tsfe id, if the given pid is null
 *
 * @param       integer         $pid  : given pid (may be null)
 * @return	integer		$pid  : id of the page with the caddy plugin
 * @internal    #54634
 * @version     4.0.3
 * @since       4.0.5
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

 /**
  * initVars( )
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version    4.0.5
  * @since      4.0.5
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
  * payment( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function payment( )
  {
    $followTheWorkflow = false;

    switch( true )
    {
      case( $this->paymillTokenValue( ) ):
      case( $this->paymentWiPowermail( ) ):
        $followTheWorkflow = true;
        break;
      default:
        $followTheWorkflow = false;
        break;
    }
    if( ! $followTheWorkflow )
    {
      return;
    }

    if( ! $this->paymill( ) )
    {
      $this->sessionDataRemove( );
      return;
    }

    switch( $this->paymentId )
    {
      case( 4 ):
        $this->paymentByCreditcard( );
        break;
      case( 5 ):
        $this->paymentByElv( );
        break;
      case( 6 ):
        $this->paymentByElvIban( );
        break;
      default:
        $this->paymentByUndefined( );
        break;
    }
  }

 /**
  * paymentByCreditcard( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentByCreditcard( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-creditcard-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-creditcard' );
//    var_dump( __FILE__, __LINE__,
//            $this->paymillPaymentResponse
////            $this->paymillPaymentResponse->getCode( ),
////            $this->paymillPaymentResponse->getAccount( ),
////            $this->paymillPaymentResponse->getHolder( )
//            );
    $expiry = $this->paymillPaymentResponse->getExpireMonth( ) . '/' . $this->paymillPaymentResponse->getExpireYear( );
    $marker['###VALUE_CREDITCARD-NUMBER###']  = '**** **** **** ' . $this->paymillPaymentResponse->getLastFour( );
    $marker['###VALUE_CREDITCARD-EXPIRY###']  = $expiry;
    $marker['###VALUE_CREDITCARD-HOLDER###']  = $this->paymillPaymentResponse->getCardHolder( );
    $marker['###VALUE_CREDITCARD-CVC###']     = '****';

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentByElv( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentByElv( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-elv-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-elv' );

    $marker['###VALUE_ELV-ACCOUNT###']    = $this->paymillPaymentResponse->getAccount( );
    $marker['###VALUE_ELV-BANKCODE###']   = $this->paymillPaymentResponse->getCode( );
    $marker['###VALUE_ELV-HOLDER###'] = $this->paymillPaymentResponse->getHolder( );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentByElvIban( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentByElvIban( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'prompt-elv-iban-ok' );
    $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|success|'   . $this->pi_getLL( 'prompt-paywith-elv-iban' );

    $iban = '****' . $this->paymillPaymentResponse->getCode( ) . $this->paymillPaymentResponse->getAccount( );
    $marker['###VALUE_ELV-IBAN###']         = $iban;
    $marker['###VALUE_ELV-BIC###']          = '**********';
    $marker['###VALUE_ELV-IBAN-HOLDER###']  = $this->paymillPaymentResponse->getHolder( );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }

 /**
  * paymentByUndefined( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentByUndefined( )
  {
      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
    $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|'     . $this->pi_getLL( 'prompt-paywith-undefined' );
  }

 /**
  * paymentIdReset( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentIdReset( )
  {
    // Will reset by sessionDataRemove( ) later in the workflow.
  }

 /**
  * paymill( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymill( )
  {
    $error = $this->paymillInit( );
    if( ! empty( $error ) )
    {
      $this->paymillError( $error );
      $this->paymentIdReset( );
      return false;
    }
    return true;
  }

 /**
  * paymentWiPowermail( ):
  *
  * @return	boolean
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymentWiPowermail( )
  {
    return $this->powermailInAction( );
  }

 /**
  * paymillError( ):
  *
  * @param      array         $error  :
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillError( $error )
  {
    $prompt = $error[ 'prompt' ];
    switch( true )
    {
      case( empty( $prompt ) ):
        $prompt = 'Paymill exception: '
                . $this->pi_getLL( 'prompt-without-any-content' )
                . '<br />'
                . $this->pi_getLL( 'error-assumption-new-object-with-wasted-token' )
                ;
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $prompt;
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|'   . $this->pi_getLL( 'prompt-send-bugreport' );
        break;
      case( $prompt == 'Parameter is mandatory' ):
        $prompt = $this->pi_getLL( 'error-parameter-is-mandatory' );
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART||' . $prompt;
        break;
      default:
        $prompt = 'Paymill exception: '
                . $prompt
                ;
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $prompt;
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|'   . $this->pi_getLL( 'prompt-send-bugreport' );
        break;
    }

      // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert

  }

 /**
  * paymillInit( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInit( )
  {
    $this->paymillInitApiKey( );
    $this->paymillInitAutoload( );
    $error = $this->paymillInitPayment( );

    return $error;
  }

 /**
  * paymillInit( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitApiKey( )
  {
    $name   = $this->conf['api.']['e-payment.']['provider.']['paymill.']['keys.']['private'];
    $conf   = $this->conf['api.']['e-payment.']['provider.']['paymill.']['keys.']['private.'];
    $value  = $this->local_cObj->cObjGetSingle( $name, $conf );

    define( 'PAYMILL_API_KEY', $value );
  }

 /**
  * paymillInitAutoload( ):
  *
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitAutoload( )
  {
    $phpAutoload = $this->conf['api.']['e-payment.']['provider.']['paymill.']['files.']['php.']['autoload'];
    $phpAutoload = t3lib_extMgm::extPath( 'caddy' ) . $phpAutoload;

    require_once( $phpAutoload );
  }

 /**
  * paymillInitPayment( ):
  *
  * @return	mixed       $error  : returns an error array in case of an error / exception
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPayment( )
  {
      // Get session token (used token)
    $sessionToken  = $this->sessionGetToken( );

    if( $sessionToken )
    {
      return $this->paymillInitPaymentBySession( $sessionToken );
    }
    return $this->paymillInitPaymentByToken( );
  }

 /**
  * paymillInitPaymentError( ) :
  *
  * @return	mixed       $error  : returns an error array in case of an error / exception
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPaymentError( )
  {
    $error = array( );

    $account  = $this->paymillPaymentResponse->getAccount( );
    $lastFour = $this->paymillPaymentResponse->getLastFour( );

    switch( true )
    {
      case( ! empty( $account ) ):
      case( ! empty( $lastFour ) ):
        // no error
        break;
      default:
        $error = array(
          'code' => array(
            1 => null,
            2 => null
          ),
          'prompt' => $this->pi_getLL( 'error-account-empty' ),
        );
        break;
    }

    unset( $account  );
    unset( $lastFour );

    return $error;
  }

 /**
  * paymillTokenIsSet( ) :
  *
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function paymillTokenIsSet( )
  {
    if( isset( $this->pObj->piVars['e-payment']['paymill']['token'] ) )
    {
      return true;
    }

    return false;
  }

 /**
  * paymillTokenValue( ) :
  *
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function paymillTokenValue( )
  {
    $token = $this->sessionGetToken( );
    if( ! $token )
    {
      $token = $this->pObj->piVars['e-payment']['paymill']['token'];
    }
    return $token;
  }

 /**
  * paymillInitPaymentBySession( ) : Init the paymill payment object by the paymill payment id.
  *
  * @return	mixed       $error  : returns an error array in case of an error / exception
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPaymentBySession( )
  {
    $paymillService = new Paymill\Request(PAYMILL_API_KEY);
    $paymillPayment = new Paymill\Models\Request\Payment();
    $id = $this->sessionGetPaymentId( );

    try {
      $paymillPayment->setid( $id );
      $this->paymillPaymentResponse = $paymillService->getOne( $paymillPayment );
      //var_dump( __FILE__, __LINE__, $paymillPayment, $this->paymillPaymentResponse );
      return $this->paymillInitPaymentError( );
    }
    catch( \Paymill\Services\PaymillException $e )
    {
      $error = array(
        'code' => array(
          1 => $e->getResponseCode( ),
          2 => $e->getResponseCode( )
        ),
        'prompt'  => $e->getErrorMessage( )
      );
      return $error;
    }
  }

 /**
  * paymillInitPaymentByToken( ) :  Init the paymill payment object by the given token.
  *                                 BE AWARE: A reinit isn't possible. You will lost all paymill data.
  *
  * @return	mixed       $error  : returns an error array in case of an error / exception
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPaymentByToken( )
  {
    $paymillService = new Paymill\Request(PAYMILL_API_KEY);
    $paymillClient  = new Paymill\Models\Request\Client();
    $paymillPayment = new Paymill\Models\Request\Payment();

    try {
      $paymillClient->setEmail( 'test@test.de' );
      $paymillClient->setDescription( 'This is a temporary user.' );
      $this->paymillClientResponse = $paymillService->create( $paymillClient );
      //var_dump( __FILE__, __LINE__, $this->paymillClientResponse->getId(), $this->paymillClientResponse->getEmail() );

      $paymillPayment->setToken( $this->paymillTokenValue( ) );
      $paymillPayment->setClient( $this->paymillClientResponse->getId( ) );
      $this->paymillPaymentResponse = $paymillService->create( $paymillPayment );
      return $this->paymillInitPaymentError( );
      //var_dump( __FILE__, __LINE__, $paymillPayment, $this->paymillPaymentResponse );
    }
    catch( \Paymill\Services\PaymillException $e )
    {
      $error = array(
        'code' => array(
          1 => $e->getResponseCode( ),
          2 => $e->getResponseCode( )
        ),
        'prompt'  => $e->getErrorMessage( )
      );
      //var_dump( __FILE__, __LINE__, $this->paymillTokenValue( ), $error );
      return $error;
    }
  }

 /**
  * powermailInAction( ):
  *
  * @return	boolean
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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
        //break;
      default:
        return false;
        //break;
    }

    unset( $GP );
  }

 /**
  * requirements( ):
  *
  * @return	boolean
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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
  * requirementsToken( ): Returns false, if token isn't part of the URL params
  *
  * @return	boolen
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function requirementsToken( )
  {
      // Token is proper
    if( $this->sessionGetToken( ) )
    {
      return true;
    }

      // Token isn't set, e-payment form is called the first time
    if( ! $this->paymillTokenIsSet( ) )
    {
      return false;
    }

      // Token is set by sending the e-payment form
      // IF token is unproper, prompts it
    if( ! $this->paymillTokenValue( ) )
    {
        // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert
      $this->prompts[] = 'SERVER_PROMPT_WOCLOSE_SUBPART|alert|' . $this->pi_getLL( 'prompt-token-error' );
      $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART||' . $this->pi_getLL( 'prompt-token-info1' );
      $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART||' . $this->pi_getLL( 'prompt-token-info2' );
    }

      // Requirements matchs in principle
    return true;
  }

 /**
  * requirementsWiPowermail( ) :
  *
  * @return	boolean
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function requirementsWiPowermail( )
  {
    return $this->powermailInAction( );
  }

 /**
  * requirementsDie( ) :
  *
  * @return	void
  * @access public
  * @version     4.0.5
  * @since       4.0.5
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
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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

 /**
  * session( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function session( )
  {
    if( ! isset( $this->pObj->piVars['e-payment']['paymill']['token'] ) )
    {
      $this->sessionDataRemove( );
      return;
    }

    switch( true )
    {
      case( $this->paymillTokenValue( ) ):
        $this->sessionDataAdd( );
        break;
      case( ! $this->paymillTokenValue( ) ):
      default:
        $this->sessionDataRemove( );
        break;
    }
  }

 /**
  * sessionDataAdd( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionDataAdd( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );

    unset( $sesArray['e-payment']['paymill'] );

    $sesArray['e-payment']['paymill']['token']          = $this->paymillTokenValue( );
    if( is_object( $this->paymillClientResponse ) )
    {
      $sesArray['e-payment']['paymill']['client']['id']  = $this->paymillClientResponse->getId( );
    }
    if( is_object( $this->paymillPaymentResponse ) )
    {
      $sesArray['e-payment']['paymill']['payment']['id']  = $this->paymillPaymentResponse->getId( );
    }

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
//    var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
  }

 /**
  * sessionDataAddTransactionResponseCode( ):
  *
  * @param      integer         $responseCode :
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionDataAddTransactionResponseCode( $responseCode )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );

    $sesArray['e-payment']['paymill']['transaction']['responsecode'] = $responseCode;

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
//    var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
  }

 /**
  * sessionDataRemove( ):
  *
  * @param      integer         $paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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

 /**
  * sessionDataRemoveWiPowermailCreate( ):
  *
  * @return	boolean
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionDataRemoveWiPowermail( )
  {
    return $this->powermailInAction( );
  }

 /**
  * sessionGetClientId( ):
  *
  * @return	string      $value  : paymill client id
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionGetClientId( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value    = $sesArray['e-payment']['paymill']['client']['id'];

    return $value;
  }

 /**
  * sessionGetPaymentId( ):
  *
  * @return	string      $value  : paymill payment id
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionGetPaymentId( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value    = $sesArray['e-payment']['paymill']['payment']['id'];

    return $value;
  }

 /**
  * sessionGetToken( ):
  *
  * @return	string      $value  : paymill token
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionGetToken( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value    = $sesArray['e-payment']['paymill']['token'];

    return $value;
  }



  /***********************************************
  *
  * Setting methods
  *
  **********************************************/

 /**
  * setTransactionAmount( )  :
  *
  * @param	double		$amount  :
  * @return	void
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function setTransactionAmount( $amount )
  {
    $this->transactionAmount = $amount;
  }

 /**
  * setTransactionCurrency( )  :
  *
  * @param	string		$currency  :
  * @return	void
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function setTransactionCurrency( $currency )
  {
    $this->transactionCurrency = $currency;
  }

 /**
  * setTransactionDescription( )  :
  *
  * @param	string		$description  :
  * @return	void
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function setTransactionDescription( $description )
  {
    $this->transactionDescription = $description;
  }

 /**
  * setTransactionClientEmail( )  :
  *
  * @param	string		$email  :
  * @return	void
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function setTransactionClientEmail( $email )
  {
    $this->transactionClientEmail = $email;
  }

 /**
  * setTransactionClientName( )  :
  *
  * @param	string		$name  :
  * @return	void
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function setTransactionClientName( $name )
  {
    $this->transactionClientName = $name;
  }

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

 /**
  * template( ): Returns the template
  *
  * @param      string          $subpart  :
  * @return	string		$template : HTML template
  * @access   private
  * @version     4.0.5
  * @since       4.0.5
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
        </div>
        ';
      die( $prompt );
    }

    return $template;
  }

 /**
  * templateSectionActive( ) : Replace the ###ACTIVE### marker
  *
  * @param	string		$template : HTML template
  * @return	string		$template : HTML template
  * @access private
  * @version    4.0.5
  * @since      4.0.5
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
  * templateSectionRemove( ) : Remove ...
  *
  * @param	string		$template : HTML template
  * @return	string		$template : HTML template
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function templateSectionRemove( $template )
  {
var_dump( __FILE__, __LINE__, $template );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###CC###', null );
var_dump( __FILE__, __LINE__, $template );

    return $template;
  }

 /**
  * templateMode( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
  */
  private function templateModeTestData( )
  {
    if( $this->paymillTokenValue( ) )
    {
      return;
    }

    if( $this->templateModeTestDataWiPowermail( ) )
    {
      return;
    }
//var_dump( __METHOD__, __LINE__ );
    $this->templateModeTestDataCreditcard( );
    $this->templateModeTestDataElv( );
    $this->templateModeTestDataElvIban( );
  }

 /**
  * templateModeTestDataCreditcard( ) :
  *
  * @return	void
  * @access private
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
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
  * @version    4.0.5
  * @since      4.0.5
  */
  private function templateModeTestDataWiPowermail( )
  {
    return $this->powermailInAction( );
  }

 /**
  * transaction( ) :
  *
  * @return	array     $this->prompts  : prompts, in case of an error
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function transaction( )
  {
    if( ! $this->transactionInit( ) )
    {
      return $this->prompts;
    }

    $prompts = $this->transactionSend( );
    return $prompts;
  }


 /**
  * transactionInit( ) :
  *
  * @return	boolean
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function transactionInit( )
  {
    $this->init( );

    return $this->paymill( );
  }

 /**
  * transactionSend( ) :
  *
  * @return	array     $this->prompts  : prompts, in case of an error
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function transactionSend( )
  {
//var_dump( __FILE__, __LINE__, $this->pObj->x );
//die( );
    $prompts = array( );

    $paymillService     = new Paymill\Request(PAYMILL_API_KEY);
    $paymillClient      = new Paymill\Models\Request\Client();
//    $paymillPayment     = new Paymill\Models\Request\Payment();
    $paymillTransaction = new Paymill\Models\Request\Transaction();

    $paymentId  = $this->sessionGetPaymentId( );
    $clientId   = $this->sessionGetClientId( );

    try {
      $paymillClient->setId( $clientId );
      $paymillClient->setEmail( $this->transactionClientEmail );
      $paymillClient->setDescription( $this->transactionClientName );
      $paymillClientResponse = $paymillService->update( $paymillClient );
      //var_dump( __FILE__, __LINE__, $paymillClientResponse );
      unset( $paymillClientResponse );

      $error = $this->paymillInitPaymentError( );
      if( $error )
      {
        $prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $error['prompt'];
        return $prompts;
      }

//      $paymillPayment->setId( $paymentId );
//      //$paymillPayment->setClient( $clientId );
//      var_dump( __FILE__, __LINE__, $paymillPayment );
//      $this->paymillPaymentResponse = $paymillService->update( $paymillPayment );
//      var_dump( __FILE__, __LINE__, $$this->paymillPaymentResponse );
//      //unset( $paymillPaymentResponse );

//      $paymillTransaction->setPayment( $paymillPaymentResponse->getId( ) );
      $paymillTransaction->setPayment( $paymentId );
//      $paymillTransaction->setAmount($_POST['amount'] * 100);
//      $paymillTransaction->setCurrency($_POST['currency']);
      $paymillTransaction->setAmount(       $this->transactionAmount * 100 );
      $paymillTransaction->setCurrency(     $this->transactionCurrency          );
      $paymillTransaction->setDescription(  $this->transactionDescription       );
      $paymillTransactionResponse = $paymillService->create($paymillTransaction);
      $responseCode = $paymillTransactionResponse->getResponseCode( );
//var_dump( __FILE__, __LINE__, $paymillTransactionResponse );
//die( );
      $force = true;
      $this->sessionDataRemove( $force );
      $this->sessionDataAddTransactionResponseCode( $responseCode );
      unset( $paymillTransactionResponse );
//var_dump( __FILE__, __LINE__, $responseCode );
      return;
    }
    catch( \Paymill\Services\PaymillException $e )
    {
      $prompt = $e->getErrorMessage( );
      if( empty( $prompt ) )
      {
        $prompt = 'sorry, error prompt is empty.';
      }
      $prompt = 'Paymill exception: ' . $prompt;
      $prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $prompt;
      return $prompts;
    }
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_epayment_paymill.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_epayment_paymill.php']);
}
?>