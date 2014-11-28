<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * powermail controlling for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	6.0.3
 * @since       2.0.0
 */
//class tx_caddy_powermail extends tslib_pibase
class tx_caddy_powermail extends tx_caddy_pi1
{

//  public $prefixId = 'tx_caddy_powermail';
//  // same as class name
//  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
//  // path to this script relative to the extension dir.
//  public $extKey = 'caddy';
//  public $pObj = null;
//  public $conf = null;
  public $drsUserfunc = false;
  public $fieldFfConfirm = null;
  public $fieldFfMailreceiver = null;
  public $fieldFfMailsender = null;
  public $fieldFfSubjectReceiver = null;
  public $fieldFfSubjectSender = null;
  public $fieldFfThanks = null;
  public $fieldFormCss = null;
  public $fieldUid = null;
  public $fieldTitle = null;
  public $markerReceiver = null;
  public $markerReceiverWtcart = null;
  public $markerSender = null;
  public $markerSenderWtcart = null;
  public $markerSubjectReceiver = null;
  public $markerSubjectSender = null;
  public $markerThanks = null;
  private $markerTsCaddy = null;
  private $markerTsOrdernumber = null;
  private $markerTsThanks = null;
  private $markerTsWtcart = null;
  // Current GET parameter
  private $paramGet = null;
  // Current POST parameter
  private $paramPost = null;
  // True, if powermail form is sent
  public $sent = null;
  public $versionInt = null;
  public $versionStr = null;
  // [Object]
  private $pdf = null;
  // [Object]
  private $userfunc = null;
  // caddyEmail
  private $calc = null;
  public $cartCount = 0;
  public $cartServiceAttribute1Sum = 0;
  public $cartServiceAttribute1Max = 0;
  public $cartServiceAttribute2Sum = 0;
  public $cartServiceAttribute2Max = 0;
  public $cartServiceAttribute3Sum = 0;
  public $cartServiceAttribute3Max = 0;
  private $content = null;
  private $dynamicMarkers = null;
  private $markerArray = null;
  private $outerMarkerArray = null;
  private $product = null;
  private $render = null;
  private $session = null;
  public $tmpl = null;
  private $pmVersAppendix = null;

  /*   * *********************************************
   *
   * Caddy E-Payment
   *
   * ******************************************** */

  /**
   * caddyEpayment( ) : Dies, if there is an error with the e-payment
   *
   * @return  void
   * @access private
   * @version 4.0.6
   * @since  4.0.5
   */
  private function caddyEpayment()
  {
    $arrReturn = array(
      'content' => null,
      'isPayed' => false
    );

    $isPayed = $this->caddyEpaymentOrderIsPayed();
    if ( $isPayed )
    {
      $arrReturn = array(
        'content' => null,
        'isPayed' => true
      );
      return;
    }

    $provider = $this->conf[ 'api.' ][ 'e-payment.' ][ 'provider' ];

    switch ( $provider )
    {
      case( 'Paymill' ):
        $arrReturn = $this->caddyEpaymentPaymill();
        $isPayed = $arrReturn[ 'isPayed' ];
        if ( !$isPayed )
        {
          var_dump( __METHOD__, __LINE__, 'Expected: $isPayed is true. But result is "' . $isPayed . '"' );
          die();
        }
        $this->caddyEpaymentOrderSetPayed( $isPayed );
        return;
      case( null ):
      case( false ):
        // folow the workflow
        return;
      default:
        // Die
        $this->caddyEpaymentDie();
        break;
    }

    var_dump( __METHOD__, __LINE__, 'Expected: return within switch. But there was no return.' );
    die();
  }

  /**
   * caddyEpaymentDie( ) : Dies, if there is an undefined e-payment provider
   *
   * @return  void
   * @access public
   * @version 4.0.5
   * @since  4.0.5
   */
  private function caddyEpaymentDie()
  {
    // DRS
    if ( $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ': E-payment has an undefined provider!';
      t3lib_div::devlog( '[ERROR/EPAYMENT/POWERMAIL] ' . $prompt, $this->extKey, 3 );
    }
    // DRS

    $prompt = 'ERROR: E-payment<br />
      The provider isn\'t defined.<br />
      Sorry for the trouble.<br />
      <a href="javascript:history.back( )">Back</a><br />
      Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $this->extKey;
    die( $prompt );
  }

  /**
   * caddyEpaymentOrderIsPayed( ) : Returns the payed status of the current order
   *
   * @return  string           $responsecode : paymill response code
   * @access private
   * @version 4.0.5
   * @since  4.0.5
   */
  private function caddyEpaymentOrderIsPayed()
  {
    $isPayed = false;

    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

    $currentOrderNumber = $sesArray[ 'numberOrderCurrent' ];
    $epaymentOrderNumber = $sesArray[ 'e-payment' ][ 'order' ][ 'numberOrderCurrent' ];

//var_dump( __FILE__, __LINE__, $sesArray['numberOrderCurrent'], $sesArray['e-payment']['order']['numberOrderCurrent'] );
    // RETURN false : Order isn't payed
    if ( $currentOrderNumber != $epaymentOrderNumber )
    {
      $isPayed = false;
//var_dump( __FILE__, __LINE__, $isPayed );
      return $isPayed;
    }

    // Get payed status from session
    $isPayed = $sesArray[ 'e-payment' ][ 'order' ][ 'isPayed' ];
    // RETURN payed status
//var_dump( __FILE__, __LINE__, $sesArray['e-payment'] );
    return $isPayed;
  }

  /**
   * caddyEpaymentOrderSetPayed( ) : Sets the payed status of the current order
   *
   * @param    boolean   $isPayed  : true, if order is payed
   * @return  void
   * @access private
   * @version 4.0.6
   * @since  4.0.6
   */
  private function caddyEpaymentOrderSetPayed( $isPayed )
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $currentOrderNumber = $sesArray[ 'numberOrderCurrent' ];

    $sesArray[ 'e-payment' ][ 'order' ][ 'numberOrderCurrent' ] = $currentOrderNumber;
    $sesArray[ 'e-payment' ][ 'order' ][ 'isPayed' ] = $isPayed;

    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();
    //var_dump( __FILE__, __LINE__, $this->extKey . '_' . $this->pid, $sesArray, $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pid ) );
//var_dump( __FILE__, __LINE__, $isPayed );
  }

  /**
   * caddyEpaymentPaymill( ) :
   *
   * @return  array      $arrReturn  :
   * @access private
   * @version 4.0.6
   * @since  4.0.5
   */
  private function caddyEpaymentPaymill()
  {
    $arrReturn = array(
      'content' => null,
      'isPayed' => false
    );

    $this->caddyEpaymentPaymillInit();
    $arrReturn = $this->caddyEpaymentPaymillTemplateAfterTransaction();
    return $arrReturn;
  }

  /**
   * caddyEpaymentPaymillInit( ) :
   *
   * @return  void
   * @access private
   * @version 4.0.6
   * @since  4.0.6
   */
  private function caddyEpaymentPaymillInit()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'e-payment/powermail/class.tx_caddy_epayment_powermail.php' );
    $this->epayment = t3lib_div::makeInstance( 'tx_caddy_epayment_powermail' );
    $this->epayment->setParentObject( $this );
  }

  /**
   * caddyEpaymentPaymillTemplateAfterTransaction( ) :
   *
   * @return  array    $arrReturn  :
   * @access private
   * @version 4.0.6
   * @since  4.0.5
   */
  private function caddyEpaymentPaymillTemplateAfterTransaction()
  {
    $arrReturn = array(
      'content' => null,
      'isPayed' => false
    );

    $arrReturn = $this->epayment->getContentAfterTransaction();
//var_dump( __FILE__, __LINE__, $arrReturn );
    return $arrReturn;
  }

  /*   * *********************************************
   *
   * Caddy
   *
   * ******************************************** */

  /**
   * caddyEmail( )  : Returns a caddy rendered for an e-mail
   *
   * @return  string    caddy content
   * @access public
   * @version 4.0.12
   * @since  2.0.2
   */
  public function caddyEmail( $content = '', $conf = array() )
  {
    unset( $content );
    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // #54634, 140103, dwildt, 9+
    if ( ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ] )
    {
      if ( $this->drsUserfunc )
      {
        $prompt = '$GLOBALS["TSFE"]->id is set by userFunc.caddyPid to ' . ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      $GLOBALS[ "TSFE" ]->id = ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
    }

    // Get the typoscript configuration of the caddy plugin 1
    $this->conf = $this->caddyEmailInitConf();

    // RETURN null, if init is unproper
    if ( !$this->caddyEmailInit() )
    {
//var_dump( __METHOD__, __LINE__ );
//die( );
      return null;
    }
    // RETURN null, if init is unproper

    $this->caddyEmailEpayment();

    // Get the caddy
    $caddy = $this->caddy->caddy();
    $marker = $caddy[ 'marker' ];
    $subparts = $caddy[ 'subparts' ];
    $tmpl = $caddy[ 'tmpl' ];
    unset( $caddy );

    $content = $content . $this->cObj->substituteMarkerArrayCached
                    (
                    $tmpl, $marker, $subparts
    );

    $content = $this->dynamicMarkers->main( $content, $this ); // Fill dynamic locallang or typoscript markers
    $content = preg_replace( '|###.*?###|i', '', $content ); // Finally clear not filled markers
    // #58646, 140517, dwildt, 8-
//    // #i0019, 130628, dwildt, 6+
//    // Items are utf8 encoded: decode all code
//    $content = utf8_decode($content);
//    // Move html entities like &nbsp; to ' ' or &uuml; to ü
//    $content = html_entity_decode($content);
//    // Decode code for UTF8
//    $content = utf8_encode($content);
    // #58646, 140517, dwildt, 2+
    // Move html entities like &nbsp; to ' ' or &uuml; to ü
    $content = html_entity_decode( $content );

//var_dump( __METHOD__, __LINE__, $content );
//die( );
//
    return $content;
  }

  /**
   * caddyEmailInit( )  :
   *
   * @return  boolean      : true in case of products
   * @access private
   * @version 4.0.6
   * @since  1.4.6
   */
  private function caddyEmailInit()
  {
    $this->pi_loadLL();

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Get the HTML template for CADDY_EMAIL
    $this->tmpl = $this->caddyEmailInitTemplate();

    $this->cObj = $GLOBALS[ 'TSFE' ]->cObj;

    // Init instances
    $this->caddyEmailInstances();

    // RETURN false : no products
    if ( !$this->caddyEmailInitProducts() )
    {
      return false;
    }
    // RETURN false : no products
    // RETURN true : there are products
    return true;
  }

  /**
   * caddyEmailEpayment( )  :
   *
   * @return  void
   * @access private
   * @version 4.0.6
   * @since  4.0.6
   */
  private function caddyEmailEpayment()
  {
    $this->caddyEpayment();
  }

  /**
   * caddyEmailInitConf( )  : Take the conf from plugin.tx_caddy_pi1
   *
   * @access private
   * @version 2.0.0
   * @since  2.0.0
   */
  private function caddyEmailInitConf()
  {
    $conf = $GLOBALS[ 'TSFE' ]->tmpl->setup[ 'plugin.' ][ 'tx_caddy_pi1.' ];
    $conf = array_merge( ( array ) $this->conf, ( array ) $conf );

    return $conf;
  }

  /**
   * caddyEmailInitProducts( )  :
   *
   * @return  boolean          : true in case of products
   * @access private
   * @version 2.0.0
   * @since  1.4.6
   */
  private function caddyEmailInitProducts()
  {
    // Get products from session
    $this->products = $this->session->productsGet();

    // RETURN : empty content, no product in session
    if ( count( $this->products ) < 1 )
    {
      // DRS
      if ( $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      // DRS

      return false;
    }
    // RETURN : empty content, no product in session

    return true;
  }

  /**
   * caddyEmailInitTemplate( )  : Get teh template CADDY_EMAIL
   *
   * @access private
   * @version 2.0.0
   * @since  2.0.0
   */
  private function caddyEmailInitTemplate()
  {
    $file = $this->conf[ 'templates.' ][ 'e-mail.' ][ 'file' ];
    $markerAll = $this->conf[ 'templates.' ][ 'e-mail.' ][ 'marker.' ][ 'all' ];
    $markerItem = $this->conf[ 'templates.' ][ 'e-mail.' ][ 'marker.' ][ 'item' ];

    $tmplFile = $GLOBALS[ 'TSFE' ]->cObj->fileResource( $file );
    $tmpl = array();
    $tmpl[ 'all' ] = $GLOBALS[ 'TSFE' ]->cObj->getSubpart( $tmplFile, $markerAll );
    $tmpl[ 'item' ] = $GLOBALS[ 'TSFE' ]->cObj->getSubpart( $tmpl[ 'all' ], $markerItem );

    $tmpl = $this->caddyEmailInitTemplateTable( $tmpl );
    return $tmpl;
  }

  /**
   * caddyEmailInitTemplateTable( )
   *
   * @param      string        $tmpl :
   * @return	string        $tmpl :
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyEmailInitTemplateTable( $tmpl )
  {
    $table = $this->conf[ 'templates.' ][ 'e-mail.' ][ 'table.' ];

    foreach ( $table as $property => $value )
    {
      $marker = '###' . strtoupper( $property ) . '###';
      $tmpl[ 'all' ] = str_replace( $marker, $value, $tmpl[ 'all' ] );
      $tmpl[ 'item' ] = str_replace( $marker, $value, $tmpl[ 'item' ] );
    }

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
  private function caddyEmailInstances()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy = t3lib_div::makeInstance( 'tx_caddy' );
    $this->local_cObj = $this->cObj;
    $this->caddy->setParentObject( $this );
    $this->caddy->setContentRow( $this->cObj->data );

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session = t3lib_div::makeInstance( 'tx_caddy_session' );
  }

  /*   * *********************************************
   *
   * Powermail
   *
   * ******************************************** */

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
    if ( empty( $this->fieldFormCss ) )
    {
      // DRS
      if ( $this->drs->drsPowermail )
      {
        $prompt = 'Caddy contains a product, powermail form will displayed.';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return $content;
    }
    // RETURN : there isn't any CSS for powermail
//      // DRS
//    if( $this->drs->drsPowermail )
//    {
//      $prompt = 'Caddy is empty, powermail form will hidden.';
//      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
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
  public function formHide()
  {
    if ( $this->drs->drsPowermail )
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
  public function formShow()
  {
    $this->fieldFormCss = null;
  }

  /*   * *********************************************
   *
   * Get fields
   *
   * ******************************************** */

  /**
   * getFieldById( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function getFieldById( $uid )
  {
    switch ( true )
    {
      // #58645, 141011, dwildt, 2+
      case( empty( $uid ) ):
        return;
      case( $this->pmVersAppendix == '1x' ):
        $value = $this->getFieldById1x( $uid );
        break;
      case( $this->pmVersAppendix == '2x' ):
        $value = $this->getFieldById2x( $uid );
        break;
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is neither 1.x nor 2.x. Internal: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }
    return $value;
  }

  /**
   * getFieldById1x( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldById1x( $uid )
  {
    switch ( $this->fieldFfConfirm )
    {
      case( false ):
        $value = $this->getFieldById1xWoConfirm( $uid );
        break;
      case( true ):
      default:
        $value = $this->getFieldById1xWiConfirm( $uid );
        break;
    }
    return $value;
  }

  /**
   * getFieldById1xWiConfirm( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldById1xWiConfirm( $uid )
  {
    $value = $this->getFieldByIdFromSession( $uid );

    return $value;
  }

  /**
   * getFieldById1xWoConfirm( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldById1xWoConfirm( $uid )
  {
    $value = $this->getFieldByIdFromPost( $uid );

    return $value;
  }

  /**
   * getFieldById2x( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldById2x( $uid )
  {
    // #61892, 140925, dwildt, 1+
    $uid = $this->getFieldById2x210( $uid );
    $value = $this->getFieldByIdFromPost( $uid );

    return $value;
  }

  /**
   * getFieldById210( ) : Get the field lable in case of powermail version >= 2.1.0
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @internal  #61892
   * @version    6.0.0
   * @since      6.0.0
   */
  private function getFieldById2x210( $uid )
  {
    // #i0056, 141004, dwildt, 1+
    // 141009, dwildt, 4+
    if ( $this->versionInt === null )
    {
      $this->initVersion();
    }

    switch ( true )
    {
      case($this->versionInt < 2001000):
        // Smaller than 2.1.0
        return $uid;
//      case($this->versionInt < 2001004):
//        // Smaller than 2.1.4
//        // 2.1.0 released at 2014-09-10
//        // 141009, dwildt, 2+
//        // Remove tx_powermail_domain_model_fields_ from tx_powermail_domain_model_fields_435
//        $uid = substr( $uid, strlen( 'tx_powermail_domain_model_fields_' ) );
//        break;
//      case($this->versionInt >= 2001004):
//      default:
//        // Equal to or greater than 2.1.4
//        // 141011, dwildt
//        // 2.1.4 released at 06.10.2014
//        // do nothing: $uid is the uid.
//        break;
      default:
        // Equal to or greater than 2.1.0
        // 141011, dwildt
        if( (int) $uid != 0 )
        {
          break;
        }
        // Remove tx_powermail_domain_model_fields_ from tx_powermail_domain_model_fields_435
        $uid = substr( $uid, strlen( 'tx_powermail_domain_model_fields_' ) );
        break;
    }

    $select = 'marker';
    $table = 'tx_powermail_domain_model_fields';
    $where = 'uid = ' . $uid;
    $groupBy = null;
    $orderBy = null;
    $limit = 1;

    $res = $GLOBALS[ 'TYPO3_DB' ]->exec_SELECTquery( $select, $table, $where, $groupBy, $orderBy, $limit );
    $error = $GLOBALS[ 'TYPO3_DB' ]->sql_error();

    if ( $error )
    {
      $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery( $select, $table, $where, $groupBy, $orderBy, $limit );
      $prompt = '<h1>caddy: SQL-Error</h1>' . PHP_EOL
              . '<p>' . $error . '</p>' . PHP_EOL
              . '<p>' . $query . '</p>' . PHP_EOL
              . '<p>' . PHP_EOL
              . 'Please take care for a proper configuration at plugin.tx_caddy_pi1.db<br />' . PHP_EOL
              . 'Sorry for the trouble.<br />' . PHP_EOL
              . 'TYPO3 Caddy<br />' . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')'
      ;
      die( $prompt );
    }

    $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res );
//    var_dump( __METHOD__, __LINE__, $row );
//    var_dump( __METHOD__, __LINE__, $this->versionInt, $uid );
    $uid = $row[ 'marker' ];
    //var_dump(__METHOD__, __LINE__, $this->versionInt, $uid );
    return $uid;
  }

  /**
   * getFieldByIdFromPost( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromPost( $uid )
  {
    $value = null;

    if ( empty( $uid ) )
    {
      $prompt = 'FATAL ERROR: getFieldByIdFromPost( $uid ) is called with an empty uid<br />
                Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
                TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }

    switch ( true )
    {
      case( $this->pmVersAppendix == '1x' ):
        $value = $this->getFieldByIdFromPost1x( $uid );
        break;
      case( $this->pmVersAppendix == '2x' ):
        $value = $this->getFieldByIdFromPost2x( $uid );
        break;
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is neither 1.x nor 2.x. Internal: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }

    return $value;
  }

  /**
   * getFieldByIdFromPost1x( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromPost1x( $uid )
  {
    $value = null;

    $uid1x = 'uid' . $uid;
    $value = $this->paramPost[ $uid1x ];
    // DRS
    if ( $this->drs->drsError )
    {
      if ( !isset( $this->paramPost[ $uid1x ] ) )
      {
        $prompt = 'POST[' . $uid1x . '] isn\'t set!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->extKey, 3 );
      }
    }
    // DRS

    return $value;
  }

  /**
   * getFieldByIdFromPost2x( ) :  Return the value of the given uid from the POST params.
   *                     The uid is the uid only - without any prefix!
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromPost2x( $uid2x )
  {
    $value = null;

    $value = $this->paramPost[ 'field' ][ $uid2x ];
    // DRS
    if ( $this->drs->drsError )
    {
      if ( !isset( $this->paramPost[ 'field' ][ $uid2x ] ) )
      {
        $prompt = 'POST[field][' . $uid2x . '] isn\'t set!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->extKey, 3 );
      }
    }
    // DRS

    return $value;
  }

  /**
   * getFieldByIdFromSession( ) :
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromSession( $uid )
  {
    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $value = $this->getFieldByIdFromSession1x( $uid );
        break;
      case( $this->versionInt < 3000000 ):
        $value = $this->getFieldByIdFromSession2x( $uid );
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
   * getFieldByIdFromSession1x( ) :
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromSession1x( $uid )
  {
    $value = null;

    $sessionData = $this->sessionData();

    $uid1x = 'uid' . $uid;
    $value = $sessionData[ $uid1x ];

    // DRS
    if ( $this->drs->drsError )
    {
      if ( !isset( $sessionData[ $uid1x ] ) )
      {
        $prompt = 'SESSION[' . $uid1x . '] isn\'t set!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->extKey, 3 );
      }
    }
    // DRS

    return $value;
  }

  /**
   * getFieldByIdFromSession2x( ) :
   *
   * @param      integer     $uid    : The uid of the value, which should returned
   * @return	string      $value  : The value of the given uid
   * @access     private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getFieldByIdFromSession2x( $uid )
  {
    $value = null;
    unset( $uid );

    $prompt = 'TODO: powermail 2.x<br />
      Please maintain the code!<br />
      Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $this->extKey;
    die( $prompt );

    return $value;
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( ): Global vars are initiated:
   *                   * versionInt
   *                   * versionStr
   *                   * fieldUid
   *                   * fieldTitle
   *                   * fieldFfConfirm
   *
   * @param	array		$row: ...
   * @return	void
   * @access public
   * @internal   #45915
   * @version    4.0.5
   * @since      2.0.0
   */
  public function init( $row )
  {
    $this->initInstances();

    if ( !$this->initVersion() )
    {
      return;
    }

    $this->initFields( $row );

    $this->initPowermailVersionAppendix();
    $this->initMarker();

    // GET- and POST-parameters
    $this->initGetPost();

    $this->initSend();

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
   * @version 4.0.5
   * @since 2.0.0
   */
  private function initFields( $row )
  {
    //$arrReturn = null;
    // Page uid
//    $pid = $this->cObj->data['pid'];
    $pid = $row[ 'pid' ];

    if ( !$pid )
    {
      $prompt = 'ERROR: unexpected result<br />
        pid is empty<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }

    // Query
    $select_fields = '*';
    $from_table = 'tt_content';
    $where_clause = "pid = " . $pid . " AND hidden = 0 AND deleted = 0";
    switch ( true )
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
    $groupBy = '';
    $orderBy = 'sorting';
    $limit = '1';
    // Query
    // DRS
    if ( $this->drs->drsSql )
    {
      $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery
              (
              $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit
      );
      $prompt = $query;
      t3lib_div::devlog( ' [INFO/SQL] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Execute SELECT
    $res = $GLOBALS[ 'TYPO3_DB' ]->exec_SELECTquery
            (
            $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit
    );
    // Execute SELECT
    // Current powermail record
    $pmRecord = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res );

    // RETURN : no row
    if ( empty( $pmRecord ) )
    {
      if ( $this->drs->drsError )
      {
        $prompt = 'Abort. SQL query is empty!';
        t3lib_div::devlog( ' [WARN/SQL] ' . $prompt, $this->extKey, 2 );
      }
      return false;
    }
    // RETURN : no row

    $pmUid = $pmRecord[ 'uid' ];
    $pmTitle = $pmRecord[ 'header' ];
    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $pmFfConfirm = $pmRecord[ 'tx_powermail_confirm' ];
        $pmFfMailreceiver = $pmRecord[ 'tx_powermail_mailreceiver' ];
        $pmFfMailsender = $pmRecord[ 'tx_powermail_mailsender' ];
        $pmFfSubjectReceiver = $pmRecord[ 'tx_powermail_subject_r' ];
        $pmFfSubjectSender = $pmRecord[ 'tx_powermail_subject_s' ];
        $pmFfThanks = $pmRecord[ 'tx_powermail_thanks' ];
        break;
      case( $this->versionInt < 3000000 ):
      default:
        $pmFlexform = t3lib_div::xml2array( $pmRecord[ 'pi_flexform' ] );
        $pmFfConfirm = $pmFlexform[ 'data' ][ 'main' ][ 'lDEF' ][ 'settings.flexform.main.form' ][ 'vDEF' ];
        $pmFfMailreceiver = $pmFlexform[ 'data' ][ 'receiver' ][ 'lDEF' ][ 'settings.flexform.receiver.body' ][ 'vDEF' ];
        $pmFfSubjectReceiver = $pmFlexform[ 'data' ][ 'receiver' ][ 'lDEF' ][ 'settings.flexform.receiver.subject' ][ 'vDEF' ];
        $pmFfMailsender = $pmFlexform[ 'data' ][ 'sender' ][ 'lDEF' ][ 'settings.flexform.sender.body' ][ 'vDEF' ];
        $pmFfSubjectSender = $pmFlexform[ 'data' ][ 'sender' ][ 'lDEF' ][ 'settings.flexform.sender.subject' ][ 'vDEF' ];
        $pmFfThanks = $pmFlexform[ 'data' ][ 'thx' ][ 'lDEF' ][ 'settings.flexform.thx.body' ][ 'vDEF' ];
        break;
    }

    $this->fieldFfConfirm = $pmFfConfirm;
    $this->fieldFfMailreceiver = $pmFfMailreceiver;
    $this->fieldFfMailsender = $pmFfMailsender;
    $this->fieldFfSubjectReceiver = $pmFfSubjectReceiver;
    $this->fieldFfSubjectSender = $pmFfSubjectSender;
    $this->fieldFfThanks = $pmFfThanks;
    $this->fieldTitle = $pmTitle;
    $this->fieldUid = $pmUid;

    // DRS
    if ( $this->drs->drsPowermail )
    {
      $prompt = 'powermail.uid: "' . $this->fieldUid . '"';
      t3lib_div::devlog( ' [INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      $prompt = 'powermail.title: "' . $this->fieldTitle . '"';
      t3lib_div::devlog( ' [INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      $prompt = 'powermail.confirm: "' . $this->fieldFfConfirm . '"';
      t3lib_div::devlog( ' [INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return;
  }

  /**
   * initGetPost( ):
   *
   * @return	void
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initGetPost()
  {
    $this->paramGet = t3lib_div::_GET( 'tx_powermail_pi1' );
    $this->paramPost = t3lib_div::_POST( 'tx_powermail_pi1' );

    // RETURN : no DRS
    if ( !( $this->drs->drsPowermail || $this->drs->drsSession || $this->drsUserfunc ) )
    {
      return;
    }
    // RETURN : no DRS
    // DRS
    $prompt = 'GET[tx_powermail_pi1]: ' . var_export( $this->paramGet, true );
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    $prompt = 'POST[tx_powermail_pi1]: ' . var_export( $this->paramPost, true );
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @version    4.0.5
   * @since      2.0.0
   */
  private function initInstances()
  {
    $this->initInstancesUserfunc();
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @version    6.0.0
   * @since      6.0.0
   */
  private function initInstancesUserfunc()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
  }

  /*   * *********************************************
   *
   * Init Marker
   *
   * ******************************************** */

  /**
   * initMarker( ):
   *
   * @return	array		$arrReturn  : version as int (integer) and str (string)
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarker()
  {
    $this->initMarkerValues();

    $this->initMarkerReceiver();
    $this->initMarkerReceiverWtcart();
    $this->initMarkerSender();
    $this->initMarkerSenderWtcart();
    $this->initMarkerSubjectReceiver();
    $this->initMarkerSubjectSender();
    $this->initMarkerThanks();
  }

  /**
   * initMarkerReceiver( ):
   *
   * @return	array		$arrReturn  : version as int (integer) and str (string)
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerReceiver()
  {
    $this->markerReceiver = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailreceiver, $this->markerTsCaddy );
    if ( !( $pos === false ) )
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
  private function initMarkerReceiverWtcart()
  {
    $this->markerReceiverWtcart = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailreceiver, $this->markerTsWtcart );
    if ( !( $pos === false ) )
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
  private function initMarkerSender()
  {
    $this->markerSender = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailsender, $this->markerTsCaddy );
    if ( !( $pos === false ) )
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
  private function initMarkerSenderWtcart()
  {
    $this->markerSenderWtcart = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfMailsender, $this->markerTsWtcart );
    if ( !( $pos === false ) )
    {
      $this->markerSenderWtcart = true;
    }
  }

  /**
   * initMarkerSubjectReceiver( ):
   *
   * @return	array		$arrReturn  : version as int (integer) and str (string)
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerSubjectReceiver()
  {
    $this->markerSubjectReceiver = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfSubjectReceiver, $this->markerTsOrdernumber );
    if ( !( $pos === false ) )
    {
      $this->markerSubjectReceiver = true;
    }
  }

  /**
   * initMarkerSubjectSender( ):
   *
   * @return	array		$arrReturn  : version as int (integer) and str (string)
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerSubjectSender()
  {
    $this->markerSubjectSender = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfSubjectSender, $this->markerTsOrdernumber );
    if ( !( $pos === false ) )
    {
      $this->markerSubjectSender = true;
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
  private function initMarkerThanks()
  {
    $this->markerThanks = false;

    // Current IP is an element in the list
    $pos = strpos( $this->fieldFfThanks, $this->markerTsThanks );
    if ( !( $pos === false ) )
    {
      $this->markerThanks = true;
    }
  }

  /**
   * initMarkerValues( ):
   *
   * @return    void
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerValues()
  {
    switch ( true )
    {
      case( $this->pmVersAppendix == '1x' ):
        $this->initMarkerValues1x();
        break;
      case( $this->pmVersAppendix == '2x' ):
        $this->initMarkerValues2x();
        break;
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is neither 1.x nor 2.x. Internal: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
    }
  }

  /**
   * initMarkerValues1x( ):
   *
   * @return    void
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerValues1x()
  {
    $this->markerTsCaddy = '###POWERMAIL_TYPOSCRIPT_CADDY###';
    $this->markerTsOrdernumber = '###POWERMAIL_TYPOSCRIPT_CADDYORDERNUMBER###';
    $this->markerTsThanks = '###POWERMAIL_TYPOSCRIPT_CLEARCADDYSESSION###';
    $this->markerTsWtcart = '###POWERMAIL_TYPOSCRIPT_CART###';
  }

  /**
   * initMarkerValues2x( ):
   *
   * @return    void
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initMarkerValues2x()
  {
    $this->markerTsCaddy = '{f:cObject(typoscriptObjectPath:\'plugin.tx_caddy_pi1.powermail.caddy\')}';
    $this->markerTsOrdernumber = '{f:cObject(typoscriptObjectPath:\'plugin.tx_caddy_pi1.powermail.caddyordernumber\')}';
    $this->markerTsThanks = '{f:cObject(typoscriptObjectPath:\'plugin.tx_caddy_pi1.powermail.clearcaddysession\')}';
    $this->markerTsWtcart = '###POWERMAIL_TYPOSCRIPT_CART###';
  }

  /*   * *********************************************
   *
   * Init PDF
   *
   * ******************************************** */

  /**
   * initPdf( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initPdf()
  {
    if ( is_object( $this->pdf ) )
    {
      return;
    }

    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'pdf/class.tx_caddy_pdf.php' );
    $this->pdf = t3lib_div::makeInstance( 'tx_caddy_pdf' );

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
    {
      $this->pdf->drsUserfunc = true;
      $prompt = __METHOD__ . ': PDF object is initiated.';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
  }

  /*   * *********************************************
   *
   * Init Powermail Version
   *
   * ******************************************** */

  /**
   * initPowermailVersionAppendix( ):
   *
   * @return	void
   * @access private
   * @version 4.0.5
   * @since   2.0.0
   */
  private function initPowermailVersionAppendix()
  {
    if ( $this->versionInt === null )
    {
      $this->initVersion();
    }

    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $this->pmVersAppendix = '1x';
        break;
      case( $this->versionInt < 3000000 ):
        $this->pmVersAppendix = '2x';
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

  /*   * *********************************************
   *
   * Init Send
   *
   * ******************************************** */

  /**
   * initSend( ): Set the global $send, if the powermail is sent
   *
   * @return	void
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function initSend()
  {
    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $this->initSend1x();
        break;
      case( $this->versionInt < 3000000 ):
        $this->initSend2x();
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
  private function initSend1x()
  {
    $this->sent = false;
    switch ( true )
    {
      case(!$this->fieldFfConfirm ):
        // Confirmation page is disabled
        $this->initSend1xWoConfirm();
        break;
      case( $this->fieldFfConfirm ):
      default:
        // Confirmation page is enabled
        $this->initSend1xWiConfirm();
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
  private function initSend1xWiConfirm()
  {
    $this->sent = false;
    if ( !empty( $this->paramGet[ 'sendNow' ] ) )
    {
      $this->sent = true;
      // DRS
      if ( $this->drs->drsPowermail )
      {
        $prompt = 'Powermail form is sent. Version 1.x with confirmation mode and with $GET[sendNow].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return;
    }

    // DRS
    if ( $this->drs->drsPowermail )
    {
      $prompt = 'Powermail form isn\'t sent. Version 1.x with confirmation mode but without any $GET[sendNow].';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
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
  private function initSend1xWoConfirm()
  {
    $this->sent = false;
    if ( !empty( $this->paramGet[ 'mailID' ] ) )
    {
      $this->sent = true;
      // DRS
      if ( $this->drs->drsPowermail )
      {
        $prompt = 'Powermail form is sent. Version 1.x without confirmation mode and with $GET[mailID].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return;
    }

    // DRS
    if ( $this->drs->drsPowermail )
    {
      $prompt = 'Powermail form isn\'t sent. Version 1.x without confirmation mode and without any $GET[mailID].';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
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
  private function initSend2x()
  {
    $this->sent = false;
    if ( $this->paramGet[ 'action' ] == 'create' )
    {
      $this->sent = true;
    }

    return;
  }

  /*   * *********************************************
   *
   * Init Version
   *
   * ******************************************** */

  /**
   * initVersion( ):  Returns the version of powermail as an interger and a string.
   *                           I.e
   *                           * int: 1006006
   *                           * str: 1.6.6
   *
   * @return	boolean       true: success, false: failed
   * @access private
   * @version 4.0.5
   * @since   2.0.0
   */
  private function initVersion()
  {
    if ( !is_object( $this->userfunc ) )
    {
      $this->initInstances();
    }

    $arrResult = $this->userfunc->extMgmVersion( 'powermail' );

    $this->versionInt = $arrResult[ 'int' ];
    $this->versionStr = $arrResult[ 'str' ];

    if ( empty( $this->versionInt ) )
    {
      // DRS
      if ( $this->drs->drsError )
      {
        $prompt = 'Powermail version is 0!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->extKey, 3 );
      }
      // DRS
      return false;
    }

    // DRS
    if ( $this->drs->drsPowermail )
    {
      $prompt = 'Powermail version is ' . $this->versionStr . ' ' .
              '(internal ' . $this->versionInt . ')';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return true;
  }

  /*   * *********************************************
   *
   * params
   *
   * ******************************************** */

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

    if ( empty( $key ) )
    {
      $prompt = 'FATAL ERROR: paramGetByKey( $key ) is called with an empty uid<br />
                Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
                TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }

    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $prompt = 'TODO: powermail 1.x<br />
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

  /*   * *********************************************
   *
   * Send
   *
   * ******************************************** */

  /**
   * sendToCustomer( ):
   *
   * @return	string		$path : path to the attachment, which should send
   * @access public
   * @version 4.0.3
   * @since   2.0.0
   */
  public function sendToCustomer( $content = '', $conf = array() )
  {
    $path = null;
    $paths = null;

    unset( $content );

    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // #54634, 140103, dwildt, 9+
    if ( ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ] )
    {
      if ( $this->drsUserfunc )
      {
        $prompt = '$GLOBALS["TSFE"]->id is set by userFunc.caddyPid to ' . ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      $GLOBALS[ "TSFE" ]->id = ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
    }

    $this->initPowermailVersionAppendix();

    $this->initPdf();
    $this->pdf->setParentObject( $this );

    $path = $this->sendToCustomerDeliveryorder();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToCustomerInvoice();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToCustomerRevocation();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToCustomerTerms();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }

    if ( empty( $paths ) )
    {
      return null;
    }

    $path = implode( ',', $paths );
    $path = ',' . $path . ',';

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToCustomerDeliveryorder()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendCustomerDeliveryorder' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->deliveryorder();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToCustomerInvoice()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendCustomerInvoice' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->invoice();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $path;
  }

  /**
   * sendToCustomerRevocation( ):
   *
   * @return	string		$path : path to the attachment, which should send
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function sendToCustomerRevocation()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendCustomerRevocation' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->revocation();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToCustomerTerms()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendCustomerTerms' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->terms();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
   * @version 4.0.3
   * @since   2.0.0
   */
  public function sendToVendor( $content = '', $conf = array() )
  {
    $path = null;
    $paths = null;

    unset( $content );

    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // #54634, 140103, dwildt, 9+
    if ( ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ] )
    {
      if ( $this->drsUserfunc )
      {
        $prompt = '$GLOBALS["TSFE"]->id is set by userFunc.caddyPid to ' . ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      $GLOBALS[ "TSFE" ]->id = ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
    }

    $this->initPowermailVersionAppendix();

    $this->initPdf();
    $this->pdf->setParentObject( $this );

    $path = $this->sendToVendorDeliveryorder();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToVendorInvoice();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToVendorRevocation();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }
    $path = $this->sendToVendorTerms();
    if ( !empty( $path ) )
    {
      $paths[] = $path;
    }

    if ( empty( $paths ) )
    {
      return null;
    }

    $path = implode( ',', $paths );
    $path = ',' . $path . ',';

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToVendorDeliveryorder()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendVendorDeliveryorder' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->deliveryorder();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToVendorInvoice()
  {

    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendVendorInvoice' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->invoice();


    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $path;
  }

  /**
   * sendToVendorRevocation( ):
   *
   * @return	string		$path : path to the attachment, which should send
   * @access private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function sendToVendorRevocation()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendVendorRevocation' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->revocation();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
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
  private function sendToVendorTerms()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );
    $path = $sesArray[ 'sendVendorTerms' ];

    if ( empty( $path ) )
    {
      // DRS
      if ( $this->drs->drsSession || $this->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return null;
    }

    $path = $this->pdf->terms();

    // DRS
    if ( $this->drs->drsSession || $this->drsUserfunc )
    {
      $prompt = __METHOD__ . ' returns: ' . $path;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $path;
  }

  /*   * *********************************************
   *
   * Session
   *
   * ******************************************** */

  /**
   * sessionData( ):
   *
   * @return    array        $sessionData : the session data
   * @access  public
   * @version 2.0.0
   * @since   2.0.0
   */
  public function sessionData()
  {
    $sessionData = null;

    switch ( true )
    {
      case( $this->versionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->versionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: ' . $this->extKey;
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $sessionData = $this->sessionData1x();
        break;
      case( $this->versionInt < 3000000 ):
        $sessionData = $this->sessionData2x();
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

    return $sessionData;
  }

  /**
   * sessionData1x( ):
   *
   * @return    string        The content that should be displayed on the website
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function sessionData1x()
  {
    // DIE  : $fieldUid is empty
    if ( empty( $this->fieldUid ) )
    {
      $prompt = 'FATAL ERROR: powermail->fieldUid is empty.<br />
        Probably powermail->init( ) wasn\'t called.<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: ' . $this->extKey;
      die( $prompt );
    }
    // DIE  : $fieldUid is empty
    // Get the Powermail session data
    $uid = $this->fieldUid;
    $key = 'powermail_' . $uid;
    $sessionData = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $key );

    // RETURN: no session data
    if ( empty( $sessionData ) )
    {
      if ( $this->drsPowermail )
      {
        $prompt = 'There isn\'t any powermail session data (powermail 1.x)!';
        t3lib_div::devlog( ' [INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      return null;
    }
    // RETURN: no session data

    return $sessionData;
  }

  /**
   * sessionData2x( ):
   *
   * @return    string        The content that should be displayed on the website
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function sessionData2x()
  {
    // Get the Powermail session data
//    $post = t3lib_div::_POST( 'tx_powermail_pi1' );
//    $uid  = $post['form'];
//    $key  = 'powermailFormstart';
    $uid = $this->fieldUid;
    $key = 'powermail_' . $uid;
    $sessionData = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $key );

    // RETURN: no session data
    if ( empty( $sessionData ) )
    {
      if ( $this->drsPowermail )
      {
        $prompt = 'There isn\'t any powermail session data (powermail 2.x)!';
        t3lib_div::devlog( ' [INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
      return null;
    }
    // RETURN: no session data

    if ( $this->drs->drsPowermail || $this->drs->drsWarn )
    {
      $prompt = 'powermail session uid: _LOCALIZED_UID will not respected!';
      t3lib_div::devlog( ' [WARN/POWERMAIL] ' . $prompt, $this->extKey, 2 );
    }
    // RETURN: no session data

    return $sessionData;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/powermail/class.tx_caddy_powermail.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/powermail/class.tx_caddy_powermail.php' ]);
}
?>
