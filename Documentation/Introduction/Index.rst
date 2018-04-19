.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Introduction
============

What does it do?
----------------

The extension provides a plugin that forwards a user to another page when visiting the page the first time.



Screenshots
-----------

The plugin can be configured by TypoScript or in the plugin form:

.. figure:: ../Images/screenshot.png

    example plugin configuration


How does it work?
----------------

The plugin stores the information if a user visited the page already for each page separately. Therefore this is not a
global forwarder but for a specific page. You can use the plugin on multiple pages though.

The information if a user was already on the page is stored in a cookie.
