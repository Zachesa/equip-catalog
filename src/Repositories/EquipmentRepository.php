<?php
namespace App\Repositories;

use App\Core\Database;
use App\Enums\EquipmentStatus;
use App\Models\Equipment;
use App\Traits\Sanitizable;
use App\Traits\Validatable;

class EquipmentRepository
{
    use Sanitizable;
    use Validatable;

    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Equipment
    {
        $stmt = $this->pdo->prepare('
            SELECT id, model, description, status, vendor_id, price, specifications
            FROM equipments WHERE id = ?
        ');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->mapRowToModel($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT id, model, description, status, vendor_id, price, specifications
            FROM equipments ORDER BY id DESC
        ');
        return array_map(fn($row) => $this->mapRowToModel($row), $stmt->fetchAll());
    }

    public function create(array $data): int
    {
        $errors = $this->validateForCreate($data);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        $model = $this->sanitizeString($data['model']);
        $description = $this->sanitizeString($data['description'] ?? '');
        $status = EquipmentStatus::from($data['status'] ?? EquipmentStatus::ACTIVE->value);
        $vendorId = $this->sanitizeInt($data['vendor_id'] ?? null);
        $price = $this->sanitizeFloat($data['price'] ?? null);
        $specifications = $this->sanitizeString($data['specifications'] ?? null);

        $stmt = $this->pdo->prepare('
            INSERT INTO equipments (model, description, status, vendor_id, price, specifications)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$model, $description, $status->value, $vendorId, $price, $specifications]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $errors = $this->validateForCreate($data);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        $model = $this->sanitizeString($data['model']);
        $description = $this->sanitizeString($data['description']);
        $status = EquipmentStatus::from($data['status']);
        $vendorId = $this->sanitizeInt($data['vendor_id'] ?? null);
        $price = $this->sanitizeFloat($data['price'] ?? null);
        $specifications = $this->sanitizeString($data['specifications'] ?? null);

        $stmt = $this->pdo->prepare('
            UPDATE equipments
            SET model = ?, description = ?, status = ?, vendor_id = ?, price = ?, specifications = ?
            WHERE id = ?
        ');
        return $stmt->execute([$model, $description, $status->value, $vendorId, $price, $specifications, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM equipments WHERE id = ?');
        return $stmt->execute([$id]);
    }

    private function validateForCreate(array $data): array
    {
        $rules = [
            'model' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];
        $errors = $this->validate($data, $rules);
        if (!in_array($data['status'] ?? '', EquipmentStatus::values())) {
            $errors['status'] = 'Недопустимый статус';
        }
        if (!empty($data['price']) && !is_numeric($data['price'])) {
            $errors['price'] = 'Некорректная цена';
        }
        return $errors;
    }

    private function mapRowToModel(array $row): Equipment
    {
        return new Equipment(
            id: (int)$row['id'],
            model: $row['model'],
            description: $row['description'],
            status: EquipmentStatus::from($row['status']),
            vendorId: $row['vendor_id'] !== null ? (int)$row['vendor_id'] : null,
            price: $row['price'] !== null ? (float)$row['price'] : null,
            specifications: $row['specifications'],
        );
    }
}