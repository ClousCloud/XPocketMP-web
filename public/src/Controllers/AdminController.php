<?php

namespace App\Controllers;

use App\Models\Contact;
use App\Middleware\UserAuth;

class AdminController
{
    public function __construct()
    {
        UserAuth::checkAdmin();
    }

    public function index()
    {
        $contacts = Contact::all();
        include __DIR__ . '../views/admin.php';
    }
}