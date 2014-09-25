

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


Ordering
^^^^^^^^

The customer can order the items of course.

The confirmation e-mail – the e-mail to the customer – and the order
e-mail – the e-mail to the vendor – is generated and sent by
Powermail.

Caddy controls Powermail:

- If the caddy is empty, it hides the Powermail form by CSS.

Caddy is listening on powermail parameters:

- GET parameters and POST parameters

- Some other "internal" parameters like
  
  - the Powermail confirmation mode and
  
  - the Powermail version (1.x or 2.x)

Caddy is listening on the Powermail session.

Caddy can detect – depending on the parameters above –, if the
Powermail form is sent.

If the Powermail form is sent:

- Caddy updates all needed session data

- Caddy serves some methods (user functions), which are called by
  Powermail like
  
  - controlling attachments for the e-mails to the customer and the sender
  
  - serving data like the whole caddy and the numbers for the delivery
    order, the invoice and the order itself.

- Caddy cleans up the own session.


((generated))
"""""""""""""

TypoScript
~~~~~~~~~~

- "Setup > tx\_caddy\_pi1.powermail" on page 67 below.

