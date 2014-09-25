.. include:: Images.txt

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


DRS – Development Reporting System
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


Inspect work-flow, session data and errors
""""""""""""""""""""""""""""""""""""""""""

The DRS – Development Reporting System – enables you

- to follow the work-flow of Caddy,

- to inspect alternate configuration possibilities,

- to inspect session data and their corresponding cObject fields,

- to inspect unproper configuration and last but not least

- the DRS prompts warnings and errors


Enabling by the Extension Manager
"""""""""""""""""""""""""""""""""

Enable the DRS – Development Reporting System – for all caddy plugins
by the Extension Manager. If you have more than one plugin on a page
(like a caddy and a mini caddy), you will the report for each item. It
is recommended to enable the DRS by the Plugin / Flexform (see below).

BE AWARE: This feature can't debug user functions. For a complete
report, you have to enable this feature by the Constant Editor (see
below).

|img-56|

#. Module: Admin Tools Extension Manager

#. Edit Area Select the Caddy extension

#. Tab [Configuration]

#. Category: [Debugging]

#. DRS – Development Reporting System: [Enabled]

#. [Update]


Enabling by the Plugin / Flexform (recommended)
"""""""""""""""""""""""""""""""""""""""""""""""

Enable the DRS – Development Reporting System – for the current plugin
only by the plugin / flexform.

BE AWARE: This feature can't debug user functions. For a complete
report, you have to enable this feature by the Constant Editor (see
below).

|img-57|

#. Module: Web List

#. Page tree Page with the Caddy plugin (here: [8194] Caddy)

#. Edit Area Caddy plugin: tab [Plugin]

#. Tab [Check it!]

#. DRS: You will get a report ... [X]


Enabling by the Constant Editor (recommended)
"""""""""""""""""""""""""""""""""""""""""""""

Enable the DRS – Development Reporting System – for user functions.

BE AWARE: This feature debugs user functions only. For a complete
report, you have to enable the DRS by the plugin / flexform or the
extension manager too.

|img-58|

#. Module: Web Template

#. Page tree Page with the Caddy plugin (here: [8194] Caddy)

#. Edit Area [Constant Editor]

#. category [CADDY - DEBUG]

#. Debug user functions (backend) [X]


The DRS report
""""""""""""""


Precondition
~~~~~~~~~~~~

You need the extension

- Developer Log (devlog)


Report
~~~~~~

|img-59|

Get the report:

#. Module > Admin Tools: Development Log

