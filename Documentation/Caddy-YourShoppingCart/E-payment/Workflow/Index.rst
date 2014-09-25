

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


Workflow
^^^^^^^^

Any knowledge about the e-payment workflow is needed. But if you are
interested, read the section below.


Paymill
"""""""

Caddy handles e-payment in the two steps:

- Evaluation

- Transaction

In detail:

#. data for e-payment are evaluated by JavaScript `:sup:`1`
   <#sdfootnote8sym>`_
   
   #. data will send to Paymill
   
   #. if data are proper, Paymill sends back a token
   
   #. if data aren't proper, Paymill tries to send a qualified error prompt
      or Caddy tries to generate a qualified error prompt.

#. if data for e-payment are proper, the customer can send the order to
   your server
   
   #. the order doesn't contain any e-payment account data but the Paymill
      token `:sup:`2`  <#sdfootnote9sym>`_
   
   #. your server sends the token and some other data like the amount and
      the invoice number to the server of Paymill for transaction
   
   #. if the transaction is successful, caddy
      
      #. sends the confirmation e-mail to the shop owner and a copy to the
         customer
      
      #. stores some order data in the database
   
   #. if the transaction fails, caddy
      
      #. outputs a HTML error page with a qualified prompt
      
      #. the customer can go back to the order

#. if data for e-payment aren't proper, the customer can't send the order
   to your server

