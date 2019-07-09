<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "form_newsletter"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Newsletter',
    'description' => 'This extension combines the Form extension with the newsletter service Newsletter2go. It creates a new finisher which can add new recipients to your Newsletter2go account.',
    'category' => 'plugin',
    'author' => '',
    'author_email' => 'info@brainworxx.de',
    'author_company' => 'BRAINWORXX GmbH',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'state' => 'beta',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
            'form' => '9.5.0-9.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
