<?php

namespace core;

abstract class Controller
{
    /**
     * Render a view and pass data to it.
     *
     * @param string $view The view file name (without .php extension).
     * @param array $data Data to be extracted and passed to the view.
     */
    protected function render($view, $data = [])
    {
        extract($data); // Convert array keys into variables
        require __DIR__ . "/../views/$view.php"; // Include the view file
    }

    /**
     * Get a value from the GET request.
     *
     * @param string $key The parameter name.
     * @param mixed $default Default value if the key is missing.
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * Get a value from the POST request.
     *
     * @param string $key The parameter name.
     * @param mixed $default Default value if the key is missing.
     * @return mixed
     */
    protected function post($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * Redirect to a different page.
     *
     * @param string $url The target URL.
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Check if the user is logged in (session-based authentication).
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Require authentication before allowing access to a page.
     * Redirects to login if the user is not authenticated.
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'You must be logged in to access this page.'
            ]);
        }
    }

    protected function redirectWithMessage($url, $params = []) {
        $query = http_build_query($params);
        header("Location: " . $url . ($query ? '?' . $query : ''));
        exit;
    }
}

?>
