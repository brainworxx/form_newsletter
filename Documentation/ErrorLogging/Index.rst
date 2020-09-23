.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Error Logging
=============

If an exception is thrown while trying to communicate with the API, the
extension will create an entry in the log file.
The error message will also be displayed at the end of the entry.
The default path for the log file is :file:`typo3temp/var/log`.

In case of an error the extension will always redirect to the failure page.
