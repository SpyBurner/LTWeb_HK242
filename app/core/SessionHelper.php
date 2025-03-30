<?php

namespace core;

class SessionHelper
{
    /**
     * Retrieves a session value and clears it immediately.
     *
     * @param string $key The session key to retrieve.
     * @return mixed|null The session value or null if not set.
     */
    public static function getFlash($key)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]); // Clear after retrieving
            return $value;
        }
        return null;
    }

    public static function setFlash($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}
