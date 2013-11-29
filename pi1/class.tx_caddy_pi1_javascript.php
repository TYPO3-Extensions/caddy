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
* The class tx_caddy_pi1_javascript bundles methods for javascript and AJAX.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @version  4.0.0
* @since    4.0.0
*
* @internal     #53679 
* @package      TYPO3
* @subpackage   caddy
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   66: class tx_caddy_pi1_javascript
 *   82:     function __construct($parentObj)
 *
 *              SECTION: CSS
 *  135:     function class_onchange($obj_ts, $arr_ts, $number_of_items)
 *  381:     function wrap_ajax_div($template)
 *
 *              SECTION: Files
 *  505:     function load_jQuery()
 *  615:     function addJssFileToHead( $path, $name, $keyPathTs )
 *
 *              SECTION: Helper
 *  693:     function set_arrSegment()
 *  759:     public function addCssFiles()
 *  809:     public function addJssFiles()
 * 1063:     public function addCssFile($path, $ie_condition, $name, $keyPathTs, $str_type, $inline )
 *
 *              SECTION: Dynamic methods
 * 1245:     function dyn_method_load_all_modes( )
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_caddy_pi1_javascript
{
  
  public $prefixId = 'tx_caddy_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1_javascript.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;
  
  private $local_cObj = null;


  
/**
 * addJssFilesJqueryPluginsCaddy( )
 *
 * @return	void
 * @access      public
 * @version     4.0.0
 * @since       4.0.0
 */
  public function addJssFilesJqueryPluginsCaddy( )
  {
//      // #i0034, 131128, dwildt, 1+
//    return;
    
    $this->local_cObj = $this->pObj->local_cObj;

    $this->addJssFilesJqueryPluginsCaddyCSS( );
    $this->addJssFilesJqueryPluginsCaddyPlugin( );
    $this->addJssFilesJqueryPluginsCaddyLocalisation( );
    $this->addJssFilesJqueryPluginsCaddyLibrary( );
  }
  
/**
 * addJssFilesJqueryPluginsCaddyCSS( )
 *
 * @return	void
 * @access      private
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFilesJqueryPluginsCaddyCSS( )
  {
      // Short variable
    $tsPathToCaddy = $this->pObj->conf['javascript.']['jquery.']['plugins.']['caddy.'];
    
      // Include the inline css
    $name         = 'jquery_plugins_caddy_css';
    $path         = $tsPathToCaddy['css.']['path'];
    $path_tsConf  = 'javascript.jquery.plugins.caddy.css';
    $marker       = $tsPathToCaddy['css.']['marker.'];
    $bool_success = $this->addCssFile( $path, $name, $path_tsConf, $marker );
    unset( $bool_success );
  }

/**
 * addJssFilesJqueryPluginsCaddyLibrary( )
 *
 * @return	void
 * @access      private
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFilesJqueryPluginsCaddyLibrary( )
  {
      // Short variable
    $tsPathToCaddy = $this->pObj->conf['javascript.']['jquery.']['plugins.']['caddy.'];
    
      // Include the library code
    $name         = 'jquery_plugins_caddy_library';
    $path         = $tsPathToCaddy['library.']['path'];
    $inline       = $tsPathToCaddy['library.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.caddy.library';
    $marker       = $tsPathToCaddy['library.']['marker.'];
    $footer       = $tsPathToCaddy['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
  }
  
/**
 * addJssFilesJqueryPluginsCaddyLocalisation( )
 *
 * @return	void
 * @access      private
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFilesJqueryPluginsCaddyLocalisation( )
  {
      // RETURN : current language is English
    if( $GLOBALS['TSFE']->lang == 'en' )
    {
      return;
    }
      // RETURN : current language is English

      // Short variable
    $tsPathToCaddy = $this->pObj->conf['javascript.']['jquery.']['plugins.']['caddy.'];
    
      // Include localised file, if current language isn't English
    $name         = 'jquery_plugins_caddy_localisation';
    $path         = $tsPathToCaddy['localisation.']['path'];
    $path         = str_replace('###LANG###', $GLOBALS['TSFE']->lang, $path);
    $inline       = $tsPathToCaddy['localisation.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.caddy.localisation';
    $marker       = $tsPathToCaddy['localisation.']['marker.'];
    $footer       = $tsPathToCaddy['localisation.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // Include localised file, if current language isn't English

  }
  
/**
 * addJssFilesJqueryPluginsCaddyPlugin( )
 *
 * @return	void
 * @access      private
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFilesJqueryPluginsCaddyPlugin( )
  {
      // Short variable
    $tsPathToCaddy = $this->pObj->conf['javascript.']['jquery.']['plugins.']['caddy.'];
    
      // Include the plugin code
    $name         = 'jquery_plugins_caddy_plugin';
    $path         = $tsPathToCaddy['plugin.']['path'];
    $inline       = $tsPathToCaddy['plugin.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.caddy.plugin';
    $marker       = $tsPathToCaddy['plugin.']['marker.'];
    $footer       = $tsPathToCaddy['plugin.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );

  }
/**
 * addCssFile(): 
 *
 * @param	string		$path       : Path to the Javascript or CSS
 * @param	string		$name       : For the key of additionalHeaderData
 * @param	string		$keyPathTs  : The TypoScript element path to $path for the DRS
 * @param	array		$marker     : marker array
 * @return	boolean		True        : success. False: error.
 * @since   3.0.1
 * @version 3.0.1
 */
  private function addCssFile( $path, $name, $keyPathTs, $marker )
  {
      // RETURN file is loaded
    if( isset ( $GLOBALS['TSFE']->additionalHeaderData[ $this->extKey . '_' . $name ] ) )
    {
      if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        $prompt = 'file isn\'t added again: ' . $path;
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN file is loaded

    $absPath = $this->getPathAbsolute( $path );
    if( $absPath == false )
    {
      return false;
    }

    $path = $this->getPathRelative( $path );
    if( $path == false )
    {
      return false;
    }

    $css = '<style type="text/css">
' . implode ('', file( $absPath )) . '
</style>';
    
    $marker = $this->getHashMarker( $marker );
//var_dump( __METHOD__, __LINE__, $marker );
    $css    = $this->pObj->cObj->substituteMarkerArray( $css, $marker );
    $css = $this->pObj->dynamicMarkers->main( $css, $this->pObj );

    $GLOBALS['TSFE']->additionalHeaderData[ $this->pObj->extKey.'_'.$name ] = $css;

      // DRS
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      $prompt = 'file is included: ' . $path;
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change it? Configure: \''.$keyPathTs.'\'';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

    return true;
  }

/**
 * addJssFilePromptError(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path: Path to the Javascript
 * @param	string		$keyPathTs: The TypoScript element path to $path for the DRS
 * @return	boolean		True: success. False: error.
 * @version   4.0.0
 * @since     4.0.0
 */
  private function addJssFilePromptError( $path, $keyPathTs )
  {
    if( empty( $this->pObj->objFlexform->str_caddy_libraries ) )
    {
        // DRS
      if( $this->pObj->drs->drsJavascript )
      {
        $prompt = 'Flexform Javascript|caddy_libraries is empty.';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'Script isn\'t included.';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return true;
    }
      // RETURN, there isn't any file for embedding

      // DRS
    if( $this->pObj->drs->drsError )
    {
      $prompt = 'Script can not be included: ' . $path;
      t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
      $prompt = 'Solve it? Configure: \''.$keyPathTs.'\'';
      t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

    return false;
  }

/**
 * addJssFileTo(): Add a JavaScript file to header or footer section
 *
 * @param	string		$path         : Path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$footer       : Add JSS script to the footer section
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * 
 * @access      private
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker )
  {
    $bool_success = false; 
    
      // Get absolute path
    $absPath = $this->getPathAbsolute( $path );
      // RETURN : there is an error with the absolute path
    if( empty( $absPath ) )
    {
      return false;
    }
      // RETURN : there is an error with the absolute path

      // Get relative path without 'EXT:'
    $path = $this->getPathRelative( $path );
      // RETURN : there is an error with the relative path
    if( empty( $path ) )
    {
      return $this->addJssFilePromptError( );
    }
      // RETURN : there is an error with the relative path

    switch( true )
    {
      case( $footer == false ):
        $bool_success = $this->addJssFileToHead( $path, $absPath, $name, $path_tsConf, $inline, $marker );
        break;
      case( $footer == true ):
      default:
        $bool_success = $this->addJssFileToFooter( $path, $absPath, $name, $path_tsConf, $inline, $marker );
        break;
    }
    
    unset( $footer );

    return $bool_success;
  }

/**
 * addJssFileToHead(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * @version 4.0.0
 * @since 4.0.0
 */
  private function addJssFileToHead( $path, $absPath, $name, $keyPathTs, $inline, $marker )
  {
      // RETURN : script is included
    if( isset( $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $this->pObj->extKey . '_' . $name ] ) )
    {
      return true;
    }
      // RETURN : script is included

    $script = $this->getTagScript( $inline, $absPath, $path, $marker );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $key ] = $script;

      // DRS
    if( $this->pObj->drs->drsJavascript )
    {
      $prompt = 'file is placed in the header section: ' . $path;
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change the path? Configure: \'' . $keyPathTs . '\'';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      $prompt = 'Change the section for all JSS files? Take the Constant Editor: [Browser - JAVASCRIPT]';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

    return true;
  }

  
/**
 * addJssFileToFooter(): Add a JavaScript file at the bottom of the page (the footer section)
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * 
 * @internal    #50069
 * @version     4.0.0
 * @since       4.0.0
 */
  private function addJssFileToFooter( $path, $absPath, $name, $keyPathTs, $inline, $marker )
  {
    if( isset( $GLOBALS[ 'TSFE' ]->additionalFooterData[ $this->pObj->extKey . '_' . $name ] ) )
    {
      return true;
    }

    $script = $this->getTagScript( $inline, $absPath, $path, $marker );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalFooterData[ $key ] = $script;

      // DRS
    if( $this->pObj->drs->drsJavascript )
    {
      $prompt = 'file is placed in the footer section: ' . $path;
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change the path? Configure: \'' . $keyPathTs . '\'';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      $prompt = 'Change the section for all JSS files? Take the Constant Editor: [Browser - JAVASCRIPT]';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

    return true;
  }

/**
 * getHashMarker( ): 
 *
 * @param	array		$marker : marker array with key value pairs
 * @return	array		$marker : marker array with hashkey value pairs
 *
 * @access    private 
 * @since     3.0.1
 * @version   3.0.1
 */
  private function getHashMarker( $marker )
  {
    foreach( array_keys( ( array ) $marker ) as $key )
    {
      if( substr( $key, -1, 1 ) != '.' )
      {
        continue;
      }
        // I.e. $key is 'title.', but we like the marker name without any dot
      $keyWoDot         = substr( $key, 0, strlen( $key ) -1 );
      $hashKey          = '###' . strtoupper( $keyWoDot ) . '###';
      $coa              = $marker[ $keyWoDot ];
      $conf             = $marker[ $key ];
      $marker[$hashKey] = $this->pObj->cObj->cObjGetSingle( $coa, $conf );
      unset( $marker[ $keyWoDot ] );
      unset( $marker[ $key ] );
    }
    
    return $marker;
  }

/**
 * getPathAbsolute( ): Returns the absolute path of the given path
 *
 * @param	string		$path : relative or absolute path to Javascript or CSS
 * @return	string		$path : absolute path or false in case of an error
 * 
 * @internal    #50069
 * @since       4.0.0
 * @version     4.0.0
 */
  private function getPathAbsolute( $path )
  {
      // RETURN path is empty
    if( empty( $path ) )
    {
        // DRS
      if( $this->pObj->drs->drsWarn )
      {
        $prompt = 'file can not be included. Path is empty. Maybe it is ok.';
        t3lib_div::devlog( '[WARN/JSS] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Change it? Configure: \'' . $keyPathTs . '\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN path is empty

      // URL or EXT:...
    $arr_parsed_url = parse_url( $path );
    if( isset( $arr_parsed_url[ 'scheme' ] ) )
    {
      if( $arr_parsed_url[ 'scheme' ] == 'EXT' )
      {
        unset( $arr_parsed_url[ 'scheme' ] );
      }
    }
      // URL or EXT:...

      // link to a file
    $bool_file_exists = true;
    if( ! isset( $arr_parsed_url['scheme'] ) )
    {
      $onlyRelative       = 1;
      $relToTYPO3_mainDir = 0;
      $absPath  = t3lib_div::getFileAbsFileName( $path, $onlyRelative, $relToTYPO3_mainDir );
      if ( ! file_exists( $absPath ) )
      {
        $bool_file_exists = false;
      }
        // relative path
      $path = preg_replace('%' . PATH_site . '%', null, $absPath);
    }
      // link to a file


      // RETURN : false, file does not exist
    if( ! $bool_file_exists )
    {
        // DRS
      if ( $this->pObj->drs->drsError )
      {
        $prompt = 'Script can not be included. File doesn\'t exist: ' . $path;
        t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'Solve it? Configure: \''.$keyPathTs.'\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN : false, file does not exist
    
    return $path;
  }

/**
 * getPathRelative( ): Returns the relative path. Prefix 'EXT:' will handled
 *
 * @param	string		$path : relative path with or without prefix 'EXT:'
 * @return	string		$path : relative path without prefix 'EXT:'
 * 
 * @internal    #50069
 * @since       4.0.0
 * @version     4.0.0
 */
  private function getPathRelative( $path )
  { 
      // RETURN : path hasn't any prefix EXT:
    if( substr( $path, 0, 4 ) != 'EXT:' )
    {
      return $path;
    }
      // RETURN : path hasn't any prefix EXT:
    
      // relative path to the JssFile as measured from the PATH_site (frontend)
    $matches  = array( );
    preg_match( '%^EXT:([a-z0-9_]*)/(.*)$%', $path, $matches );
    $path     = t3lib_extMgm::siteRelPath( $matches[ 1 ] ) . $matches[ 2 ];

    return $path;
  }

/**
 * getTagScript( ): Returns a script tag
 *
 * @param	boolean		$inline       : include the javascript inline
 * @param	string		$absPath      : absPath to the Javascript
 * @param	string		$path         : path to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.0.0
 * @version   4.0.0
 */
  private function getTagScript( $inline, $absPath, $path, $marker )
  {
    $script = null;
    
    switch( $inline )
    {
      case( true ):
        $script = $this->getTagScriptInline( $absPath, $marker );
        break;
      case( false ):
      default:
        $script = $this->getTagScriptSrc( $path );
        break;
    }
    
    return $script;
  }

/**
 * getTagScriptInline( ): Returns a script tag
 *
 * @param	string		$absPath      : absPath to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.0.0
 * @version   4.0.0
 */
  private function getTagScriptInline( $absPath, $marker )
  {
    $script = 
'  <script type="text/javascript">
  <!--
' . implode ( null , file( $absPath ) ) . '
  //-->
  </script>';

    $script = $this->getTagScriptInlineMarker( $script, $marker );
    
    return $script;
  }

/**
 * getTagScriptInlineMarker( ): 
 *
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 *
 * @access    private 
 * @since     4.0.0
 * @version   4.0.0
 */
  private function getTagScriptInlineMarker( $script, $marker )
  {
    if( ! is_array( $marker ) )
    {
      unset( $marker );
      $marker = array( );
    }
    
    $marker = $this->getHashMarker( $marker );
    
//    foreach( $this->pObj->cObj->data as $key => $value )
//    {
//      $hashKey          = '###' . strtoupper( $key ) . '###';
//      switch( true )
//      {
//        case( is_array( $value ) ):
//          $marker[$hashKey] = var_export( $value, true );
//          break;
//        case( ! is_array( $value ) ):
//        default:
//          $marker[$hashKey] = $value;
//          break;
//      }
//    }
//var_dump( __METHOD__, __LINE__, $this->pObj->cObj->data );
//var_dump( __METHOD__, __LINE__, $marker );
    
    $script = $this->pObj->cObj->substituteMarkerArray( $script, $marker );
    $script = $this->pObj->dynamicMarkers->main( $script, $this->pObj );

    return $script;
  }

/**
 * getTagScriptSrc( ): Returns a script tag
 *
 * @param	string		$path         : path to the Javascript
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.0.0
 * @version   4.0.0
 */
  private function getTagScriptSrc( $path )
  {
    $script = '  <script src="' . $path . '" type="text/javascript"></script>';

    return $script;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_javascript.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_javascript.php']);
}

?>