<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 *  112: class tx_caddy_pi1 extends tslib_pibase
 *
 *              SECTION: Main
 *  174:     public function main( $content, $conf )
 *
 *              SECTION: Caddy
 *  252:     private function caddyHide( $content )
 *  283:     private function caddyProductAdd( )
 *  305:     private function caddyProductDelete( )
 *  321:     private function caddyRendered( )
 *  338:     private function caddyUpdate( )
 *  361:     private function caddyUpdateOptions( )
 *
 *              SECTION: Clean
 *  430:     private function clean( )
 *
 *              SECTION: Debug
 *  452:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: HTML
 *  500:     private function htmlActiveSection( $tmpl )
 *
 *              SECTION: Init
 *  541:     private function init( )
 *  573:     private function initAccessByIp( )
 *  619:     private function initDatabase( )
 *  632:     private function initDatabaseTable( )
 *  683:     private function initFlexform( )
 *  697:     private function initNumbers( )
 *  748:     private function initNumbersSession( )
 *  786:     private function initGetPost( )
 *  860:     private function initGetPostQty( )
 *  876:     private function initInstances( )
 *  934:     private function initJavascript( )
 *  946:     private function initPid( )
 *  985:     private function initPidClasses( )
 * 1000:     private function initPowermail( )
 * 1013:     private function initTemplate( )
 *
 *              SECTION: Powermail
 * 1034:     private function send( )
 *
 *              SECTION: Send
 * 1056:     private function powermailDataToSession( )
 * 1081:     private function powermailData( )
 * 1112:     private function sendCustomer( )
 * 1128:     private function sendCustomerDeliveryorder( )
 * 1159:     private function sendCustomerInvoice( )
 * 1190:     private function sendCustomerRevocation( )
 * 1221:     private function sendCustomerTerms( )
 * 1252:     private function sendVendor( )
 * 1268:     private function sendVendorDeliveryorder( )
 * 1299:     private function sendVendorInvoice( )
 * 1330:     private function sendVendorRevocation( )
 * 1361:     private function sendVendorTerms( )
 *
 *              SECTION: Update Wizard
 * 1401:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1447:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 40
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	6.0.0
 * @since       1.4.6
 */
class tx_caddy_pi1 extends tslib_pibase
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  public $arr_extConf = null;
  private $accessByIP = null;
  private $product = array();
  private $newProduct = array();
  private $markerArray = array();
  private $outerMarkerArray = array();
  public $gpvar = array();
  public $pid = null;
  private $caddy = null;
  private $clean = null;
  public $cObj = null;
  public $conf = null;
  public $drs = null;
  public $dynamicMarkers = null;
  public $flexform = null;
  public $gpvar = array();
  public $local_cObj = null;
  private $newProduct = array();
  private $numberDeliveryorderCurrent = null;
  private $numberDeliveryorderRegistry = null;
  private $numberInvoiceCurrent = null;
  private $numberInvoiceRegistry = null;
  private $numberOrderCurrent = null;
  private $numberOrderRegistry = null;
  public $pid = null;
  public $powermail = null;
  private $session = null;
  private $template = null;
  public $tmpl = null;

  /*   * *********************************************
   *
   * Main
   *
   * ******************************************** */

  /**
   * the main method of the PlugIn
   *
   * @param	string		$content: The PlugIn content
   * @param	array		$conf: The PlugIn configuration
   * @return	The		content that is displayed on the website
   * @version    4.0.5
   * @since      1.4.6
   */
  public function main( $content, $conf )
  {
    // page object
    $this->local_cObj = $GLOBALS[ 'TSFE' ]->cObj;

    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    // Init extension configuration array
    $this->arr_extConf = unserialize( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXT' ][ 'extConf' ][ $this->extKey ] );
    // 130227, dwildt, 1-
    //$this->pi_USER_INT_obj = 1;
    // Init DRS, flexform, gpvars, HTML template, service attributes
    $this->init();

    // Prompt of the update wizard
    $content = $this->updateWizard( $content );

    // Output debugging prompts in debug mode
    $this->debugOutputBeforeRunning();

    // Remove current product
    $this->caddyProductDelete();
    // Update several order values
    $this->caddyUpdate();
    // Add a product
    $this->caddyProductAdd();

    // Get the caddy
    $caddy = $this->caddyRendered();
    $marker = $caddy[ 'marker' ];
    $subparts = $caddy[ 'subparts' ];
    $tmpl = $caddy[ 'tmpl' ];

    // 140109, dwildt, 1+
    $tmpl = $this->htmlActiveSection( $tmpl );
//var_dump( __METHOD__, __LINE__ , $tmpl );
    unset( $caddy );

    $content = $this->powermail->formCss( $content );
    $this->powermailDataToSession();

    $this->send();

    $this->clean();

    $content = $content . $this->cObj->substituteMarkerArrayCached
                    (
                    $tmpl, $marker, $subparts
    );

    $content = $this->caddyHide( $content );

//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
//var_dump( __METHOD__, __LINE__, $this->pObj->local_cObj->data );
    $content = $this->dynamicMarkers->main( $content, $this ); // Fill dynamic locallang or typoscript markers
    return $this->pi_wrapInBaseClass( $content );
  }

  /*   * *********************************************
   *
   * Caddy
   *
   * ******************************************** */

  /**
   * caddyHide( )  : Hide the caddy, if client has sent the order
   *
   * @param	[type]		$$content: ...
   * @return	string		$content
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyHide( $content )
  {
    $GP = t3lib_div::_GET() + t3lib_div::_POST()
    ;

//var_dump( __METHOD__, __LINE__, $GP );

    switch ( true )
    {
      case( $GP[ 'tx_powermail_pi1' ][ 'action' ] == 'create' ):
        $content = null;
        break;
      default:
        // Follow the workflow
        break;
    }

    unset( $GP );

    return $content;
  }

  /**
   * caddyProductAdd( )
   *
   * @return	void
   * @access private
   * @version    6.0.0
   * @since      2.0.0
   */
  private function caddyProductAdd()
  {
    // 140207, dwildt, 5+
    // Workaround for new bug ( since 140207 ) after sending the powermail form
    if ( !isset( $this->gpvar[ 'uid' ] ) )
    {
      return;
    }

    // add further product to session
    $this->newProduct = $this->session->productGet( $this->gpvar[ 'uid' ] );
    if ( $this->newProduct !== false )
    {
      $this->newProduct[ 'qty' ] = $this->gpvar[ 'qty' ];
      $this->newProduct[ 'service_attribute_1' ] = $this->gpvar[ 'service_attribute_1' ];
      $this->newProduct[ 'service_attribute_2' ] = $this->gpvar[ 'service_attribute_2' ];
      $this->newProduct[ 'service_attribute_3' ] = $this->gpvar[ 'service_attribute_3' ];
      $this->session->productAdd( $this->newProduct, $this->pid );
    }
  }

  /**
   * caddyProductDelete( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyProductDelete()
  {
    // remove product from session
    if ( isset( $this->piVars[ 'del' ] ) )
    {
      $this->session->productDelete( $this->pid );
    }
  }

  /**
   * caddyRendered( )
   *
   * @return	array		$caddy : ...
   * @access private
   * @version    3.0.1
   * @since      2.0.0
   */
  private function caddyRendered()
  {
    // #54634, 131128, dwildt, 1-
    //$arrReturn = $this->caddy->caddy( );
    // #54634, 131128, dwildt, 1+
    $arrReturn = $this->caddy->caddy( $this->pid );
    return $arrReturn;
  }

  /**
   * caddyUpdate( )
   *
   * @return	void
   * @access private
   * @version    3.0.1
   * @since      2.0.0
   */
  private function caddyUpdate()
  {
    // change qty
    if ( isset( $this->piVars[ 'qty' ] ) && is_array( $this->piVars[ 'qty' ] ) )
    {
      // #54634, 131128, dwildt, 1-
      //$this->session->quantityUpdate( );
      // #54634, 131128, dwildt, 1+
      $this->session->quantityUpdate( $this->pid );
    }

    $this->caddyUpdateOptions();
  }

  /**
   * caddyUpdateOptions( )
   *
   * @return	void
   * @access private
   * @version    4.0.4
   * @since      2.0.0
   */
  private function caddyUpdateOptions()
  {
//    $GP = t3lib_div::_GET( )
//        + t3lib_div::_POST( )
//        ;
//    var_dump( __METHOD__, __LINE__, $GP );
    // RETURN : Don't update options, if form isn't by itself
    if ( !intval( $this->piVars[ 'updateByCaddy' ] ) )
    {
      return;
    }

    $this->caddyUpdateOptionsPayment();
    $this->caddyUpdateOptionsShipping();
    $this->caddyUpdateOptionsSpecials();
  }

  /**
   * caddyUpdateOptionsPayment( )
   *
   * @return	void
   * @access private
   * @version    4.0.6
   * @since      4.0.6
   */
  private function caddyUpdateOptionsPayment()
  {
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    // #54858, 140109, dwildt, 1-
    //$payment = null;
    // #54858, 140109, dwildt, 1+
    $payment = $this->session->paymentGet( $this->pid );
    if ( isset( $this->piVars[ 'payment' ] ) )
    {
      $payment = $this->piVars[ 'payment' ];
    }

    // #54858, 140109, dwildt, 1-
    //$this->session->paymentUpdate( $this->piVars['payment'], $this->pid );
    // #54858, 140109, dwildt, 1+
    $this->session->paymentUpdate( $payment, $this->pid );
  }

  /**
   * caddyUpdateOptionsShipping( )
   *
   * @return	void
   * @access private
   * @version    4.0.6
   * @since      4.0.6
   */
  private function caddyUpdateOptionsShipping()
  {
    // #54858, 140109, dwildt, 1-
    //$shipping = null;
    // #54858, 140109, dwildt, 1+
    $shipping = $this->session->shippingGet( $this->pid );
//var_dump( __METHOD__, __LINE__, $shipping );
    if ( isset( $this->piVars[ 'shipping' ] ) )
    {
      $shipping = $this->piVars[ 'shipping' ];
    }
//var_dump( __METHOD__, __LINE__, $shipping );
    // #54858, 140109, dwildt, 1-
    //$this->session->shippingUpdate( $this->piVars['shipping'], $this->pid );
    // #54858, 140109, dwildt, 1+
    $this->session->shippingUpdate( $shipping, $this->pid );
  }

  /**
   * caddyUpdateOptionsSpecials( )
   *
   * @return	void
   * @access private
   * @version    4.0.6
   * @since      4.0.6
   */
  private function caddyUpdateOptionsSpecials()
  {
    // #54858, 140109, dwildt, 1-
    //$special = null;
    // #54858, 140109, dwildt, 1+
    $special = $this->session->specialGet( $this->pid );
//var_dump( __METHOD__, __LINE__, $special );
    // 140202, dwildt, 1-
    //if( isset( $this->piVars['specials'] ) )
    // 140202, dwildt, 1+
    if ( isset( $this->piVars[ 'updateByCaddy' ][ 'specials' ] ) )
    {
      $special = $this->piVars[ 'specials' ];
    }
//var_dump( __METHOD__, __LINE__, $special );
    // #54858, 140109, dwildt, 1-
    //$this->session->specialUpdate( $this->piVars['specials'], $this->pid );
    // #54858, 140109, dwildt, 1+
    $this->session->specialUpdate( $special, $this->pid );
  }

  /*   * *********************************************
   *
   * Clean
   *
   * ******************************************** */

  /**
   * clean( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function clean()
  {
    $this->clean->main();
  }

  /*   * *********************************************
   *
   * Debug
   *
   * ******************************************** */

  /**
   * debugOutputBeforeRunning( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function debugOutputBeforeRunning()
  {
    // RETURN : Don't output debug prompt
    if ( !$this->conf[ 'debug.' ][ 'paramsAndTs' ] )
    {
      return;
    }
    // RETURN : Don't output debug prompt
    // output debug prompt
    // 140127, dwildt, -
    // deprectad since TYPO3 4.5
//    t3lib_div::debug
//    (
//      $this->session->productsGet( $this->pid ), $this->extKey . ': ' . 'Values in session at the beginning'
//    );
//    t3lib_div::debug($this->gpvar, $this->extKey . ': ' . 'Given params');
//    t3lib_div::debug($this->conf, $this->extKey . ': ' . 'Typoscript configuration');
//    t3lib_div::debug($_POST, $this->extKey . ': ' . 'All POST variables');
    // 140127, dwildt, +
    t3lib_utility_Debug::debug
            (
            $this->session->productsGet( $this->pid ), $this->extKey . ': ' . 'Values in session at the beginning'
    );
    t3lib_utility_Debug::debug( $this->gpvar, $this->extKey . ': ' . 'Given params' );
    t3lib_utility_Debug::debug( $this->conf, $this->extKey . ': ' . 'Typoscript configuration' );
    t3lib_utility_Debug::debug( $_POST, $this->extKey . ': ' . 'All POST variables' );

    // output debug prompt
  }

  /*   * *********************************************
   *
   * HTML
   *
   * ******************************************** */

  /**
   * htmlActiveSection( ) : Replace the ###ACTIVE### marker
   *
   * @param	string		$tmpl: current template
   * @return	string		$tmpl: handled template
   * @access private
   * @version    4.0.4
   * @since      4.0.4
   */
  private function htmlActiveSection( $tmpl )
  {
    if ( t3lib_div::_GP( 'tx_powermail_pi1' ) )
    {
      return $tmpl;
    }

    // Set default value;
    $accordion = 1;
    if ( isset( $this->piVars[ 'accordion' ] ) )
    {
      $accordion = ( int ) $this->piVars[ 'accordion' ];
    }

    $marker = '###CADDY_SECTION_' . $accordion . '###';

    $tmpl = str_replace( $marker, ' active ', $tmpl );
    $tmpl = str_replace( '  active', ' active', $tmpl );
    $tmpl = str_replace( 'active  ', 'active ', $tmpl );
    $tmpl = str_replace( '" active', '"active', $tmpl );
    $tmpl = str_replace( 'active "', 'active"', $tmpl );

    return $tmpl;
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function init()
  {
    // init flexform
    $this->pi_initPIflexForm();

    $this->initInstances();
    $this->drs->init();
    $this->initFlexform();
    $this->initPid();
    $this->initAccessByIp();
    $this->initTemplate();
    $this->initGetPost();
    $this->initPowermail();
    // #53679, 131115, dwildt, 2+
    // #i0041, initJavaScript must be after powermail because of ###UID_POWERMAIL_FORM###
    $this->initJavascript();
    $this->initDatabase();
    $this->initNumbers();

    $this->caddy->setParentObject( $this );
    $this->caddy->setContentRow( $this->cObj->data );

    $this->session->setParentObject( $this );
//    var_dump( __METHOD__, __LINE__ );
//    die( ":(" );
  }

  /**
   * initAccessByIp( ): Set the global $bool_accessByIP.
   *
   * @return	void
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initAccessByIp()
  {
    // No access by default
    $this->accessByIP = false;

    // Get list with allowed IPs
    $csvIP = $this->flexform->sdefCsvallowedip;
    $currentIP = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

    // Current IP is an element in the list
    $pos = strpos( $csvIP, $currentIP );
    if ( !( $pos === false ) )
    {
      $this->accessByIP = true;
    }
    // Current IP is an element in the list
    // DRS
    if ( !$this->drs->drsInit )
    {
      return;
    }

    switch ( $this->accessByIP )
    {
      case( true ):
        $prompt = 'Access: current IP matchs the list of allowed IP. Result will prompt to the frontend.';
        t3lib_div::devlog( ' [OK/INIT] ' . $prompt, $this->extKey, -1 );
        break;
      case( false ):
      default:
        $prompt = 'No access: current IP doesn\'t match the list of allowed IP. Result won\'t prompt to the frontend.';
        t3lib_div::devlog( ' [WARN/INIT] ' . $prompt, $this->extKey, 2 );
        break;
    }
    // DRS
  }

  /**
   * initDatabase( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initDatabase()
  {
    $this->initDatabaseTable();
  }

  /**
   * initDatabaseTable( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initDatabaseTable()
  {
    $table = $this->conf[ 'db.' ][ 'table' ];

    //echo $GLOBALS['TCA'][$table]['columns'] ) )
    // RETURN : TCA is loaded
    // Load the TCA
    t3lib_div::loadTCA( $table );

    if ( !empty( $GLOBALS[ 'TCA' ][ $table ] ) )
    {
      // DRS
      if ( $this->drs->drsInit )
      {
        $prompt = $table . ' is an element of the $TCA.';
        t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return;
    }

    $prompt = '
      <div style="border:1em solid red;color:red;padding:1em;text-align:center">
        <h1>
          ERROR: table isn\'t proper
        </h1>
        <p>
          The name of the given table isn\'t proper "' . $table . '".
        </p>
        <p>
          Please take care of a proper configuration of the typoscript property db.table.<br />
          See: constant editor > CADDY - DATABASE > table name
        </p>
        <p>
          Caddy - the Shopping Cart
        </p>
      </div>
      ';
    die( $prompt );
  }

  /**
   * initFlexform( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initFlexform()
  {
    $this->flexform->drs = $this->drs;
    $this->flexform->pi_getFFvalue = $this->pi_getFFvalue;
    $this->flexform->row = $this->row;
    $this->flexform->flexform();
  }

  /**
   * initNumbers( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initNumbers()
  {
    $registry = t3lib_div::makeInstance( 't3lib_Registry' );
    // #54634, 131229, dwildt, 1-
    //$prefix = 'page_' . $GLOBALS["TSFE"]->id . '_';
    // #54634, 131229, dwildt, 1+
    $prefix = 'page_' . $this->pid . '_';

    $this->numberDeliveryorderRegistry = ( int ) $registry->get( 'tx_caddy', $prefix . 'deliveryorder' );
    $this->numberInvoiceRegistry = ( int ) $registry->get( 'tx_caddy', $prefix . 'invoice' );
    $this->numberOrderRegistry = ( int ) $registry->get( 'tx_caddy', $prefix . 'order' );

    $this->numberDeliveryorderCurrent = $this->numberDeliveryorderRegistry + $this->flexform->originDeliveryorder + 1;
    $this->numberInvoiceCurrent = $this->numberInvoiceRegistry + $this->flexform->originInvoice + 1;
    $this->numberOrderCurrent = $this->numberOrderRegistry + $this->flexform->originOrder + 1;

    // DRS
    if ( $this->drs->drsInit )
    {
      $prompt = 'Delivery order number from registry: ' . $this->numberDeliveryorderRegistry;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Order number from registry: ' . $this->numberOrderRegistry;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Invoice number from registry: ' . $this->numberInvoiceRegistry;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Current delivery order number: ' . $this->numberDeliveryorderCurrent;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Current invoice number: ' . $this->numberInvoiceCurrent;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Current order number: ' . $this->numberOrderCurrent;
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    $this->initNumbersSession();
  }

  /**
   * initNumbersSession( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initNumbersSession()
  {
    $sesArray = array();
    // get already exting session
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );

    $sesArray[ 'numberDeliveryorderCurrent' ] = $this->numberDeliveryorderCurrent;
    $sesArray[ 'numberInvoiceCurrent' ] = $this->numberInvoiceCurrent;
    $sesArray[ 'numberOrderCurrent' ] = $this->numberOrderCurrent;

    // generate session with session array
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();

    // DRS
    if ( $this->drs->drsInit )
    {
      $prompt = 'Current numbers are stored in the session.';
      t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
  }

  /**
   * initGetPost( )
   *
   * @return	void
   * @access private
   * @version    4.0.7
   * @since      2.0.0
   */
  private function initGetPost()
  {

    foreach ( array_keys( ( array ) $this->conf[ 'api.' ][ 'getpost.' ] ) as $getpostKey )
    {
      if ( stristr( $getpostKey, '.' ) )
      {
        continue;
      }

      $name = $this->conf[ 'api.' ][ 'getpost.' ][ $getpostKey ];
      $conf = $this->conf[ 'api.' ][ 'getpost.' ][ $getpostKey . '.' ];

      // 140207, dwildt, +
      $value = $this->zz_cObjGetSingle( $name, $conf );
      if ( empty( $value ) && $value !== 0 )
      {
        continue;
      }
      $this->gpvar[ $getpostKey ] = $value;

      // 140207, dwildt, 1-
//      $this->gpvar[ $getpostKey ] = $this->zz_cObjGetSingle( $name, $conf );
    }

    $this->initGetPostQty();
//var_dump( __METHOD__, __LINE__, $this->gpvar );
    return;
  }

  /**
   * initGetPostQty( )
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.0
   */
  private function initGetPostQty()
  {
    if ( $this->gpvar[ 'qty' ] === 0 )
    {
      $this->gpvar[ 'qty' ] = 1;
    }
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initInstances()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy = t3lib_div::makeInstance( 'tx_caddy' );
    // Next two line will set in init( )
//    $this->caddy->setParentObject( $this );
//    $this->caddy->setContentRow( $this->cObj->data );
    // Class with methods for get clean values
    require_once( 'class.tx_caddy_pi1_clean.php' );
    $this->clean = t3lib_div::makeInstance( 'tx_caddy_pi1_clean' );
    $this->clean->pObj = $this;
    $this->clean->row = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj = $this;
    $this->drs->row = $this->cObj->data;

    // Class with methods for get flexform values
    require_once( 'class.tx_caddy_pi1_flexform.php' );
    $this->flexform = t3lib_div::makeInstance( 'tx_caddy_pi1_flexform' );
    $this->flexform->pObj = $this;
    $this->flexform->row = $this->cObj->data;

    // #53679, 131115, dwildt, 4+
    require_once( 'class.tx_caddy_pi1_javascript.php' );
    $this->javascript = t3lib_div::makeInstance( 'tx_caddy_pi1_javascript' );
    $this->javascript->pObj = $this;
    $this->javascript->row = $this->cObj->data;

    require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
    $this->powermail = t3lib_div::makeInstance( 'tx_caddy_powermail' );
    $this->powermail->pObj = $this;

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session = t3lib_div::makeInstance( 'tx_caddy_session' );

    require_once( $path2lib . 'class.tx_caddy_template.php' );
    $this->template = t3lib_div::makeInstance( 'tx_caddy_template' );
    $this->template->pObj = $this;
  }

  /**
   * initJavascript( )
   *
   * @return	void
   * @access private
   * @internal   #53679
   * @version    4.0.0
   * @since      4.0.0
   */
  private function initJavascript()
  {
    $this->javascript->addJssFilesJqueryPluginsCaddy();
  }

  /**
   * initPid( ) :
   *
   * @return	void
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initPid()
  {
    $pid = ( int ) $this->conf[ 'pid' ];

    switch ( true )
    {
      case(!empty( $pid ) ):
        $this->pid = $pid;
        // DRS
        if ( $this->drs->drsInit )
        {
          $prompt = 'pid is taken from main.pid and is set to ' . $this->pid;
          t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        break;
      case( empty( $pid ) ):
      default:
        $this->pid = $GLOBALS[ 'TSFE' ]->id;
        // DRS
        if ( $this->drs->drsInit )
        {
          $prompt = 'main.pid is empty. pid is set to the id of the current page: ' . $this->pid;
          t3lib_div::devlog( ' [INFO/INIT] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        break;
    }

    $this->initPidClasses();
  }

  /**
   * initPidClasses( ) :
   *
   * @return	void
   * @version 4.0.3
   * @since   4.0.3
   */
  private function initPidClasses()
  {
    $this->clean->cleanInitPidCaddy( $this->pid );
    $this->dynamicMarkers->initPidCaddy( $this->pid );
  }

  /**
   * initPowermail( )
   *
   * @return	void
   * @access private
   * @internal   #45915
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initPowermail()
  {
    $this->powermail->init( $this->cObj->data );
  }

  /**
   * initTemplate( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initTemplate()
  {
    $this->tmpl = $this->template->main();
  }

  /*   * *********************************************
   *
   * Powermail
   *
   * ******************************************** */

  /**
   * send( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function send()
  {
    $this->sendCustomer();
    $this->sendVendor();
  }

  /*   * *********************************************
   *
   * Send
   *
   * ******************************************** */

  /**
   * powermailDataToSession( )
   *
   * @return	void
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function powermailDataToSession()
  {
    if ( !$this->powermailData() )
    {
      return;
    }

    $pmUid = $this->flexform->emailCustomerEmail;
    $emailCustomer = $this->powermail->getFieldById( $pmUid );

    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    $sesArray[ 'powermail' ][ 'values' ][ 'customer' ][ 'email' ] = $emailCustomer;
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * powermailData( ):
   *
   * @return	boolean
   * @access private
   * @version     4.0.5
   * @since       4.0.5
   */
  private function powermailData()
  {
    $GP = t3lib_div::_GET() + t3lib_div::_POST()
    ;

    switch ( true )
    {
//      case( $GP['tx_powermail_pi1']['action'] == 'confirmation' ):
//      case( $GP['tx_powermail_pi1']['action'] == 'create' ):
//      case( $GP['tx_powermail_pi1']['action'] == 'form' ):
      case( $GP[ 'tx_powermail_pi1' ] ):
        return true;
        break;
      default:
        return false;
        break;
    }

    unset( $GP );
  }

  /**
   * sendCustomer( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendCustomer()
  {
    $this->sendCustomerDeliveryorder();
    $this->sendCustomerInvoice();
    $this->sendCustomerRevocation();
    $this->sendCustomerTerms();
  }

  /**
   * sendCustomerDeliveryorder( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendCustomerDeliveryorder()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailDeliveryorderMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray[ 'sendCustomerDeliveryorder' ] = $this->flexform->pathsDeliveryorder;
        break;
      default:
        unset( $sesArray[ 'sendCustomerDeliveryorder' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendCustomerInvoice( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendCustomerInvoice()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailInvoiceMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray[ 'sendCustomerInvoice' ] = $this->flexform->pathsInvoice;
        break;
      default:
        unset( $sesArray[ 'sendCustomerInvoice' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendCustomerRevocation( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendCustomerRevocation()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailRevocationMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray[ 'sendCustomerRevocation' ] = $this->flexform->pathsRevocation;
        break;
      default:
        unset( $sesArray[ 'sendCustomerRevocation' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendCustomerTerms( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendCustomerTerms()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailTermsMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray[ 'sendCustomerTerms' ] = $this->flexform->pathsTerms;
        break;
      default:
        unset( $sesArray[ 'sendCustomerTerms' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendVendor( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendVendor()
  {
    $this->sendVendorDeliveryorder();
    $this->sendVendorInvoice();
    $this->sendVendorRevocation();
    $this->sendVendorTerms();
  }

  /**
   * sendVendorDeliveryorder( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendVendorDeliveryorder()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailDeliveryorderMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray[ 'sendVendorDeliveryorder' ] = $this->flexform->pathsDeliveryorder;
        break;
      default:
        unset( $sesArray[ 'sendVendorDeliveryorder' ] );
        break;
    }
    // #54634, 131229, dwildt, 1+
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1-
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendVendorInvoice( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendVendorInvoice()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailInvoiceMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray[ 'sendVendorInvoice' ] = $this->flexform->pathsInvoice;
        break;
      default:
        unset( $sesArray[ 'sendVendorInvoice' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendVendorRevocation( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendVendorRevocation()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailRevocationMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray[ 'sendVendorRevocation' ] = $this->flexform->pathsRevocation;
        break;
      default:
        unset( $sesArray[ 'sendVendorRevocation' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sendVendorTerms( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sendVendorTerms()
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid );
    switch ( $this->flexform->emailTermsMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray[ 'sendVendorTerms' ] = $this->flexform->pathsTerms;
        break;
      default:
        unset( $sesArray[ 'sendVendorTerms' ] );
        break;
    }
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /*   * *********************************************
   *
   * Update Wizard
   *
   * ******************************************** */

  /**
   * updateWizard( )
   *
   * @param	string		$content  : current content
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function updateWizard( $content )
  {
    // RETURN : update wizard is disabled
    if ( !$this->flexform->sdefUpdatewizard )
    {
      return $content;
    }
    // RETURN : update wizard is disabled
    // RETURN : current IP isn't part of list with allowed IP
    if ( !$this->accessByIP )
    {
      return $content;
    }
    // RETURN : current IP isn't part of list with allowed IP

    require_once( PATH_typo3conf . 'ext/' . $this->extKey . '/lib/updatewizard/class.tx_caddy_pi1_updatewizard.php' );

    // Class with methods for Update Checking
    $updatewizard = new tx_caddy_pi1_updatewizard( $this );
    $content = $updatewizard->main( $content );
    // Current IP has access

    return $content;
  }

  /*   * *********************************************
   *
   * ZZ
   *
   * ******************************************** */

  /**
   * zz_cObjGetSingle( ) : Renders a typoscript property with cObjGetSingle, if it is an array.
   *                       Otherwise returns the property unchanged.
   *
   * @param	string		$cObj_name  : value or the name of the property like TEXT, COA, IMAGE, ...
   * @param	array		$cObj_conf  : null or the configuration of the property
   * @return	string		$value      : unchanged value or rendered typoscript property
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch ( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case(!( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/pi1/class.tx_caddy_pi1.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/pi1/class.tx_caddy_pi1.php' ]);
}
?>
