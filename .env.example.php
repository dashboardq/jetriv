<?php

// These are permanent values that should not be overwritten during execution.
// If you need configuration values that can be potentially changed, use the .conf.php file.
// These are typically uppercase to emphasize their permanence.

if(is_file(__DIR__ . DIRECTORY_SEPARATOR . '.keys.php')) {
    $keys = require __DIR__ . DIRECTORY_SEPARATOR . '.keys.php';
} else {
    $keys = [];
}

// No closing slash on the directories or urls.
return [
    // App
    'APP_NAME' => 'Example', 
    'APP_ENV' => 'dev',
    'APP_HOST' => 'www.example.com',
    'APP_SITE' => 'https://www.example.com',
    // Author information used on Terms and Privacy pages.
    'APP_AUTHOR' => 'Organization', 
    'APP_AUTHOR_EMAIL' => 'support@example.com', 
    'APP_AUTHOR_ADDRESS' => "123 Main Street\nNashville, TN 37203", 
    // Terms and Privacy info
    'APP_PRIVACY_UPDATED' => 'July 15, 2022',
    'APP_TERMS_UPDATED' => 'July 15, 2022',

    // Only used for simple, non-database sites.
    'APP_LOGIN_TYPE' => 'db', // Options: list, db
    // The number is the user_id.
    'APP_LOGIN_USERS' => [
        1 => ['email' => 'user@example.com', 'password' => 'example'],
    ],
    // APP_LOGIN needs to be 'db' in order for true to turn on registrations.
    'APP_REGISTER_ALLOW' => true,

    // How long the session cookie should be saved. Note this can also be affected by the
    // PHP session.gc_maxlifetime configuration.
    'APP_SESSION_SECONDS' => 60 * 60 * 24 * 30,

    'APP_PRIVATE_HOME' => '/home',
    'APP_PUBLIC_HOME' => '/login',

    'APP_ENCRYPT_CONNECTIONS' => 'CONNECTIONS_1',

    // Mavoc
    'AO_APP_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'app',
    'AO_BASE_DIR' => __DIR__,
    'AO_PLUGIN_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'plugins',
    'AO_SETTINGS_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'settings',
    'AO_MAVOC_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'mavoc',
    'AO_MAVOC_CONSOLE_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'mavoc' . DIRECTORY_SEPARATOR . 'console',
    'AO_MAVOC_CORE_DIR' => __DIR__ . DIRECTORY_SEPARATOR . 'mavoc' . DIRECTORY_SEPARATOR . 'core',

    'AO_OUTPUT_HOOKS' => false,
    'AO_KEYS' => $keys, 

    // DB
    'DB_USE' => true,
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'example_com',
    'DB_USER' => 'example_com',
    'DB_PASS' => '',
    'DB_CHARSET' => 'utf8mb4',

    // Email
    'EMAIL_ADMIN' => 'admin@example.com',
    'EMAIL_FROM' => 'Sender Name <sender@example.com>',
    'EMAIL_SEND' => false, // null pretends to send, true sends, false errors and does not send
    'EMAIL_OVERRIDE_TO' => false, // if set to an email address and EMAIL_SEND is true, all emails will only be sent to this email address

    // OAuth Services
    'TWITTER_URL' => 'https://api.twitter.com',
    'TWITTER_URL_AUTHORIZE' => 'https://twitter.com/i/oauth2/authorize',
    'TWITTER_URL_REDIRECT' => 'http://example.com/twitter/redirect',
    'TWITTER_URL_TOKEN' => 'https://api.twitter.com/2/oauth2/token',
    'TWITTER_CLIENT_ID' => 'YOUR_OAUTH2_CLIENT_ID',
    'TWITTER_CLIENT_SECRET' => 'YOUR_OAUTH2_CLIENT_SECRET',
];

