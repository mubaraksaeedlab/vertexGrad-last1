<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

// استدعاء bootstrap/app.php
$app = require __DIR__.'/../bootstrap/app.php';

// Laravel 12 لا يستخدم Kernel بنفس الطريقة القديمة
$response = $app->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
