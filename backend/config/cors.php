<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /* O servidor aceita requisições de origem cruzada  (*) todas */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /* métodos http podem ser recebidos ( post, get, patch, delete, etc ... )*/
    'allowed_methods' => ['*'],

    /*  Especifica quais origens são permitidas */
    'allowed_origins' => [env('FRONTEND_URL','http://localhost:8080')],

    /* São permitidos padrões de origem, pode se especificar u padraão regex que corresponda  as origens    */
    'allowed_origins_patterns' => [],

    /* são os cabeçalhos permitidas, os cabeçalhos que podem serem enviados Ex: x-xsrf-token, content-type, etc... */
    'allowed_headers' => ['*'],

     /* são os cabeçalhos de resposta que podem serem expostos ao java script */
    'exposed_headers' => [],

    /* Permite armazenar em cache as configurações do cors */
    'max_age' => 0,

    /* Compartilhar ou não cookies com a SPA , permitir ou não solicitações de origem cruzada  (cookies de autenticação e certificados dos clientes) */
    'supports_credentials' => true,

];

