

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


Database is read-only
^^^^^^^^^^^^^^^^^^^^^

By default this database is read-only:

- You can't change any value in a record.

- You can't delete any record.

The database stores among others:

- All attachments, which are sent to
  
  - the customer
  
  - the vendor

- The e-mail address of the customer.

Both – read-only and the disabled delete property – is meant with
audit proof `:sup:`1`  <#sdfootnote16sym>`_ .

Be aware:

If you need the logged data for ever, you have to take care off a
backup!

