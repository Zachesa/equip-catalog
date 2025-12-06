<?php
// src/Models/Equipment.php

namespace App\Models;

use App\Enums\EquipmentStatus;

readonly class Equipment
{
    public function __construct(
        public int $id,
        public string $model,
        public string $description,
        public EquipmentStatus $status,
        public ?int $vendorId,
        public ?float $price,
        public ?string $specifications,
    ) {}
}