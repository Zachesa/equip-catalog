<?php

namespace App\Http\Controllers;

use App\Repositories\VendorRepository;
use App\Enums\VendorType;

class VendorController
{
    public function index(): void
    {
        $repo = new VendorRepository();
        $vendors = $repo->findAll();
        include __DIR__ . '/../../../templates/vendors/index.php';
    }

    public function create(): void
    {
        $vendorTypes = VendorType::cases(); // ← передаём в шаблон
        include __DIR__ . '/../../../templates/vendors/create.php';
    }

    public function store(): void
    {
        try {
            validate_csrf();
            $repo = new VendorRepository();
            $stmt = $repo->pdo->prepare('
                INSERT INTO vendors (name, type, contact_email, website)
                VALUES (?, ?, ?, ?)
            ');
            $stmt->execute([
                $_POST['name'],
                $_POST['type'],
                $_POST['contact_email'],
                $_POST['website']
            ]);
            redirect('/vendors');
        } catch (\Exception $e) {
            $_SESSION['errors'] = ['error' => $e->getMessage()];
            $_SESSION['old'] = $_POST;
            redirect('/vendors/create');
        }
    }

    public function edit(string $id): void
    {
        $repo = new VendorRepository();
        $vendor = $repo->findById((int)$id);
        if (!$vendor) {
            http_response_code(404);
            return;
        }
        $vendorTypes = VendorType::cases(); // ← передаём в шаблон
        include __DIR__ . '/../../../templates/vendors/edit.php';
    }

    public function update(string $id): void
    {
        try {
            validate_csrf();
            $repo = new VendorRepository();
            $stmt = $repo->pdo->prepare('
                UPDATE vendors
                SET name = ?, type = ?, contact_email = ?, website = ?
                WHERE id = ?
            ');
            $stmt->execute([
                $_POST['name'],
                $_POST['type'],
                $_POST['contact_email'],
                $_POST['website'],
                (int)$id
            ]);
            redirect('/vendors');
        } catch (\Exception $e) {
            $_SESSION['errors'] = ['error' => $e->getMessage()];
            $_SESSION['old'] = $_POST;
            redirect("/vendors/{$id}/edit");
        }
    }

    public function destroy(string $id): void
    {
        try {
            validate_csrf();
            $repo = new VendorRepository();
            $repo->delete((int)$id);
            redirect('/vendors');
        } catch (\Exception $e) {
            http_response_code(400);
            echo h($e->getMessage());
        }
    }
}