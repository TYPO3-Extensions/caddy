#
# INDEX
#
# tx_caddy_item
# tx_caddy_order
# tx_caddy_order_mm_tx_caddy_item



#
# Table structure for table 'tx_caddy_item'
#
CREATE TABLE tx_caddy_item (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  deleted tinyint(4) DEFAULT '0' NOT NULL,
  title tinytext,
  
  PRIMARY KEY (uid),
  KEY parent (pid)
);



#
# Table structure for table 'tx_caddy_order'
#
CREATE TABLE tx_caddy_order (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  deleted tinyint(4) DEFAULT '0' NOT NULL,

  customerEmail text,

  fileDeliveryorder text,
  fileInvoice text,
  fileRevocation text,
  fileTerms text,

  items tinytext,

  numberDeliveryorder int(11) DEFAULT '0' NOT NULL,
  numberInvoice int(11) DEFAULT '0' NOT NULL,
  numberOrder int(11) DEFAULT '0' NOT NULL,

  pdfDeliveryorderToCustomer tinyint(4) DEFAULT '0' NOT NULL,
  pdfDeliveryorderToVendor tinyint(4) DEFAULT '0' NOT NULL,
  pdfInvoiceToCustomer tinyint(4) DEFAULT '0' NOT NULL,
  pdfInvoiceToVendor tinyint(4) DEFAULT '0' NOT NULL,
  pdfRevocationToCustomer tinyint(4) DEFAULT '0' NOT NULL,
  pdfRevocationToVendor tinyint(4) DEFAULT '0' NOT NULL,
  pdfTermsToCustomer tinyint(4) DEFAULT '0' NOT NULL,
  pdfTermsToVendor tinyint(4) DEFAULT '0' NOT NULL,

  quantity int(11) DEFAULT '0' NOT NULL,

  sumNet double(11,2) DEFAULT '0.00' NOT NULL,
  sumGross double(11,2) DEFAULT '0.00' NOT NULL,
  sumTaxNormal double(11,2) DEFAULT '0.00' NOT NULL,
  sumTaxReduced double(11,2) DEFAULT '0.00' NOT NULL,

  PRIMARY KEY (uid),
  KEY parent (pid)
);



#
# Table structure for table 'tx_caddy_order_mm_tx_caddy_item'
# 
CREATE TABLE tx_caddy_order_mm_tx_caddy_item (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);