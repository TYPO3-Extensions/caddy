<?php

if ( !defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

$_EXTKEY = 'caddy';

return array(
  'ctrl' => array(
    'title' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order',
    'label' => 'numberOrder',
    'label_alt' => 'numberDeliveryorder,numberInvoice,customerEmail',
    'label_alt_force' => true,
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
    'delete' => 'deleted',
    'default_sortby' => 'ORDER BY numberOrder DESC, tstamp DESC',
    'readOnly' => $confArr[ 'databaseReadonly' ],
    'hideAtCopy' => true,
    'dividers2tabs' => true,
    'iconfile' => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif',
    'searchFields' => 'customerEmail,' .
    'numberDeliveryorder,numberInvoice,numberOrder,' .
    'sumGross,sumNet,sumTaxReduced,sumTaxNormal,'
  ),
  'interface' => array(
    'showRecordFieldList' => 'customerEmail,' .
    'fileDeliveryorder,fileInvoice,fileRevocation,fileTerms,' .
    'items,' .
    'numberDeliveryorder,numberInvoice,numberOrder,' .
    'pdfDeliveryorderToCustomer,pdfDeliveryorderToVendor,pdfInvoiceToCustomer,' .
    'pdfInvoiceToVendor,pdfRevocationToCustomer,pdfRevocationToVendor,pdfTermsToCustomer,pdfTermsToVendor,' .
    'quantity,' .
    'sumGross,sumNet,sumTaxReduced,sumTaxNormal,' .
    'tstamp',
  ),
  'columns' => array(
    'customerEmail' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.customerEmail',
      'config' => array(
        'type' => 'input',
        'size' => '40',
        'eval' => '',
      )
    ),
    'fileDeliveryorder' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileDeliveryorder',
      'config' => array(
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',
        'max_size' => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'BE' ][ 'maxFileSize' ],
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,
        'size' => 1,
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'fileInvoice' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileInvoice',
      'config' => array(
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',
        'max_size' => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'BE' ][ 'maxFileSize' ],
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,
        'size' => 1,
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'fileRevocation' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileRevocation',
      'config' => array(
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',
        'max_size' => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'BE' ][ 'maxFileSize' ],
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,
        'size' => 1,
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'fileTerms' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileTerms',
      'config' => array(
        'type' => 'group',
        'internal_type' => 'file',
        'allowed' => 'pdf',
        'max_size' => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'BE' ][ 'maxFileSize' ],
        'uploadfolder' => 'uploads/tx_caddy',
        'show_thumbs' => 1,
        'size' => 1,
        'minitems' => 0,
        'maxitems' => 1,
      )
    ),
    'numberDeliveryorder' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberDeliveryorder',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'numberInvoice' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberInvoice',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'numberOrder' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberOrder',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'int',
      )
    ),
    'pdfDeliveryorderToCustomer' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToCustomer',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfDeliveryorderToVendor' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToVendor',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfInvoiceToCustomer' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfInvoiceToCustomer',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfInvoiceToVendor' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfInvoiceToVendor',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfRevocationToCustomer' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfRevocationToCustomer',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfRevocationToVendor' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfRevocationToVendor',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfTermsToCustomer' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfTermsToCustomer',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'pdfTermsToVendor' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfTermsToVendor',
      'config' => array(
        'type' => 'check',
        'default' => 1,
      )
    ),
    'sumGross' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumGross',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'double2,nospace',
      )
    ),
    'sumNet' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumNet',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'double2,nospace',
      )
    ),
    'sumTaxNormal' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumTaxNormal',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'double2,nospace',
      )
    ),
    'sumTaxReduced' => array(
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumTaxReduced',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'eval' => 'double2,nospace',
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
    'items' => array(
      'l10n_mode' => 'exclude',
      'exclude' => 0,
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.items',
      'XXXconfig' => array(
        'type' => 'select',
        'foreign_table' => 'tx_caddy_item',
        'foreign_table_where' => 'AND tx_caddy_item.pid=###CURRENT_PID### ORDER BY tx_caddy_item.uid',
        'size' => 10,
        'minitems' => 0,
        'maxitems' => 10,
        'MM' => 'tx_caddy_order_mm_tx_caddy_item',
        'wizards' => array(
          '_PADDING' => 2,
          '_VERTICAL' => 1,
          'add' => array(
            'type' => 'script',
            'title' => 'Create new record',
            'icon' => 'add.gif',
            'params' => array(
              'table' => 'tx_caddy_item',
              'pid' => '###CURRENT_PID###',
              'setValue' => 'prepend'
            ),
            'script' => 'wizard_add.php',
          ),
          'list' => array(
            'type' => 'script',
            'title' => 'List',
            'icon' => 'list.gif',
            'params' => array(
              'table' => 'tx_caddy_item',
              'pid' => '###CURRENT_PID###',
            ),
            'script' => 'wizard_list.php',
          ),
          'edit' => array(
            'type' => 'popup',
            'title' => 'Edit',
            'script' => 'wizard_edit.php',
            'popup_onlyOpenIfSelected' => 1,
            'icon' => 'edit2.gif',
            'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
          ),
        ),
      ),
      'config' => array(
        'type' => 'inline',
        'foreign_table' => 'tx_caddy_item',
        'foreign_table_where' => ''
        //. 'AND tx_caddy_item.pid=###CURRENT_PID### ORDER BY tx_caddy_item.uid'
        . 'AND tx_caddy_item.deleted=1 '
        ,
        //'MM' => 'tx_caddy_order_mm_tx_caddy_item',
        //'foreign_table' => 'tx_powermail_domain_model_pages',
//        'foreign_table_where' => ''
//        . 'AND tx_powermail_domain_model_pages.deleted = 1 '
//        . 'AND tx_powermail_domain_model_pages.hidden = 0 '
//        . 'and tx_powermail_domain_model_pages.sys_language_uid = 0'
//        ,
        'foreign_field' => 'tx_caddy_order',
        'foreign_sortby' => 'sorting',
        'maxitems' => 1000,
        'appearance' => array(
          'collapseAll' => 1,
          'expandSingle' => 1,
          'useSortable' => 1,
          'newRecordLinkAddTitle' => 1,
          'levelLinksPosition' => 'top',
          'showSynchronizationLink' => 0,
          'showAllLocalizationLink' => 1,
          'showPossibleLocalizationRecords' => 1,
          'showRemovedLocalizationRecords' => 1,
        ),
        'behaviour' => array(
          'localizeChildrenAtParentLocalization' => 1,
          'localizationMode' => 'select',
        ),
      ),
    ),
    'tstamp' => array(
      'exclude' => 0,
      'l10n_mode' => 'exclude',
      'label' => 'LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.tstamp',
      'config' => array(
        'type' => 'input',
        'size' => '10',
        'max' => '20',
        'eval' => 'datetime',
        'default' => mktime( date( 'H' ), date( 'i' ), 0, date( 'm' ), date( 'd' ), date( 'Y' ) ),
      ),
    ),
  ),
  'types' => array(
    '0' => array(
      'showitem' =>
      '--div--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.div.email,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.emailDate;emailDate,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.isSentToCustomer;isSentToCustomer,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.isSentToVendor;isSentToVendor,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.files;files,' .
      '--div--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.div.numbers,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.numbers;numbers,' .
      '--div--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.div.items,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.items;items,' .
      '--div--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.div.sum,' .
      '--palette--;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.palette.sum;sum,' .
      '',
    ),
  ),
  'palettes' => array(
    'emailDate' => array(
      'showitem' =>
      'customerEmail;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.customerEmail,' .
      'tstamp;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.tstamp,' .
      '',
      'canNotCollapse' => 1,
    ),
    'files' => array(
      'showitem' =>
      'fileDeliveryorder;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileDeliveryorder,' .
      'fileInvoice;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileInvoice,' .
      '--linebreak--,' .
      'fileRevocation;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileRevocation,' .
      'fileTerms;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.fileTerms,' .
      '',
      'canNotCollapse' => 1,
    ),
    'isSentToCustomer' => array(
      'showitem' =>
      'pdfDeliveryorderToCustomer;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToCustomer,' .
      'pdfInvoiceToCustomer;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfInvoiceToCustomer,' .
      'pdfRevocationToCustomer;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfRevocationToCustomer,' .
      'pdfTermsToCustomer;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfTermsToCustomer,' .
      '',
      'canNotCollapse' => 1,
    ),
    'isSentToVendor' => array(
      'showitem' =>
      'pdfDeliveryorderToVendor;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfDeliveryorderToVendor,' .
      'pdfInvoiceToVendor;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfInvoiceToVendor,' .
      'pdfRevocationToVendor;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfRevocationToVendor,' .
      'pdfTermsToVendor;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.pdfTermsToVendor,' .
      '',
      'canNotCollapse' => 1,
    ),
    'items' => array(
      'showitem' =>
      'items;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.item,' .
      '',
      'canNotCollapse' => 1,
    ),
    'numbers' => array(
      'showitem' =>
      'numberOrder;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberOrder,' .
      'numberDeliveryorder;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberDeliveryorder,' .
      'numberInvoice;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.numberInvoice,' .
      '',
      'canNotCollapse' => 1,
    ),
    'sum' => array(
      'showitem' =>
      'sumNet;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumNet,' .
      '--linebreak--,' .
      'sumTaxReduced;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumTaxReduced,' .
      '--linebreak--,' .
      'sumTaxNormal;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumTaxNormal,' .
      '--linebreak--,' .
      'sumGross;LLL:EXT:caddy/Resources/Private/Language/locallang_db.xml:tx_caddy_order.sumGross,' .
      '',
      'canNotCollapse' => 1,
    ),
  )
);
