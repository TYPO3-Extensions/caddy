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
* plugin 'Minicart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.2
 * @since       1.4.6
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
  * @since    1.4.6
  */	
  public function main( $content, $conf ) 
  {
    unset( $content );
    
      // config
    global $TSFE;
    $local_cObj = $TSFE->cObj;
    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
    $path2pi1 = t3lib_extMgm::extPath( 'caddy' ) . 'pi1/';
    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    require_once( $path2lib . 'class.tx_caddy_session.php'); // file for div functions
    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php'); // file for dynamicmarker functions
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;
      // Class with methods for get flexform values
    require_once( $path2pi1 . 'class.tx_caddy_pi1_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi1_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;

    $this->drs->init( );
    
    $this->session = t3lib_div::makeInstance('tx_caddy_session'); // Create new instance for div functions
    $this->session->setParentObject( $this );
    
    $this->dynamicMarkers = t3lib_div::makeInstance('tx_caddy_dynamicmarkers', $this->scriptRelPath); // Create new instance for dynamicmarker function

    $this->tmpl['minicart']       = $this->cObj->getSubpart($this->cObj->fileResource($this->conf['main.']['template']), '###CADDY_MINICART###'); // Load FORM HTML Template
    $this->tmpl['minicart_empty'] = $this->cObj->getSubpart($this->cObj->fileResource($this->conf['main.']['template']), '###CADDY_MINICART_EMPTY###'); // Load FORM HTML Template

    //Read Flexform
    $row          = $this->pi_getRecord('tt_content', $this->cObj->data['uid']); 
    $flexformData = t3lib_div::xml2array($row['pi_flexform']);
    $pid          = $this->pi_getFFvalue($flexformData, 'pid', 'sDEF');

    $this->products = $this->session->productsGet( );
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
var_dump( __METHOD__, __LINE__, $this->products );
    if( $count ) 
    {
      $outerArr = array(
        'count'           => $count,
        'minicart_gross'  => $this->session->productsGetGross($pid)
      );
      $local_cObj->start($outerArr, $this->conf['db.']['table']);
      foreach ((array) $this->conf['settings.']['fields.'] as $key => $value)
      {
        if (!stristr($key, '.'))
        { // no .
          $minicartMarkerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['fields.'][$key], $this->conf['settings.']['fields.'][$key . '.']);
        }
      }

      $typolink_conf = array();
      $minicartMarkerArray['###MINICART_LINK###']= $this->pi_linkToPage($this->pi_getLL('link'), $pid, "", $typolink_conf);
      $minicartMarkerArray['###MINICART_LINK_URL###']= $this->pi_getPageLink($pid, "", $typolink_conf);

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
}