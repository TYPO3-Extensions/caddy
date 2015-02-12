<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
if ( $version < 6002000 )
{
  require_once(PATH_tslib . 'class.tslib_pibase.php');
}
// #61634, 140916, dwildt, +

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   82: class tx_caddy_paymill_lib extends tslib_pibase
 *  108:     public function getBankAccount( )
 *  121:     public function getBankCode( )
 *  134:     public function getBankHolder( )
 *  147:     public function getCardMonth( )
 *  160:     public function getCardHolder( )
 *  173:     public function getCardNumber( )
 *  186:     public function getCardYear( )
 *  199:     public function getPaymillClientId( )
 *  212:     public function getPaymillPaymentId( )
 *  225:     public function getPaymillToken( )
 *
 *              SECTION: Paymill
 *  258:     private function paymillError( $prompt )
 *  294:     public function paymillCheckAccount( )
 *  328:     public function paymillInit( )
 *  356:     public function paymillInitWiTokenOnly( )
 *  373:     private function paymillInitApiKey( )
 *  390:     private function paymillInitAutoload( )
 *  406:     private function paymillInitPayment( )
 *  443:     private function paymillInitPaymentBySession( )
 *  483:     private function paymillInitPaymentByToken( )
 *  521:     private function paymillInitVars( )
 *
 *              SECTION: Session
 *  547:     private function sessionGetClientId( )
 *  563:     private function sessionGetPaymentId( )
 *  579:     private function sessionGetToken( )
 *
 *              SECTION: Setting methods
 *  604:     public function setParentObject( $pObj )
 *  645:     public function setPid( $pid )
 *  659:     public function setPiVars( $piVars )
 *
 * TOTAL FUNCTIONS: 26
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
class tx_caddy_paymill_lib extends tslib_pibase
{
    public $extKey        = 'caddy';
    public $prefixId      = 'tx_caddy_paymill_lib';
    public $scriptRelPath = 'lib/e-payment/paymill/class.tx_caddy_paymill_lib.php';

    public  $conf           = null;       // array    : current TypoScript configuration
    public  $drs            = null;       // object   : instance of drs class.
    public  $local_cObj     = null;
    private $pObj           = null;       // object   : parent object
    private $pid            = null;       // integer  : pid of the current page
    private $prompts        = array( );   // array    : prompts

    private $paymillClientResponse  = null;       // object   : Paymill payment response object
    private $paymillPaymentResponse = null;       // object   : Paymill payment response object



 /**
  * getBankAccount( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getBankAccount( )
  {
    return $this->paymillPaymentResponse->getAccount( );
  }

 /**
  * getBankCode( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getBankCode( )
  {
    return $this->paymillPaymentResponse->getCode( );
  }

 /**
  * getBankHolder( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getBankHolder( )
  {
    return $this->paymillPaymentResponse->getHolder( );
  }

 /**
  * getCardMonth( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getCardMonth( )
  {
    return $this->paymillPaymentResponse->getExpireMonth( );
  }

 /**
  * getCardHolder( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getCardHolder( )
  {
    return $this->paymillPaymentResponse->getCardHolder( );
  }

 /**
  * getCardNumber( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getCardNumber( )
  {
    return $this->paymillPaymentResponse->getLastFour( );
  }

 /**
  * getCardYear( )  :
  *
  * @return	string
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getCardYear( )
  {
    return $this->paymillPaymentResponse->getExpireYear( );
  }

 /**
  * getPaymillClientId( )  :
  *
  * @return	object
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getPaymillClientId( )
  {
    return $this->paymillClientResponse->getId( );
  }

 /**
  * getPaymillPaymentId( )  :
  *
  * @return	object
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function getPaymillPaymentId( )
  {
    return $this->paymillPaymentResponse->getId( );
  }

 /**
  * getPaymillToken( ) :
  *
  * @return	string		$token :
  * @access public
  * @version    4.0.5
  * @since      4.0.5
  */
  public function getPaymillToken( )
  {
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( );
//$prompt = str_replace( '//' , PHP_EOL . '//' , $prompt );
//var_dump( __METHOD__, __LINE__, $prompt);
    $token = $this->piVars['e-payment']['paymill']['token'];
#var_dump( __METHOD__, __LINE__, $token );
    if( ! $token )
    {
      $token = $this->sessionGetToken( );
#var_dump( __METHOD__, __LINE__, $token );
    }
    return $token;
  }




 /***********************************************
  *
  * Paymill
  *
  **********************************************/

 /**
  * paymillError( ):
  *
  * @param	string		$prompt  :
  * @return	void
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillError( $prompt )
  {

    // notice (grey) = secondary, info (blue) = [empty!], ok (green) = success, error (red) = alert

    switch( true )
    {
      case( empty( $prompt ) ):
        $prompt = $this->pi_getLL( 'prompt-without-any-content' )
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
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $prompt;
        $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|'   . $this->pi_getLL( 'prompt-send-bugreport' );
        break;
    }

    return $this->prompts;
  }

 /**
  * paymillCheckAccount( ) :
  *
  * @return	mixed		$error  : returns an error array in case of an error / exception
  * @access public
  * @version     4.0.5
  * @since       4.0.5
  */
  public function paymillCheckAccount( )
  {
    $prompts = array( );

    $account  = $this->paymillPaymentResponse->getAccount( );
    $lastFour = $this->paymillPaymentResponse->getLastFour( );

    switch( true )
    {
      case( ! empty( $account ) ):
      case( ! empty( $lastFour ) ):
        return null;
        // no error
      default:
        $error    = $this->pi_getLL( 'error-account-empty' );
        $prompts  = $this->paymillError( $error );
        break;
    }

    unset( $account  );
    unset( $lastFour );

    return $prompts;
  }

 /**
  * paymillInit( ):
  *
  * @return	boolean   true, if paymill is initiated. false, if paymill isn't initiated.
  * @access public
  * @version     4.0.10
  * @since       4.0.5
  */
  public function paymillInit( )
  {
    if( ! $this->paymillInitWiTokenOnly( ) )
    {
      return false;
    }

    $this->paymillInitVars( );
    $this->paymillInitApiKey( );
    $this->paymillInitAutoload( );
    $prompts = $this->paymillInitPayment( );

    if( ! empty( $prompts ) )
    {
      return true;
    }

    return false;
  }

 /**
  * paymillInitWiTokenOnly( ):
  *
  * @return	boolean   true, if there is a paymill token. false, if there isn't any paymill token.
  * @access public
  * @version     4.0.5
  * @since       4.0.5
  */
  public function paymillInitWiTokenOnly( )
  {
    if( $this->getPaymillToken( ) )
    {
      return true;
    }
    return false;
  }

 /**
  * paymillInit( ):
  *
  * @return	void
  * @access private
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
  * @access private
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
  * @return	mixed		$error  : returns an error array in case of an error / exception
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPayment( )
  {
    $piVarToken   = $this->piVars['e-payment']['paymill']['token'];
    $sessionToken = $this->sessionGetToken( );

      // token is send twice probably because of a page reload
    if( $piVarToken == $sessionToken )
    {
      return $this->paymillInitPaymentBySession( $sessionToken );
    }

      // token is set by piVar: this is a new token
    if( $piVarToken )
    {
      return $this->paymillInitPaymentByToken( );
    }

    return $this->paymillInitPaymentBySession( $sessionToken );

  }

 /**
  * paymillInitPaymentBySession( ) : Init the paymill payment object by the paymill payment id.
  *
  * @return	mixed		$error  : returns an error array in case of an error / exception
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function paymillInitPaymentBySession( )
  {
    $paymillService = new Paymill\Request(PAYMILL_API_KEY);
    $paymillClient  = new Paymill\Models\Request\Client();
    $paymillPayment = new Paymill\Models\Request\Payment();

    $clientId       = $this->sessionGetClientId( );
    $paymentId      = $this->sessionGetPaymentId( );

    try {
//var_dump( __METHOD__, __LINE__, $clientId );
      $paymillClient->setid( $clientId );
//var_dump( __METHOD__, __LINE__, $paymillClient );
      $this->paymillClientResponse = $paymillService->getOne( $paymillClient );
//var_dump( __METHOD__, __LINE__, $this->paymillClientResponse );
//var_dump( __METHOD__, __LINE__, $paymentId );
      $paymillPayment->setid( $paymentId );
//var_dump( __METHOD__, __LINE__, $paymillClient );
      $this->paymillPaymentResponse = $paymillService->getOne( $paymillPayment );
//var_dump( __METHOD__, __LINE__, $this->paymillPaymentResponse );
      $paymillCheckAccount = $this->paymillCheckAccount( );
//var_dump( __METHOD__, __LINE__, $paymillCheckAccount );
      return $paymillCheckAccount;

    }
    catch( \Paymill\Services\PaymillException $e )
    {
      $error    = $e->getErrorMessage( );
      $error    = 'Paymill exception: ' . $error;
      $prompts  = $this->paymillError( $error );
      return $prompts;
    }
  }

 /**
  * paymillInitPaymentByToken( ) :  Init the paymill payment object by the given token.
  *                                 BE AWARE: A reinit isn't possible. You will lost all paymill data.
  *
  * @return	mixed		$error  : returns an error array in case of an error / exception
  * @access private
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

//var_dump( __METHOD__, __LINE__, $this->getPaymillToken( ) );
      $paymillPayment->setToken( $this->getPaymillToken( ) );
      $paymillPayment->setClient( $this->paymillClientResponse->getId( ) );
      $this->paymillPaymentResponse = $paymillService->create( $paymillPayment );
//var_dump( __FILE__, __LINE__, $this->paymillPaymentResponse );
      return $this->paymillCheckAccount( );
    }
    catch( \Paymill\Services\PaymillException $e )
    {
      $error    = $e->getErrorMessage( );
      $error    = 'Paymill exception: ' . $error;
      $prompts  = $this->paymillError( $error );
      return $prompts;
    }
  }

 /**
  * paymillInitVars( )
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @param	[type]		$pid: ...
  * @return	void
  * @access private
  * @version    4.0.6
  * @since      4.0.6
  */
  private function paymillInitVars( )
  {
    $this->conf         = $this->pObj->conf;
    $this->local_cObj   = $GLOBALS['TSFE']->cObj;

    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    //$this->pi_initPIflexForm( );
  }



  /***********************************************
  *
  * Session
  *
  **********************************************/

 /**
  * sessionGetClientId( ):
  *
  * @return	string		$value  : paymill client id
  * @access private
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
  * @return	string		$value  : paymill payment id
  * @access private
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
  * @return	string		$value  : paymill token
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionGetToken( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value    = $sesArray['e-payment']['paymill']['token'];
//var_dump( __METHOD__, __LINE__, $this->pid, $sesArray['e-payment'] );
    return $value;
  }



  /***********************************************
  *
  * Setting methods
  *
  **********************************************/

 /**
  * setParentObject( )  :
  *
  * @param	object		$pObj: ...
  * @return	void
  * @access public
  * @version    4.0.6
  * @since      4.0.6
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
  * setPid( )  :
  *
  * @param	integer		$pid : ...
  * @return	void
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function setPid( $pid )
  {
    $this->pid = $pid;
  }

 /**
  * setPiVars( )  :
  *
  * @param	array		$piVars : ...
  * @return	void
  * @access public
  * @version    4.0.6
  * @since      4.0.6
  */
  public function setPiVars( $piVars )
  {
    $this->piVars = $piVars;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_paymill_lib.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/paymill/class.tx_caddy_paymill_lib.php']);
}
?>