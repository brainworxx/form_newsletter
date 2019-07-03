<?php

namespace Brain\FormNewsletter\Domain\Finishers;

use \Brain\FormNewsletter\API\Newsletter2goApi;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

if (!function_exists('curl_init')) {
  die('cURL is not installed!');
}

class NewsletterFinisher extends \TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher {

  /**
   * Makes a call to the Newsletter2go REST API.
   * Redirects to given page depending on the response.
   */
  protected function executeInternal() {
    // Finisher's options
    $authKey = $this->parseOption('authkey');
    $userEmail = $this->parseOption('email');
    $userPassword = $this->parseOption('pass');
    $successPid = $this->parseOption('successPid');
    $failurePid = $this->parseOption('failurePid');
    $fieldNames = $this->parseOption('fieldNames');

    // Form Values
    $formValues = $this->finisherContext->getFormValues();

    // Make an api call and redirect depending on the response.
    $api = new Newsletter2goApi($authKey, $userEmail, $userPassword);
    $api->setSSLVerification(false);
    $user = $api->createRecipient($formValues, explode(',', $fieldNames));

    $uri = $user->status <= 201 ? $this->getURI($successPid) : $this->getURI($failurePid);

    header('Location: ' . $uri);
  }

  /**
   * Returns a valid TYPO3 Page URI by using the UriBuilder.
   *
   * @param integer $pid
   * @return string
   */
  protected function getURI($pid) {
    $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    $uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);

    return $uriBuilder
        ->reset()
        ->setTargetPageUid($pid)
        ->build();
  }
}
