<?php
namespace App\Traits;

trait Sanitizable
{
    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) return null;
        return trim($value);
    }

    protected function sanitizeFloat(?string $value): ?float
    {
        if ($value === null || $value === '') return null;
        $val = filter_var($value, FILTER_VALIDATE_FLOAT);
        return $val !== false ? $val : null;
    }

    protected function sanitizeInt(?string $value): ?int
    {
        if ($value === null || $value === '') return null;
        $val = filter_var($value, FILTER_VALIDATE_INT);
        return $val !== false ? $val : null;
    }
}