

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


Version 4
^^^^^^^^^


E-Payment
"""""""""

[4.0.5]

Interface for e-payment. See "E-Payment" on page18above.


Options are configurable
""""""""""""""""""""""""

[4.0.5]

If you like to change the default options for payment, shipping and
specials, you don't need to configure TypoScript directly any longer.

See "Options" on page37above.


Pid for Caddy
"""""""""""""

[4.0.3]

You can use the Caddy plugin on different pages with the same content.
This is useful for development among others: You can use Caddy with
different configurations on page A and page B. Both plugins must have
the same page id at

plugin.caddy {

pages.caddy =

}

See "PAGES" on page38above.


Responsive
""""""""""

[4.0.5]

Caddy has a responsive design. See "Responsive Design" on page16above.


Inventory Control
"""""""""""""""""

[4.0.7]

Caddy can control your inventory. If your items have the properties
"quantity in stock" and "stock management".

If inventory control is enabled:

- Caddy will decrease the quantity of your item after an order.

- The quantity in stock is the maximum order quantity of an item.

- Items can't ordered, if they aren't in stock.

[CADDY – INVENTORY CONTROL]

See XXX


TypoScript
""""""""""

[4.0.5]


###TABLE.FIELD### replaced by fields
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All so called table.field-markers are removed from the static
template. They are no longer needed.

Former TypoScript

my\_table {

my\_field = TEXT

my\_field {

value = ###MY\_TABLE.MY\_FIELD###

}

}

Current TypoScript

my\_table {

my\_field = TEXT

my\_field {

field = my\_table.my\_field

}

}


Structure cleaned up
~~~~~~~~~~~~~~~~~~~~

The TypoScript structure is cleaned up:

plugin.tx\_caddy\_pi1

- conditions -> moved to: api.conditions

- getpost -> moved to: api.getpost

- marker -> moved to: api.marker

- options -> moved to: api.options

- symbols -> moved to: api.symbols

- tax -> moved to: api.tax

See "tx\_caddy\_pi1.api" on page57above.

