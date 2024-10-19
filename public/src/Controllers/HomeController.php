<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        include __DIR__ . ('/../views/home.php');
    }

    public function about()
    {
        include __DIR__ . ('/../views/about.php');
    }
}