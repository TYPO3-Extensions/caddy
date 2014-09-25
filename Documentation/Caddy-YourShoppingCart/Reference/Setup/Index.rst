

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


Setup
^^^^^

It is recommended to configure Caddy by the Constant Editor and not by
the TypoScript Object Browser directly.

All properties have the prefix plugin.tx\_caddy\_pi1.

Example:db.table is plugin.tx\_caddy\_pi1.db.table


tx\_caddy\_pi1 – Overview
"""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         plugin.tx\_caddy\_pi1
   
   Data type
         array
   
   Description
         Configuration for the Caddy main plugin
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api ...
         
         db ...
         
         debug ...
         
         pdf ...
         
         pid ...
         
         pluginCheck ...
         
         templates ...
         
         \_HTMLMARKER ...
         
         \_CSS\_DEFAULT\_STYLE ...
         
         powermail ...
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api
""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api
   
   Data type
         array
   
   Description
         Configuration for the Caddy API
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         conditions ...
         
         e-payment ...
         
         getpost ...
         
         marker ...
         
         options ...
         
         symbols ...
         
         tax ...
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.conditions
"""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.conditions
   
   Data type
         array
   
   Description
         Conditions for calculating.
         
         Array contains one condition only: the minimum limit for the gross sum
         over all items(without any other cost like for delivery or service)
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         conditions {
         
         ... <-please inspect it with the TypoScript Object Browser
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.e-payment
""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.e-payment
   
   Data type
         array
   
   Description
         E-Payment Interface
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         e-payment{
         
         ... <-please inspect it with the TypoScript Object Browser
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.getpost
""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.getpost
   
   Data type
         array
   
   Description
         Configuration for the Get-/Post-parameters, which are sent by GET or
         POST.
         
         Each item has the COA property.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > GETPOST" on page 35 above.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         getpost {
         
         gross ...
         
         min ...
         
         max ...
         
         qty ...
         
         sku ...
         
         tax ...
         
         title ...
         
         uid ...
         
         volume ...
         
         weight ...
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.marker.items
"""""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.marker.items
   
   Data type
         array
   
   Description
         Configuration of marker, which are used in HTML templates for an item.
         
         Example for an HTML marker:
         
         - ###SUMGROSS###
         
         Please inspect the items below with the TypoScript Object Browser.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         marker {
         
         items {
         
         delete = IMAGE // delete icon
         
         gross = COA // gross costs of the item
         
         min = TEXT // minimum quantity for ordering
         
         max = TEXT // maximum quantity for ordering
         
         net = COA // net costs of the item
         
         uid = TEXT // uid of the item
         
         qty = TEXT // quantity of the item
         
         service\_attribute\_1 = TEXT // service attribute 1
         
         service\_attribute\_2 = TEXT // service attribute 2
         
         service\_attribute\_3 = TEXT // service attribute 3
         
         sku = TEXT // stock keeping unit
         
         sumgross = COA // gross costs sum ( gross costs \* qty )
         
         sumnet = COA // net costs sum ( net costs \* qty )
         
         sumtax = COA // tax costs sum ( tax costs \* qty )
         
         tax = TEXT // tax value of the item (0 , 1, 2)
         
         taxrate = COA // taxrate of the item
         
         title = TEXT // label of the item
         
         }
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.marker.sum
"""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.marker.sum
   
   Data type
         array
   
   Description
         Configuration of marker, which are used in HTML templates for a sum.
         
         Example for an HTML marker:
         
         - ###sumsumtaxnormal###
         
         Please inspect the items below with the TypoScript Object Browser.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         api {
         
         marker {
         
         sum {
         
         labels {
         
         optionspaymentlabel = TEXT
         
         optionsshippinglabel = TEXT
         
         optionsspecialslabels = TEXT
         
         taxrate\_reduced\_string = COA
         
         taxrate\_normal\_string = COA
         
         }
         
         rates {
         
         optionspaymentsumrate = COA
         
         optionsshippingsumrate = COA
         
         optionsspecialssumrate = COA
         
         }
         
         values {
         
         optionspaymentsumgross = COA
         
         optionspaymentsumnet = COA
         
         optionspaymentsumtaxnormal = COA
         
         optionspaymentsumtaxreduced = COA
         
         optionsshippingsumgross = COA
         
         optionsshippingsumnet = COA
         
         optionsshippingsumtaxnormal = COA
         
         optionsshippingsumtaxreduced = COA
         
         optionsspecialssumgross = COA
         
         optionsspecialssumnet = COA
         
         optionsspecialssumtaxnormal = COA
         
         optionsspecialssumtaxreduced = COA
         
         sumitemsgross = COA
         
         sumitemsnet = COA
         
         sumitemstaxnormal = COA
         
         sumitemstaxreduced = COA
         
         sumoptionsgross = COA
         
         sumoptionsnet = COA
         
         sumoptionstaxnormal = COA
         
         sumoptionstaxreduced = COA
         
         sumsumgross = COA
         
         sumsumnet = COA
         
         sumsumtaxnormal = COA
         
         sumsumtaxreduced = COA
         
         sumsumtaxsum = COA
         
         }
         
         }
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.options
""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.options
   
   Data type
         array
   
   Description
         Configuration of the options
         
         - payment,
         
         - shipping,
         
         - special
         
         You are controlling the calculating and the output in HTML, e-mail and
         PDF.
         
         Please inspect the items below with the TypoScript Object Browser.
         
         **Constant Editor:**
         
         See "Options" on page 37 above.
         
         **Example:**
         
         Sorry, there isn't any example.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         caddy {
         
         options = // payment, shipping, special
         
         options {
         
         payment = // preset, options
         
         payment {
         
         preset = 1 // default method
         
         options = // cash in advance, invoice, cash on delivery
         
         options {
         
         ...
         
         }
         
         }
         
         shipping = // preset, options
         
         shipping {
         
         preset = 1 // default method
         
         options = // maildefault, mailexpress
         
         options {
         
         ...
         
         }
         
         }
         
         specials = // preset, options
         
         specials {
         
         devider = TEXT // devider for list of options
         
         // in e-mails und PDF attachments
         
         options = // neutralpackage, addfeeforislands
         
         options {
         
         ...
         
         }
         
         }
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.symbols
""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.symbols
   
   Data type
         array
   
   Description
         Configuration of symbols.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > SYMBOLS AND SERVICE ATTRIBUTES" on page 41 above.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         api {
         
         symbols {
         
         decimal = 2
         
         dec\_point = ,
         
         thousands\_sep = .
         
         currencySymbol = &euro;
         
         currencySymbolBeforePrice = 0
         
         quantitySymbol = St.
         
         service\_attribute\_1\_symbol =
         
         service\_attribute\_2\_symbol =
         
         service\_attribute\_3\_symbol =
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.api.tax
""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         api.tax
   
   Data type
         array
   
   Description
         Configuration for tax calculating.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > TAX" on page 41 above.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         api {
         
         tax {
         
         reduced = 7
         
         reducedCalc = 0.07
         
         normal = 19
         
         normalCalc = 0.19
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.db
"""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         db
   
   Data type
         array
   
   Description
         Interface for your database.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > DATABASE" on page 34 above.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         db {
         
         table = tx\_quickshop\_products
         
         title = title
         
         gross = price
         
         tax = tax
         
         sku = sku
         
         min = quantity\_min
         
         max = quantity\_max
         
         service\_attribute\_1 =
         
         service\_attribute\_2 =
         
         service\_attribute\_3 =
         
         sql {
         
         ... -> See tx\_caddy\_pi1.db.sql below
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.db.sql
"""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         db.sql
   
   Data type
         array
   
   Description
         Extend the database interface for special needs – usually variants.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         db {
         
         sql {
         
         ... -> See Error: Reference source not found on page Error: Reference
         source not found Error: Reference source not found
         
         }
         
         }
         
         settings {
         
         ... -> See Error: Reference source not found on page Error: Reference
         source not found Error: Reference source not found
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.debug
""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         debug
   
   Data type
         boolean
   
   Description
         Prompts a debug report with informations about TypoScript to the
         frontend.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > DEBUG" on page 34 above.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         debug = 0
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.pdf
""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         pdf
   
   Data type
         array
   
   Description
         Configuration for the PDF attachments.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > PDF" on page 39 above.
         
         Please inspect the items below with the TypoScript Object Browser.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         pdf {
         
         deliveryorder {
         
         filename = COA // label - delivery order number - date . pdf
         
         content {
         
         address = // deliveryorder, invoice
         
         caddy = // body
         
         date = // body
         
         numbers = // deliveryorder, invoice and order
         
         }
         
         }
         
         invoice {
         
         filename = COA // label - invoicenumber - date . pdf
         
         content {
         
         address = // deliveryorder, invoice
         
         caddy = // body
         
         date = // body
         
         numbers = // deliveryorder, invoice and order
         
         termOfCredit = // body
         
         }
         
         }
         
         revocation {
         
         filename = COA // label - invoicenumber - date . pdf
         
         content {
         
         address = // invoice
         
         date = // body
         
         additionaltextblocks = // revocation
         
         }
         
         }
         
         terms {
         
         filename = COA // label - invoicenumber - date . pdf
         
         content {
         
         address = // invoice
         
         date = // body
         
         additionaltextblocks = // terms
         
         }
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.pid
""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         pid
   
   Data type
         integer
   
   Description
         Id of the page with the CADDY plugin.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > PAGES" on page 38 above.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         pid = 8154
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.pluginCheck
""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         pluginCheck
   
   Data type
         string
   
   Description
         For self evaluation only.  **Never touch it!**
         
         **Code:**
         
         plugin.tx\_caddy {
         
         pluginCheck = dummy
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.templates
""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         templates
   
   Data type
         array
   
   Description
         Configuration of templating.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > TEMPLATES" on page 42 above.
         
         **Code:**
         
         plugin.tx\_caddy {
         
         templates {
         
         e-mail {
         
         file = EXT:caddy/files/templates/caddy.html
         
         marker {
         
         // all, item
         
         }
         
         table {
         
         // border, cellpadding, cellspacing
         
         }
         
         }
         
         html {
         
         caddy {
         
         // pi1: file, marker, table
         
         }
         
         caddymini {
         
         // pi3: file, marker
         
         }
         
         caddysum {
         
         // pi2: file, marker
         
         }
         
         }
         
         pdf {
         
         deliveryorder {
         
         // file, marker, table
         
         }
         
         invoice {
         
         // file, marker, table
         
         }
         
         revocation {
         
         // file, marker, table
         
         }
         
         terms {
         
         // file, marker, table
         
         }
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.\_HTMLMARKER
"""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         \_HTMLMARKER
   
   Data type
         array
   
   Description
         Feel free to add any marker to the templates for HTML, the e-mail and
         the PDF attachments.
         
         The property from below will replace ###\_HTMLMARKER\_LINTOSHOP###.
         
         Please inspect the code from below with the TypoScript Object Browser.
         
         **Example:**
         
         plugin.tx\_caddy {
         
         \_HTMLMARKER {
         
         linktoshop = COA
         
         linktoshop {
         
         // Label
         
         10 = TEXT
         
         10 {
         
         ...
         
         }
         
         // Icon
         
         20 = IMAGE
         
         20 {
         
         ...
         
         }
         
         wrap = <div class="linktoshop caddylinktoshop">\|</div>
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.\_CSS\_DEFAULT\_STYLE
""""""""""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         \_CSS\_DEFAULT\_STYLE
   
   Data type
         string
   
   Description
         Inline CSS. An inline CSS can configured while runtime.
         
         This property is configured only, if a CSS static template is
         included. See
         
         - "Static Templates > CSS" on page 31 above.
         
         Please inspect the code from below with the TypoScript Object Browser.
         
         **Code:**
         
         plugin.tx\_caddy\_pi1 {
         
         \_CSS\_DEFAULT\_STYLE (
         
         form.caddy {
         
         text-align: right;
         
         }
         
         form.caddy button.submit img {
         
         position:relative;
         
         top:.1em;
         
         }
         
         ...
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi1.powermail
""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         powermail
   
   Data type
         array
   
   Description
         Configuration for controlling powermail.
         
         This property is configured only, if a powermail static template is
         included. See
         
         - "Static Templates > Powermail (obligated)" on page 31 above.
         
         The configuration depends on the powermail version 1.x and 2.x
         
         The example below corresponds with the fluid marker in the powermail
         form.
         
         **Code (powermail 2.x):**
         
         plugin.tx\_caddy {
         
         powermail {
         
         caddy = USER
         
         caddy {
         
         // The caddy content
         
         }
         
         caddydeliveryordernumber = USER
         
         caddydeliveryordernumber {
         
         // Delivery order number
         
         }
         
         caddyinvoicenumber = USER
         
         caddyinvoicenumber {
         
         // Invoice number
         
         }
         
         caddyordernumber = USER
         
         caddyordernumber {
         
         // Order number
         
         }
         
         clearcaddysession = USER
         
         clearcaddysession {
         
         // Clear the caddy session
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi2
""""""""""""""


Frontend and Plugin
~~~~~~~~~~~~~~~~~~~

- See "Plugins>Sum Gross" on page23above.


TypoScript
~~~~~~~~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         plugin.tx\_caddy\_pi2
   
   Data type
         array
   
   Description
         Configuration for the Caddy Sum Gross plugin
         
         **Code:**
         
         plugin.tx\_caddy\_pi2 {
         
         // item, marker
         
         content =
         
         content {
         
         // label, sumsumgross
         
         sum = COA
         
         sum {
         
         // label
         
         20 = TEXT
         
         20 {
         
         data = LLL:EXT:caddy/pi2/locallang.xml:phrasesum
         
         noTrimWrap = \|<span class="label sumlabel">\|</span> \|
         
         }
         
         // sumsumgross
         
         30 = COA
         
         30 {
         
         // value
         
         10 = USER
         
         10.userFunc = tx\_caddy\_userfunc->numberformat
         
         10.userFunc {
         
         number = TEXT
         
         number.field = sumsumgross
         
         decimal = 2
         
         dec\_point = {$plugin.caddy.main.dec\_point}
         
         thousands\_sep = {$plugin.caddy.main.thousands\_sep}
         
         drs = {$plugin.caddy.debug.userfunc}
         
         }
         
         // currency
         
         20 = TEXT
         
         20 {
         
         value = {$plugin.caddy.main.currencySymbol}
         
         noTrimWrap = \| \|\|
         
         }
         
         wrap = <span class="gross sumgross">\|</span>
         
         }
         
         }
         
         }
         
         // page id of the caddy
         
         pid = {$plugin.caddy.pages.caddy}
         
         // e-mail, html, pdf
         
         templates =
         
         templates < plugin.tx\_caddy\_pi1.templates
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3 – Overview
"""""""""""""""""""""""""


Frontent and Plugin
~~~~~~~~~~~~~~~~~~~

- See "Plugins>Mini Caddy" on page22above.


TypoScript
~~~~~~~~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         plugin.tx\_caddy\_pi3
   
   Data type
         array
   
   Description
         Configuration for the Caddy Mini plugin
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // item, marker
         
         content =
         
         content {
         
         // quantity, label, gross
         
         item = COA
         
         item {
         
         ...
         
         }
         
         // quantity, label, gross
         
         sum = COA
         
         sum {
         
         ...
         
         }
         
         }
         
         // page id of the caddy
         
         pid = {$plugin.caddy.pages.caddy}
         
         // e-mail, html, pdf
         
         templates =
         
         templates < plugin.tx\_caddy\_pi1.templates
         
         // linktocaddy, linktoshop
         
         \_HTMLMARKER =
         
         \_HTMLMARKER {
         
         ...
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.content.item
"""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         content.item
   
   Data type
         array
   
   Description
         Configuration for the items of the Caddy Mini plugin
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // item, marker
         
         content =
         
         content {
         
         // quantity, label, gross
         
         item = COA
         
         item {
         
         // quantity
         
         10 = TEXT
         
         10 {
         
         field = quantity
         
         wrap = <div class="quantity itemquantity">\|</div>
         
         }
         
         // label
         
         20 = TEXT
         
         20 {
         
         field = label
         
         wrap = <div class="label itemlabel">\|</div>
         
         }
         
         // gross
         
         30 = COA
         
         30 {
         
         // value
         
         10 = USER
         
         10.userFunc = tx\_caddy\_userfunc->numberformat
         
         10.userFunc {
         
         number = TEXT
         
         number.field = gross
         
         decimal = 2
         
         dec\_point = {$plugin.caddy.main.dec\_point}
         
         thousands\_sep = {$plugin.caddy.main.thousands\_sep}
         
         drs = {$plugin.caddy.debug.userfunc}
         
         }
         
         // currency
         
         20 = TEXT
         
         20 {
         
         value = {$plugin.caddy.main.currencySymbol}
         
         noTrimWrap = \| \|\|
         
         }
         
         wrap = <div class="gross itemgross">\|</div>
         
         }
         
         wrap (
         
         <div class="item minicaddyitem">\|</div>
         
         <div class="caddy\_cleaner"></div>
         
         )
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.content.sum
""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         content.sum
   
   Data type
         array
   
   Description
         Configuration for the Sum of the Caddy Mini plugin
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // item, marker
         
         content =
         
         content {
         
         // quantity, label, gross
         
         sum = COA
         
         sum {
         
         // quantity
         
         10 = TEXT
         
         10 {
         
         field = quantity
         
         wrap = <div class="quantity sumquantity">\|</div>
         
         }
         
         // label
         
         20 = COA
         
         20 {
         
         // in case of one item
         
         10 = TEXT
         
         10 {
         
         if {
         
         value = 2
         
         isLessThan {
         
         field = quantity
         
         }
         
         }
         
         data = LLL:EXT:caddy/pi3/locallang.xml:item
         
         }
         
         // in case of more than one item
         
         20 = TEXT
         
         20 {
         
         if {
         
         value = 2
         
         isLessThan {
         
         field = quantity
         
         }
         
         negate = 1
         
         }
         
         data = LLL:EXT:caddy/pi3/locallang.xml:items
         
         }
         
         wrap = <div class="label sumlabel">\|</div>
         
         }
         
         // gross
         
         30 = COA
         
         30 {
         
         10 = USER
         
         10.userFunc = tx\_caddy\_userfunc->numberformat
         
         10.userFunc {
         
         number = TEXT
         
         number.field = gross
         
         decimal = 2
         
         dec\_point = {$plugin.caddy.main.dec\_point}
         
         thousands\_sep = {$plugin.caddy.main.thousands\_sep}
         
         drs = {$plugin.caddy.debug.userfunc}
         
         }
         
         20 = TEXT
         
         20 {
         
         value = {$plugin.caddy.main.currencySymbol}
         
         noTrimWrap = \| \|\|
         
         }
         
         wrap (
         
         <div class="gross sumgross">\|</div>
         
         <div class="caddy\_cleaner"></div>
         
         )
         
         }
         
         wrap = <div class="sum minicaddysum">\|</div>
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.pid
""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         pid
   
   Data type
         integer
   
   Description
         Id of the page with the Caddy plugin.
         
         Please configure it with the Constant Editor. See
         
         - "Constant Editor > PAGES" on page 38 above.
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // page id of the caddy
         
         pid = {$plugin.caddy.pages.caddy}
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.templates
""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         templates
   
   Data type
         array
   
   Description
         Configuration of the templates is taken from the Caddy plugin.
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // e-mail, html, pdf
         
         templates =
         
         templates < plugin.tx\_caddy\_pi1.templates
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.\_HTMLMARKER.linktocaddy
"""""""""""""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         \_HTMLMARKER.linktocaddy
   
   Data type
         array
   
   Description
         Link to the page with the Caddy plugin. Link contains an image – the
         Caddy icon.
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         // linktocaddy, linktoshop
         
         \_HTMLMARKER =
         
         \_HTMLMARKER {
         
         // label, icon. Replaces \_HTMLMARKER\_LINTOCADDY
         
         linktocaddy = COA
         
         linktocaddy {
         
         // Label
         
         10 = TEXT
         
         10 {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:linktocaddy
         
         typolink {
         
         parameter = {$plugin.caddy.pages.caddy}
         
         title {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:linktocaddy
         
         }
         
         }
         
         noTrimWrap = \|\| \|
         
         }
         
         // Icon
         
         20 = IMAGE
         
         20 {
         
         file = {$plugin.caddy.html.color.icon.caddy}
         
         altText {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:caddy
         
         }
         
         titleText {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:caddy
         
         }
         
         params = class="linktocaddy"
         
         imageLinkWrap = 1
         
         imageLinkWrap {
         
         enable = 1
         
         typolink {
         
         parameter = {$plugin.caddy.pages.caddy}
         
         }
         
         }
         
         }
         
         wrap = <div class="linktocaddy minicaddylinktocaddy">\|</div>
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######


tx\_caddy\_pi3.\_HTMLMARKER.linktoshop
""""""""""""""""""""""""""""""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:
   
   Data type
         Data type:
   
   Description
         Description:


.. container:: table-row

   Property
         \_HTMLMARKER.linktoshop
   
   Data type
         array
   
   Description
         Link to the page with the Shop plugin. Link contains an image – the
         Caddy icon.
         
         **Code:**
         
         plugin.tx\_caddy\_pi3 {
         
         \_HTMLMARKER {
         
         // label, icon. Replaces \_HTMLMARKER\_LINTOSHOP
         
         linktoshop = COA
         
         linktoshop {
         
         // Label
         
         10 = TEXT
         
         10 {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:linktoshop
         
         typolink {
         
         parameter = {$plugin.caddy.pages.shop}
         
         title {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:linktoshop
         
         }
         
         }
         
         noTrimWrap = \|\| \|
         
         }
         
         // Icon
         
         20 = IMAGE
         
         20 {
         
         file = {$plugin.caddy.html.color.icon.caddy}
         
         altText {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:shop
         
         }
         
         titleText {
         
         data = LLL:EXT:caddy/pi3/locallang.xml:shop
         
         }
         
         params = class="linktoshop"
         
         imageLinkWrap = 1
         
         imageLinkWrap {
         
         enable = 1
         
         typolink {
         
         parameter = {$plugin.caddy.pages.shop}
         
         }
         
         }
         
         }
         
         wrap = <div class="linktoshop minicaddylinktoshop">\|</div>
         
         }
         
         }
         
         }


.. ###### END~OF~TABLE ######

