<?php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<?php $content = '
<h1>Редактировать поставщика</h1>
<form method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

    <label>Название:<br>
        <input name="name" value="' . h($old['name'] ?? $vendor->name) . '" required>
    </label>
    ' . (isset($errors['name']) ? '<div class="error">' . h($errors['name']) . '</div>' : '') . '

    <label>Тип:<br>
        <select name="type">
'; ?>
<?php foreach ($vendorTypes as $case): ?>
    <option value="<?= $case->value ?>" <?= (isset($old['type']) && $old['type'] === $case->value) ? 'selected' : ($vendor->type->value === $case->value ? 'selected' : '') ?>>
        <?= h($case->label()) ?>
    </option>
<?php endforeach; ?>
<?php $content .= '
        </select>
    </label>

    <label>Email:<br>
        <input type="email" name="contact_email" value="' . h($old['contact_email'] ?? $vendor->contactEmail) . '" required>
    </label>

    <label>Сайт:<br>
        <input type="url" name="website" value="' . h($old['website'] ?? $vendor->website) . '" required>
    </label>

    <button type="submit">Сохранить</button>
    <a href="/vendors">Отмена</a>
</form>
'; ?>
<?php include __DIR__ . '/../layout.php'; ?>