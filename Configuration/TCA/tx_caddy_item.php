<?php

if ( !defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

$_EXTKEY = 'caddy';

return array(
  'ctrl' => array(
    'title' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item',
    'label' => 'title',
    'label_alt' => '',
    'label_alt_force' => true,
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
    'delete' => 'deleted',
    'sortby' => 'sorting',
    'default_sortby' => 'ORDER BY sorting',
    'readOnly' => $confArr[ 'databaseReadonly' ],
    'dividers2tabs' => true,
    'iconfile' => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif',
    'searchFields' => ''
    . 'numberOrder,'
    . 'price,'
    . 'quantity,'
    . 'sku,'
    . 'tax,'
    . 'title,'
    . 'weight'
  ,
  ),
  'interface' => array(
    'showRecordFieldList' => 'numberOrder,title'
  ),
  'columns' => array(
    'numberOrder' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.numberOrder',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'price' => array(
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.price',
      'config' => array(
        'type' => 'input',
        'size' => '30',
        'eval' => 'required,double2,nospace',
      )
    ),
    'quantity' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.quantity',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'sku' => array(
      'exclude' => 1,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.sku',
      'config' => array(
        'type' => 'input',
        'size' => '30',
        'eval' => 'trim',
      )
    ),
    'tax' => array(
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.tax',
      'config' => array(
        'type' => 'select',
        'items' => array(
          array( 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.tax.I.0', '1' ),
          array( 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.tax.I.1', '2' ),
          array( 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.tax.I.2', '0' ),
        ),
        'size' => 1,
        'maxitems' => 1,
        'eval' => 'required',
      )
    ),
    'title' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.title',
      'config' => array(
        'type' => 'input',
        'size' => '30',
        'eval' => 'required',
      )
    ),
    'tx_caddy_order' => array(
      'exclude' => 1,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.tx_caddy_order',
      'config' => array(
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => array(
          array( '', 0 ),
        ),
        'foreign_table' => 'tx_caddy_order',
        'foreign_table_where' => ''
        . 'AND tx_caddy_order.pid=###CURRENT_PID### '
//        'AND tx_caddy_order.sys_language_uid IN (-1,###REC_FIELD_sys_language_uid###)'
      ,
      ),
    ),
    'weight' => array(
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_item.weight',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'double2,nospace',
      )
    ),
  ),
  'types' => array(
    '0' => array(
      'showitem' => ''
      . 'numberOrder,'
      . 'price,'
      . 'quantity,'
      . 'sku,'
      . 'tax,'
      . 'title,'
      . 'weight'
    ,
    )
  ),
  'palettes' => array(
    '1' => array( 'showitem' => '' ),
  )
);
