<?php
namespace App\Models;

use App\Enums\VendorType;

readonly class Vendor
{
    public function __construct(
        public int $id,
        public string $name,
        public VendorType $type,
        public string $contactEmail,
        public string $website,
    ) {}
}