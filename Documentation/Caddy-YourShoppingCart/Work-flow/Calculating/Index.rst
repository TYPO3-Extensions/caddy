

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


Calculating
^^^^^^^^^^^

Caddy calculates the cost of the order.

Parts of this calculation are among others

- items x quantity x price gross

- delivery costs

- payment costs

- special costs

- sum net

- sum gross

- tax

Caddy stores all values in the session. You can use this data
everywhere at your website i.e. with the mini caddy plugin.

The DRS – Development Reporting System – prompts the session data to
devlog.


((generated))
"""""""""""""

TypoScript
~~~~~~~~~~

- "Setup > tx\_caddy\_pi1.api.marker.sum" on page 60 below.

