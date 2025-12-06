<?php
namespace App\Traits;

trait Validatable
{
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = trim($data[$field] ?? '');
            if ($rule === 'required' && $value === '') {
                $errors[$field] = "Поле обязательно";
            }
            if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "Некорректный email";
            }
            if ($rule === 'url' && $value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
                $errors[$field] = "Некорректный URL";
            }
            if ($rule === 'price' && $value !== '') {
                if (!is_numeric($value) || (float)$value < 0) {
                    $errors[$field] = "Цена должна быть положительным числом";
                }
            }
        }
        return $errors;
    }
}