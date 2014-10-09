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
 *   82: class tx_caddy_epayment_powermail extends tslib_pibase
 *
 *              SECTION: Main
 *  118:     public function main( )
 *
 *              SECTION: E-payment
 *  174:     private function epayment( )
 *  202:     private function epaymentProviderDie( )
 *  224:     private function epaymentProviderPaymill( )
 *  249:     private function epaymentProviderPaymillInit( )
 *  274:     private function epaymentProviderPaymillInitValues( )
 *  307:     private function getEpaymentProvider( )
 *
 *              SECTION: Init
 *  350:     private function init( )
 *  367:     private function initDrs( )
 *  380:     private function initInstances( )
 *  404:     private function initPid( $pid=null )
 *  439:     private function initVars( )
 *
 *              SECTION: Prompt
 *  475:     private function serverPrompt( )
 *
 *              SECTION: Session
 *  517:     private function sessionErrorAdd( )
 *  536:     private function sessionErrorRemove( )
 *
 *              SECTION: Setting methods
 *  564:     public function setParentObject( $pObj )
 *
 *              SECTION: Templating
 *  596:     private function template( $subpart )
 *  683:     private function templateCleanUp( )
 *
 * TOTAL FUNCTIONS: 18
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Class is for extending the powermail class. Class returns the content (HTML) after an e-payment transaction.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @internal    #53678
 * @version     6.0.0
 * @since       4.0.5
 */
class tx_caddy_epayment_powermail extends tslib_pibase
{
    public $extKey          = 'caddy';
    public $prefixId        = 'tx_caddy_epayment_powermail';
    public $scriptRelPath   = 'lib/e-payment/powermail/class.tx_caddy_epayment_powermail.php';

    public  $conf           = null;       // array    : current TypoScript configuration
    private $content        = null;       // string   : content. Will returned by main( )
    public  $drs            = null;       // object   : instance of drs class.
    private $dynamicMarkers = null;       // object   : instance of dynamicMarkers class.
    public  $local_cObj     = null;
    private $pObj           = null;       // object   : parent object
    private $pid            = null;       // integer  : pid of the current page
    private $prompts        = array( );   // array    : prompts

    private $paymill        = null;       // object   : Paymill object





  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * getContentAfterTransaction( ):
  *
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	string		$template   : HTML template
  * @access public
  * @version     4.0.6
  * @since       4.0.6
  */
  public function getContentAfterTransaction( )
  {
//var_dump( __METHOD__, __LINE__ );
    $arrReturn = array(
      'content' => null,
      'isPayed' => false
    );

    $this->init( );

    $this->content = $this->template( '###HTML###' );

    $successEpayment = $this->epayment( );

    if( $successEpayment )
    {
        // success: transaction is done
      $marker = array(
        '###TITLE###'   => $this->pi_getLL( 'transaction-success-title'  ),
        '###HEADER###'  => $this->pi_getLL( 'transaction-success-header'  )
      );
      $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
      $this->prompts[] = 'SERVER_PROMPT_WICLOSE_SUBPART|success|' . $this->pi_getLL( 'transaction-success-prompt'  );
      $this->serverPrompt( );
      $this->sessionErrorRemove( );
      $this->templateCleanUp( );
      // Next two lines is for development only!
      //$this->sessionErrorAdd( );
      //die( $this->content );
      $arrReturn = array(
        'content' => $this->content,
        'isPayed' => true
      );
//var_dump( __FILE__, __LINE__, $arrReturn );
      return $arrReturn;
    }

      // error: transaction wasn't possible
    $marker = array(
      '###TITLE###'   => $this->pi_getLL( 'transaction-error-title'  ),
      '###HEADER###'  => $this->pi_getLL( 'transaction-error-header'  )
    );
    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
    $this->serverPrompt( );
    $this->sessionErrorAdd( );
    $this->templateCleanUp( );
    die( $this->content );
  }



  /***********************************************
  *
  * E-payment
  *
  **********************************************/

 /**
  * epayment( ):
  *
  * @return	boolean
  * @access private
  * @version     4.0.6
  * @since       4.0.5
  */
  private function epayment( )
  {
    $success  = false;
    $provider = $this->getEpaymentProvider( );

    switch( $provider )
    {
      case( 'Paymill' ):
        $success = $this->epaymentProviderPaymill( );
        break;
      case( null ):
      case( false ):
      default:
        $this->epaymentProviderDie( $provider );
        $success  = false;
        break;
    }

    return $success;
  }

 /**
  * epaymentProviderDie( ) :
  *
  * @return	boolean
  * @access private
  * @internal   #53678
  * @version    4.0.5
  * @since      4.0.5
  */
  private function epaymentProviderDie( $provider )
  {
    $prompt = 'Fatal error: undefined e-payment provider "' . $provider . '"<br />'
            . 'Method ' . __METHOD__ . ' at line ' . __LINE__ . ' <br />'
            . 'Sorry for the trouble<br />'
            . 'Caddy - your TYPO3 shopping cart<br />'
            ;
    die( $prompt );
  }

 /**
  * epaymentProviderPaymill( ) :  Returns true, if tasaction was successfull.
  *                               Returns false in case of an error. Error prompts will written to $this->prompts.
  *
  * @return	boolean
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.5
  */
  private function epaymentProviderPaymill( )
  {
    $success = false;

      // Init paymill
    $this->epaymentProviderPaymillInit( );

      // Execute the transaction
    $success = $this->epaymentProviderPaymillTransaction( );
    return $success;
  }

 /**
  * epaymentProviderPaymillInit( ) :
  *
  * @return	void
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.5
  */
  private function epaymentProviderPaymillInit( )
  {
    $this->epaymentProviderPaymillInitClass( );
    $this->epaymentProviderPaymillInitValues( );
  }

 /**
  * epaymentProviderPaymillInitClass( ) :
  *
  * @return	void
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.6
  */
  private function epaymentProviderPaymillInitClass( )
  {
    $path2provider  = t3lib_extMgm::extPath( 'caddy' ) . 'lib/e-payment/paymill/class.tx_caddy_paymill_transaction.php';

      // Initiate the provider class
    require_once( $path2provider );
    $this->paymillTransaction = t3lib_div::makeInstance( 'tx_caddy_paymill_transaction' );
    $this->paymillTransaction->setParentObject( $this );
  }

 /**
  * epaymentProviderPaymillInitValues( ) :
  *
  * @return	void
  * @access private
  * @internal   #53678
  * @version    4.0.5
  * @since      4.0.5
  */
  private function epaymentProviderPaymillInitValues( )
  {
      // Get caddy sesssion
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );

      // Get values
    $amount         = $sesArray['sumsumgross'];
    $clientEmail    = $sesArray['powermail']['values']['customer']['email'];
    $clientName     = $sesArray['invoiceFirstname'] . ' ' . $sesArray['invoiceLastname'];
    $currency       = $this->conf['api.']['e-payment.']['currency'];
    $numberOrder    = $this->pi_getLL( 'label-short-number-order'   ) . ': ' . $sesArray['numberOrderCurrent'];
    $numberInvoice  = $this->pi_getLL( 'label-short-number-invoice' ) . ': ' . $sesArray['numberInvoiceCurrent'];
    $description    = $numberOrder . ', ' . $numberInvoice;

      // Set values
    $this->paymillTransaction->setTransactionAmount(       $amount );
    $this->paymillTransaction->setTransactionCurrency(     $currency );
    $this->paymillTransaction->setTransactionDescription(  $description );
    $this->paymillTransaction->setTransactionClientEmail(  $clientEmail );
    $this->paymillTransaction->setTransactionClientName(   $clientName );
  }

 /**
  * epaymentProviderTransaction( )  : Returns
  *                                   * true, if tasaction was successfull.
  *                                   * false in case of an error. Error prompts will written to $this->prompts.
  *
  * @return	boolean
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.6
  */
  private function epaymentProviderPaymillTransaction( )
  {
    // Nur ausfuehren, wenn nicht bereits ausgefuehrt
    // Session merkt sich order number und status bezahlt/nicht bezahlt

    $success = false;

      // Execute the transaction
    $success = $this->epaymentProviderPaymillTransactionExec( );
    return $success;
  }

 /**
  * epaymentProviderTransactionExec( )  : Returns
  *                                       * true, if tasaction was successfull.
  *                                       * false in case of an error. Error prompts will written to $this->prompts.
  *
  * @return	boolean
  * @access private
  * @internal   #53678
  * @version    4.0.6
  * @since      4.0.6
  */
  private function epaymentProviderPaymillTransactionExec( )
  {
    // Nur ausfuehren, wenn nicht bereits ausgefuehrt
    // Session merkt sich order number und status bezahlt/nicht bezahlt

    $prompts = $this->paymillTransaction->transaction( );
    if( ! empty( $prompts ) )
    {
      $this->prompts  = $this->prompts
                      + $prompts
                      ;
      return false;
    }

    return true;
  }

 /**
  * getEpaymentProvider( ) :
  *
  * @return	string		$provider :
  * @access private
  * @internal   #53678
  * @version    4.0.5
  * @since      4.0.5
  */
  private function getEpaymentProvider( )
  {
    $provider = $this->conf['api.']['e-payment.']['provider'];

    switch( $provider )
    {
      case( 'Paymill' ):
          // Follow the workflow;
        break;
      case( null ):
      case( false ):
          // Don't do anything
        return;
      default:
        $prompt = 'Fatal error: undefined e-payment provider "' . $provider . '"<br />'
                . 'Method ' . __METHOD__ . ' at line ' . __LINE__ . ' <br />'
                . 'Sorry for the trouble<br />'
                . 'Caddy - your TYPO3 shopping cart<br />'
                ;
        die( $prompt );
        break;
    }

    return $provider;
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
  * @return	void
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function init( )
  {
    $this->initVars( );
    $this->initInstances( );
    $this->initDrs( );
    $this->initPid( );
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
  * @access private
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

  }

/**
 * initPid( )  : Returns the globlas tsfe id, if the given pid is null
 *
 * @param	integer		$pid  : given pid (may be null)
 * @return	integer		$pid  : id of the page with the caddy plugin
 * @internal    #54634
 * @version     4.0.5
 * @since       4.0.5
 */
  private function initPid( $pid=null )
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
  * @param	integer		$paymentId  : current payment id. 1: credit card, 2: elv. 3: sepa (elv-iban).
  * @return	void
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function initVars( )
  {
    $this->conf         = $this->pObj->conf;
    $this->local_cObj   = $GLOBALS['TSFE']->cObj;

    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    //$this->pi_initPIflexForm( );
  }



  /***********************************************
  *
  * Prompt
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
  * @version     4.0.5
  * @since       4.0.5
  */
  private function serverPrompt( )
  {

    $server_prompt  = null;

    if( empty( $this->prompts ) )
    {
      return;
    }

    foreach( $this->prompts as $prompt )
    {
      list( $subpartMarker, $marker['###TYPE###'], $marker['###PROMPT###'] ) = explode( '|', $prompt );
//var_dump( __METHOD__, __LINE__, $prompt );
      $subpart = $this->template( '###' . $subpartMarker . '###' );
      $subpart = $this->pObj->cObj->substituteMarkerArray( $subpart, $marker );
      $server_prompt  = $server_prompt
                      . $subpart;
    }

    $marker = array(
      '###SERVER_PROMPT###' => $server_prompt
    );

    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );
  }



  /***********************************************
  *
  * Session
  *
  **********************************************/

 /**
  * sessionErrorAdd( ): Error is needed by class tx_caddy_pi1_clean
  *
  * @return	void
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionErrorAdd( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $sesArray['e-payment']['powermail']['error'] = true;

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
    //var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
  }

 /**
  * sessionErrorRemove( ): Error is needed by class tx_caddy_pi1_clean
  *
  * @return	void
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function sessionErrorRemove( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    unset( $sesArray['e-payment']['powermail']['error'] );

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
      // save session
    $GLOBALS['TSFE']->storeSessionData( );
    //var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
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
  * @version    4.0.5
  * @since      4.0.5
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
  }



  /***********************************************
  *
  * Templating
  *
  **********************************************/


 /**
  * template( ): Returns the template
  *
  * @param	string		$subpart  :
  * @return	string		$template : HTML template
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function template( $subpart )
  {
    $cObj     = $this->pObj->cObj;
    $conf     = $this->pObj->conf;
    $template = $cObj->fileResource( $conf['api.']['e-payment.']['powermail.']['files.']['html.']['transactionPrompts'] );

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
              TypoScript Constant Editor: [CADDY - E-PAYMENT - POWERMAIL FILES]
            </li>
            <li>
              TypoScript Object Browser: plugin.tx_caddy_pi1.api.e-payment.powermail.files.html.transactionPrompts
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
              TypoScript Constant Editor: [CADDY - E-PAYMENT - POWERMAIL FILES]
            </li>
            <li>
              TypoScript Object Browser: plugin.tx_caddy_pi1.api.e-payment.powermail.files.html.transactionPrompts
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
  * templateCleanUp( ):
  *
  * @return	void
  * @access private
  * @version     4.0.5
  * @since       4.0.5
  */
  private function templateCleanUp( )
  {

    $marker['###PATH_TO_FOUNDATION###'] = $this->conf['api.']['e-payment.']['powermail.']['paths.']['foundation'];
    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $marker );

    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, '###SERVER_PROMPT_WICLOSE_SUBPART###', null );
    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, '###SERVER_PROMPT_WOCLOSE_SUBPART###', null );

    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
    $this->content = $this->dynamicMarkers->main( $this->content, $this );
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/powermail/class.tx_caddy_epayment_powermail.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/e-payment/powermail/class.tx_caddy_epayment_powermail.php']);
}
?>