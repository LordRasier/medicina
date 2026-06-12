<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Web routes are split by domain to keep this entrypoint small and scannable.
| Keep public paths stable while controllers are being refactored.
|
*/

$routeFiles = [
    'auth',
    'base',
    'users',
    'spaces',
    'billing',
    'dispensas',
];

foreach ($routeFiles as $routeFile) {
    require __DIR__ . "/web/{$routeFile}.php";
}
