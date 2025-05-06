<?php

namespace core;

use JetBrains\PhpStorm\NoReturn;
use service\AuthService;

abstract class Controller
{
    /**
     * Render a view and pass data to it.
     *
     * @param string $view The view file name (without .php extension).
     * @param array $data Data to be extracted and passed to the view.
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data); // Convert array keys into variables
        require __DIR__ . "/../view/$view.php"; // Include the view file
    }

    /**
     * Get a value from the GET request.
     *
     * @param string $key The parameter name.
     * @param mixed $default Default value if the key is missing.
     * @return mixed
     */
    protected function get($key, $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get a value from the POST request.
     *
     * @param string $key The parameter name.
     * @param mixed $default Default value if the key is missing.
     * @return mixed
     */
    protected function post($key, $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Redirect to a different page.
     *
     * @param string $url The target URL.
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Check if the user is logged in (session-based authentication).
     *
     * @return array
     */
    protected function isAuthenticate(): array
    {
        return AuthService::validateSession();
    }

    /**
     * PUT THIS IN THE BEGINNING OF EVERY CONTROLLER METHOD THAT REQUIRES AUTHENTICATION.
     *
     * Require authentication before allowing access to a page.
     * Redirects to login if the user is not authenticated.
     */
    protected function requireAuth($expectAdmin = false): void
    {
        $result = $this->isAuthenticate();
        if (!$result['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => $result['message']
            ]);
        }

        $user = $result['user'];
        if (!$user['role'] && $expectAdmin) {
            $this->redirectWithMessage('/', [
                'error' => 'You do not have the required permission.'
            ]);
        }
    }

    /**
     * Redirect to a URL with flash (session stored) messages.
     * @param string $url The target URL.
     * @param array $params An associative array of flash (session-stored) messages to set in the session. Use SessionHelper::getFlash(<message>) extract.
     */
    #[NoReturn] protected function redirectWithMessage($url, $params = []): void
    {
        Logger::log("Redirecting to $url " . json_encode($params));
        foreach ($params as $key => $value) {
            Logger::log("With message: " . $key . " = " . $value);
            SessionHelper::setflash($key, $value);
        }
        header("Location: " . $url);
        exit;
    }
}

?>
