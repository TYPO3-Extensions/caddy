<?php
if (!defined ('TYPO3_MODE'))  die ('Access denied.');



  ///////////////////////////////////////
  // 
  // INDEX
  // 
  // tx_caddy_item
  // tx_caddy_order



  ///////////////////////////////////////
  // 
  // tx_caddy_item
  
$TCA['tx_caddy_item'] = array (
  'ctrl' => $TCA['tx_caddy_item']['ctrl'],
  'interface' => array (
    'showRecordFieldList' => 'title'
  ),
  'feInterface' => $TCA['tx_caddy_item']['feInterface'],
  'columns' => array (
    'title' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_item.title',
      'config' => array (
        'type' => 'input',  
        'size' => '30',  
        'eval' => 'required',
      )
    ),
  ),
  'types' => array (
    '0' => array('showitem' => 'hidden;;1;;1-1-1, title;;%2%;;2-2-2')
  ),
  'palettes' => array (
    '1' => array('showitem' => ''),
  )
);
  // tx_caddy_item



  ///////////////////////////////////////
  // 
  // tx_caddy_order
  
$TCA['tx_caddy_order'] = array (
  'ctrl' => $TCA['tx_caddy_order']['ctrl'],
  'interface' => array (
    'showRecordFieldList' =>  'net,tax,gross,quantity,item' ,
  ),
  'feInterface' => $TCA['tx_caddy_order']['feInterface'],
  'columns' => array (
    'net' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.net',
      'config' => array (
        'type' => 'input',
        'size' => '30',
        'eval' => 'trim',
      )
    ),
    'tax' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.tax',
      'config' => array (
        'type' => 'input',
        'size' => '30',
        'eval' => 'trim,required',
      )
    ),
    'gross' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.gross',
      'config' => array (
        'type' => 'text',
        'cols' => '30',  
        'rows' => '5',
      )
    ),
    'quantity' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.quantity',
      'config' => array (
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'item' => array (
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.item',
      'config' => array (
        'type' => 'select',  
        'foreign_table' => 'tx_caddy_item',  
        'foreign_table_where' => 'AND tx_caddy_item.pid=###CURRENT_PID### ORDER BY tx_caddy_item.uid',  
        'size' => 10,  
        'minitems' => 0,
        'maxitems' => 10,  
        "MM" => "tx_caddy_order_mm_tx_caddy_item",  
        'wizards' => array(
          '_PADDING'  => 2,
          '_VERTICAL' => 1,
          'add' => array(
            'type'   => 'script',
            'title'  => 'Create new record',
            'icon'   => 'add.gif',
            'params' => array(
              'table'    => 'tx_caddy_item',
              'pid'      => '###CURRENT_PID###',
              'setValue' => 'prepend'
            ),
            'script' => 'wizard_add.php',
          ),
          'list' => array(
            'type'   => 'script',
            'title'  => 'List',
            'icon'   => 'list.gif',
            'params' => array(
              'table' => 'tx_caddy_item',
              'pid'   => '###CURRENT_PID###',
            ),
            'script' => 'wizard_list.php',
          ),
          'edit' => array(
            'type'                     => 'popup',
            'title'                    => 'Edit',
            'script'                   => 'wizard_edit.php',
            'popup_onlyOpenIfSelected' => 1,
            'icon'                     => 'edit2.gif',
            'JSopenParams'             => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
          ),
        ),
      )
    ),
  ),
  'types' => array (
    '0' => array(
      'showitem' => 
        'net,tax,gross,item,quantity,hidden,',
      ),
  ),
  'palettes' => array (
    '1' => array('showitem' => ''),
  )
);
  // tx_caddy_order

?>