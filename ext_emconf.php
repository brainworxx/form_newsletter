<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "form_newsletter"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Newsletter',
    'description' => 'form_newsletter combines the Form extension with the newsletter service Newsletter2go. It creates a finisher which can add new recipients to your Newsletter2go account.',
    'category' => 'plugin',
    'author' => 'BRAINWORXX GmbH',
    'author_email' => 'info@brainworxx.de',
    'author_company' => NULL,
    'clearCacheOnLoad' => 0,
    'state' => 'stable',
    'version' => '1.4.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.4.99',
            'form' => '8.7.0-10.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
