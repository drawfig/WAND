<?php
namespace Middleware;

class Middleware_Software_Groups {
    public $GLOBAL_MIDDLEWARE = [
        'Rate_Limiter',
        'User_Filter',
        'Example_Middleware',

    ];

    public $REGIONAL_MIDDLEWARE = [
        'example_region' => [
            'GROUP:example_group',
        ],
        'test' => [
        ],
        'new_test' => [
        ],

    ];

    public $LOCAL_GROUP_MIDDLEWARE = [
        '0' => [
        ],
        '1' => [
        ],

    ];
}