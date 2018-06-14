<?php
/**
 * Pop PHP Bootstrap Console Configuration File
 */

return [
    'routes' => [
        'install [--simple] [--api] [<namespace>]' => [
            'controller' => 'Pop\Bootstrap\Controller\ConsoleController',
            'action'     => 'install'
        ],
        'help' => [
            'controller' => 'Pop\Bootstrap\Controller\ConsoleController',
            'action'     => 'help'
        ],
        '*' => [
            'controller' => 'Pop\Bootstrap\Controller\ConsoleController',
            'action'     => 'error'
        ]
    ]
];