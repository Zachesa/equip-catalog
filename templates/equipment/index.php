<?php $content = '
    <h1>Оборудование</h1>
    <a href="/equipment/create">Добавить новое</a>
    <ul>
'; ?>
<?php foreach ($equipments as $eq): ?>
    <li>
        <a href="/equipment/<?= $eq->id ?>"><?= h($eq->model) ?></a>
        — <?= h($eq->status->label()) ?>
        <?php if ($eq->price): ?>
            (<?= format_price($eq->price) ?>)
        <?php endif; ?>
    </li>
<?php endforeach; ?>
<?php $content .= '</ul>'; ?>
<?php include __DIR__ . '/../layout.php'; ?>