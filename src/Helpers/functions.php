<?php
// src/Helpers/functions.php
// Глобальные утилиты для безопасности, редиректов и вывода

/**
 * Безопасный вывод строки в HTML (защита от XSS)
 */
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Генерация абсолютного URL к ресурсу (CSS, JS, изображения)
 */
function asset(string $path): string
{
    // Предполагается, что сайт работает из корня домена (например, http://localhost:8080/)
    return '/' . ltrim($path, '/');
}

/**
 * Редирект и завершение скрипта
 */
function redirect(string $uri, int $statusCode = 302): never
{
    header("Location: " . $uri, true, $statusCode);
    exit;
}

/**
 * Проверка, является ли текущий URI активным (для навигации)
 */
function is_active(string $path): string
{
    $current = $_SERVER['REQUEST_URI'] ?? '/';
    $path = rtrim($path, '/') ?: '/';

    // Сравниваем без query string
    $currentPath = parse_url($current, PHP_URL_PATH);
    $currentPath = rtrim($currentPath, '/') ?: '/';

    return $currentPath === $path ? 'active' : '';
}

/**
 * Форматирование цены в рублях
 */
function format_price(?float $price): string
{
    if ($price === null) {
        return '—';
    }
    return number_format($price, 0, ',', ' ') . ' ₽';
}

/**
 * Генерация CSRF-токена и сохранение в сессии
 */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Проверка CSRF-токена из POST-запроса
 * @throws RuntimeException если токен недействителен
 */
function validate_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        throw new RuntimeException('Недействительный CSRF-токен');
    }
}

/**
 * Безопасное чтение значения из массива с фильтрацией
 */
function input(string $key, ?string $default = null): ?string
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}