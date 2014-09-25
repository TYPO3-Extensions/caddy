

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


GETPOST
^^^^^^^

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
         getpost.uid
   
   Type
         text
   
   Description
         Uid\*: \*Obligate! Parameter name for a unique id (integer) of your
         item. caddy uses this id for SQL requests.
         
         **Example**
         
         - tx\_browser\_pi1\|showUid
   
   Default


.. container:: table-row

   Property
         getpost.quantity
   
   Type
         text
   
   Description
         Quantity\*: \*Obligate! Parameter name for the quantity/amount field
         of your order form (e.g. tx\_trbooks\_qty). The provided parameter
         must be equal to the name in your HTML template.
   
   Default
         qty


.. container:: table-row

   Property
         getpost.title
   
   Type
         text
   
   Description
         Title: Parameter name for the title field in your order form.
   
   Default


.. container:: table-row

   Property
         getpost.price
   
   Type
         text
   
   Description
         Parameter name for the gross price field in your order form.
   
   Default


.. container:: table-row

   Property
         getpost.service\_attribute\_1
   
   Type
         text
   
   Description
         Service Attribute 1: Parameter name for the service attribute field 1
         of your order form (e.g. weight).
   
   Default


.. container:: table-row

   Property
         getpost.service\_attribute\_1
   
   Type
         text
   
   Description
         Service Attribute 2: Parameter name for the service attribute field 2
         of your order form (e.g. volume).
   
   Default


.. container:: table-row

   Property
         getpost.service\_attribute\_1
   
   Type
         text
   
   Description
         Service Attribute 3: Parameter name for the service attribute field 3
         of your order form (e.g. length).
   
   Default


.. container:: table-row

   Property
         getpost.sku
   
   Type
         text
   
   Description
         Parameter name for your SKU (stock keeping unit).
   
   Default


.. ###### END~OF~TABLE ######

