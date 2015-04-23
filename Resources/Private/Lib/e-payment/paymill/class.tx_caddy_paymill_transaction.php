<?php

/* * *************************************************************
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
 * ************************************************************* */

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
 *   84: class tx_caddy_paymill_transaction extends tslib_pibase
 *
 *              SECTION: Init
 *  127:     private function init( $paymentId=null, $pid=null )
 *  143:     private function initDrs( )
 *  156:     private function initInstances( )
 *  171:     private function initInstancesDrs( )
 *  189:     private function initInstancesDynamicMarkers( )
 *  208:     private function initInstancesPaymillLib( )
 *  229:     private function initVarsPid( $pid=null )
 *  265:     private function initVars( $paymentId, $pid )
 *
 *              SECTION: Session
 *  295:     private function sessionGetPaymillClientId( )
 *  311:     private function sessionGetPaymillPaymentId( )
 *
 *              SECTION: Setting methods
 *  336:     public function setParentObject( $pObj )
 *  377:     public function setTransactionAmount( $amount )
 *  391:     public function setTransactionCurrency( $currency )
 *  405:     public function setTransactionDescription( $description )
 *  419:     public function setTransactionClientEmail( $email )
 *  433:     public function setTransactionClientName( $name )
 *
 *              SECTION: Transaction
 *  454:     public function transaction( )
 *  480:     private function transactionInit( )
 *  502:     private function transactionRequirements( )
 *  539:     private function transactionSend( )
 *  594:     private function transactionSendCatch( $exception )
 *  642:     private function transactionSendTry( )
 *  703:     private function transactionSentEval( $paymillTransactionResponse )
 *  718:     private function transactionSentEvalResponseCode( $paymillTransactionResponse )
 *  746:     private function transactionUpdate( )
 *  760:     private function transactionUpdateClient( )
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
class tx_caddy_paymill_transaction extends tslib_pibase
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_paymill_transaction';
  public $scriptRelPath = 'Resources/Private/Lib/e-payment/paymill/class.tx_caddy_paymill_transaction.php';
  public $conf = null;       // array    : current TypoScript configuration
  public $drs = null;       // object   : instance of drs class.
  private $drsUserfunc = null;
  private $dynamicMarkers = null;       // object   : instance of dynamicMarkers class.
  public $local_cObj = null;
  private $paymentId = null;       // integer  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  private $pObj = null;       // object   : parent object
  private $pid = null;       // integer  : pid of the current page
  private $prompts = array();   // array    : prompts
  private $transactionAmount = '0.00';
  private $transactionCurrency = 'EUR';
  private $transactionDescription = 'Transaction by TYPO3 Caddy';
  private $transactionClientEmail = 'default@typo3-caddy.de';
  private $transactionClientName = 'TYPO3 Caddy';

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( ):
   *
   * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
   * @param	[type]		$pid: ...
   * @return	void
   * @access private
   * @version     4.0.5
   * @since       4.0.5
   */
  private function init( $paymentId = null, $pid = null )
  {
    $this->initVars( $paymentId, $pid );
    $this->initInstances();
    $this->initDrs();
  }

  /**
   * initDrs( )
   *
   * @return	void
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function initDrs()
  {
    $this->drs->init();
  }

  /**
   * initInstances( ):
   *
   * @return	void
   * @access private
   * @version     4.0.6
   * @since       4.0.6
   */
  private function initInstances()
  {
    $this->initInstancesDrs();
    $this->initInstancesDynamicMarkers();
    $this->initInstancesPaymillLib();
  }

  /**
   * initInstancesDrs( ):
   *
   * @return	void
   * @access private
   * @version     4.0.6
   * @since       4.0.6
   */
  private function initInstancesDrs()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/';

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs = t3lib_div::makeInstance( 'tx_caddy_drs' );
    // #i0061, 141129, dwildt, 2-/3+
    //$this->drs->pObj = $this;
    //$this->drs->row = $this->cObj->data;
    $this->drs->setExtConf( $this->arr_extConf );
    $this->drs->setFlexform( $this->flexform );
    $this->drs->setRow( $this->cObj->data );
  }

  /**
   * initInstancesDynamicMarkers( ):
   *
   * @return	void
   * @access private
   * @version     4.0.6
   * @since       4.0.6
   */
  private function initInstancesDynamicMarkers()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/';

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
  private function initInstancesPaymillLib()
  {
    $path2paymillLib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/e-payment/paymill/class.tx_caddy_paymill_lib.php';

    // Initiate the provider class
    require_once( $path2paymillLib );
    $this->paymillLib = t3lib_div::makeInstance( 'tx_caddy_paymill_lib' );
    $this->paymillLib->setParentObject( $this );
    $this->paymillLib->setPiVars( $this->pObj->piVars );
    $this->paymillLib->setPid( $this->pid );
  }

  /**
   * initPid( )  : Returns the globlas tsfe id, if the given pid is null
   *
   * @param	integer		$pid  : given pid (may be null)
   * @return	integer		$pid  : id of the page with the caddy plugin
   * @internal    #54634
   * @version     4.0.3
   * @since       4.0.5
   */
  private function initVarsPid( $pid = null )
  {
    if ( $pid !== null )
    {
      $this->pid = $pid;
      return;
    }

    if ( ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ] )
    {
      $pid = ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
    }

    if ( $pid === null || $pid === '' )
    {
      if ( $this->drs->drsError || $this->drsUserfunc )
      {
        $prompt = 'Given pid of the Caddy is empty!';
        t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
      }
      $pid = $GLOBALS[ "TSFE" ]->id;
    }

    $this->pid = $pid;
  }

  /**
   * initVars( )
   *
   * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
   * @param	[type]		$pid: ...
   * @return	void
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function initVars( $paymentId, $pid )
  {
    $this->conf = $this->pObj->conf;
    $this->local_cObj = $GLOBALS[ 'TSFE' ]->cObj;
    // #i0058, 141011, dwildt, 1-
    //$this->paymentId = $paymentId;

    $this->initVarsPid( $pid );
    // #i0058, 141011, dwildt, 4+
    if ( $paymentId === NULL )
    {
      $this->paymentId = $this->sessionGetPaymentId();
    }
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    //$this->pi_initPIflexForm( );
  }

  /**
   * isEpayment( ) : Returns true, if current cash method is e-payment, false, if it isn't
   *
   * @return	boolean $isEpayment: true, if current cash method is e-payment, false, if it isn't
   * @access private
   * @internal   #i0058, #i0062
   * @version    6.0.3
   * @since      6.0.0
   */
  private function isEpayment()
  {
    $woEpayment = $this->sessionGetWoEpayment();
    //var_dump( __METHOD__, __LINE__, $woEpayment, !$woEpayment );

    if ( !$this->drsUserfunc )
    {
      return !$woEpayment;
    }

    $paymentId = $this->sessionGetPaymentId();

    $prompt = 'Current payment method with the id "' . $paymentId . '" isn\'t defined as an e-payment method.';
    t3lib_div::devlog( '[INFO/E-PAYMENT] ' . $prompt, $this->extKey, 0 );

    $prompt = 'Change it: Please configure api.options.payment.options.' . $paymentId . '.e-payment';
    t3lib_div::devlog( '[HELP/E-PAYMENT] ' . $prompt, $this->extKey, 1 );

    return !$woEpayment;
  }

  /*   * *********************************************
   *
   * Session
   *
   * ******************************************** */

  /**
   * sessionGetWoEpayment( ):
   *
   * @return	string		$value  : e-payment.woEpayment
   * @access private
   * @internal    #i0062
   * @version     6.0.3
   * @since       6.0.3
   */
  private function sessionGetWoEpayment()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value = $sesArray[ 'e-payment' ][ 'woEpayment' ];
    //var_dump( __METHOD__, __LINE__, $value, $sesArray[ 'e-payment' ] );

    return $value;
  }

  /**
   * sessionGetPaymillPaymentId( ):
   *
   * @return	string		$value  : paymill payment id
   * @access private
   * @internal    #i0058
   * @version     6.0.0
   * @since       6.0.0
   */
  private function sessionGetPaymentId()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value = $sesArray[ 'options' ][ 'payment' ][ 'id' ];

    return $value;
  }

  /**
   * sessionGetPaymillClientId( ):
   *
   * @return	string		$value  : paymill client id
   * @access private
   * @version     4.0.5
   * @since       4.0.5
   */
  private function sessionGetPaymillClientId()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $value = $sesArray[ 'e-payment' ][ 'paymill' ][ 'client' ][ 'id' ];

    return $value;
  }

  /**
   * sessionGetPaymillPaymentId( ):
   *
   * @return	string		$value  : paymill payment id
   * @access private
   * @version     4.0.5
   * @since       4.0.5
   */
  private function sessionGetPaymillPaymentId()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    // #i0062, 141129, dwildt, 4-, 1+
//    // #i0058, 141011, dwildt, 1-
//    //$value = $sesArray[ 'e-payment' ][ 'paymill' ][ 'payment' ][ 'id' ];
//    // #i0058, 141011, dwildt, 1+
//    $value = $sesArray[ 'options' ][ 'payment' ][ 'id' ];
    $value = $sesArray[ 'e-payment' ][ 'paymill' ][ 'payment' ][ 'id' ];

    return $value;
  }

  /*   * *********************************************
   *
   * Setting methods
   *
   * ******************************************** */

  /**
   * setDrsUserfunc( )  :
   *
   * @param	[type]		$$pObj: ...
   * @return	void
   * @access public
   * @version    6.0.3
   * @since      6.0.3
   */
  public function setDrsUserfunc( $drsUserfunc )
  {
    $this->drsUserfunc = $drsUserfunc;
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
    if ( !is_object( $pObj ) )
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

    if ( !is_object( $pObj->drs ) )
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

//
//  /**
//   * setTransactionPaymentId( )  :
//   *
//   * @param	double		$amount  :
//   * @return	void
//   * @access public
//   * @version    6.0.0
//   * @since      6.0.0
//   */
//  public function setPaymentId( $paymentId )
//  {
//    $this->paymentId = $paymentId;
//  }

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

  /*   * *********************************************
   *
   * Transaction
   *
   * ******************************************** */

  /**
   * transaction( ) :
   *
   * @return	array		$this->prompts  : prompts, in case of an error
   * @access public
   * @version    6.0.3
   * @since      4.0.5
   */
  public function transaction()
  {
    if ( $this->transactionInit() )
    {
      return $this->prompts;
    }

    // #i0062, 141129, dwildt, 4+
    if ( !$this->isEpayment() )
    {
      return;
    }

    $arrResult = $this->transactionSend();
    if ( $arrResult[ 'errors' ] )
    {
      $prompts = $arrResult[ 'errors' ];
      //var_dump( __METHOD__, __LINE__, $prompts );
      //die( ":(" );
      return $prompts;
    }

    //var_dump( __METHOD__, __LINE__, $prompts );
    //die( ":(" );
    return $prompts;
  }

  /**
   * transactionInit( ) :
   *
   * @return	boolean   true, if paymill is initiated. False, if paymill insn't initiated.
   * @access private
   * @version    4.0.10
   * @since      4.0.5
   */
  private function transactionInit()
  {
    $this->init();

    // #i0053, 140408, dwildt, +
    $isInitiated = $this->paymillLib->paymillInit();
    if ( $isInitiated )
    {
      return true;
    }
    else
    {
      return false;
    }

    // #i0053, 140408, dwildt, -
//    $this->prompts = $this->paymillLib->paymillInit( );
//    if( $this->prompts )
//    {
//      return false;
//    }
//
//    return true;
  }

  /**
   * transactionRequirments( ) :
   *
   * @return	array		$this->prompts  : prompts, in case of an error
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function transactionRequirements()
  {
    $prompts = array();

    // Check requirements for transaction
    $error = $this->paymillLib->paymillCheckAccount();
    if ( !$error )
    {
      return;
    }

    $prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $error[ 'prompt' ];
    return $prompts;
  }

  /**
   * transactionSend( )  : Executes the transaction. Returns in case of
   *                       * success
   *                         $arrReturn(
   *                           'error'                       => false,
   *                           'exception'                   => false,
   *                           'paymillTransactionResponse'  => $paymillTransactionResponse,
   *                           'responseCode'                => 20000    // in case of 20000
   *                         )
   *                       * failure
   *                         $arrReturn(
   *                           'error'                       => true,
   *                           'errors'                      => [array]
   *                           'exception'                   => [true || false],
   *                           'responseCode'                => 0
   *                         )
   *
   * @return	array		$arrReturn  : See method description
   * @access private
   * @version    4.0.6
   * @since      4.0.4
   */
  private function transactionSend()
  {
    $arrReturn = array(
      'error' => true,
      'errors' => array(),
      'exception' => false,
      'responseCode' => null,
      'transactionId' => null
    );

    // Update the client
    $prompts = $this->transactionUpdate();
    if ( $prompts )
    {
      $arrReturn[ 'errors' ] = $prompts;
      return $arrReturn;
    }

    // Check requirements
    $prompts = $this->transactionRequirements();
    if ( $prompts )
    {
      $arrReturn[ 'errors' ] = $prompts;
      return $arrReturn;
    }

    // try transaction
    try
    {
      $arrReturn = $this->transactionSendTry();
      //var_dump( __METHOD__, __LINE__, $arrReturn );
      //die( ":(" );
      return $arrReturn;
    }
    catch ( \Paymill\Services\PaymillException $e )
    {
      $arrReturn = $this->transactionSendCatch( $e );
      //var_dump( __METHOD__, __LINE__, $arrReturn );
      //die( ":(" );
      return $arrReturn;
    }
  }

  /**
   * transactionSendCatch( ) : Returns
   *                               $arrReturn(
   *                                 'error'         => true,
   *                                 'errors'        => array( $prompts ),
   *                                 'exception'     => true,
   *                                 'responseCode'  => null
   *                               )
   *
   * @param	[type]		$$exception: ...
   * @return	array		$arrReturn  : See method description
   * @access private
   * @version    6.0.3
   * @since      4.0.6
   */
  private function transactionSendCatch( $exception )
  {
    $responseCode = $exception->getResponseCode();
    $statusCode = $exception->getStatusCode();
    $errorMessage = $exception->getErrorMessage();

    $prompts = array();
    $strResponseCode = $this->pi_getLL( $responseCode );

    if ( !empty( $strResponseCode ) )
    {
      // secondary (grey), [empty!] (blue), success (green), error (red)
      $prompts[ 0 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $strResponseCode;
    }
    else
    {
      $prompts[ 0 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|Respsonse code: ' . $responseCode;
    }

    if ( !empty( $errorMessage ) )
    {
      $prompts[ 1 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $errorMessage;
    }

    if ( $statusCode > 0 )
    {
      $prompts[ 2 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|Status code: ' . $statusCode;
    }

    $prompts[ 3 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'transaction-not-operated' );
    $prompts[ 4 ] = 'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'transaction-todo-default' );

    $arrReturn = array(
      'error' => true,
      'errors' => $prompts,
      'exception' => true,
      'responseCode' => $responseCode,
      'transactionId' => null
    );

    return $arrReturn;
  }

  /**
   * transactionSendTry( )  : Try to execute the transaction. Returns in case of
   *                       * success
   *                         $arrReturn(
   *                           'error'         => false,
   *                           'errors'        => null,
   *                           'exception'     => false,
   *                           'responseCode'  => $paymillTransactionResponse->getResponseCode( )
   *                         )
   *                       * failure
   *                         $arrReturn(
   *                           'error'         => true,
   *                           'errors'        => $prompts,
   *                           'exception'     => false,
   *                           'responseCode'  => $paymillTransactionResponse->getResponseCode( )
   *                         )
   *
   * @return	array		$arrReturn  : See method description
   * @access private
   * @version    4.0.10
   * @since      4.0.4
   */
  private function transactionSendTry()
  {
    $arrReturn = array(
      'error' => true,
      'errors' => array(),
      'exception' => false,
      'responseCode' => null,
      'transactionId' => null
    );

    // Initiate paymill objects
    $paymillService = new Paymill\Request( PAYMILL_API_KEY );
    //var_dump( __METHOD__, __LINE__, $paymillService );
    $paymillTransaction = new Paymill\Models\Request\Transaction();
    //var_dump( __METHOD__, __LINE__, $paymillTransaction );
    // Set transaction params
    // Get paymill payment id and client id from caddy session
    $paymentId = $this->sessionGetPaymillPaymentId();
    //var_dump( __METHOD__, __LINE__, $paymentId );
    $paymillTransaction->setPayment( $paymentId );
    $paymillTransaction->setAmount( round( $this->transactionAmount, 2 ) * 100 ); // #i0052
    $paymillTransaction->setCurrency( $this->transactionCurrency );
    $paymillTransaction->setDescription( $this->transactionDescription );
    // Execute the transaction
    //var_dump( __METHOD__, __LINE__, $paymillTransaction );
    $paymillTransactionResponse = $paymillService->create( $paymillTransaction );
    //var_dump( __METHOD__, __LINE__, $paymillTransactionResponse );
    //die( ":(" );
    // Set the response code
    // Evaluate the executed transaction object
    $prompts = $this->transactionSentEval( $paymillTransactionResponse );

    // Transaction failed
    if ( $prompts )
    {
      $arrReturn = array(
        'error' => true,
        'errors' => $prompts,
        'exception' => false,
        'responseCode' => $paymillTransactionResponse->getResponseCode(),
        'transactionId' => $paymillTransactionResponse->getId()
      );
      return $arrReturn;
    }

    // Transaction was successful
    $arrReturn = array(
      'error' => false,
      'errors' => null,
      'exception' => false,
      'responseCode' => $paymillTransactionResponse->getResponseCode(),
      'transactionId' => $paymillTransactionResponse->getId()
    );
    return $arrReturn;
  }

  /**
   * transactionSentEval( )  :
   *
   * @param	[type]		$paymillTransactionResponse: ...
   * @return	array		$prompts  : prompts, in case of an error
   * @access private
   * @version    4.0.6
   * @since      4.0.6
   */
  private function transactionSentEval( $paymillTransactionResponse )
  {
    return $this->transactionSentEvalResponseCode( $paymillTransactionResponse );
  }

  /**
   * transactionSentEvalResponseCode( )  : Returns prompts, if response code isn't proper
   *                                       Response code isn't proper, if code isn't 20000
   *
   * @param	[type]		$$paymillTransactionResponse: ...
   * @return	array		$prompts  : prompts, in case of an error
   * @access private
   * @version    4.0.6
   * @since      4.0.6
   */
  private function transactionSentEvalResponseCode( $paymillTransactionResponse )
  {
    $responseCode = $paymillTransactionResponse->getResponseCode();

    if ( $responseCode == 20000 )
    {
      return null;
    }

    // secondary (grey), [empty!] (blue), success (green), error (red)
    $prompts = array(
      'SERVER_PROMPT_WOCLOSE_SUBPART||' . $this->pi_getLL( $responseCode ),
      'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'transaction-not-operated' ),
      'SERVER_PROMPT_WICLOSE_SUBPART|secondary|' . $this->pi_getLL( 'transaction-todo-default' )
    );

    return $prompts;
  }

  /**
   * transactionUpdate( ) :
   *
   * @return	array		$prompts  : prompts, in case of an error
   * @access private
   * @internal   #i0043
   * @version    4.0.6
   * @since      4.0.6
   */
  private function transactionUpdate()
  {
    $prompts = $this->transactionUpdateClient();
    return $prompts;
  }

  /**
   * transactionUpdateClient( ) : Updates the description and the e-mail of the client
   *
   * @return	array		$prompts  : prompts, in case of an error
   * @access private
   * @internal   #i0043
   * @since      4.0.6
   */
  private function transactionUpdateClient()
  {
    $prompts = array();

    // Initiate paymill objects
    $paymillService = new Paymill\Request( PAYMILL_API_KEY );
    $paymillClient = new Paymill\Models\Request\Client();

    // Get paymill client id from caddy session
    $clientId = $this->sessionGetPaymillClientId();

    // try transaction
    try
    {
      // Update the client email and description
      $paymillClient->setId( $clientId );
      $paymillClient->setEmail( $this->transactionClientEmail );
      $paymillClient->setDescription( $this->transactionClientName );
      $paymillClientResponse = $paymillService->update( $paymillClient );
      unset( $paymillClientResponse );

      return;
    }
    catch ( \Paymill\Services\PaymillException $e )
    {
      // transaction failed
      $prompt = $e->getErrorMessage();
      if ( empty( $prompt ) )
      {
        $prompt = 'sorry, error prompt is empty.';
      }
      $prompt = 'Paymill exception while updating the client: ' . $prompt;
      $prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|alert|' . $prompt;
      return $prompts;
    }
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/Resources/Private/Lib/e-payment/paymill/class.tx_caddy_paymill_transaction.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/Resources/Private/Lib/e-payment/paymill/class.tx_caddy_paymill_transaction.php' ]);
}
?>