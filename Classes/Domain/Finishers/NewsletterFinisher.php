<?php
/**
 * Form Newsletter
 *
 * This extension combines the Form extension with the newsletter service
 * Newsletter2go. It creates a new finisher which can add new recipients to
 * your Newsletter2go account.
 *
 * @author
 *   BRAINWORXX GmbH <info@brainworxx.de>
 *
 * @license
 *   http://opensource.org/licenses/LGPL-2.1
 *
 *   GNU Lesser General Public License Version 2.1
 *
 *   Form Newsletter Copyright (C) 2019 Brainworxx GmbH
 *
 *   This library is free software; you can redistribute it and/or modify it
 *   under the terms of the GNU Lesser General Public License as published by
 *   the Free Software Foundation; either version 2.1 of the License, or (at
 *   your option) any later version.
 *   This library is distributed in the hope that it will be useful, but WITHOUT
 *   ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 *   FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License
 *   for more details.
 *   You should have received a copy of the GNU Lesser General Public License
 *   along with this library; if not, write to the Free Software Foundation,
 *   Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

namespace Brainworxx\FormNewsletter\Domain\Finishers;

class NewsletterFinisher extends \TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher
{

  /**
   * Makes a call to the Newsletter2go REST API.
   * Redirects to given page depending on the response.
   */
  protected function executeInternal()
  {
    // error flag
    $error = false;

    // Finisher's options
    $authKey = $this->parseOption('authkey');
    $userEmail = $this->parseOption('email');
    $userPassword = $this->parseOption('pass');
    $successPid = $this->parseOption('successPid');
    $failurePid = $this->parseOption('failurePid');
    $fieldNames = $this->parseOption('fieldNames');

    // Form Values
    $formValues = $this->finisherContext->getFormValues();

    // get rid of fields without content (e.g. static text)
    foreach ($formValues as $key => $value) {
      if ($value === null) {
        unset($formValues[$key]);
      }
    }

    // Make an api call and redirect depending on the response.
    $api = new \Brainworxx\FormNewsletter\API\Newsletter2goApi($authKey, $userEmail, $userPassword);
    $api->setSSLVerification(false);

    try {
      $user = $api->createRecipient($formValues, explode(',', $fieldNames));
    } catch (\Exception $e) {
      $error = true;

      // log error in typo3temp/var/log/typo3.log
      $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
      $logger->error(
        'An error occured while trying to communicate with Newsletter2go\'s REST API. Check the finisher\'s options!',
        [
          'error' => $e->getMessage()
        ]
      );
    }

    $pid = (!$error && $user->status <= 201) ? $successPid : $failurePid;

    // create Form's Redirect Finisher
    $formDefinition = $this->finisherContext->getFormRuntime()->getFormDefinition();
    $formDefinition->createFinisher('Redirect', [
      'pageUid' => $pid
    ]);

    // execute redirect finisher which will always be the last one
    end($formDefinition->getFinishers())->execute($this->finisherContext);
  }
}
