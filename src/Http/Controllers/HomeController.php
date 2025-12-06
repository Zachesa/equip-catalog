<?php
namespace App\Http\Controllers;

class HomeController
{
    public function index(): void
    {
        include __DIR__ . '/../../../templates/home.php';
    }
}