<?php

use FontManager\Controller\FontController;
use FontManager\Controller\FontGroupController;
// config/routes.php
return [
    'fonts' => [
        'GET' => [
            'handler' => [FontController::class, 'listFonts'],
            'auth' => false
        ],
        'POST' => [
            'handler' => [FontController::class, 'upload'],
            'auth' => true
        ]
    ],
    'font-groups' => [
        'GET' => [
            'handler' => [FontGroupController::class, 'listGroups'],
            'auth' => false
        ],
        'POST' => [
            'handler' => [FontGroupController::class, 'create'],
            'auth' => true
        ]
    ]
];