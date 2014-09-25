

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


PDF
^^^

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
         pdfGeneral.fontfamily
   
   Type
         options
   
   Description
         Font family: The font family of your PDF attachments
         
         **Options**
         
         - Courier
         
         - Helvetica
         
         - Symbol
         
         - Times
         
         - ZapfDingbats
   
   Default
         Helvetica


.. container:: table-row

   Property
         pdfGeneral.fontStretching
   
   Type
         integer
   
   Description
         Font Stretching: In percent. 100: no effect. 90: smaller. 110:
         expanded
   
   Default
         90


.. ###### END~OF~TABLE ######


PDF CADDY
"""""""""

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
         pdf.caddy.x
   
   Type
         int+
   
   Description
         Caddy position left: Position of the caddy (items) from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.caddy.y
   
   Type
         int+
   
   Description
         Caddy position top: Position of the caddy (items) from the top margin
         in millimeters
   
   Default
         130


.. container:: table-row

   Property
         pdf.caddy.width
   
   Type
         int+
   
   Description
         Caddy width: Width of the caddy (items) in millimeters
   
   Default
         165


.. container:: table-row

   Property
         pdf.caddy.fontsize
   
   Type
         int+
   
   Description
         Caddy font-size: Font-size of the caddy (items) in points
   
   Default
         10


.. ###### END~OF~TABLE ######


PDF DATE
""""""""

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
         pdf.date.align
   
   Type
         options
   
   Description
         Date alignment: L: left. C: center. R: right. empty: left for LTR or
         right for RTL
         
         **Options**
         
         - *empty*
         
         - L
         
         - C
         
         - R
   
   Default
         L


.. container:: table-row

   Property
         pdf.date.x
   
   Type
         int+
   
   Description
         Date position left: Position of the date from the left margin in
         millimeters
   
   Default
         175


.. container:: table-row

   Property
         pdf.date.y
   
   Type
         int+
   
   Description
         Date position top: Position of the date from the top margin in
         millimeters
   
   Default
         104


.. container:: table-row

   Property
         pdf.date.width
   
   Type
         int+
   
   Description
         Date width: Width of the date in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.date.fontsize
   
   Type
         int+
   
   Description
         Date font-size: Font-size of the date in points
   
   Default
         10


.. container:: table-row

   Property
         pdf.date.strftime
   
   Type
         string
   
   Description
         Date format: Format of the date. Examples: %Y-m-%d, %d. %M %Y
   
   Default
         %Y-%m-%d


.. ###### END~OF~TABLE ######


PDF DELIVERY ORDER
""""""""""""""""""

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
         pdf.deliveryorder.address.x
   
   Type
         int+
   
   Description
         Address position left: Position of the delivery order address from the
         left margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.deliveryorder.address.y
   
   Type
         int+
   
   Description
         Address position top: Position of the delivery order address from the
         top margin in millimeters
   
   Default
         55


.. container:: table-row

   Property
         pdf.deliveryorder.address.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the delivery order address in points
   
   Default
         10


.. container:: table-row

   Property
         pdf.deliveryorder.number.x
   
   Type
         int+
   
   Description
         Address position left: Position of the delivery number from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.deliveryorder.number.y
   
   Type
         int+
   
   Description
         Address position top: Position of the delivery number from the top
         margin in millimeters
   
   Default
         110


.. container:: table-row

   Property
         pdf.deliveryorder.number.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the delivery number in points
   
   Default
         24


.. container:: table-row

   Property
         pdf.deliveryorder.number.prefix
   
   Type
         string
   
   Description
         Number prefix: Prefix for the delivery number. Example : D-
   
   Default


.. container:: table-row

   Property
         pdf.deliveryorder.termOfCredit.x
   
   Type
         int+
   
   Description
         Address position left: Position of the term of credit from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.deliveryorder.termOfCredit.y
   
   Type
         int+
   
   Description
         Address position top: Position of the term of credit from the top
         margin in millimeters
   
   Default
         250


.. container:: table-row

   Property
         pdf.deliveryorder.termOfCredit.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the term of credit in points
   
   Default
         10


.. ###### END~OF~TABLE ######


PDF INVOICE
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
         pdf.invoice.address.x
   
   Type
         int+
   
   Description
         Address position left: Position of the invoice address from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.invoice.address.y
   
   Type
         int+
   
   Description
         Address position top: Position of the invoice address from the top
         margin in millimeters
   
   Default
         55


.. container:: table-row

   Property
         pdf.invoice.address.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the invoice address in points
   
   Default
         10


.. container:: table-row

   Property
         pdf.invoice.number.x
   
   Type
         int+
   
   Description
         Address position left: Position of the invoice number from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.invoice.number.y
   
   Type
         int+
   
   Description
         Address position top: Position of the invoice number from the top
         margin in millimeters
   
   Default
         110


.. container:: table-row

   Property
         pdf.invoice.number.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the invoice number in points
   
   Default
         24


.. container:: table-row

   Property
         pdf.invoice.number.prefix
   
   Type
         string
   
   Description
         Number prefix: Prefix for the invoice number. Example : D-
   
   Default


.. container:: table-row

   Property
         pdf.invoice.termOfCredit.x
   
   Type
         int+
   
   Description
         Address position left: Position of the term of credit from the left
         margin in millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.invoice.termOfCredit.y
   
   Type
         int+
   
   Description
         Address position top: Position of the term of credit from the top
         margin in millimeters
   
   Default
         250


.. container:: table-row

   Property
         pdf.invoice.termOfCredit.fontsize
   
   Type
         int+
   
   Description
         Address font-size: Font-size of the term of credit in points
   
   Default
         10


.. ###### END~OF~TABLE ######


PDF ORDER
"""""""""

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
         pdf.order.number.prefix
   
   Type
         string
   
   Description
         Number prefix: Prefix for the order number. Example : O-
   
   Default


.. ###### END~OF~TABLE ######


PDF REVOCATION
""""""""""""""

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
         pdf.revocation.align
   
   Type
         options
   
   Description
         Revocation alignment: L: left. C: center. R: right. empty: left for
         LTR or right for RTL
         
         **Options**
         
         - *empty*
         
         - L
         
         - C
         
         - R
   
   Default


.. container:: table-row

   Property
         pdf.revocation.x
   
   Type
         int+
   
   Description
         Revocation position left: Position of the date from the left margin in
         millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.revocation.y
   
   Type
         int+
   
   Description
         Revocation position top: Position of the date from the top margin in
         millimeters
   
   Default
         100


.. container:: table-row

   Property
         pdf.revocation.width
   
   Type
         int+
   
   Description
         Revocation width: Width of the date in millimeters
   
   Default
         165


.. container:: table-row

   Property
         pdf.revocation.fontsize
   
   Type
         int+
   
   Description
         Revocation font-size: Font-size of the date in points
   
   Default
         10


.. ###### END~OF~TABLE ######


PDF TERMS
"""""""""

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
         pdf.terms.align
   
   Type
         options
   
   Description
         Terms alignment: L: left. C: center. R: right. empty: left for LTR or
         right for RTL
         
         **Options**
         
         - *empty*
         
         - L
         
         - C
         
         - R
   
   Default


.. container:: table-row

   Property
         pdf.terms.x
   
   Type
         int+
   
   Description
         Terms position left: Position of the date from the left margin in
         millimeters
   
   Default
         25


.. container:: table-row

   Property
         pdf.terms.y
   
   Type
         int+
   
   Description
         Terms position top: Position of the date from the top margin in
         millimeters
   
   Default
         100


.. container:: table-row

   Property
         pdf.terms.width
   
   Type
         int+
   
   Description
         Terms width: Width of the date in millimeters
   
   Default
         165


.. container:: table-row

   Property
         pdf.terms.fontsize
   
   Type
         int+
   
   Description
         Terms font-size: Font-size of the date in points
   
   Default
         10


.. ###### END~OF~TABLE ######


PDF TEXTCOLOR
"""""""""""""

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
         pdf.textColour.address
   
   Type
         colour
   
   Description
         Address: font colour as CSS name or HTML notation like #FFF or #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.caddy
   
   Type
         colour
   
   Description
         Caddy: font colour as CSS name or HTML notation like #FFF or #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.date
   
   Type
         colour
   
   Description
         Date: font colour as CSS name or HTML notation like #FFF or #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.numberDeliveryorder
   
   Type
         colour
   
   Description
         Number delivery order: font colour as CSS name or HTML notation like
         #FFF or #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.numberInvoice
   
   Type
         colour
   
   Description
         Number invoice: font colour as CSS name or HTML notation like #FFF or
         #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.numberLine
   
   Type
         colour
   
   Description
         Number line: font colour as CSS name or HTML notation like #FFF or
         #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.numberOrder
   
   Type
         colour
   
   Description
         Number order: font colour as CSS name or HTML notation like #FFF or
         #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.revocation
   
   Type
         colour
   
   Description
         Revocation: font colour as CSS name or HTML notation like #FFF or
         #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.termOfCredit
   
   Type
         colour
   
   Description
         Term of credit: font colour as CSS name or HTML notation like #FFF or
         #123456
   
   Default
         navy


.. container:: table-row

   Property
         pdf.textColour.terms
   
   Type
         colour
   
   Description
         Terms: font colour as CSS name or HTML notation like #FFF or #123456
   
   Default
         navy


.. ###### END~OF~TABLE ######

