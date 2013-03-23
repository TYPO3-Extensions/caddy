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

require_once( PATH_tslib . 'class.tslib_pibase.php' );

/**
* plugin 'Minicart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.2
 * @since       2.0.2
 */
class tx_caddy_pi3 extends tslib_pibase
{

  public $prefixId = 'tx_caddy_pi3';
  public $scriptRelPath = 'pi3/class.tx_caddy_pi3.php';
  public $extKey = 'caddy';

 /**
  * the main method of the PlugIn
  *
  * @param string    $content: The PlugIn content
  * @param array   $conf: The PlugIn configuration
  * @return  The content that is displayed on the website
  * @version  2.0.2
  * @since    2.0.2
  */	
  public function main( $content, $conf ) 
  {
    unset( $content );
    
      // config
    $this->conf = $conf;
    $this->init( );
    

    $this->tmpl['minicart']       = $this->cObj->getSubpart( $this->cObj->fileResource($this->conf['main.']['template'] ), '###CADDY_MINICART###' ); // Load FORM HTML Template
    $this->tmpl['minicart_empty'] = $this->cObj->getSubpart( $this->cObj->fileResource($this->conf['main.']['template'] ), '###CADDY_MINICART_EMPTY###' ); // Load FORM HTML Template

    //Read Flexform

    $this->products = $this->session->productsGet( $this->pidCaddy );
    $count = count( $this->products );
//    switch( true )
//    {
//      case( count( $this->products ) > 0 ):
//        $caddy = $this->caddyWiProducts( );
//        break;
//      case( ! ( count( $this->products ) > 0 ) ):
//      default:
//        $this->caddyWoProducts( );
//        $caddy = null;
//        break;
//    }
var_dump( __METHOD__, __LINE__, $this->pidCaddy, $this->products, $this->tmpl );
    if( $count ) 
    {
      $outerArr = array(
        'count'           => $count,
        'minicart_gross'  => $this->session->productsGetGross( $this->pidCaddy )
      );
      $this->local_cObj->start($outerArr, $this->conf['db.']['table']);
      foreach ((array) $this->conf['settings.']['fields.'] as $key => $value)
      {
        if (!stristr($key, '.'))
        { // no .
          $minicartMarkerArray['###' . strtoupper($key) . '###'] = $this->local_cObj->cObjGetSingle($this->conf['settings.']['fields.'][$key], $this->conf['settings.']['fields.'][$key . '.']);
        }
      }

      $typolink_conf = array();
      $minicartMarkerArray['###MINICART_LINK###']= $this->pi_linkToPage($this->pi_getLL('link'), $this->pidCaddy, "", $typolink_conf);
      $minicartMarkerArray['###MINICART_LINK_URL###']= $this->pi_getPageLink($this->pidCaddy, "", $typolink_conf);

      $this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['minicart'], $minicartMarkerArray); // Get html template
      $this->content = $this->dynamicMarkers->main($this->content, $this); // Fill dynamic locallang or typoscript markers
      //$this->content = preg_replace('|###.*?###|i', '', $this->content); // Finally clear not filled markers
    } 
    else 
    {
      $this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['minicart_empty'], null, $minicartMarkerArray); // Get html template
      $this->content = $this->dynamicMarkers->main($this->content, $this);
    }
    return $this->pi_wrapInBaseClass($this->content);
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
    $this->initVars( );
    
    $this->initInstances( );
    $this->initFlexform( );
    $this->initDrs( );

    $this->initPid( );
    $this->initTemplate( );
    
    $this->session->setParentObject( $this );
  }
  
 /**
  * initDrs( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDrs( )
  {
    $this->drs->init( );
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
    
    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

    require_once( 'class.tx_caddy_pi1_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi1_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_session.php'); // file for div functions
    $this->session = t3lib_div::makeInstance('tx_caddy_session'); // Create new instance for div functions
    $this->session->setParentObject( $this );
    
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
    $this->pidCaddy = $this->flexform->sdefPidCaddy;

      // DIE  : $row is empty
    if( empty( $this->pidCaddy ) )
    {
      $prompt = 'ERROR: uid of the page with the caddy is empty!' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
      // DIE  : $row is empty
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

 /**
  * initVars( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initVars( )
  {
    $this->local_cObj = $GLOBALS['TSFE']->cObj;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
  }

}