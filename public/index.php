<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\BuildingController;
use App\Controllers\ContactController;
use App\Controllers\AdminController;
use App\Controllers\HomeController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

session_start();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestUri) {
    case '/':
        (new HomeController())->index();
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            (new AuthController())->showLoginForm();
        } else {
            (new AuthController())->login();
        }
        break;
    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            (new AuthController())->showRegisterForm();
        } else {
            (new AuthController())->register();
        }
        break;
    case '/logout':
        (new AuthController())->logout();
        break;
    case '/contact':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new ContactController())->submitContactForm();
        } else {
            // Tampilkan form kontak jika permintaan GET
            include __DIR__ . '/src/views/contact.php';
        }
        break;
    case '/home':
        (new HomeController())->index();
        break;
    case '/admin':
        if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) {
            (new AdminController())->index();
        } else {
            header('Location: /login');
        }
        break;
    case '/building':
        (new BuildingController())->index();
        break;
    default:
        include __DIR__ . '/src/views/404.php';
        break;
}

// Hapus atau komentari var_dump untuk menghindari output yang tidak diinginkan
// var_dump($_ENV);