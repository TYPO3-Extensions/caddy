<?php
if( ! defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}



  ////////////////////////////////////////////////////////////////////////////
  //
  // INDEX

  // Set TYPO3 version
  // Set powermail version
  // Configuration by the extension manager
  //    Database read only
  //    Localization support
  // Enables the Include Static Templates
  // Add pagetree icons
  // Methods for backend workflows
  // Plugin Configuration
  // TCA for tables
  // Allow tables on pages



  ////////////////////////////////////////////////////////////////////////////
  //
  // Set TYPO3 version

  // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
$typo3Version = $version;
  // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

if( $typo3Version < 3000000 ) 
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
  // Set TYPO3 version


    

  ////////////////////////////////////////////////////////////////////////////
  //
  // Set powermail version

$path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
$userfunc = t3lib_div::makeInstance( 'tx_caddy_userfunc' );

$arrResult = $userfunc->extMgmVersion( 'powermail' );
$versionInt = $arrResult['int'];
//$versionStr = $arrResult['str'];
switch( true )
{
  case( $versionInt < 1000000 ):
    $prompt = 'ERROR: unexpected result<br />
      powermail version is below 1.0.0: ' . $versionInt . '<br />
      ext_tables.php method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $_EXTKEY;
      // 130720, dwildt, 1-: die( ) blocks the whole TYPO3 system
    //die( $prompt );
      // 130720, dwildt, 1+
    $pmVers = '0x';
    break;
  case( $versionInt < 2000000 ):
    $pmVers = '1x';
    break;
  case( $versionInt < 3000000 ):
    $pmVers = '2x'; 
    break;
  case( $versionInt >= 3000000 ):
  default:
    $prompt = 'ERROR: unexpected result<br />
      powermail version is 3.x: ' . $versionInt . '<br />
      ext_tables.php method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $_EXTKEY;
      // 130720, dwildt, 1-: die( ) blocks the whole TYPO3 system
    //die( $prompt );
      // 130720, dwildt, 1+
    $pmVers = '3x';
    break;
}
  // Set powermail version


    

  ////////////////////////////////////////////////////////////////////////////
  //
  // Configuration by the extension manager

$confArr  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['caddy']);

  // Language for labels of static templates and page tsConfig
$beLanguage = $confArr['beLanguage'];
switch( $beLanguage )
{
  case( 'German'):
    $beLanguage = 'de';
    break;
  default:
    $beLanguage = 'default';
}
  // Language for labels of static templates and page tsConfig
  // Configuration by the extension manager




  ////////////////////////////////////////////////////////////////////////////
  //
  // Enables the Include Static Templates

  // Case $beLanguage
switch( true ) 
{
  case( $beLanguage == 'de' ):
      // German
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/',                  'Caddy');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/',              '+Caddy CSS');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/orange/',       '+Caddy CSS +Orange');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/green/',        '+Caddy CSS +Gruen');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/red/',          '+Caddy CSS +Rot');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/properties/de/',    '+Caddy Einstellungen Deutsch');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/1x/',     '+Caddy Powermail 1.x');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/2x/',     '+Caddy Powermail 2.x Main');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/2x/css/', '+Caddy Powermail 2.x +CSS fancy');
//    switch( true )
//    {
//      case( $typo3Version < 4007000 ):
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/typo3/4.6/', '+Flip it!: Basis fuer TYPO3 < 4.7 (einbinden!)');
//        break;
//      default:
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/typo3/4.6/', '+Flip it!: Basis fuer TYPO3 < 4.7 (NICHT einbinden!)');
//        break;
//    }
    break;
  default:
      // English
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/',                  'Caddy');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/',              '+Caddy CSS');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/orange/',       '+Caddy CSS +orange');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/green/',        '+Caddy CSS +green');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/css/red/',          '+Caddy CSS +red');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/properties/de/',    '+Caddy properties German');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/1x/',     '+Caddy Powermail 1.x');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/2x/',     '+Caddy Powermail 2.x Main');
    t3lib_extMgm::addStaticFile($_EXTKEY, 'static/powermail/2x/css/', '+Caddy Powermail 2.x +CSS fancy');
//    switch( true )
//    {
//      case( $typo3Version < 4007000 ):
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/typo3/4.6/', '+Flip it!: Basis for TYPO3 < 4.7 (obligate!)');
//        break;
//      default:
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/typo3/4.6/', '+Flip it!: Basis for TYPO3 < 4.7 (don\'t use it!)');
//        break;
//    }
    break;
}
  // Case $beLanguage
  // Enables the Include Static Templates
  


  ////////////////////////////////////////////////////////////////////////////
  //
  // Add pagetree icons

  // Case $beLanguage
switch( true )
{
  case( $beLanguage == 'de' ):
      // German
    $TCA['pages']['columns']['module']['config']['items'][] =
       array( 'Caddy', 'caddy', t3lib_extMgm::extRelPath( $_EXTKEY ).'files/img/caddy_100_02.png' );
    break;
  default:
      // English
    $TCA['pages']['columns']['module']['config']['items'][] =
       array( 'Caddy', 'caddy', t3lib_extMgm::extRelPath( $_EXTKEY ).'files/img/caddy_100_02.png' );
}
  // Case $beLanguage

t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-caddy', '../typo3conf/ext/caddy/files/img/caddy_100_02.png');
  // Add pagetree icons



  ///////////////////////////////////////////////////////////
  //
  // Methods for backend workflows

//require_once(t3lib_extMgm::extPath($_EXTKEY).'lib/flexform/class.tx_caddy_flexform.php');
require_once(t3lib_extMgm::extPath($_EXTKEY).'lib/userfunc/class.tx_caddy_userfunc.php');
  // Methods for backend workflows



  ////////////////////////////////////////////////////////////////////////////
  //
  // Plugin Configuration

t3lib_div::loadTCA('tt_content');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][ $_EXTKEY . '_pi1' ]  = 'layout,select_key,recursive,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][ $_EXTKEY . '_pi1' ]      = 'pi_flexform';
t3lib_extMgm::addPlugin(array(
  'LLL:EXT:caddy/locallang_db.xml:tt_content.list_type_pi1',
  $_EXTKEY . '_pi1',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'files/img/caddy_100_02.png'
),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/pi1/flexform' . $pmVers . '.xml' ); 

$TCA['tt_content']['types']['list']['subtypes_excludelist'][ $_EXTKEY . '_pi3']  = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][ $_EXTKEY . '_pi3']      ='pi_flexform';
t3lib_extMgm::addPlugin(array(
  'LLL:EXT:caddy/locallang_db.xml:tt_content.list_type_pi3',
  $_EXTKEY . '_pi3',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'files/img/caddy_050_03.png'
),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi3', 'FILE:EXT:' . $_EXTKEY . '/pi3/flexform.xml' );
  // Plugin Configuration



  ////////////////////////////////////
  //
  // TCA for tables

  // Orders
$TCA['tx_caddy_order'] = array (
  'ctrl' => array (
    'title'             => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order',
    'label'             => 'numberOrder',  
    'label_alt'         => 'numberDeliveryorder,numberInvoice,customerEmail',  
    'label_alt_force'   => true,  
    'tstamp'            => 'tstamp',
    'crdate'            => 'crdate',
    'cruser_id'         => 'cruser_id',
    'delete'            => 'deleted',  
    'default_sortby'    => 'ORDER BY numberOrder DESC, tstamp DESC',  
    'readOnly'          => $confArr['databaseReadonly'],
    'hideAtCopy'        => true,
    'dividers2tabs'     => true,
    'dynamicConfigFile' => t3lib_extMgm::extPath( $_EXTKEY ) . 'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'files/img/caddy_100_02.png',
  ),
);
  // Orders

  // Items
$TCA['tx_caddy_item'] = array (
  'ctrl' => array (
    'title'             => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_item',
    'label'             => 'uid',  
    'label_alt'         => 'title',  
    'label_alt_force'   => true,  
    'tstamp'            => 'tstamp',
    'crdate'            => 'crdate',
    'cruser_id'         => 'cruser_id',
    'delete'            => 'deleted',
    'default_sortby'    => 'ORDER BY uid DESC',  
    'readOnly'          => $confArr['databaseReadonly'],
    'dividers2tabs'     => true,
    'dynamicConfigFile' => t3lib_extMgm::extPath( $_EXTKEY ) . 'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'files/img/caddy_100_02.png',
  ),
);
  // Items

  // TCA for tables



  ////////////////////////////////////
  //
  // Allow tables on pages

t3lib_extMgm::allowTableOnStandardPages( 'tx_caddy_item ');
t3lib_extMgm::allowTableOnStandardPages( 'tx_caddy_order ');
t3lib_extMgm::addToInsertRecords( 'tx_caddy_item ');
t3lib_extMgm::addToInsertRecords( 'tx_caddy_order ');
  // Allow tables on pages




?>