<?php

/**
 * Loads environment variables from a .env file and provides access via env($key, $default)
 */
function env($key, $default = null) {
    static $env = null;
    if ($env === null) {
        $env = [];
        $envFile = __DIR__ . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;
                if (strpos($line, '=') !== false) {
                    list($envKey, $envValue) = explode('=', $line, 2);
                    $envKey = trim($envKey);
                    $envValue = trim($envValue);
                    // Remove surrounding quotes if present
                    if (
                        (substr($envValue, 0, 1) === '"' && substr($envValue, -1) === '"') ||
                        (substr($envValue, 0, 1) === "'" && substr($envValue, -1) === "'")
                    ) {
                        $envValue = substr($envValue, 1, -1);
                    }
                    $env[$envKey] = $envValue;
                }
            }
        }
    }
    return array_key_exists($key, $env) ? $env[$key] : $default;
}