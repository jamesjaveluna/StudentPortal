<?php
require_once($_SERVER['DOCUMENT_ROOT'].'./class/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'./class/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        $decoded = JWT::decode($token, new Key(API_SECRET_KEY, 'HS256'));
        $current_time = time();
        if (isset($decoded->exp) && $decoded->exp < $current_time) {
            return false;
        }

        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}

function getUserIdFromToken() {
    $headers = apache_request_headers();
    $token_prefix = 'Bearer ';

    // Check if token is missing
    if (!isset($headers['Authorization']) || empty(trim($headers['Authorization']))) {
        http_response_code(401);
        echo json_encode(array('message' => 'Token is missing'));
        exit;
    }

    // Get the token from the header
    $token = trim($headers['Authorization']);
    if (substr($token, 0, strlen($token_prefix)) === $token_prefix) {
        $token = substr($token, strlen($token_prefix));
    }

    // Verify the token
    $decoded_token = verifyToken($token);

    if (!$decoded_token) {
        http_response_code(401);
        echo json_encode(array('message' => 'Invalid token'));
        exit;
    }

    // Get the user ID from the token
    $user_id = $decoded_token['user_id'];

    return $user_id;
}

?>
