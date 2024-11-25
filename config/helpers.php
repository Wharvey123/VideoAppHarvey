<?php

return [
    'default_user' => [
        'name' => env('DEFAULT_USER_NAME', 'alumne'),
        'email' => env('DEFAULT_USER_EMAIL', 'alumne@gmail.com'),
        'password' => env('DEFAULT_USER_PASSWORD', 'Admin123'),
        'team_id' => env('DEFAULT_USER_TEAM_ID',1),
        'team_name' => env('DEFAULT_USER_TEAM_NAME','Team 1'),
        'user_id' =>  env('DEFAULT_USER_USER_ID',1),
        'personal_team' =>  env('DEFAULT_USER_PERSONAL_TEAM',true),
    ],
    'default_professor' => [
        'name' => env('DEFAULT_PROFESSOR_NAME', 'harvey'),
        'email' => env('DEFAULT_PROFESSOR_EMAIL', 'harvey@gmail.com'),
        'password' => env('DEFAULT_PROFESSOR_PASSWORD', 'Admin123'),
        'team_id' => env('DEFAULT_PROFESSOR_TEAM_ID',2),
        'team_name' => env('DEFAULT_PROFESSOR_TEAM_NAME','Team 2'),
        'user_id' =>  env('DEFAULT_PROFESSOR_USER_ID',2),
        'personal_team' =>  env('DEFAULT_PROFESSOR_PERSONAL_TEAM',true),
    ],
];
