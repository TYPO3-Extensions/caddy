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
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    caddy
* @version  2.0.0
* @since    2.0.0
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_caddy_userfunc
 *   67:     function promptCheckUpdate()
 *  102:     function promptCurrIP()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_caddy_userfunc
{
  
 /**
  * Extension key
  *
  * @var string
  */
  public $extKey = 'caddy';

 /**
  * Extension configuration
  *
  * @var array
  */
  private $arr_extConf = null;
  
 /**
  * Plugin / flexform configuration
  *
  * @var array
  */
  private $plugin = null;
  
 /**
  * Max width of div tags
  *
  * @var string
  */
  private $maxWidth = "600px";
  
 /**
  * Version of TYPO3 (sample: 4.7.7 -> 4007007)
  *
  * @var string
  */
  public $typo3Version = null;
  
  private $conf       = null;
  public $drs        = null;
  private $pid        = null;
  private $pObj       = null;
  private $powermail  = null;
  private $pageObject = null;
  private $pluginPiFlexform = null;
  private $typoscriptObject = null;
  public $userfunc   = null;



///**
// * Constructor. The method initiate the parent object
// *
// * @param    object        The parent object
// * @return    void
// */
//  function __construct( $pObj )
//  {
//    $this->pObj = $pObj;
//  }
  
  
  
  /***********************************************
   *
   * Database
   *
   **********************************************/
    
/**
 * databaseCheck( ): 
 *
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function databaseCheck( )
  {
    if( empty( $this->conf['db.']['table'] ) )
    {
      $prompt = '
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:dbTableEmpty'). '
          </div>
        </div>
        ';

      return $prompt;
    }

    return;
  }
 
  
  
  /***********************************************
   *
   * Empty
   *
   **********************************************/
  
  /**
   * emptyCheck():
   *
   * @param   string    $prompt
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function emptyCheck( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
      // RETURN : flexform isn't empty
    if( ! empty ( $this->pluginPiFlexform ) )
    {
      return null;
    }
      // RETURN : flexform isn't empty
    
      // RETURN prompt : felxform is empty
    $prompt = '
      <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:flexformIsEmpty'). '
        </div>
      </div>
      ';
      // RETURN prompt : felxform is empty
    
    return $prompt;
  }

  
  
  /***********************************************
   *
   * Extension Management
   *
   **********************************************/

 /**
  * extMgmVersion( ): Returns the version of an extension as an interger and a string.
  *                   I.e
  *                   * int: 4007007
  *                   * str: 4.7.7
  *
  * @param    string        $_EXTKEY    : extension key
  * @return    array        $arrReturn  : version as int (integer) and str (string)
  * @access public
  * @version 0.0.1
  * @since 0.0.1
  */
  public function extMgmVersion( $_EXTKEY )
  {
    $arrReturn = null;
    
    if( ! t3lib_extMgm::isLoaded( $_EXTKEY ) )
    {
      $arrReturn['int'] = 0;
      $arrReturn['str'] = 0;
      return $arrReturn;
    }

      // Do not use require_once!
    require( t3lib_extMgm::extPath( $_EXTKEY ) . 'ext_emconf.php');
    $strVersion = $EM_CONF[$_EXTKEY]['version'];

      // Set version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', $strVersion );
    $intVersion = ( ( int ) $main ) * 1000000;
    $intVersion = $intVersion + ( ( int ) $sub ) * 1000;
    $intVersion = $intVersion + ( ( int ) $bugfix ) * 1;
      // Set version as integer (sample: 4.7.7 -> 4007007)
    
    $arrReturn['int'] = $intVersion;
    $arrReturn['str'] = $strVersion;
    return $arrReturn;
  }
 
  
  
  /***********************************************
   *
   * Flexform
   *
   **********************************************/
  
  /**
   * flexformCheck():
   *
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function flexformCheck( )
  {
    $prompt = $this->flexformCheckPowermailCustomerEmail( );
    $prompt = $prompt . $this->flexformCheckPowermailOtherFields( );

    return $prompt;
  }  
  
  /**
   * flexformCheckPowermailCustomerEmail( ):
   *
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function flexformCheckPowermailCustomerEmail( )
  {
    $sheet = 'email';
    $field = 'customerEmail';
    $customerEmail = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];

      // RETURN : there is a flexform form
    if( $customerEmail )
    {
      return null;
    }
      // RETURN : there is a flexform form
    
      // RETURN prompt : there isn't any flexform form
    $prompt = '
      <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:flexformCheckPowermailCustomerEmail'). '
        </div>
      </div>
      ';
      // RETURN prompt : there isn't any flexform form
    
    return $prompt;
  }
  
  /**
   * flexformCheckPowermailOtherFields( ):
   *
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function flexformCheckPowermailOtherFields( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
      
      // Sheet deliveryorder
    $sheet = 'deliveryorder';
var_dump( __METHOD__, __LINE__, $this->pluginPiFlexform['data'][$sheet]['lDEF'] );
    $field = 'company';
    $deliveryorderCompany   = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'firstName';
    $deliveryorderFirstname = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'lastName';
    $deliveryorderLastname  = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'address';
    $deliveryorderAddress   = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'zip';
    $deliveryorderZip       = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'city';
    $deliveryorderCity      = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'country';
    $deliveryorderCountry   = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
      // Sheet deliveryorder
      
      // Sheet invoice
    $sheet = 'invoice';
var_dump( __METHOD__, __LINE__, $this->pluginPiFlexform['data'][$sheet]['lDEF'] );
    $field = 'company';
    $invoiceCompany         = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'firstName';
    $invoiceFirstname       = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'lastName';
    $invoiceLastname        = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'address';
    $invoiceAddress         = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'zip';
    $invoiceZip             = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'city';
    $invoiceCity            = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    $field = 'country';
    $invoiceCountry         = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
      // Sheet invoice

    switch( true )
    {
      case( empty( $deliveryorderCompany ) ):
      case( empty( $deliveryorderFirstname ) ):
      case( empty( $deliveryorderLastname ) ):
      case( empty( $deliveryorderAddress ) ):
      case( empty( $deliveryorderZip ) ):
      case( empty( $deliveryorderCity ) ):
      case( empty( $deliveryorderCountry ) ):
      case( empty( $invoiceCompany ) ):
      case( empty( $invoiceFirstname ) ):
      case( empty( $invoiceLastname ) ):
      case( empty( $invoiceAddress ) ):
      case( empty( $invoiceZip ) ):
      case( empty( $invoiceCity ) ):
      case( empty( $invoiceCountry ) ):
        break;
      default:
        return null;
    }
    
      // RETURN prompt : there isn't any flexform form
    $prompt = '
      <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:flexformCheckPowermailOtherFields'). '
        </div>
      </div>
      ';
      // RETURN prompt : there isn't any flexform form
    
    return $prompt;
  }
 
  
  
  /***********************************************
   *
   * Numberformat
   *
   **********************************************/
  
  /**
 * number Format for typoscript
 *
 * @param	[type]		$$content: ...
 * @param	[type]		$conf: ...
 * @return	string		formatted number
 */
  public function numberformat( $content = '', $conf = array( ) )
  {
    global $TSFE;
    $local_cObj = $TSFE->cObj; // cObject

    if( ! $content )
    {
      $conf     = $conf['userFunc.']; // TS configuration
      $content  = $local_cObj->cObjGetSingle($conf['number'], $conf['number.']); // get number
    }

    $numberFormat =  number_format( $content, $conf['decimal'], $conf['dec_point'], $conf['thousands_sep'] );

      // DRS
    unset( $content );
    $drs = false;
    if( $conf['userFunc.']['drs'] )
    {
      $drs = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    if( $this->drs->drsSession || $drs )
    {
      $prompt = __METHOD__ . ' returns: ' . $numberFormat;
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    return $numberFormat;
  }
  
  
  
  /***********************************************
   *
   * Plugin 1 report
   *
   **********************************************/    

  /**
   * pi1FfSdefReport()  : Check the configuration of
   *                      * the plugin / flexform
   *                      * the powermail form
   *                      * the typoscript 
   *
   * @param    array        $plugin : Configuration of the plugin / flexform
   * @return  string        $prompt         : Prompt
   * @access  public
   * @version 2.0.0
   * @since   2.0.0
   */
  public function pi1FfSdefReport( $plugin )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $this->plugin           = $plugin;
    $this->pluginPiFlexform = t3lib_div::xml2array( $this->plugin['row']['pi_flexform'] );
    
    $prompt = null;

    $sheet            = 'sDEF';
    $field            = 'sdefReportEnable';
    $sdefReportEnable = null;
    if( ! empty ( $this->plugin['row']['pi_flexform'] ) )
    {
      $sdefReportEnable = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    }

    $prompt = $this->emptyCheck( );
    if( $prompt )
    {
      $prompt = $prompt . '
        <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:pi1FfSdefReportPerformance'). '
          </div>
        </div>
        ';
      return $prompt;    
    }

      // RETURN : Check it! report is disabled
    if( empty ( $sdefReportEnable ) )
    {
      $prompt = '
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:pi1FfSdefReportDisabled'). '
          </div>
        </div>
        ';
      return $prompt;
    }
      // RETURN : Check it! report is disabled

    $this->pi1FfSdefReportInit( );

    $prompt = $this->typoscriptCheck( );

    $prompt = $prompt . $this->databaseCheck( );

    $prompt = $prompt . $this->powermailCheck( );

    $prompt = $prompt . $this->flexformCheck( );

    $prompt = $prompt . '
      <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:pi1FfSdefReportPerformance'). '
        </div>
      </div>
      ';

      // OK prompt, if there isn't any other prompt
    if( empty( $prompt ) )
    { 
      $prompt = '
        <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:pi1FfSdefReportOk'). '
          </div>
        </div>
        <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:pi1FfSdefReportPerformance'). '
          </div>
        </div>
        ';
    }
      // OK prompt, if there isn't any other prompt
    
    return $prompt;
  }
  
  /**
   * pi1FfSdefReportInit( ): Displays the quick start message.
   *
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function pi1FfSdefReportInit( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
    
    
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/'; 
    
    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->plugin->row;

    require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
    $this->powermail        = t3lib_div::makeInstance( 'tx_caddy_powermail' );

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc         = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
    
    $this->pi1FfSdefReportInitDrs( );

    $this->powermail->pObj  = $this;
    $this->powermail->init( $this->plugin['row'] );

    return true;
  }
  
  /**
   * pi1FfSdefReportInitDrs( ): Displays the quick start message.
   *
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function pi1FfSdefReportInitDrs( )
  {
    $sheet    = 'sDEF';
    $field    = 'sdefDrs';
    $sdefDrs  = null;
    if( ! empty ( $this->plugin['row']['pi_flexform'] ) )
    {
      $sdefDrs  = $this->pluginPiFlexform['data'][$sheet]['lDEF'][$field]['vDEF'];
    }
    
//var_dump( $sdefDrs, $this->plugin['row']['pi_flexform'] );    
    
    if( empty( $sdefDrs ) )
    {
      return;
    }
    
    $this->drs->zzDrsPromptsTrue( );

    $prompt = 'The DRS - Development Reporting System is enabled by the flexform (backend mode).';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    $str_header = $this->plugin['row']['header'];
    $int_uid    = $this->plugin['row']['uid'];
    $int_pid    = $this->plugin['row']['pid'];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog('[INFO/DRS] ' . $prompt, $this->extKey, 0);


  }



//  /**
//   * pageWizard( ): Builds an input form that also includes the link popup wizard.
//   * @param        array        Parameter array.  Contains fieldName and fieldValue.
//   * @return        string        HTML output for form widget.
//   * @version 2.0.0
//   * @since   2.0.0
//   */
//  public function pageWizard( $params )
//  {
//    /* Pull the current fieldname and value from constants */
//    $fieldName  = $params['fieldName'];
//    $fieldValue = $params['fieldValue'];
//    
//    $input = '<input style="margin-right: 3px;" name="'. $fieldName .'" value="'. $fieldValue .'" />';
//
//    /* @todo     Don't hardcode the inclusion of the wizard this way.  Use more backend APIs. */
//    $wizard = '<a href="#" onclick="this.blur(); vHWin=window.open(\'../../../../typo3/browse_links.php?mode=wizard&amp;P[field]='. $fieldName .'&amp;P[formName]=editForm&amp;P[itemName]='. $fieldName .'&amp;P[fieldChangeFunc][typo3form.fieldGet]=null&amp;P[fieldChangeFunc][TBE_EDITOR_fieldChanged]=null\',\'popUpID478be36b64\',\'height=300,width=500,status=0,menubar=0,scrollbars=1\'); vHWin.focus(); return false;"><img src="../../../../typo3/sysext/t3skin/icons/gfx/link_popup.gif" width="16" height="15" border="0" alt="Link" title="Link" /></a>';
//
//    return $input.$wizard;
//  }
 
  
  
  /***********************************************
   *
   * Powermail
   *
   **********************************************/
  
  /**
   * powermailCheck():
   *
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function powermailCheck( )
  {
    $prompt = $this->powermailCheckContent( );
    if( $prompt )
    {
      return $prompt;
    }
        
    $prompt = $this->powermailCheckMarker( );

    return $prompt;
  }  
  
  /**
   * powermailCheckContent():
   *
   * @param   string    $prompt
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function powermailCheckContent( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
      // RETURN : there is a powermail form
    if( $this->powermail->fieldUid )
    {
      return null;
    }
      // RETURN : there is a powermail form
    
      // RETURN prompt : there isn't any powermail form
    $prompt = '
      <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:powermailNocontent'). '
        </div>
      </div>
      ';
      // RETURN prompt : there isn't any powermail form
    
    return $prompt;
  }
  
  
  /**
   * powermailCheckMarker():
   *
   * @return  string    $prompt : message wrapped in HTML
   * @access  private
   * @version 2.0.0
   * @since   2.0.0
   */
  private function powermailCheckMarker( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
    $prompt = null; 

    if( ! $this->powermail->markerReceiver )
    {
      $prompt = $prompt . '
      <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL( 'LLL:EXT:caddy/lib/userfunc/locallang.xml:pmReceiverMarkerWo' ) . '
        </div>
      </div>
      ';
    }
    
    if( $this->powermail->markerReceiverWtcart )
    {
      $prompt = $prompt . '
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL( 'LLL:EXT:caddy/lib/userfunc/locallang.xml:pmReceiverMarkerWiWtcart' ) . '
        </div>
      </div>
      ';
    }
    
    if( ! $this->powermail->markerSender )
    {
      $prompt = $prompt . '
      <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL( 'LLL:EXT:caddy/lib/userfunc/locallang.xml:pmSenderMarkerWo' ) . '
        </div>
      </div>
      ';
    }
    
    if( $this->powermail->markerSenderWtcart )
    {
      $prompt = $prompt . '
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL( 'LLL:EXT:caddy/lib/userfunc/locallang.xml:pmSenderMarkerWiWtcart' ) . '
        </div>
      </div>
      ';
    }
    
    if( ! $this->powermail->markerThanks )
    {
      $prompt = $prompt . '
      <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL( 'LLL:EXT:caddy/lib/userfunc/locallang.xml:pmThanksMarkerWo' ) . '
        </div>
      </div>
      ';
    }
    
    return $prompt;
  }
 
  
  
  /***********************************************
   *
   * Prompts
   *
   **********************************************/    

  /**
   * promptCurrIP( ): Displays the IP of the current backend user
   *
   * @return    string        message wrapped in HTML
   * @access  public
   * @version 0.0.1
   * @since   0.0.1
   */
  public function promptCurrIP( )
  {
      $prompt = null;

      $prompt = $prompt.'
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptCurrIPBody') . ': ' . t3lib_div :: getIndpEnv('REMOTE_ADDR') . '
  </div>
</div>';

    return $prompt;
  }
  
  /**
   * promptEvaluatorTYPO3version(): Displays the quick start message.
   *
   * @return  string    message wrapped in HTML
   * @access  public
   * @version 2.0.0
   * @since   2.0.0
   */
  public function promptEvaluatorTYPO3version()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $prompt = null;

    $this->set_TYPO3Version( );
    
    switch( true )
    {
      case( $this->typo3Version < 4005000 ):
          // Smaller than 4.5
        $prompt = $prompt . '
          <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version45smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4006000 ):
          // Greater than 4.7
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version46smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4007000 ):
          // Greater than 4.7
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version47smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4008000 ):
          // Greater than 4.7
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version48smaller'). '
            </div>
          </div>
          ';
        break;
//      case( ( $this->typo3Version >= 4006000 ) && ( $this->typo3Version < 4007000 ) ):
      default:
          // Equal to 4.6
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version48orGreater'). '
            </div>
          ';
        break;
    }
        
    return $prompt;
  }

  
  
  /**
   * promptExternalLinks(): Displays the quick start message.
   *
   * @return  string    message wrapped in HTML
   * @access  public
   * @version 2.0.0
   * @since   2.0.0
   */
  public function promptExternalLinks()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $prompt = null;

      $prompt = $prompt . '
<div class="message-body" style="max-width:600px;">
  ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptExternalLinksBody') . '
</div>';

    return $prompt;
  }
  
  /**
   * promptSponsors( ): Displays the quick start message.
   *
   * @return  string    message wrapped in HTML
   * @access  public
   * @version 2.0.0
   * @since   2.0.0
   */
  public function promptSponsors()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $prompt = null;

      $prompt = $prompt . '
<div class="message-body" style="max-width:600px;">
  ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:promptSponsors') . '
</div>';

    return $prompt;
  }
   
  
  
  /***********************************************
   *
   * TYPO3
   *
   **********************************************/    

 /**
   * set_TYPO3Version( ):
   *
   * @return  void
   * @access  private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function set_TYPO3Version( )
  {
      // RETURN : typo3Version is set
    if( $this->typo3Version !== null )
    {
      return;
    }
      // RETURN : typo3Version is set
    
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
    $version = ( ( int ) $main ) * 1000000;
    $version = $version + ( ( int ) $sub ) * 1000;
    $version = $version + ( ( int ) $bugfix ) * 1;
    $this->typo3Version = $version;
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

    if( $this->typo3Version < 3000000 )
    {
      $prompt = '<h1>ERROR</h1>
        <h2>Unproper TYPO3 version</h2>
        <ul>
          <li>
            TYPO3 version is smaller than 3.0.0
          </li>
          <li>
            constant TYPO3_version: ' . TYPO3_version . '
          </li>
          <li>
            integer $this->typo3Version: ' . ( int ) $this->typo3Version . '
          </li>
        </ul>
          ';
      die ( $prompt );
    }
  }
  
  
  
  /***********************************************
   *
   * Typoscript
   *
   **********************************************/
    
/**
 * typoscriptCheck( ): 
 *
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function typoscriptCheck( )
  {
    $this->typoscriptInit( );

    if( ! empty( $this->conf['pluginCheck'] ) )
    {
      return;
    }

    $prompt = '
      <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:caddy/lib/userfunc/locallang.xml:typoscriptMissing'). '
        </div>
      </div>
      ';
    
    return $prompt;
  }
   
/**
 * typoscriptInit( ): 
 *
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function typoscriptInit( )
  {
    //$this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']; // get ts

      // Init page id and the page object
    $this->typoscriptInitPageUid( );
    $this->typoscriptInitPageObj( );

      // Init agregrated TypoScript
    $arr_rows_of_all_pages_inRootLine = $this->pageObject->getRootLine( $this->pid );
    if( empty( $arr_rows_of_all_pages_inRootLine ) )
    {
      return;
    }
    $this->typoscriptInitTsObj( $arr_rows_of_all_pages_inRootLine );

    $this->conf = $this->typoscriptObject->setup['plugin.']['tx_caddy_pi1.'];

    return;
  }

/**
 * typoscriptInitPageObj(): Initiate an page object.
 *
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function typoscriptInitPageObj( )
  {
    if( ! empty( $this->pageObject ) )
    {
      return;
    }

      // Set current page object
    require_once( PATH_t3lib . 'class.t3lib_page.php' );
    $this->pageObject = t3lib_div::makeInstance( 't3lib_pageSelect' );

    return;
  }

  /**
 * typoscriptInitPageUid(): Initiate the page uid.
 *
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function typoscriptInitPageUid( )
  {
    if( ! empty( $this->pid ) )
    {
      return;
    }

      // Update: Get current page id from the plugin
    $int_pid = false;
    if( $this->row['pid'] > 0 )
    {
      $int_pid = $this->row['pid'];
    }
      // Update: Get current page id from the plugin

      // New: Get current page id from the current URL
    if( ! $int_pid )
    {
        // Get backend URL - something like .../alt_doc.php?returnUrl=db_list.php&id%3D2926%26table%3D%26imagemode%3D1&edit[tt_content][1734]=edit
      $str_url = $_GET['returnUrl'];
        // Get curent page id
      $int_pid = intval( substr( $str_url, strpos( $str_url, 'id=' ) + 3 ) );
    }
      // New: Get current page id from the current URL

      // Set current page id
    $this->pid = $int_pid;

    return;
  }

  /**
 * typoscriptInitTsObj(): Initiate the TypoScript of the current page.
 *
 * @param	array		$arr_rows_of_all_pages_inRootLine: Agregate the TypoScript of all pages in the rootline
 * @return	void
 * @access    private
 * @version 2.0.0
 * @since 2.0.0
 */
  private function typoscriptInitTsObj( $arr_rows_of_all_pages_inRootLine )
  {
    if( ! empty( $this->typoscriptObject ) )
    {
      return;
    }

    require_once( PATH_t3lib . 'class.t3lib_tstemplate.php' );
    require_once( PATH_t3lib . 'class.t3lib_tsparser_ext.php' );

    $this->typoscriptObject = t3lib_div::makeInstance( 't3lib_tsparser_ext' );
    $this->typoscriptObject->tt_track = 0;
    $this->typoscriptObject->init( );
    $this->typoscriptObject->runThroughTemplates( $arr_rows_of_all_pages_inRootLine );
    $this->typoscriptObject->generateConfig( );

    return;
  }
  


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_userfunc.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_userfunc.php']);
}

?>