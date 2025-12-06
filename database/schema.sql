-- database/schema.sql
-- Схема БД для каталога оборудования

CREATE TABLE IF NOT EXISTS vendors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('manufacturer', 'distributor', 'service_center') NOT NULL,
    contact_email VARCHAR(255) NOT NULL,
    website VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS equipments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('active', 'discontinued', 'archived') NOT NULL DEFAULT 'active',
    vendor_id INT UNSIGNED NULL,
    price DECIMAL(10,2) NULL,
    specifications TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL
);

-- Тестовые данные
INSERT IGNORE INTO vendors (name, type, contact_email, website) VALUES
('ООО ТехноПром', 'manufacturer', 'contact@techprom.ru', 'https://techprom.ru'),
('ГлобалСнаб', 'distributor', 'info@globalsnab.ru', 'https://globalsnab.ru');

INSERT IGNORE INTO equipments (model, description, status, vendor_id, price, specifications) VALUES
('TP-2000', 'Промышленный станок', 'active', 1, 1500000.00, 'Мощность: 15 кВт'),
('GS-Monitor X5', 'Монитор для АСУ ТП', 'discontinued', 2, 45000.00, 'Разрешение: 1920x1080');