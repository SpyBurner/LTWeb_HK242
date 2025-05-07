<?php
namespace service;

use Exception;
use model\UserModel;
use core\Logger;
use function core\handleException;

//Special service, no IService implementation
class AuthService
{
    public static function register($username, $email, $password): array|int
    {
        $hashed_password = self::getPassword_hash($password);

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
            return ['success' => false, 'message' => 'Invalid username or password'];
        }

        // User is authenticated, create a session token
        $payload = [
            'userid' => $result['data']->getUserid(),
            'role' => $result['data']->getIsadmin(),
            'exp' => time() + TOKEN_LIFETIME
        ];

        $token = self::generateJWT($payload);
        return ['success' => true, 'message' => 'Login successful', 'token' => $token];
    }

    public static function generateJWT($payload): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $base64UrlHeader = self::base64url_encode(json_encode($header));
        $base64UrlPayload = self::base64url_encode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", SECRET_KEY, true);
        $base64UrlSignature = self::base64url_encode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    private static function base64url_encode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        if (isset($_COOKIE['auth_token'])) {
            setcookie('auth_token', '', time() - 3600, '/');
        }
        
        session_start();
    }

    public static function changePassword($oldPassword, $newPassword){
        $user = self::getCurrentUser()['user'];

        assert($user instanceof UserModel);

        Logger::log("Changing password for user: " . $user->getUserid());

        if (!password_verify($oldPassword, $user->getPassword())) {
            // Logger::log("Old password is incorrect");
            return ['success' => false, 'message' => "Old password is incorrect"];
        }

        if (password_verify($newPassword, $user->getPassword())) {
            // Logger::log("New password is the same as old password");
            return ['success' => false, 'message' => 'New password cannot be the same as old password'];
        }

        $hashed = self::getPassword_hash($newPassword);
        $user->setpassword($hashed);

        $result = UserService::save($user);
        if (!$result['success']) {
            // Logger::log("Failed to update password: " . $result['message']);
            return ['success' => false, 'message' => $result['message']];
        }

        return ['success' => true, 'message' => 'Password updated successfully'];
    }

    public static function validateSession(): array
    {
        if (!isset($_COOKIE['auth_token'])) {
            return ['success' => false, 'message' => 'You are not logged in'];
        }

        $payload = self::verifyJWT($_COOKIE['auth_token']);
        if (!$payload) {
            return ['success' => false, 'message' => 'Invalid token'];
        }

        if ($payload['exp'] < time()) {
            return ['success' => false, 'message' => 'Token expired'];
        }

        return ['success' => true, 'user' => $payload];
    }

    private static function verifyJWT(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) return null;

        list($headerB64, $payloadB64, $signatureB64) = $parts;

        $header = json_decode(self::base64url_decode($headerB64), true);
        $payload = json_decode(self::base64url_decode($payloadB64), true);
        $signature = self::base64url_decode($signatureB64);

        if (!$header || !$payload || !isset($payload['exp'])) return null;

        $expectedSig = hash_hmac('sha256', "$headerB64.$payloadB64", SECRET_KEY, true);
        if (!hash_equals($expectedSig, $signature)) return null;

        // if ($payload['exp'] < time()) return null;

        return $payload;
    }

    private static function base64url_decode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function getPassword_hash($password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function getCurrentUser()
    {
        $result = self::validateSession();
        if (!$result['success']) return $result;

        $userId = $result['user']['userid'];
        $result = UserService::findById($userId);
        if (!$result['success']) return $result;

        return ['success' => true, 'user' => $result['data'], 'avatar' => $result['avatar']];
    }

    public static function generateResetToken($userModel){
        if (!$userModel instanceof UserModel) {
            return ['success' => false, 'message' => 'Invalid user model'];
        }

        try {
            $token = random_bytes(16);
        }
        catch (Exception $e) {
            return handleException($e);
        }

        $userModel->setToken($token);
        $userModel->setTokenExpiration(time() + RESET_PASSWORD_TOKEN_LIFETIME);

        $result = UserService::save($userModel);
        if (!$result['success']) {
            return ['success' => false, 'message' => $result['message']];
        }

        return ['success' => true, 'data' => $token];
    }

    public static function resetPassword($user, $password){
        $hashedPassword = self::getPassword_hash($password);
        $user->setPassword($hashedPassword);
        $user->setToken(null);
        $user->setTokenExpiration(null);

        $result = UserService::save($user);

        if (!$result['success']) {
            return ['success' => false, 'message' => $result['message']];
        }

        return ['success' => true, 'message' => 'Password reset successfully'];
    }

}