<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Bootstrap
|--------------------------------------------------------------------------
|
| Loaded by index.php before routing. This is the ONLY place .env is read.
| It sets up constants, error handling, the class autoloader, and one
| shared Database instance ($db) for pages and controllers.
|
*/


/*
|--------------------------------------------------------------------------
| Environment Loader
|--------------------------------------------------------------------------
*/

/**
 * Read a value from .env (parsed once, cached for the request).
 */
function env(string $key, mixed $default = null): mixed
{
    static $env = null;

    if ($env === null) {

        $envFile = dirname(__DIR__) . '/.env';

        if (!is_readable($envFile)) {
            die('Application configuration file (.env) not found.');
        }

        $env   = [];
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            // Skip comments and malformed lines
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$k, $v] = explode('=', $line, 2);

            $env[trim($k)] = trim(trim($v), "\"'");
        }
    }

    return $env[$key] ?? $default;
}


/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
*/

define('APP_ENV',   (string) env('APP_ENV', 'production'));
define('APP_DEBUG', filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN));
define('APP_NAME',  (string) env('APP_NAME', 'MyLibrary'));
define('APP_ROOT',  dirname(__DIR__));
define('APP_URL',   (string) env('APP_URL', '/mylibrary'));


/*
|--------------------------------------------------------------------------
| Error Reporting + Time Zone
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('display_startup_errors', APP_DEBUG ? '1' : '0');

date_default_timezone_set((string) env('APP_TIMEZONE', 'America/Chicago'));


/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
|
| Database is the base class and lives in config/dbc.php.
| Every other class goes in /classes/ClassName.php and is loaded
| automatically the first time it is used (e.g. new Books()).
|
*/
require_once APP_ROOT . '/config/dbc.php';

spl_autoload_register(function (string $class): void {

    $file = APP_ROOT . '/config/' . $class . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});


/*
|--------------------------------------------------------------------------
| Shared Instances + Procedural Helpers
|--------------------------------------------------------------------------
*/

$db = new Books();   // no connection is made until the first query
