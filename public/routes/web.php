<?php

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\BuildingController;
use App\Controllers\AdminController;

$router->get('/', function() {
    include __DIR__ . '/../src/views/home.php';
});

$router->get('/login', [AuthController::class, 'showLoginForm']);

$router->get('/buiding', [BuildingController::class, 'index']);

$router->get('/contact', [ContactController::class, 'showContactForm']);
$router->post('/submit-contact', [ContactController::class, 'submitContactForm']);
$router->get('/admin', [AdminController::class, 'index']);