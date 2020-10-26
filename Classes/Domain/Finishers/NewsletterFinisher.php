<?php
/**
 * Form Newsletter
 *
 * form_newsletter combines the Form extension with the newsletter service
 * Newsletter2go. It creates a finisher which can add new recipients to
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
 *   Form Newsletter Copyright (C) 2019–2020 Brainworxx GmbH
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

        // finisher's options
        $authKey = $this->parseOption('authkey');
        $userEmail = $this->parseOption('email');
        $userPassword = $this->parseOption('pass');
        $successPage = $this->parseOption('successPage');
        $failurePage = $this->parseOption('failurePage');
        $fieldNames = $this->parseOption('fieldNames');
        $doubleOptIn = $this->parseOption('doubleOptIn');

        // form values
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
            if ($doubleOptIn !== '') {
                $response = $api->sendDoubleOptIn($formValues, explode(',', $fieldNames), strtolower($doubleOptIn));
            } else {
                $response = $api->createRecipient($formValues, explode(',', $fieldNames));
            }
        } catch (\Exception $e) {
            $error = true;

            // log error, default path: typo3temp/var/log

            /** @var $logger \TYPO3\CMS\Core\Log\Logger */
            $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            $logger->log(
                \TYPO3\CMS\Core\Log\LogLevel::ERROR,
                'An error occured while trying to communicate with Newsletter2go\'s REST API. Check the finisher\'s options!',
                [
                    'error' => $e->getMessage()
                ]
            );
        }

        $pid = (!$error && $response->status <= 201) ? $successPage : $failurePage;

        // create Form's redirect finisher
        $formDefinition = $this->finisherContext->getFormRuntime()->getFormDefinition();
        $formDefinition->createFinisher('Redirect', [
            'pageUid' => $pid
        ]);

        // execute redirect finisher which will always be the last one
        end($formDefinition->getFinishers())->execute($this->finisherContext);
    }
}
