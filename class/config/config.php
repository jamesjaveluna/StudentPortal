<?php

// Modify here only, do not touch sa ubos.
$dbHost = '';
$dbName = '';
$dbUser = '';
$dbPass = '';

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
    $query = "SELECT config_key, config_value FROM config";
    $stmt = $db->query($query);
    $config = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // General configuration
    if($config['GEN_DEBUG'] === 'true' || $config['GEN_DEBUG'] == 'true'){
        define('GEN_DEBUG', true);
    } else {
        define('GEN_DEBUG', false);
    }

    if($config['GEN_MAINTENANCE'] === 'true' || $config['GEN_MAINTENANCE'] == 'true'){
        define('GEN_MAINTENANCE', true);
    } else {
        define('GEN_MAINTENANCE', false);
    }

    define('USER_TYPE', explode(',', $config['USER_TYPE']));
    
    // Support Channel
    define('MESSAGE_COOLDOWN', $config['MESSAGE_COOLDOWN']);
    define('SUPPORT_STATUS', explode(',', $config['SUPPORT_STATUS']));
    
    // Database configuration
    define('DB_HOST', $dbHost);
    define('DB_NAME', $dbName);
    define('DB_USER', $dbUser);
    define('DB_PASSWORD', $dbPass);
    
    // Mailer configuration
    define('MAILER_HOST', $config['MAILER_HOST']);
    define('MAILER_PORT', $config['MAILER_PORT']);
    define('MAILER_USERNAME', $config['MAILER_USERNAME']);
    define('MAILER_PASSWORD', $config['MAILER_PASSWORD']);
    define('MAILER_FROM_EMAIL', $config['MAILER_FROM_EMAIL']);
    define('MAILER_FROM_NAME', $config['MAILER_FROM_NAME']);
    define('MAILER_SECURE', $config['MAILER_SECURE']);
    define('MAILER_AUTH', $config['MAILER_AUTH']);
    define('MAILER_DEBUG', $config['MAILER_DEBUG']);
    
    // Recaptcha System
    if($config['RECAPTCHA_ENABLED'] === 'true' || $config['RECAPTCHA_ENABLED'] == 'true'){
        define('RECAPTCHA_ENABLED', true);
    } else {
        define('RECAPTCHA_ENABLED', false);
    }
    
    define('RECAPTCHA_SECRET_KEY_HTML', $config['RECAPTCHA_SECRET_KEY_HTML']);
    define('RECAPTCHA_SECRET_KEY', $config['RECAPTCHA_SECRET_KEY']);
    
    // Website configuration
    define('SITE_URL', $config['SITE_URL']);
    define('SITE_NAME', $config['SITE_NAME']);
    define('SITE_AUTHOR', $config['SITE_AUTHOR']);
    define('SITE_ICON', $config['SITE_ICON']);
    
    // Directory
    define('TEMPLATES_DIR', __DIR__ . './../../template/email/');
    define('CLASS_DIR', __DIR__ . './../../class/');
    
    // Email Templates
    define('VERIFICATION_TEMPLATE', file_get_contents(TEMPLATES_DIR.'/verify.html'));
    define('CHANGEPASS_TEMPLATE', file_get_contents(TEMPLATES_DIR.'/password-change.html'));
        
    // API Security
    define('API_SECRET_KEY', $config['API_SECRET_KEY']);
    define('API_TOKEN_DURATION', $config['API_TOKEN_DURATION']);

} catch (PDOException $e) {
   // There was an error fetching the configuration values
   // Redirect to maintenance page
   header('HTTP/1.1 503 Service Unavailable');
   header('Status: 503 Service Unavailable');
   header('Location: ./../../maintenance.php');
   exit;
}
?>
