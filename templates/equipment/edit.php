<?php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<?php $content = '
<h1>Редактировать: ' . h($equipment->model) . '</h1>
<form method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

    <label>Модель:<br>
        <input name="model" value="' . h($old['model'] ?? $equipment->model) . '" required>
    </label>
    ' . (isset($errors['model']) ? '<div class="error">' . h($errors['model']) . '</div>' : '') . '

    <label>Описание:<br>
        <textarea name="description">' . h($old['description'] ?? $equipment->description) . '</textarea>
    </label>

    <label>Статус:<br>
        <select name="status">
'; ?>
<?php foreach ($equipmentStatuses as $case): ?>
    <option value="<?= $case->value ?>" <?= (isset($old['status']) && $old['status'] === $case->value) ? 'selected' : ($equipment->status->value === $case->value ? 'selected' : '') ?>>
        <?= h($case->label()) ?>
    </option>
<?php endforeach; ?>
<?php $content .= '
        </select>
    </label>

    <label>Поставщик:<br>
        <select name="vendor_id">
            <option value="">— не выбран —</option>
'; ?>
<?php foreach ($vendors as $v): ?>
    <option value="<?= $v->id ?>" <?= (isset($old['vendor_id']) && (int)$old['vendor_id'] === $v->id) ? 'selected' : ($equipment->vendorId === $v->id ? 'selected' : '') ?>>
        <?= h($v->name) ?>
    </option>
<?php endforeach; ?>
<?php $content .= '
        </select>
    </label>

    <label>Цена (руб):<br>
        <input type="number" step="0.01" name="price" value="' . h($old['price'] ?? $equipment->price) . '">
    </label>
    ' . (isset($errors['price']) ? '<div class="error">' . h($errors['price']) . '</div>' : '') . '

    <label>Характеристики:<br>
        <textarea name="specifications">' . h($old['specifications'] ?? $equipment->specifications) . '</textarea>
    </label>

    <button type="submit">Сохранить</button>
    <a href="/equipment/' . $equipment->id . '">Отмена</a>
</form>
'; ?>
<?php include __DIR__ . '/../layout.php'; ?>