<?php
namespace App\Repositories;

use App\Core\Database;
use App\Enums\VendorType;
use App\Models\Vendor;
use App\Traits\Sanitizable;

class VendorRepository
{
    use Sanitizable;

    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT id, name, type, contact_email, website
            FROM vendors ORDER BY name
        ');
        return array_map(fn($row) => $this->mapRowToModel($row), $stmt->fetchAll());
    }

    public function findById(int $id): ?Vendor
    {
        $stmt = $this->pdo->prepare('SELECT id, name, type, contact_email, website FROM vendors WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->mapRowToModel($row) : null;
    }

    public function canBeDeleted(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM equipments WHERE vendor_id = ? AND status = "active"');
        $stmt->execute([$id]);
        return $stmt->fetchColumn() == 0;
    }

    public function delete(int $id): bool
    {
        if (!$this->canBeDeleted($id)) {
            throw new \RuntimeException('Нельзя удалить: есть активное оборудование');
        }
        $stmt = $this->pdo->prepare('DELETE FROM vendors WHERE id = ?');
        return $stmt->execute([$id]);
    }

    private function mapRowToModel(array $row): Vendor
    {
        return new Vendor(
            id: (int)$row['id'],
            name: $row['name'],
            type: VendorType::from($row['type']),
            contactEmail: $row['contact_email'],
            website: $row['website'],
        );
    }
}