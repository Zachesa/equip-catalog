<?php
namespace App\Core;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\VendorController;

class Router
{
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $path = parse_url($path, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        if ($path === '/' && $method === 'GET') {
            (new HomeController())->index(); return;
        }

        if ($path === '/equipment' && $method === 'GET') {
            (new EquipmentController())->index(); return;
        }
        if ($path === '/equipment/create' && $method === 'GET') {
            (new EquipmentController())->create(); return;
        }
        if ($path === '/equipment' && $method === 'POST') {
            (new EquipmentController())->store(); return;
        }
        if (preg_match('#^/equipment/(\d+)/edit$#', $path, $matches) && $method === 'GET') {
            (new EquipmentController())->edit($matches[1]); return;
        }
        if (preg_match('#^/equipment/(\d+)$#', $path, $matches) && $method === 'POST' && ($_POST['_method'] ?? '') === 'PUT') {
            (new EquipmentController())->update($matches[1]); return;
        }
        if (preg_match('#^/equipment/(\d+)$#', $path, $matches) && $method === 'POST' && ($_POST['_method'] ?? '') === 'DELETE') {
            (new EquipmentController())->destroy($matches[1]); return;
        }

        if ($path === '/vendors' && $method === 'GET') {
            (new VendorController())->index(); return;
        }
        if ($path === '/vendors/create' && $method === 'GET') {
            (new VendorController())->create(); return;
        }
        if ($path === '/vendors' && $method === 'POST') {
            (new VendorController())->store(); return;
        }
        if (preg_match('#^/vendors/(\d+)/edit$#', $path, $matches) && $method === 'GET') {
            (new VendorController())->edit($matches[1]); return;
        }
        if (preg_match('#^/vendors/(\d+)$#', $path, $matches) && $method === 'POST' && ($_POST['_method'] ?? '') === 'PUT') {
            (new VendorController())->update($matches[1]); return;
        }
        if (preg_match('#^/vendors/(\d+)$#', $path, $matches) && $method === 'POST' && ($_POST['_method'] ?? '') === 'DELETE') {
            (new VendorController())->destroy($matches[1]); return;
        }

        http_response_code(404);
        echo "Page not found";
    }
}