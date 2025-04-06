<?php
// TO LOG SERVER-SIDE ERRORS, WITHOUT EXPOSING THEM TO THE USER
namespace core;

class Logger
{
    public static function log($message, $file = "errors.log")
    {
        $logFile = __DIR__ . "/../" . $file;
        $date = date("Y-m-d H:i:s");
        error_log("[$date] $message\n", 3, $logFile);
    }
}
