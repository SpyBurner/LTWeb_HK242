<?php
// TO LOG SERVER-SIDE ERRORS, WITHOUT EXPOSING THEM TO THE USER
namespace core;
class Logger
{
    public static function log($message, $file = "errors.log")
    {
        if (!USE_LOGGER) {
            return;
        }

        $logFile = __DIR__ . "/../" . $file;
        $date = date("Y-m-d H:i:s");
        $dbt=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
        $caller = $dbt[1]['function'] ?? null;
        error_log("[$date] $caller: $message\n", 3, $logFile);
    }
}
