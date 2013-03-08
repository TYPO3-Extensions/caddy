<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   78: class tx_caddy_powermail
 *
 *              SECTION: Powermail
 *  136:     public function formCss( $content )
 *  173:     public function formHide( )
 *  201:     public function formShow( )
 *
 *              SECTION: Init
 *  229:     public function init( $row )
 *  300:     private function initFields( $row )
 *  436:     private function initGetPost( )
 *  450:     private function initMarker( )
 *  466:     private function initMarkerReceiver( )
 *  486:     private function initMarkerReceiverWtcart( )
 *  506:     private function initMarkerSender( )
 *  526:     private function initMarkerSenderWtcart( )
 *  546:     private function initSend( )
 *  585:     private function initSend1x( )
 *  614:     private function initSend1xWiConfirm( )
 *  651:     private function initSend1xWoConfirm( )
 *  687:     private function initSend2x( )
 *  716:     private function initSend2xWiConfirm( )
 *  737:     private function initSend2xWoConfirm( )
 *  759:     private function initVersion( )
 *
 *              SECTION: Send
 *  780:     public function sendToCustomerDeliveryorder( )
 *  795:     public function sendToVendorDeliveryorder( )
 *  810:     public function sendToCustomerInvoice( )
 *  825:     public function sendToVendorInvoice( )
 *  840:     public function sendToCustomerTerms( )
 *  855:     public function sendToVendorTerms( )
 *
 * TOTAL FUNCTIONS: 25
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once( PATH_tslib . 'class.tslib_pibase.php');

/**
 * powermail controlling for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       2.0.0
 */
class tx_caddy_powermail extends tslib_pibase
{

  public $prefixId = 'tx_caddy_powermail';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

  public $pObj = null;
  public $conf = null;


  public  $drsUserfunc = false;

  public  $fieldFfConfirm      = null;
  public  $fieldFfMailreceiver = null;
  public  $fieldFfMailsender   = null;
  public  $fieldFfThanks       = null;
  public  $fieldFormCss        = null;
  public  $fieldUid            = null;
  public  $fieldTitle          = null;

  public  $markerReceiver       = null;
  public  $markerReceiverWtcart = null;
  public  $markerSender         = null;
  public  $markerSenderWtcart   = null;
  public  $markerThanks         = null;
  private $markerTsCaddy        = '###POWERMAIL_TYPOSCRIPT_CADDY###';
  private $markerTsThanks       = '###POWERMAIL_TYPOSCRIPT_CLEARCADDYSESSION###';
  private $markerTsWtcart       = '###POWERMAIL_TYPOSCRIPT_CART###';

    // Current GET parameter
  private $paramGet  = null;
    // Current POST parameter
  private $paramPost = null;

    // True, if powermail form is sent
  public $sent  = null;

  public $versionInt  = null;
  public $versionStr  = null;
  
    // [Object]
  private $pdf      = null;
    // [Object]
  private $userfunc = null;
  
    // caddyForEmail
  public  $cartCount                = 0;
  public  $cartServiceAttribute1Sum = 0;
  public  $cartServiceAttribute1Max = 0;
  public  $cartServiceAttribute2Sum = 0;
  public  $cartServiceAttribute2Max = 0; 
  public  $cartServiceAttribute3Sum = 0;
  public  $cartServiceAttribute3Max = 0;
  private $content                  = null;
  private $markerArray              = null;
  private $outerMarkerArray         = null;
  private $tmpl                     = null;
  private $calc                     = null;
  private $dynamicMarkers           = null;
  private $render                   = null;
  private $session                  = null;
  private $product                  = null;


  
  
 /***********************************************
  *
  * Caddy
  *
  **********************************************/




 /**
  * caddyForEmail( )  : Returns a caddy rendered for an e-mail
  *
  * @return  string    cart content
  * @access public
  * @version 2.0.0
  * @since  1.4.6
  */
  public function caddyForEmail( $content = '', $conf = array( ) )
  {   
    unset( $content );
    
    $this->pi_loadLL();

      // DRS
    if( $conf['userFunc.']['drs'] )
    {
      $this->drsUserfunc = true;
      $drs               = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
      // Get the typoscript configuration of the caddy plugin 1
    $this->conf = $this->caddyForEmailInitConf( );
      // Get the HTML template for CADDY_POWERMAIL
    $this->tmpl = $this->caddyForEmailInitTemplate( );
    
    $this->cObj = $GLOBALS['TSFE']->cObj;
    $local_cObj = $GLOBALS['TSFE']->cObj;

      // Init instances
    $this->caddyForEmailInstances( );
    

    $shipping_option  = '';
    $payment_option   = '';
    $cartNet                        = 0; 
    $cartGross                      = 0;
    $cartTaxReduced                 = 0;
    $cartTaxNormal                  = 0;

    $arrResult = $this->caddyForEmailProducts( );
    $content_item     = '';

      // read all products from session
    $this->product = $this->session->productsGet( );
    
      // RETURN : empty content, no product in session
    if( count( $this->product ) < 1 )
    {
        // DRS
      if( $this->drs->drsSession || $drs )
      {
        $prompt = __METHOD__ . ' returns null[userFunc.][drs].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      $this->content = ''; // clear content
      return $this->content;
    }
      // RETURN : empty content, no product in session

    $arrResult = $this->caddy->calcProduct( );
var_dump( __METHOD__, __LINE__, $arrResult );
die( );
      // LOOP : products
    foreach( ( array ) $this->product as $product )
    {
      $product['price_total'] = $product['price'] * $product['qty']; // price total
      $local_cObj->start($product, $this->conf['db.']['table']); // enable .field in typoscript
      foreach ((array) $this->conf['settings.']['powermailCaddy.']['fields.'] as $key => $value)
      { // one loop for every param of the current product
        if (!stristr($key, '.'))
        { // no .
          $this->markerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['powermailCaddy.']['fields.'][$key], $this->conf['settings.']['powermailCaddy.']['fields.'][$key . '.']); // write to marker
        }
      }
      $content_item .= $this->cObj->substituteMarkerArrayCached($this->tmpl['item'], $this->markerArray); // add inner html to variable

      $cartGross += $product['price_total'];
      $this->cartCount += $product['qty'];

      $this->cartServiceAttribute1Sum += $product['service_attribute_1'] * $product['qty'];
      $this->cartServiceAttribute1Max = $this->cartServiceAttribute1Max > $product['service_attribute_1'] ? $this->cartServiceAttribute1Max : $product['service_attribute_1'];
      $this->cartServiceAttribute2Sum += $product['service_attribute_2'] * $product['qty'];
      $this->cartServiceAttribute2Max = $this->cartServiceAttribute2Max > $product['service_attribute_2'] ? $this->cartServiceAttribute2Max : $product['service_attribute_2'];
      $this->cartServiceAttribute3Sum += $product['service_attribute_3'] * $product['qty'];
      $this->cartServiceAttribute3Max = $this->cartServiceAttribute3Max > $product['service_attribute_3'] ? $this->cartServiceAttribute3Max : $product['service_attribute_3'];

      $cartNet += ( $product['price_total'] - $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']));

      if ($product['tax'] == 1)
      { // reduced tax
        $cartTaxReduced += $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']); // add tax from this product to overall
      } else { // normal tax
        $cartTaxNormal += $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']); // add tax from this product to overall
      }
    }
      // LOOP : products

    $subpartArray['###CONTENT###'] = $content_item; // work on subpart 3

    $cartGrossNoService = $cartGross;
    $cartNetNoService = $cartNet;

    // calculate pice incl. shipping
    $shipping_id = $this->session->shippingGet();

    if ($shipping_id) 
    {
      $shipping_values	= $this->calc->calculateOptionById($this->conf, 'shipping', $shipping_id, $this);
      $shipping_net		= $shipping_values['net'];
      $shipping_gross		= $shipping_values['gross'];
      $cartNet			+= $shipping_values['net'];
      $cartGross			+= $shipping_values['gross'];
      if ($this->conf['shipping.']['options.'][$shipping_id . '.']['tax'] == 'reduced') {
        $cartTaxReduced += $shipping_gross - $shipping_net;
      } else {
        $cartTaxNormal	+= $shipping_gross - $shipping_net;	
      }

      $shipping_option	= $this->render->renderOptionById($this->conf, 'shipping', $shipping_id, $this);
    }

    // calculate pice incl. payment
    $payment_id = $this->session->paymentGet();

    if ($payment_id)
    {
      $payment_values		= $this->calc->calculateOptionById($this->conf, 'payment', $payment_id, $this);
      $payment_net		= $payment_values['net'];
      $payment_gross		= $payment_values['gross'];
      $cartNet			+= $payment_values['net'];
      $cartGross                += $payment_values['gross'];
      if ($this->conf['payment.']['options.'][$payment_id . '.']['tax'] == 'reduced') {
              $cartTaxReduced += $payment_gross - $payment_net;
      } else {
              $cartTaxNormal += $payment_gross - $payment_net;	
      }

      $payment_option 	= $this->render->renderOptionById($this->conf, 'payment', $payment_id, $this);

    }

    // calculate pice incl. specials
    $special_ids = $this->session->specialGet();

    $overall_special_gross = 0.0;
    $overall_special_net = 0.0;
    $overall_special_option = '';

    foreach( ( array ) $special_ids as $special_id )
    {
      $special_values		= $this->calc->calculateOptionById($this->conf, 'special', $special_id, $this);
      $special_net		= $special_values['net'];
      $special_gross		= $special_values['gross'];
      $cartNet			+= $special_values['net'];
      $cartGross			+= $special_values['gross'];
      if ($this->conf['special.']['options.'][$special_id . '.']['tax'] == 'reduced') {
              $cartTaxReduced += $special_gross - $special_net;
      } else {
              $cartTaxNormal += $special_gross - $special_net;	
      }
      $overall_special_gross += $special_gross;
      $overall_special_net   += $special_net;

      $overall_special_option .= $this->render->renderOptionById($this->conf, 'special', $special_id, $this);
    }

    $outerArr = array
                (
                  'optionsNet'      => ( double ) ( $shipping_net + $payment_net + $overall_special_net ),
                  'optionsGross'    => ( double ) ( $shipping_gross + $payment_gross+ $overall_special_gross ),
                  'sumGross'        => ( double ) $cartGross,
                  'productsGross'   => ( double ) $cartGrossNoService,
                  'sumNet'          => ( double ) $cartNet,
                  'productsNet'     => ( double ) $cartNetNoService,
                  'sumTaxReduced'   => ( double ) $cartTaxReduced,
                  'sumTaxNormal'    => ( double ) $cartTaxNormal,
                  'shipping_option' => $shipping_option,
                  'payment_option'  => $payment_option,
                  'special_option'  => $overall_special_option,
                  'ordernumber'     => $this->session->getNumberOrder()
                );
    $local_cObj->start( $outerArr, $this->conf['db.']['table'] );

    foreach ((array) $this->conf['settings.']['powermailCaddy.']['overall.'] as $key => $value)
    {
      if (!stristr($key, '.'))
      { // no .
        $this->outerMarkerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['powermailCaddy.']['overall.'][$key], $this->conf['settings.']['powermailCaddy.']['overall.'][$key . '.']);
      }
    }

    $this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['all'], $this->outerMarkerArray, $subpartArray); // Get html template
    $this->content = $this->dynamicMarkers->main($this->content, $this); // Fill dynamic locallang or typoscript markers
    $this->content = preg_replace('|###.*?###|i', '&nbsp;', $this->content); // Finally clear not filled markers

      // DRS
    if( $this->drs->drsSession || $drs )
    {
      $prompt = __METHOD__ . ' returns the caddy with products and calculation.';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    return $this->content;
  }
  
 /**
  * caddyForEmailInitConf( )  : Take the conf from plugin.tx_caddy_pi1
  *
  * @access private
  * @version 2.0.0
  * @since  2.0.0
  */
  private function caddyForEmailInitConf( )
  {   
    $conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $conf = array_merge( ( array ) $this->conf, ( array ) $conf );
    
    return $conf;
  }
  
 /**
  * caddyForEmailInitTemplate( )  : Get teh template CADDY_POWERMAIL
  *
  * @access private
  * @version 2.0.0
  * @since  2.0.0
  */
  private function caddyForEmailInitTemplate( )
  {   
    $tmpl     = null;
    $template = $this->cObj->fileResource( $this->conf['main.']['template'] );
    $marker   = '###CADDY_POWERMAIL###';

    $tmpl['all']  = $this->cObj->getSubpart( $template, $marker );
    $tmpl['item'] = $this->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2
    
    return $tmpl;
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyForEmailInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy            = t3lib_div::makeInstance( 'tx_caddy' );
    $this->caddy->setParentObject( $this );
    $this->caddy->setContentRow( $this->cObj->data );

    require_once( $path2lib . 'class.tx_caddy_calc.php' );
    $this->calc           = t3lib_div::makeInstance( 'tx_caddy_calc' );

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );
    
    require_once( $path2lib . 'class.tx_caddy_render.php' );
    $this->render         = t3lib_div::makeInstance( 'tx_caddy_render' );
    
    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session        = t3lib_div::makeInstance( 'tx_caddy_session' );
    
  } 

  
  
 /***********************************************
  *
  * Powermail
  *
  **********************************************/

 /**
  * formCss( ):  Returns $this->fieldFormCss.
  *                       * The CSS will hide the powermail form
  *                       * CSS is empty, if powermail form should displayed
  *
  * @param	[type]		$$content: ...
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formCss( $content )
  {
      // RETURN : there isn't any CSS for powermail
    if( empty( $this->fieldFormCss ) )
    {
        // DRS
      if( $this->pObj->drs->drsPowermail )
      {
        $prompt = 'Caddy contains a product, powermail form will displayed.';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return $content;
    }
      // RETURN : there isn't any CSS for powermail

//      // DRS
//    if( $this->pObj->drs->drsPowermail )
//    {
//      $prompt = 'Caddy is empty, powermail form will hidden.';
//      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
//    }
//      // DRS
    $content = $content . $this->fieldFormCss;
    return $content;
  }

 /**
  * formHide( ): Powermail form should be unvisible, CSS snippet is written to
  *                       $this->fieldFormCss
  *
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formHide( )
  {
    if( $this->pObj->drs->drsPowermail )
    {
      $prompt = 'Caddy is empty, powermail form will hidden by CSS: #c' . $this->fieldUid . ' {display: none;}';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }

    $this->fieldFormCss = '
      <style type="text/css">
        #c' . $this->fieldUid . ' {
          display: none;
        }
      </style>
      ';

    return $this->fieldFormCss;
  }

 /**
  * formShow( ): Powermail form should be visible, empty CSS snippet is written to
  *                       $this->fieldFormCss
  *
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formShow( )
  {
    $this->fieldFormCss = null;
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( ): Global vars are initiated:
  *                   * versionInt
  *                   * versionStr
  *                   * fieldUid
  *                   * fieldTitle
  *                   * fieldFfConfirm
  *
  * @param	[type]		$$row: ...
  * @return	void
  * @access public
  * @internal   #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function init( $row )
  {
    $this->initInstances( );
    
    $arrResult = $this->initVersion( );
    $this->versionInt = $arrResult['int'];
    $this->versionStr = $arrResult['str'];

    if( empty( $this->versionInt ) )
    {
        // DRS
      if( $this->pObj->drs->drsError )
      {
        $prompt = 'Powermail version is 0!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->pObj->extKey, 3 );
      }
        // DRS
      return;
    }

      // DRS
    if( $this->pObj->drs->drsPowermail )
    {
      $prompt = 'Powermail version is ' . $this->versionStr . ' ' .
                '(internal ' . $this->versionInt . ')';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $arrResult = $this->initFields( $row );
    $this->fieldFfConfirm       = $arrResult['ffConfirm'];
    $this->fieldFfMailreceiver  = $arrResult['ffMailreceiver'];
    $this->fieldFfMailsender    = $arrResult['ffMailsender'];
    $this->fieldFfThanks        = $arrResult['ffThanks'];
    $this->fieldTitle           = $arrResult['title'];
    $this->fieldUid             = $arrResult['uid'];

      // DRS
    if( $this->pObj->drs->drsPowermail )
    {
      $prompt = 'powermail.uid: "' . $this->fieldUid . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
      $prompt = 'powermail.title: "' . $this->fieldTitle . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
      $prompt = 'powermail.confirm: "' . $this->fieldFfConfirm . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $this->initMarker( );

      // GET- and POST-parameters
    $this->initGetPost( );

    $this->initSend( );

    return;

  }

 /**
  * initFields( ): Reads needed values of the powermail form from the database
  *                         and returns it
  *                         * uid
  *                         * title
  *                         * ffConfirm
  *
  * @param	[type]		$$row: ...
  * @return	array		$arr : uid, title, ffConfirm of the powermail form
  * @access private
  * @internal   #45915
  * @version 2.0.0
  * @since 2.0.0
  */
  private function initFields( $row )
  {
    $arrReturn = null;

      // Page uid
//    $pid = $this->pObj->cObj->data['pid'];
    $pid = $row['pid'];

    if( ! $pid )
    {
      $prompt = 'ERROR: unexpected result<br />
        pid is empty<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }

      // Query
    $select_fields  = '*';
    $from_table     = 'tt_content';
    $where_clause   = "pid = " . $pid . " AND hidden = 0 AND deleted = 0";
    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $where_clause = $where_clause . " AND CType = 'powermail_pi1'";
        break;
      case( $this->versionInt < 3000000 ):
        $where_clause = $where_clause . " AND list_type = 'powermail_pi1'";
        break;
      case( $this->versionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }
    $groupBy        = '';
    $orderBy        = 'sorting';
    $limit          = '1';
      // Query

      // DRS
    if( $this->pObj->drs->drsSql )
    {
      $query  = $GLOBALS['TYPO3_DB']->SELECTquery
                (
                  $select_fields,
                  $from_table,
                  $where_clause,
                  $groupBy,
                  $orderBy,
                  $limit
                );
      $prompt = $query;
      t3lib_div::devlog(' [INFO/SQL] '. $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // Execute SELECT
    $res =  $GLOBALS['TYPO3_DB']->exec_SELECTquery
            (
              $select_fields,
              $from_table,
              $where_clause,
              $groupBy,
              $orderBy,
              $limit
            );
      // Execute SELECT

      // Current powermail record
    $pmRecord =  $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

      // RETURN : no row
    if( empty( $pmRecord ) )
    {
      if( $this->pObj->drs->drsError )
      {
        $prompt = 'Abort. SQL query is empty!';
        t3lib_div::devlog(' [WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // RETURN : no row

    $pmUid    = $pmRecord['uid'];
    $pmTitle  = $pmRecord['header'];
    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $pmFfConfirm      = $pmRecord['tx_powermail_confirm'];
        $pmFfMailsender   = $pmRecord['tx_powermail_mailsender'];
        $pmFfMailreceiver = $pmRecord['tx_powermail_mailreceiver'];
        $pmFfThanks       = $pmRecord['tx_powermail_thanks'];         
        break;
      case( $this->versionInt < 3000000 ):
      default:
        $pmFlexform       = t3lib_div::xml2array( $pmRecord['pi_flexform'] );
        $pmFfConfirm      = $pmFlexform['data']['main']['lDEF']['settings.flexform.main.form']['vDEF'];
        $pmFfMailsender   = $pmFlexform['data']['main']['lDEF']['settings.flexform.sender.body']['vDEF'];
        $pmFfMailreceiver = $pmFlexform['data']['main']['lDEF']['settings.flexform.receiver.body']['vDEF'];
        $pmFfThanks       = $pmFlexform['data']['main']['lDEF']['settings.flexform.thx.body']['vDEF'];;         
        break;
    }

    $arrReturn['uid']             = $pmUid;
    $arrReturn['title']           = $pmTitle;
    $arrReturn['ffConfirm']       = $pmFfConfirm;
    $arrReturn['ffMailsender']    = $pmFfMailsender;
    $arrReturn['ffMailreceiver']  = $pmFfMailreceiver;
    $arrReturn['ffThanks']        = $pmFfThanks;

    return $arrReturn;
  }

 /**
  * initGetPost( ):
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initGetPost( )
  {
    $this->paramGet  = t3lib_div::_GET( 'tx_powermail_pi1' );
    $this->paramPost = t3lib_div::_POST( 'tx_powermail_pi1' );
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

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
  }



  /***********************************************
  *
  * Init Marker
  *
  **********************************************/

 /**
  * initMarker( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarker( )
  {
    $this->initMarkerReceiver( );
    $this->initMarkerReceiverWtcart( );
    $this->initMarkerSender( );
    $this->initMarkerSenderWtcart( );
    $this->initMarkerThanks( );
  }

 /**
  * initMarkerReceiver( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarkerReceiver( )
  {
    $this->markerReceiver = false;

      // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailreceiver, $this->markerTsCaddy );
    if( ! ( $pos === false ) )
    {
      $this->markerReceiver = true;
    }
  }

 /**
  * initMarkerReceiverWtcart( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarkerReceiverWtcart( )
  {
    $this->markerReceiverWtcart = false;

      // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailreceiver, $this->markerTsWtcart );
    if( ! ( $pos === false ) )
    {
      $this->markerReceiverWtcart = true;
    }
  }

 /**
  * initMarkerSender( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarkerSender( )
  {
    $this->markerSender = false;

      // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailsender, $this->markerTsCaddy );
    if( ! ( $pos === false ) )
    {
      $this->markerSender = true;
    }
  }

 /**
  * initMarkerSenderWtcart( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarkerSenderWtcart( )
  {
    $this->markerSenderWtcart = false;

      // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailsender, $this->markerTsWtcart );
    if( ! ( $pos === false ) )
    {
      $this->markerSenderWtcart = true;
    }
  }

 /**
  * initMarkerThanks( ):
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initMarkerThanks( )
  {
    $this->markerThanks = false;

      // Current IP is an element in the list
    $pos = strpos( $this->fieldFfThanks, $this->markerTsThanks );
    if( ! ( $pos === false ) )
    {
      $this->markerThanks = true;
    }
  }



  /***********************************************
  *
  * Init PDF
  *
  **********************************************/

 /**
  * initPdf( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initPdf( )
  {
    if( is_object ( $this->pdf ) )
    {
      return;
    }
      
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'pdf/class.tx_caddy_pdf.php' );
    $this->pdf         = t3lib_div::makeInstance( 'tx_caddy_pdf' );

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $this->pdf->drsUserfunc = true;
      $prompt = __METHOD__ . ': PDF object is initiated.';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
  }



  /***********************************************
  *
  * Init Send
  *
  **********************************************/

 /**
  * initSend( ): Set the global $send, if the powermail is sent
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend( )
  {
    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $this->initSend1x( );
        break;
      case( $this->versionInt < 3000000 ):
        $this->initSend2x( );
        break;
      case( $this->versionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }

    return;
  }

 /**
  * initSend1x( ) : Set the global $send, if the powermail is sent
  *                 for powermail version 1.x
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend1x( )
  {
    $this->sent = false;
    switch( true )
    {
      case( ! $this->fieldFfConfirm ):
          // Confirmation page is disabled
        $this->initSend1xWoConfirm( );
        break;
      case( $this->fieldFfConfirm ):
      default:
          // Confirmation page is enabled
        $this->initSend1xWiConfirm( );
        break;
    }

    return;
  }

 /**
  * initSend1xWiConfirm( )  : Set the global $send, if the powermail is sent
  *                           * for powermail version 1.x
  *                           * and an enabled confirmation mode
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend1xWiConfirm( )
  {
    $this->sent = false;
    if( ! empty( $this->paramGet['sendNow'] ) )
    {
      $this->sent = true;
        // DRS
      if( $this->pObj->drs->drsPowermail )
      {
        $prompt = 'Powermail form is sent. Version 1.x with confirmation mode and with $GET[sendNow].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return;
    }

      // DRS
    if( $this->pObj->drs->drsPowermail )
    {
      $prompt = 'Powermail form isn\'t sent. Version 1.x with confirmation mode but without any $GET[sendNow].';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return;
  }

 /**
  * initSend1xWoConfirm( )  : Set the global $send, if the powermail is sent
  *                           * for powermail version 1.x
  *                           * and a disabled confirmation mode
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend1xWoConfirm( )
  {
    $this->sent = false;
    if( ! empty( $this->paramGet['mailID'] ) )
    {
      $this->sent = true;
        // DRS
      if( $this->pObj->drs->drsPowermail )
      {
        $prompt = 'Powermail form is sent. Version 1.x without confirmation mode and with $GET[mailID].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return;
    }

      // DRS
    if( $this->pObj->drs->drsPowermail )
    {
      $prompt = 'Powermail form isn\'t sent. Version 1.x without confirmation mode and without any $GET[mailID].';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return;
  }

 /**
  * initSend2x( ) : Set the global $send, if the powermail is sent
  *                 for powermail version 2.x
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend2x( )
  {
    $this->sent = false;
    switch( true )
    {
      case( ! $this->fieldFfConfirm ):
          // Confirmation page is disabled
        $this->initSend2xWoConfirm( );
        break;
      case( $this->fieldFfConfirm ):
      default:
          // Confirmation page is enabled
        $this->initSend2xWiConfirm( );
        break;
    }

    return;
  }

 /**
  * initSend2xWiConfirm( )  : Set the global $send, if the powermail is sent
  *                           * for powermail version 2.x
  *                           * and an enabled confirmation mode
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend2xWiConfirm( )
  {
    $this->sent = false;
    if( ! empty( $this->paramGet['sendNow'] ) )
    {
      $this->sent = true;
    }

    return;
  }

 /**
  * initSend2xWoConfirm( )  : Set the global $send, if the powermail is sent
  *                           * for powermail version 2.x
  *                           * and a disabled confirmation mode
  *
  * @return	void
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initSend2xWoConfirm( )
  {
    $this->sent = false;
    if( ! empty( $this->paramGet['mailID'] ) )
    {
      $this->sent = true;
    }

    return;
  }



  /***********************************************
  *
  * Init Version
  *
  **********************************************/

 /**
  * initVersion( ):  Returns the version of powermail as an interger and a string.
  *                           I.e
  *                           * int: 1006006
  *                           * str: 1.6.6
  *
  * @return	array		$arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initVersion( )
  {   
    return $this->userfunc->extMgmVersion( 'powermail' );
  }



  /***********************************************
  *
  * params
  *
  **********************************************/

 /**
  * paramGetByKey( ) :  Return the value of the given key from the GET params.
  *
  * @param      string      $key    : The key of the value, which should returned
  * @return	string      $value  : The value of the given key
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  * 
  */
  public function paramGetByKey( $key )
  {
    $value = null;
    
    if( empty( $key ) )
    {
      $prompt = 'FATAL ERROR: paramGetByKey( $key ) is called with an empty uid<br />
                Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
                TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }
    
    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $prompt = 'TODO: powermail 2.x<br />
          Please maintain the code!<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 3000000 ):
        $prompt = 'TODO: powermail 2.x<br />
          Please maintain the code!<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }

    return $value;
  }

 /**
  * paramPostById( ) :  Return the value of the given uid from the POST params.
  *                     The uid is the uid only - without any prefix!
  *
  * @param      integer     $uid    : The uid of the value, which should returned
  * @return	string      $value  : The value of the given uid
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function paramPostById( $uid )
  {
    $value = null;
    
    if( empty( $uid ) )
    {
      $prompt = 'FATAL ERROR: paramPostById( $uid ) is called with an empty uid<br />
                Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
                TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }
    
    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $uidVersion1 = 'uid' . $uid;
        $value = $this->paramPost[$uidVersion1];
        break;
      case( $this->versionInt < 3000000 ):
        $prompt = 'TODO: powermail 2.x<br />
          Please maintain the code!<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }

    return $value;
  }



  /***********************************************
  *
  * Send
  *
  **********************************************/

 /**
  * sendToCustomer( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  public function sendToCustomer( $content = '', $conf = array( ) )
  {
    $path   = null;
    $paths  = null;
    
    unset( $content );
    
    $this->conf = $conf;
    
      // DRS
    if( $this->conf['userFunc.']['drs'] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
    $paths[] = $this->sendToCustomerDeliveryorder( );
    $paths[] = $this->sendToCustomerInvoice( );
    $paths[] = $this->sendToCustomerTerms( );
    
    if( empty( $paths ) ) 
    {
      return null;
    }
    
    $path = implode( ',', $paths );
    $path = ',' . $path . ',';
    
      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
    
  }

 /**
  * sendToCustomerDeliveryorder( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToCustomerDeliveryorder( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendCustomerDeliveryorder'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

    $this->initPdf( );
    $this->pdf->pObj  = $this;
    $path = $this->pdf->deliveryorder( );

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;

  }

 /**
  * sendToCustomerInvoice( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToCustomerInvoice( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendCustomerInvoice'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

    $this->initPdf( );
    $this->pdf->pObj  = $this;
    $path = $this->pdf->invoice( );

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
  }

 /**
  * sendToCustomerTerms( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToCustomerTerms( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendCustomerTerms'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

    $this->initPdf( );
    $this->pdf->pObj  = $this;
    $path = $this->pdf->terms( );

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
  }
  
 /**
  * sendToVendor( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  public function sendToVendor( $content = '', $conf = array( ) )
  {
    $path   = null;
    $paths  = null;
    
    unset( $content );
    
    $this->conf = $conf;
    
      // DRS
    if( $this->conf['userFunc.']['drs'] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
    $paths[] = $this->sendToVendorDeliveryorder( );
    $paths[] = $this->sendToVendorInvoice( );
    $paths[] = $this->sendToVendorTerms( );
    
    if( empty( $paths ) ) 
    {
      return null;
    }
    
    $path = implode( ',', $paths );
    $path = ',' . $path . ',';
    
      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
    
  }
  
 /**
  * sendToVendorDeliveryorder( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToVendorDeliveryorder( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendVendorDeliveryorder'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

    $this->initPdf( );
    $this->pdf->pObj  = $this;
    $path = $this->pdf->deliveryorder( );

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
  }

 /**
  * sendToVendorInvoice( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToVendorInvoice( )
  {
      
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendVendorInvoice'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

    $this->initPdf( );
    $this->pdf->pObj  = $this;
    $path = $this->pdf->invoice( );


      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
  }

 /**
  * sendToVendorTerms( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function sendToVendorTerms( )
  {      
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $path     = $sesArray['sendVendorTerms'];
    
    if( empty ( $path ) )
    {
        // DRS
      if( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return null;
    }

      // DRS
    if( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    
    return $path;
  }



  /***********************************************
  *
  * Session
  *
  **********************************************/


/**
 * sessionData( ):
 *
 * @return    string        The content that should be displayed on the website
 * @access  public
 * @version 2.0.0
 * @since   2.0.0
 */
  public function sessionData(  )
  {
    $content = null; 

    switch( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $content = $this->sessionDataVers1( );
        break;
      case( $this->versionInt < 3000000 ):
        $content = $this->sessionDataVers2( );
        break;
      case( $this->versionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }

    return $content;
  }

/**
 * sessionDataVers1( ):
 *
 * @return    string        The content that should be displayed on the website
 * @access  private
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sessionDataVers1(  )
  {
      // DIE  : $fieldUid is empty
    if( empty( $this->fieldUid ) )
    {
      $prompt = 'FATAL ERROR: powermail->fieldUid is empty.<br />
        Probably powermail->init( ) wasn\'t called.<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }
      // DIE  : $fieldUid is empty

      // Get the Powermail session data
    $uid  = $this->fieldUid;
    $key  = 'powermail_';
    $sessionData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $key . $uid );

      // RETURN: no session data
    if( empty( $sessionData ) )
    {
      if( $this->pObj->drsPowermail )
      {
        $prompt = 'There isn\'t any powermail session data (powermail 1.x)!';
        t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->extKey, 0 );
      }
      return null;
    }
      // RETURN: no session data

    return $sessionData;
  }

/**
 * sessionDataVers2( ):
 *
 * @return    string        The content that should be displayed on the website
 * @access  private
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sessionDataVers2(  )
  {
      // Get the Powermail session data
    $post = t3lib_div::_POST( 'tx_powermail_pi1' );
    $uid  = $post['form'];
    $key  = 'powermailFormstart';
    $sessionData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $key . $uid );

      // RETURN: no session data
    if( empty( $sessionData ) )
    {
      if( $this->pObj->drsPowermail )
      {
        $prompt = 'There isn\'t any powermail session data (powermail 2.x)!';
        t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->extKey, 0 );
      }
      return null;
    }
      // RETURN: no session data
    
    return $sessionData;
  }
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php']);
}
?>
