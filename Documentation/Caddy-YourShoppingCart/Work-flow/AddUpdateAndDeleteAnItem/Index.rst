

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


Add, update and delete an item
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


Add an item
"""""""""""

A customer can add an item into the caddy.

- You have to create the form – or the link – with the GET parameters or
  POST parameters needed by Caddy.

- Caddy needs the uid of the item and the quantity selected by the
  customer.

- Caddy checks the price and the tax of the item in the table of the
  item.

- You have to configure the table label and some field labels by the
  TypoScript Constant Editor.

- Caddy stores all needed values in a session.

- You can use this data everywhere at your website i.e. with the mini
  caddy plugin.

- The DRS – Development Reporting System – prompts the session data to
  devlog.


Update an item
""""""""""""""

The customer can update the quantity of an item.

- Caddy updates the session data.


Delete an item
""""""""""""""

The customer can delete an item from the caddy.

- Caddy updates the session data.

