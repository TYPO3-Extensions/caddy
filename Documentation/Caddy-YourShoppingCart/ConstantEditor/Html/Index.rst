

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


HTML
^^^^


HTML COLORS
"""""""""""

There isn't any need to use the Constant Editor, if you like to use
these colours:

- blue, green, orange, red

Please iclude the static template:

- +Caddy CSS + *Colour* (caddy)

where colour is one of the colours from above.

See

- "Static Templates > CSS" on page 31 above.

- "Colours" on page 43 below.

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         html.colour.border
   
   Type
         colour
   
   Description
         Border colour: border colour as CSS name or HTML notation like #FFF or
         #123456. It is used in the HTML table, which is placed in e-mails and
         pdf attachments.
   
   Default
         #207CCA


.. container:: table-row

   Property
         html.colour.button.text
   
   Type
         colour
   
   Description
         Button text colour
   
   Default
         #FFF


.. container:: table-row

   Property
         html.colour.button.gradient1
   
   Type
         colour
   
   Description
         Button gradient colour 1
   
   Default
         #1E5799


.. container:: table-row

   Property
         html.colour.button.gradient2
   
   Type
         colour
   
   Description
         Button gradient colour 2
   
   Default
         #2989D8


.. container:: table-row

   Property
         html.colour.button.gradient3
   
   Type
         colour
   
   Description
         Button gradient colour 3
   
   Default
         #207CCA


.. container:: table-row

   Property
         html.colour.button.gradient4
   
   Type
         colour
   
   Description
         Button gradient colour 4
   
   Default
         #7DB9E8


.. container:: table-row

   Property
         html.colour.icon.caddy
   
   Type
         string
   
   Description
         Icon caddy.
         
         **Default**
         
         - EXT:caddy/files/img/caddy\_080\_08.png
         
         **Example**
         
         - "Corporate Design > Icons" on page 44 below
   
   Default
         see description


.. container:: table-row

   Property
         html.colour.icon.delete
   
   Type
         string
   
   Description
         Icon delete.
         
         **Default**
         
         - EXT:caddy/files/img/delete\_080\_08.png
         
         **Example**
         
         - "Corporate Design > Icons" on page 44 below
   
   Default
         see description


.. container:: table-row

   Property
         html.colour.powermail.text
   
   Type
         colour
   
   Description
         Powermail text
   
   Default
         #207CCA


.. container:: table-row

   Property
         html.colour.table.head.background
   
   Type
         colour
   
   Description
         Table header background
   
   Default
         #207CCA


.. container:: table-row

   Property
         html.colour.table.head.text
   
   Type
         colour
   
   Description
         Table header text
   
   Default
         #FFF


.. ###### END~OF~TABLE ######


HTML MARKER
"""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         html.marker.caddy
   
   Type
         string
   
   Description
         Subpart Marker: The subpart marker for the caddy in your Caddy HTML
         template. Usually ###CADDY###.
   
   Default
         CADDY


.. ###### END~OF~TABLE ######


HTML WIDTH
""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         html.width.table.td.qty
   
   Type
         text
   
   Description
         Width column Quantity: The width of the caddy column quantity in HTML
         tables for CSS. Units can be px, em or % among others.
   
   Default
         10%


.. container:: table-row

   Property
         html.width.table.td.sku
   
   Type
         text
   
   Description
         Width column SKU: The width of the caddy column stock keep unit in
         HTML tables for CSS. Units can be px, em or % among others.
   
   Default
         15%


.. container:: table-row

   Property
         html.width.table.td.item
   
   Type
         text
   
   Description
         Width column item: The width of the caddy column item in HTML tables
         for CSS. Units can be px, em or % among others.
   
   Default
         35%


.. container:: table-row

   Property
         html.width.table.td.tax
   
   Type
         text
   
   Description
         Width column tax: The width of the caddy column tax in HTML tables for
         CSS. Units can be px, em or % among others.
   
   Default
         10%


.. container:: table-row

   Property
         html.width.table.td.net
   
   Type
         text
   
   Description
         Width column net: The width of the caddy column net in HTML tables for
         CSS. Units can be px, em or % among others.
   
   Default
         15%


.. container:: table-row

   Property
         html.width.table.td.sum
   
   Type
         text
   
   Description
         Width column sum: The width of the caddy column sum in HTML tables for
         CSS. Units can be px, em or % among others.
   
   Default
         15%


.. container:: table-row

   Property
         html.width.table.td.skuitemtax
   
   Type
         text
   
   Description
         Width 3 columns: The total width of the caddy columns sku, item and
         tax in HTML tables for CSS. Units can be px, em or % among others.
   
   Default
         60%


.. container:: table-row

   Property
         html.width.table.td.skuitemtaxnet
   
   Type
         text
   
   Description
         Width 4 columns: The total width of the caddy columns sku, item, tax
         and net in HTML tables for CSS. Units can be px, em or % among others.
   
   Default
         75%


.. ###### END~OF~TABLE ######

