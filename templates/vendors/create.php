<?php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<?php $content = '
<h1>Добавить поставщика</h1>
<form method="POST">
    <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

    <label>Название:<br>
        <input name="name" value="' . h($old['name'] ?? '') . '" required>
    </label>
    ' . (isset($errors['name']) ? '<div class="error">' . h($errors['name']) . '</div>' : '') . '

    <label>Тип:<br>
        <select name="type">
'; ?>
<?php foreach ($vendorTypes as $case): ?>
    <option value="<?= $case->value ?>" <?= (isset($old['type']) && $old['type'] === $case->value) ? 'selected' : '' ?>>
        <?= h($case->label()) ?>
    </option>
<?php endforeach; ?>
<?php $content .= '
        </select>
    </label>
    ' . (isset($errors['type']) ? '<div class="error">' . h($errors['type']) . '</div>' : '') . '

    <label>Email:<br>
        <input type="email" name="contact_email" value="' . h($old['contact_email'] ?? '') . '" required>
    </label>
    ' . (isset($errors['contact_email']) ? '<div class="error">' . h($errors['contact_email']) . '</div>' : '') . '

    <label>Сайт:<br>
        <input type="url" name="website" value="' . h($old['website'] ?? '') . '" required>
    </label>
    ' . (isset($errors['website']) ? '<div class="error">' . h($errors['website']) . '</div>' : '') . '

    <button type="submit">Сохранить</button>
</form>
'; ?>
<?php include __DIR__ . '/../layout.php'; ?>