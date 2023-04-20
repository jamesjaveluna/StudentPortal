<?php
require_once('../../api/config.php');
require_once('../../api/vendor/autoload.php');

use Firebase\JWT\JWT;

function generateToken($user_id) {
    $issued_at = time();
    $expires_at = $issued_at + API_TOKEN_DURATION;
    $payload = array(
        "user_id" => $user_id,
        "iat" => $issued_at,
        "exp" => $expires_at
    );
    $token = JWT::encode($payload, API_SECRET_KEY, 'HS256');
    return $token;
}

function verifyToken($token) {
    try {
        $decoded = JWT::decode($token, API_SECRET_KEY, array('HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}

?>
