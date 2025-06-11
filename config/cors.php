<?php

return [
        'paths' => ['api/*', 'register', 'login', 'logout', 'sanctum/csrf-cookie', 'me', 'auth/*', 'mfa/*'],
    
        'allowed_methods' => ['*'],
    
        'allowed_origins' => ['http://185.255.112.204:4040'],
    
        'allowed_origins_patterns' => [],
    
        'allowed_headers' => ['*'],
    
        'exposed_headers' => [],
    
        'max_age' => 0,
    
        'supports_credentials' => true,
    ];
    