<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class . ':index');
$app->post('/', HomeController::class . ':store')->setName('home');
