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
    'showRecordFieldList' =>  'fileDeliveryorder,fileInvoice,fileTerms,' . 
                              'items,' . 
                              'numberDeliveryorder,numberInvoice,numberOrder,' . 
                              'pdfDeliveryorderToCustomer,pdfDeliveryorderToVendor,pdfInvoiceToCustomer,' .
                              'pdfInvoiceToVendor,pdfTermsToCustomer,pdfTermsToVendor,' .
                              'quantity,' . 
                              'sumGross,sumNet,sumTaxReduced,sumTaxNormal',
  ),
  'feInterface' => $TCA['tx_caddy_order']['feInterface'],
  'columns' => array (
    'fileDeliveryorder' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileDeliveryorder',
      'config' => array (
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',  
        'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],  
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,  
        'size' => 1,  
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'fileInvoice' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileInvoice',
      'config' => array (
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',  
        'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],  
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,  
        'size' => 1,  
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'fileTerms' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileTerms',
      'config' => array (
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',  
        'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],  
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,  
        'size' => 1,  
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'numberDeliveryorder' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberDeliveryorder',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'int',
      )
    ),
    'numberInvoice' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberInvoice',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'int',
      )
    ),
    'numberOrder' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberOrder',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'int',
      )
    ),
    'pdfDeliveryorderToCustomer' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToCustomer',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfDeliveryorderToVendor' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToVendor',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfInvoiceToCustomer' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfInvoiceToCustomer',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfInvoiceToVendor' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfInvoiceToVendor',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfTermsToCustomer' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfTermsToCustomer',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfTermsToVendor' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfTermsToVendor',
      'config' => array (
        'type' => 'check',
        'default' => 1,
      )
    ),
    'sumGross' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumGross',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'double2,nospace',
      )
    ),
    'sumNet' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumNet',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'double2,nospace',
      )
    ),
    'sumTaxNormal' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumTaxNormal',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'double2,nospace',
      )
    ),
    'sumTaxReduced' => array (
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumTaxNormal',
      'config' => array (
        'type' => 'input',  
        'size' => '10',  
        'eval' => 'double2,nospace',
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
    'items' => array (
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.items',
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
        '--div--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.div.email,' .
          '--palette--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.palette.isSentToCustomer;isSentToCustomer,' .
          '--palette--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.palette.isSentToVendor;isSentToVendor,' .
          '--palette--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.palette.files;files,' .
        '--div--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.div.numbers,' .
          '--palette--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.palette.numbers;numbers,' .
        '--div--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.div.sum,' .
          '--palette--;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.palette.sum;sum,' .
        '',
      ),
  ),
  'palettes' => array (
    'files' => array (
      'showitem' => 
        'fileDeliveryorder;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileDeliveryorder,' .
        'fileInvoice;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileInvoice,' .
        '--linebreak--,' . 
        'fileTerms;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.fileTerms,' .
        '',
      'canNotCollapse' => 1,
    ),
    'isSentToCustomer' => array (
      'showitem' => 
        'pdfDeliveryorderToCustomer;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToCustomer,' .
        'pdfInvoiceToCustomer;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfInvoiceToCustomer,' .
        'pdfTermsToCustomer;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfTermsToCustomer,' .
        '',
      'canNotCollapse' => 1,
    ),
    'isSentToVendor' => array (
      'showitem' => 
        'pdfDeliveryorderToVendor;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToVendor,' .
        'pdfInvoiceToVendor;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfInvoiceToVendor,' .
        'pdfTermsToVendor;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.pdfTermsToVendor,' .
        '',
      'canNotCollapse' => 1,
    ),
    'numbers' => array (
      'showitem' => 
        'numberOrder;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberOrder,' .
        'numberDeliveryorder;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberDeliveryorder,' .
        'numberInvoice;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.numberInvoice,' .
        '',
      'canNotCollapse' => 1,
    ),
    'sum' => array (
      'showitem' => 
        'sumNet;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumNet,' .
        '--linebreak--,' . 
        'sumTaxReduced;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumTaxReduced,' .
        '--linebreak--,' . 
        'sumTaxNormal;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumTaxNormal,' .
        '--linebreak--,' . 
        'sumGross;LLL:EXT:caddy/locallang_db.xml:tx_caddy_order.sumGross,' .
        '',
      'canNotCollapse' => 1,
    ),
  )
);
  // tx_caddy_order

?>