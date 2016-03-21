<?php

(new Dotenv\Dotenv(__DIR__ . '/..'))->load();

return [
    'debug'           => getenv('DEBUG'),
    'api_key'         => getenv('API_KEY'),
    'db_path'         => getenv('DB_PATH'),
    'mail_host'       => getenv('MAIL_HOST'),
    'mail_port'       => getenv('MAIL_PORT'),
    'mail_from'       => getenv('MAIL_FROM'),
    'mail_username'   => getenv('MAIL_USERNAME'),
    'mail_password'   => getenv('MAIL_PASSWORD'),
    'mail_encryption' => getenv('MAIL_ENCRYPTION'),
    'mail_auth_mode'  => getenv('MAIL_AUTH_MODE'),
];
