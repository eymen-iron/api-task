<?php
return [
    'get constructionStages' => [
        'class' => 'ConstructionStages',
        'method' => 'getAll',
    ],
    'get constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'getSingle',
    ],
    'post constructionStages' => [
        'class' => 'ConstructionStages',
        'method' => 'post',
        'bodyType' => 'ConstructionStagesCreate'
    ],
    'patch constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'patch',
        'bodyType' => 'ConstructionStagesUpdate'
    ],
    'delete constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'delete',
        'bodyType' => 'ConstructionStagesDelete'
    ],
];