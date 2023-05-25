<?php

// General configuration
define('GEN_DEBUG', true);
define('GEN_MAINTENANCE', false);

// Support Channel
define('MESSAGE_COOLDOWN', 0); //(Minutes) - You may write 0 for unlimited.
define('SUPPORT_STATUS', array('open', 'closed', 'solved', 'pending')); 

// Database configuration
define('DB_HOST', '82.180.174.154');
define('DB_NAME', 'u410000628_student_portal');
define('DB_USER', 'u410000628_student_portal');
define('DB_PASSWORD', 'P@ssword123');

// Mailer configuration
define('MAILER_HOST', 'smtp.hostinger.com');
define('MAILER_PORT', 465);
define('MAILER_USERNAME', 'no-reply@proudcecilian.online');
define('MAILER_PASSWORD', 'P@ssword123');
define('MAILER_FROM_EMAIL', 'no-reply@proudcecilian.online');
define('MAILER_FROM_NAME', 'Student Portal');
define('MAILER_SECURE', 'ssl'); // Replace 'ssl' with 'tls' if needed
define('MAILER_AUTH', true);
define('MAILER_DEBUG', 0);

// Recaptcha System
define('RECAPTCHA_ENABLED', false);
define('RECAPTCHA_SECRET_KEY_HTML', '6Ld9ILglAAAAAPLbeclOEH61bvkBxMYymGkjAR04'); // Site key
define('RECAPTCHA_SECRET_KEY', '6Ld9ILglAAAAAFUKePvnO8m6hYWIxQQ7XlcxZOnA'); // Secret Key

// Website configuration
define('SITE_URL', 'https://proudcecilian.online');
define('SITE_NAME', 'Cecilian Student Portal');
define('SITE_AUTHOR', 'James Javeluna');
define('SITE_ICON', '../../../app-assets/images/ico/favicon.ico');

// Directory
define('TEMPLATES_DIR', __DIR__ . './../../template/email/');
define('CLASS_DIR', __DIR__ . './../../class/');

// Email Templates
define('VERIFICATION_TEMPLATE', file_get_contents(TEMPLATES_DIR.'/verify.html'));
define('CHANGEPASS_TEMPLATE', file_get_contents(TEMPLATES_DIR.'/password-change.html'));

// API Security
define('API_SECRET_KEY', 'my-secret-key'); // your secret key
define('API_TOKEN_DURATION', 2592000); // token expiration time in seconds (1 day)
