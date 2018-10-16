<?php

return [

    'routeAttributes' => [
        'middleware' => ['api', 'cors']
    ],

    'routeAuthAttributes' => [
        'middleware' => ['auth:api']
    ]
];