<?php
namespace service;

use Exception;
use model\UserModel;
use core\Logger;

//Special service, no IService implementation
class AuthService
{
    public static function register($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $emailCheck = UserService::findByEmail($email);

        if ($emailCheck['success']) {
            return ['success' => false, 'message' => 'Email already in use'];
        }

        return UserService::save(new UserModel(null, $username, $email, $hashed_password));
    }

    public static function login($email, $password): array
    {
        $result = UserService::findByEmail($email);
        if (!$result['success']) {
            return ['success' => false, 'message' => 'Email not found'];
        }

        if (!password_verify($password, $result['data']->getPassword())) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        // User is authenticated, create a session token
        $payload = [
            'userid' => $result['data']->getUserid(),
            'role' => $result['data']->getIsadmin(),
            'expire' => time() + TOKEN_LIFETIME
        ];

        $token = AuthService::encrypt($payload);
        return ['success' => true, 'message' => 'Login successful', 'token' => $token];
    }

    public function logout(){
        try{
            session_start();
        }
        catch (Exception $e){
            //Session already started
        }
        session_destroy();
        if (isset($_COOKIE['auth_token'])) {
            unset($_COOKIE['auth_token']);
        }
        session_start();
    }

    /**
     *
     * @return array ['success' => true,  'user' => ('userid' => int, 'role' => string, 'expire' => int)]
     * OR ['success' => false, 'message' => ...]
     */
    public static function validateSession(): array
    {
        if (!isset($_COOKIE['auth_token'])) {
            return ['success' => false, 'message' => 'No active session'];
        }

        $payload = AuthService::decrypt($_COOKIE['auth_token']);
        if (!$payload || $payload['expire'] < time()) {
            return ['success' => false, 'message' => 'Session expired'];
        }

        return ['success' => true, 'user' => $payload];
    }


    /* Encrypts a payload using a secret key
     * @param array $payload The payload to encrypt
     * @return string The encrypted payload
     * */
    private static function encrypt($payload): ?string
    {
        try {
            $method = ENCRYPT_METHOD;// 'aes-256-gcm'
            $key = hash('sha256', SECRET_KEY, true);// Derive a 32-byte key
            $iv = random_bytes(openssl_cipher_iv_length($method));// Secure IV
            $tag = '';// Authentication tag (used for integrity)
            $jsonPayload = json_encode($payload);
            $ciphertext = openssl_encrypt($jsonPayload, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);

            // Store IV + Tag + Ciphertext (Base64-encoded)
            return base64_encode($iv . $tag . $ciphertext);
        } catch (Exception $e) {
            Logger::log($e->getMessage());
            return null;
        }
    }

    /* Decrypts a payload using a secret key
     * @param string $encryptedPayload The encrypted payload to decrypt
     * @return array The decrypted payload
     * */
    private static function decrypt($encryptedPayload)
    {
        $method = ENCRYPT_METHOD;
        $key = hash('sha256', SECRET_KEY, true);

        $decodedData = base64_decode($encryptedPayload);
        $ivLength = openssl_cipher_iv_length($method);

        // Extract IV, Tag, and Ciphertext
        $iv = substr($decodedData, 0, $ivLength);
        $tag = substr($decodedData, $ivLength, 16); // AES-GCM tag is 16 bytes
        $ciphertext = substr($decodedData, $ivLength + 16);

        // Decrypt
        $decryptedJson = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return json_decode($decryptedJson, true); // Convert back to an array
    }


}