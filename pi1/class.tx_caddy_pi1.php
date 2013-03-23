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

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   97: class tx_caddy_pi1 extends tslib_pibase
 *
 *              SECTION: Main
 *  154:     public function main( $content, $conf )
 *
 *              SECTION: Caddy
 *  228:     private function caddyProductAdd( )
 *  250:     private function caddyProductDelete( )
 *  266:     private function caddyRendered( )
 *  280:     private function caddyUpdate( )
 *
 *              SECTION: Clean
 *  323:     private function clean( )
 *
 *              SECTION: Debug
 *  345:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  382:     private function init( )
 *  402:     private function initAccessByIp( )
 *  448:     private function initDatabase( )
 *  461:     private function initDatabaseTable( )
 *  512:     private function initFlexform( )
 *  525:     private function initGpVar( )
 *  581:     private function initGpVarCid( )
 *  631:     private function initInstances( )
 *  681:     private function initPid( )
 *  720:     private function initPowermail( )
 *  733:     private function initTemplate( )
 *
 *              SECTION: Send
 *  754:     private function send( )
 *  768:     private function sendCustomer( )
 *  783:     private function sendCustomerDeliveryorder( )
 *  808:     private function sendCustomerInvoice( )
 *  833:     private function sendCustomerTerms( )
 *  858:     private function sendVendor( )
 *  873:     private function sendVendorDeliveryorder( )
 *  898:     private function sendVendorInvoice( )
 *  923:     private function sendVendorTerms( )
 *
 *              SECTION: Update Wizard
 *  957:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1002:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 29
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       1.4.6
 */
class tx_caddy_pi1 extends tslib_pibase
{

  public $extKey        = 'caddy';
  public $prefixId      = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';


  private $accessByIP = null;

  private $product = array( );

  private $newProduct = array( );

  private $markerArray = array( );

  private $outerMarkerArray = array( );

  public  $gpvar = array( );

  public $pid = null;

  private $caddy            = null;
  private $clean            = null;
  private $dynamicMarkers   = null;
  public  $drs              = null;
  public  $flexform         = null;
  public  $powermail        = null;
  private $session          = null;
  private $template         = null;

  public  $local_cObj       = null;
  public  $conf             = null;
  public  $arr_extConf      = null;
  public  $tmpl             = null;

  private $numberDeliveryorderCurrent   =  null;
  private $numberDeliveryorderRegistry  =  null;
  private $numberInvoiceCurrent         =  null;
  private $numberInvoiceRegistry        =  null;
  private $numberOrderCurrent           =  null;
  private $numberOrderRegistry          =  null;

  



  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    2.0.0
  * @since      1.4.6
  */
  public function main( $content, $conf )
  {
      // page object
    $this->local_cObj = $GLOBALS['TSFE']->cObj;

    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
      // Init extension configuration array
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );
      // init flexform
    $this->pi_initPIflexForm();

      // 130227, dwildt, 1-
    //$this->pi_USER_INT_obj = 1;

      // Init DRS, flexform, gpvars, HTML template, service attributes
    $this->init( );

      // Prompt of the update wizard
    $content = $this->updateWizard( $content );

      // Output debugging prompts in debug mode
    $this->debugOutputBeforeRunning( );

      // Remove current product
    $this->caddyProductDelete( );
      // Update several order values
    $this->caddyUpdate( );
      // Add a product
    $this->caddyProductAdd( );

      // Get the caddy
//$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
//var_dump( __METHOD__, __LINE__, $sesArray );
    $arrResult              = $this->caddyRendered( );
    $caddy                  = $arrResult['caddy'];
    $this->tmpl             = $arrResult['tmpl'];
    $this->outerMarkerArray = $arrResult['outerMarkerArray'];
    unset( $arrResult );

    $content = $this->powermail->formCss( $content );

    $this->send( );

    $this->clean( );

    $content = $content . $this->cObj->substituteMarkerArrayCached
                          (
                            $this->tmpl['all'],
                            $this->outerMarkerArray,
                            $caddy
                          );
//var_dump( __METHOD__, __LINE__ , $this->tmpl['all'] ) ;      

    $content = $this->dynamicMarkers->main( $content, $this ); // Fill dynamic locallang or typoscript markers
    $content = preg_replace( '|###.*?###|i', '', $content ); // Finally clear not filled markers
    return $this->pi_wrapInBaseClass( $content );
  }



  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddyProductAdd( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyProductAdd( )
  {
    // add further product to session
    $this->newProduct = $this->session->productGetDetails( $this->gpvar );
    if( $this->newProduct !== false )
    {
      $this->newProduct['qty']                  = $this->gpvar['qty'];
      $this->newProduct['service_attribute_1']  = $this->gpvar['service_attribute_1'];
      $this->newProduct['service_attribute_2']  = $this->gpvar['service_attribute_2'];
      $this->newProduct['service_attribute_3']  = $this->gpvar['service_attribute_3'];
      $this->session->productAdd( $this->newProduct );
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
  private function caddyProductDelete( )
  {
      // remove product from session
    if( isset( $this->piVars['del'] ) )
    {
      $this->session->productDelete( );
    }
  }
 /**
  * caddyRendered( )
  *
  * @return	array		$caddy : ...
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyRendered( )
  {
    $arrReturn = $this->caddy->caddy( );
    return $arrReturn;
  }

 /**
  * caddyUpdate( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyUpdate( )
  {
      // change qty
    if( isset( $this->piVars['qty'] ) && is_array( $this->piVars['qty'] ) )
    {
      $this->session->quantityUpdate( ); // change qty
    }

      // change shipping
    if( isset( $this->piVars['shipping'] ) )
    {
      $this->session->shippingUpdate($this->piVars['shipping']); // change shipping
    }

    // change payment
    if( isset( $this->piVars['payment'] ) )
    {
      $this->session->paymentUpdate($this->piVars['payment']); // change payment
    }

      // change special
    $special = null;
    if( isset( $this->piVars['special'] ) )
    {
      $special = $this->piVars['special'];
    }
    $this->session->specialUpdate( $this->piVars['special'] ); // change payment
  }



  /***********************************************
  *
  * Clean
  *
  **********************************************/

 /**
  * clean( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function clean( )
  {
    $this->clean->main( );
  }




  /***********************************************
  *
  * Debug
  *
  **********************************************/

 /**
  * debugOutputBeforeRunning( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function debugOutputBeforeRunning( )
  {
      // RETURN : Don't output debug prompt
    if( ! $this->conf['debug'] )
    {
      return;
    }
      // RETURN : Don't output debug prompt

      // output debug prompt
    t3lib_div::debug
    (
      $this->session->productsGet(), $this->extKey . ': ' . 'Values in session at the beginning'
    );
    t3lib_div::debug($this->gpvar, $this->extKey . ': ' . 'Given params');
    t3lib_div::debug($this->conf, $this->extKey . ': ' . 'Typoscript configuration');
    t3lib_div::debug($_POST, $this->extKey . ': ' . 'All POST variables');
      // output debug prompt
  }




  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
    $this->initInstances( );
    $this->drs->init( );
    $this->initFlexform( );
    $this->initPid( );
    $this->initAccessByIp( );
    $this->initTemplate( );
    $this->initGpVar( );
    $this->initPowermail( );
    $this->initDatabase( );
    $this->initNumbers( );
    
    $this->caddy->setParentObject( $this );
    $this->caddy->setContentRow( $this->cObj->data );
    
    $this->session->setParentObject( $this );
  }

/**
 * initAccessByIp( ): Set the global $bool_accessByIP.
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function initAccessByIp( )
  {
      // No access by default
    $this->accessByIP = false;

      // Get list with allowed IPs
    $csvIP      = $this->flexform->sdefCsvallowedip;
    $currentIP  = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

      // Current IP is an element in the list
    $pos = strpos( $csvIP, $currentIP );
    if( ! ( $pos === false ) )
    {
      $this->accessByIP = true;
    }
      // Current IP is an element in the list

      // DRS
    if( ! $this->drs->drsInit )
    {
      return;
    }

    switch( $this->accessByIP )
    {
      case( true ):
        $prompt = 'Access: current IP matchs the list of allowed IP. Result will prompt to the frontend.';
        t3lib_div::devlog(' [OK/INIT] '. $prompt, $this->extKey, -1 );
        break;
      case( false ):
      default:
        $prompt = 'No access: current IP doesn\'t match the list of allowed IP. Result won\'t prompt to the frontend.';
        t3lib_div::devlog(' [WARN/INIT] '. $prompt, $this->extKey, 2 );
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
  private function initDatabase( )
  {
    $this->initDatabaseTable( );
  }

 /**
  * initDatabaseTable( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDatabaseTable( )
  {
    $table = $this->conf['db.']['table'];

    //echo $GLOBALS['TCA'][$table]['columns'] ) )
    // RETURN : TCA is loaded

      // Load the TCA
    t3lib_div::loadTCA( $table );

    if( ! empty ( $GLOBALS['TCA'][$table] ) )
    {
        // DRS
      if( $this->drs->drsInit )
      {
        $prompt = $table . ' is an element of the $TCA.';
        t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
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
  private function initFlexform( )
  {
    $this->flexform->main( );
  }


 /**
  * initNumbers( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initNumbers( )
  {
    $registry =  t3lib_div::makeInstance('t3lib_Registry');
    $prefix = 'page_' . $GLOBALS["TSFE"]->id . '_';

    $this->numberDeliveryorderRegistry  = ( int ) $registry->get( 'tx_caddy', $prefix . 'deliveryorder' );
    $this->numberInvoiceRegistry        = ( int ) $registry->get( 'tx_caddy', $prefix . 'invoice' );
    $this->numberOrderRegistry          = ( int ) $registry->get( 'tx_caddy', $prefix . 'order' );

    $this->numberDeliveryorderCurrent   = $this->numberDeliveryorderRegistry
                                        + $this->flexform->originDeliveryorder
                                        + 1;
    $this->numberInvoiceCurrent         = $this->numberInvoiceRegistry
                                        + $this->flexform->originInvoice
                                        + 1;
    $this->numberOrderCurrent           = $this->numberOrderRegistry
                                        + $this->flexform->originOrder
                                        + 1;
    
      // DRS
    if( $this->drs->drsInit )
    {
      $prompt = 'Delivery order number from registry: ' . $this->numberDeliveryorderRegistry;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Order number from registry: ' .          $this->numberOrderRegistry;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Invoice number from registry: ' .        $this->numberInvoiceRegistry;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Current delivery order number: ' .       $this->numberDeliveryorderCurrent;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Current invoice number: ' .              $this->numberInvoiceCurrent;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Current order number: ' .                $this->numberOrderCurrent;
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
    }
      // DRS
    
    $this->initNumbersSession( );
  }

 /**
  * initNumbersSession( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initNumbersSession( )
  {
    $sesArray = array( );
      // get already exting session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
  
    $sesArray['numberDeliveryorderCurrent']  = $this->numberDeliveryorderCurrent  ;
    $sesArray['numberInvoiceCurrent']        = $this->numberInvoiceCurrent  ;
    $sesArray['numberOrderCurrent']          = $this->numberOrderCurrent  ;
    
      // generate session with session array
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
      // save session
    $GLOBALS['TSFE']->storeSessionData();

      // DRS
    if( $this->drs->drsInit )
    {
      $prompt = 'Current numbers are stored in the session.';
      t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * initGpVar( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initGpVar( )
  {
    $conf = $this->conf;

      // read variables
    $this->gpvar['title'] = $this->zz_cObjGetSingle( $conf['settings.']['title'], $conf['settings.']['title.'] );
    $this->gpvar['price'] = $this->zz_cObjGetSingle( $conf['settings.']['price'], $conf['settings.']['price.'] );
    $this->gpvar['qty']   = intval( $this->zz_cObjGetSingle( $conf['settings.']['qty'], $conf['settings.']['qty.'] ) );
    $this->gpvar['tax']   = $this->zz_cObjGetSingle( $conf['settings.']['tax'], $conf['settings.']['tax.'] );
    $this->gpvar['puid']  = intval( $this->zz_cObjGetSingle( $conf['settings.']['puid'], $conf['settings.']['puid.'] ) );
    $this->gpvar['cid']   = intval ($this->zz_cObjGetSingle( $conf['settings.']['cid'], $conf['settings.']['cid.'] ) );

    $this->gpvar['sku'] = $this->zz_cObjGetSingle( $conf['settings.']['sku'], $conf['settings.']['sku.'] );
    $this->gpvar['min'] = $this->zz_cObjGetSingle( $conf['settings.']['min'], $conf['settings.']['min.'] );
    $this->gpvar['max'] = $this->zz_cObjGetSingle( $conf['settings.']['max'], $conf['settings.']['max.'] );

    $this->gpvar['service_attribute_1'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_1'],
                                              $conf['settings.']['service_attribute_1.']
                                            )
                                          );
    $this->gpvar['service_attribute_2'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_2'],
                                              $conf['settings.']['service_attribute_2.']
                                            )
                                          );
    $this->gpvar['service_attribute_3'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_3'],
                                              $conf['settings.']['service_attribute_3.']
                                            )
                                          );
    $this->initGpVarCid( );

    if ($this->gpvar['qty'] === 0)
    {
      $this->gpvar['qty'] = 1;
    }
  }

 /**
  * initGpVarCid( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initGpVarCid( )
  {
    if( ! $this->gpvar['cid'] )
    {
      return;
    }

    $row=$this->pi_getRecord('tt_content', $this->gpvar['cid'] );
    $flexformData = t3lib_div::xml2array($row['pi_flexform'] );

    $this->gpvar['puid']  = $this->pi_getFFvalue( $flexformData, 'puid', 'sDEF' );
    $this->gpvar['title'] = $this->pi_getFFvalue( $flexformData, 'title', 'sDEF' );
    $this->gpvar['sku']   = $this->pi_getFFvalue( $flexformData, 'sku', 'sDEF' );
    $this->gpvar['price'] = $this->pi_getFFvalue( $flexformData, 'price', 'sDEF' );
    $this->gpvar['tax']   = $this->pi_getFFvalue( $flexformData, 'tax', 'sDEF' );

    $attributes = split( "\n", $this->pi_getFFvalue( $flexformData, 'attributes', 'sDEF' ) );

    foreach ( $attributes as $line )
    {
      list($key, $value) = explode("==", $line, 2);
      switch ( $key )
      {
        case 'service_attribute_1':
          $this->gpvar['service_attribute_1'] = floatval($value);
          break;
        case 'service_attribute_2':
          $this->gpvar['service_attribute_2'] = floatval($value);
          break;
        case 'service_attribute_3':
          $this->gpvar['service_attribute_3'] = floatval($value);
          break;
        case 'min':
          $this->gpvar['min'] = intval($value);
          break;
        case 'max':
          $this->gpvar['max'] = intval($value);
          break;
      }
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
  private function initInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy            = t3lib_div::makeInstance( 'tx_caddy' );
      // Next two line will set in init( ) 
//    $this->caddy->setParentObject( $this );
//    $this->caddy->setContentRow( $this->cObj->data );

      // Class with methods for get clean values
    require_once( 'class.tx_caddy_pi1_clean.php' );
    $this->clean            = t3lib_div::makeInstance( 'tx_caddy_pi1_clean' );
    $this->clean->pObj      = $this;
    $this->clean->row       = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers   = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

      // Class with methods for get flexform values
    require_once( 'class.tx_caddy_pi1_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi1_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;

    require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
    $this->powermail        = t3lib_div::makeInstance( 'tx_caddy_powermail' );
    $this->powermail->pObj  = $this;

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );

    require_once( $path2lib . 'class.tx_caddy_template.php' );
    $this->template         = t3lib_div::makeInstance( 'tx_caddy_template' );
    $this->template->pObj   = $this;

  }

/**
 * initPid( ) :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function initPid( )
  {
    $pid = ( int ) $this->conf['main']['pid'];

    switch( true )
    {
      case( ! empty( $pid ) ):
        $this->pid = $pid;
          // DRS
        if( $this->drs->drsInit )
        {
          $prompt = 'pid is taken from main.pid and is set to ' . $this->pid;
          t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
        }
          // DRS
        break;
      case( empty( $pid ) ):
      default:
        $this->pid = $GLOBALS['TSFE']->id;
          // DRS
        if( $this->drs->drsInit )
        {
          $prompt = 'main.pid is empty. pid is set to the id of the current page: ' . $this->pid;
          t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
        }
          // DRS
        break;
    }
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
  private function initPowermail( )
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
  private function initTemplate( )
  {
    $this->tmpl = $this->template->main( );
  }



  /***********************************************
  *
  * Send
  *
  **********************************************/

 /**
  * send( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function send( )
  {
    $this->sendCustomer( );
    $this->sendVendor( );
  }

 /**
  * sendCustomer( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomer( )
  {
    $this->sendCustomerDeliveryorder( );
    $this->sendCustomerInvoice( );
    $this->sendCustomerRevocation( );
    $this->sendCustomerTerms( );
  }

 /**
  * sendCustomerDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerDeliveryorder( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailDeliveryorderMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray['sendCustomerDeliveryorder'] = $this->flexform->pathsDeliveryorder;
        break;
      default:
        unset( $sesArray['sendCustomerDeliveryorder'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendCustomerInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerInvoice( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailInvoiceMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray['sendCustomerInvoice'] = $this->flexform->pathsInvoice;
        break;
      default:
        unset( $sesArray['sendCustomerInvoice'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendCustomerRevocation( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerRevocation( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailRevocationMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray['sendCustomerRevocation'] = $this->flexform->pathsRevocation;
        break;
      default:
        unset( $sesArray['sendCustomerRevocation'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendCustomerTerms( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerTerms( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailTermsMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray['sendCustomerTerms'] = $this->flexform->pathsTerms;
        break;
      default:
        unset( $sesArray['sendCustomerTerms'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendVendor( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendor( )
  {
    $this->sendVendorDeliveryorder( );
    $this->sendVendorInvoice( );
    $this->sendVendorRevocation( );
    $this->sendVendorTerms( );
  }

 /**
  * sendVendorDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorDeliveryorder( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailDeliveryorderMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray['sendVendorDeliveryorder'] = $this->flexform->pathsDeliveryorder;
        break;
      default:
        unset( $sesArray['sendVendorDeliveryorder'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendVendorInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorInvoice( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailInvoiceMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray['sendVendorInvoice'] = $this->flexform->pathsInvoice;
        break;
      default:
        unset( $sesArray['sendVendorInvoice'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendVendorRevocation( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorRevocation( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailRevocationMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray['sendVendorRevocation'] = $this->flexform->pathsRevocation;
        break;
      default:
        unset( $sesArray['sendVendorRevocation'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sendVendorTerms( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorTerms( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    switch( $this->flexform->emailTermsMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray['sendVendorTerms'] = $this->flexform->pathsTerms;
        break;
      default:
        unset( $sesArray['sendVendorTerms'] );
        break;
    }
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    $GLOBALS['TSFE']->storeSessionData( );
  }



  /***********************************************
  *
  * Update Wizard
  *
  **********************************************/

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
    if( ! $this->flexform->sdefUpdatewizard )
    {
      return $content;
    }
      // RETURN : update wizard is disabled

      // RETURN : current IP isn't part of list with allowed IP
    if( ! $this->accessByIP )
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




  /***********************************************
  *
  * ZZ
  *
  **********************************************/

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
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1.php']);
}
?>
