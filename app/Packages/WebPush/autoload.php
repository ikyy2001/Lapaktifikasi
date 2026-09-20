<?php

/**
 * Autoloader mandiri untuk WebPush dan dependensinya.
 * Menjamin Web Push dapat berjalan di server manapun (termasuk cPanel hosting produksi tanpa akses composer SSH).
 */
spl_autoload_register(function ($class) {
    $prefixes = [
        'Minishlink\\WebPush\\' => __DIR__ . '/minishlink/web-push/src/',
        'Jose\\Component\\'      => __DIR__ . '/web-token/jwt-library/',
        'SpomkyLabs\\Pki\\'     => __DIR__ . '/spomky-labs/pki-framework/src/',
        'Base64Url\\'           => __DIR__ . '/spomky-labs/base64url/src/',
        'Http\\Discovery\\'     => __DIR__ . '/php-http/discovery/src/',
        'Http\\Client\\'        => __DIR__ . '/php-http/httplug/src/',
        'Http\\Promise\\'       => __DIR__ . '/php-http/promise/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }
    return false;
});
