<?php

namespace App\Http\Controllers;

use App\Repositories\EquipmentRepository;
use App\Repositories\VendorRepository;
use App\Enums\EquipmentStatus;

class EquipmentController
{
    public function index(): void
    {
        $repo = new EquipmentRepository();
        $equipments = $repo->findAll();
        include __DIR__ . '/../../../templates/equipment/index.php';
    }

    public function create(): void
    {
        $vendorRepo = new VendorRepository();
        $vendors = $vendorRepo->findAll();
        $equipmentStatuses = EquipmentStatus::cases(); // ← передаём в шаблон

        include __DIR__ . '/../../../templates/equipment/create.php';
    }

    public function store(): void
    {
        try {
            validate_csrf();
            $repo = new EquipmentRepository();
            $id = $repo->create($_POST);
            redirect("/equipment/{$id}");
        } catch (\Exception $e) {
            $_SESSION['errors'] = $e instanceof \InvalidArgumentException 
                ? json_decode($e->getMessage(), true) 
                : ['error' => $e->getMessage()];
            $_SESSION['old'] = $_POST;
            redirect('/equipment/create');
        }
    }

    public function show(string $id): void
    {
        $repo = new EquipmentRepository();
        $equipment = $repo->findById((int)$id);
        if (!$equipment) {
            http_response_code(404);
            echo "Не найдено";
            return;
        }
        include __DIR__ . '/../../../templates/equipment/view.php';
    }

    public function edit(string $id): void
    {
        $repo = new EquipmentRepository();
        $equipment = $repo->findById((int)$id);
        if (!$equipment) {
            http_response_code(404);
            echo "Не найдено";
            return;
        }
        $vendorRepo = new VendorRepository();
        $vendors = $vendorRepo->findAll();
        $equipmentStatuses = EquipmentStatus::cases(); // ← передаём в шаблон

        include __DIR__ . '/../../../templates/equipment/edit.php';
    }

    public function update(string $id): void
    {
        try {
            validate_csrf();
            $repo = new EquipmentRepository();
            $repo->update((int)$id, $_POST);
            redirect("/equipment/{$id}");
        } catch (\Exception $e) {
            $_SESSION['errors'] = $e instanceof \InvalidArgumentException 
                ? json_decode($e->getMessage(), true) 
                : ['error' => $e->getMessage()];
            $_SESSION['old'] = $_POST;
            redirect("/equipment/{$id}/edit");
        }
    }

    public function destroy(string $id): void
    {
        validate_csrf();
        $repo = new EquipmentRepository();
        $repo->delete((int)$id);
        redirect('/equipment');
    }
}