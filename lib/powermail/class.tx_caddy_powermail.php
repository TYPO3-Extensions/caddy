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
 *   55: class tx_caddy_powermail
 *
 *              SECTION: Powermail
 *   97:     public function formCss( $content )
 *  135:     public function formHide( )
 *  157:     public function formShow( )
 *  178:     public function init( $row )
 *  240:     private function initFields( $row )
 *  374:     private function initVersion( $row )
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * powermail controlling for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       2.0.0
 */
class tx_caddy_powermail
{

  public $prefixId = 'tx_caddy_powermail';

  // same as class name
  public $scriptRelPath = 'lib/powermail/class.tx_caddy_powermail.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

  public $pObj = null;

  public $fieldFfConfirm      = null;
  public $fieldFfMailreceiver = null;
  public $fieldFfMailsender   = null;
  public $fieldFormCss        = null;
  public $fieldUid            = null;
  public $fieldTitle          = null;

  private $marker               = '###POWERMAIL_TYPOSCRIPT_CADDY###';
  public  $markerReceiver       = null;
  public  $markerReceiverWtcart = null;
  public  $markerSender         = null;
  public  $markerSenderWtcart   = null;
  private $markerWtcart         = '###POWERMAIL_TYPOSCRIPT_CART###';

    // Current GET parameter
  private $paramGet  = null;
    // Current POST parameter
  private $paramPost = null;

    // True, if powermail form is sent
  public $sent  = null;

  public $versionInt  = null;
  public $versionStr  = null;



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
        $prompt = 'Caddy contains a product, powermail form will dislayed.';
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
        TYPO3 extension: powermail4dev';
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
          TYPO3 extension: powermail4dev';
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
          TYPO3 extension: powermail4dev';
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
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
      case( $this->versionInt < 2000000 ):
        $pmFfConfirm      = $pmRecord['tx_powermail_confirm'];
        $pmFfMailsender   = $pmRecord['tx_powermail_mailsender'];
        $pmFfMailreceiver = $pmRecord['tx_powermail_mailreceiver'];
        break;
      case( $this->versionInt < 3000000 ):
      default:
        $pmFlexform       = t3lib_div::xml2array( $pmRecord['pi_flexform'] );
        $pmFfConfirm      = $pmFlexform['data']['main']['lDEF']['settings.flexform.main.form']['vDEF'];
        $pmFfMailsender   = $pmFlexform['data']['main']['lDEF']['settings.flexform.sender.body']['vDEF'];
        $pmFfMailreceiver = $pmFlexform['data']['main']['lDEF']['settings.flexform.receiver.body']['vDEF'];
        break;
    }

    $arrReturn['uid']             = $pmUid;
    $arrReturn['title']           = $pmTitle;
    $arrReturn['ffConfirm']       = $pmFfConfirm;
    $arrReturn['ffMailsender']    = $pmFfMailsender;
    $arrReturn['ffMailreceiver']  = $pmFfMailreceiver;

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
    $pos = strpos( $this->fieldFfMailreceiver, $this->marker );
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
    $pos = strpos( $this->fieldFfMailreceiver, $this->markerWtcart );
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
    $pos = strpos( $this->fieldFfMailsender, $this->marker );
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
    $pos = strpos( $this->fieldFfMailsender, $this->markerWtcart );
    if( ! ( $pos === false ) )
    {
      $this->markerSenderWtcart = true;
    }
  }

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
          TYPO3 extension: powermail4dev';
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
          TYPO3 extension: powermail4dev';
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
    return $this->pObj->userfunc->extMgmVersion( 'powermail' );
  }



  /***********************************************
  *
  * Send
  *
  **********************************************/

 /**
  * sendDeliveryorderToCustomer( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendDeliveryorderToCustomer( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-lieferschein_A4.pdf';
  }

 /**
  * sendDeliveryorderToVendor( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendDeliveryorderToVendor( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-lieferschein_A4.pdf';
  }

 /**
  * sendInvoiceToCustomer( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendInvoiceToCustomer( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-rechnung_A4.pdf';
  }

 /**
  * sendInvoiceToVendor( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendInvoiceToVendor( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-rechnung_A4.pdf';
  }

 /**
  * sendTermsToCustomer( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendTermsToCustomer( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-agb_A4.pdf';
  }

 /**
  * sendTermsToVendor( ):
  *
  * @return	string		$path : path to the attachment, which should send
  * @access public
  * @version 2.0.0
  * @since   2.0.0
  */
  
  public function sendTermsToVendor( )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'typo3conf/caddy/files/pdf/default/typo3-quick-shop-agb_A4.pdf';
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php']);
}
?>
