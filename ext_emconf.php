<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'One-Time Forwarder',
    'description' => 'plugin that forwards a user to another page when visiting the page the first time',
    'category' => 'plugin',
    'author' => 'René Fritz',
    'author_email' => 'r.fritz@colorcube.de',
    'author_company' => 'Colorcube',
    'version' => '1.0.1',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.999'
        ],
        'conflicts' => [],
        'suggests' => [
            'news' => '*'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Colorcube\\CookieRedirect\\' => 'Classes'
        ]
    ]
];
