<?php
$vendorName = '—';
if ($equipment->vendorId) {
    $vendorRepo = new \App\Repositories\VendorRepository();
    $vendor = $vendorRepo->findById($equipment->vendorId);
    $vendorName = $vendor ? h($vendor->name) : '—';
}
?>
<?php $content = '
<h1>' . h($equipment->model) . '</h1>
<p><strong>Описание:</strong> ' . h($equipment->description) . '</p>
<p><strong>Статус:</strong> ' . h($equipment->status->label()) . '</p>
<p><strong>Поставщик:</strong> ' . $vendorName . '</p>
'; ?>
<?php if ($equipment->price): ?>
    <p><strong>Цена:</strong> <?= format_price($equipment->price) ?></p>
<?php endif; ?>
<?php if ($equipment->specifications): ?>
    <p><strong>Характеристики:</strong><br><?= nl2br(h($equipment->specifications)) ?></p>
<?php endif; ?>
<?php $content .= '
<a href="/equipment">&larr; Назад</a>
<a href="/equipment/' . $equipment->id . '/edit">✏️ Редактировать</a>
<form method="POST" style="display:inline" onsubmit="return confirm(\'Удалить?\')" action="/equipment/' . $equipment->id . '">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="csrf_token" value="' . csrf_token() . '">
    <button type="submit" style="background:none;border:none;color:red;cursor:pointer">🗑️ Удалить</button>
</form>
'; ?>
<?php include __DIR__ . '/../layout.php'; ?>