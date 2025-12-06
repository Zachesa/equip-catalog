<?php
$content = '<h1>Поставщики</h1><a href="/vendors/create">Добавить</a><ul>';
foreach ($vendors as $v) {
    $content .= '<li>
        ' . h($v->name) . ' (' . h($v->type->label()) . ')
        | <a href="/vendors/' . $v->id . '/edit">✏️</a>
        <form method="POST" style="display:inline" onsubmit="return confirm(\'Удалить?\')">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="csrf_token" value="' . csrf_token() . '">
            <button type="submit" style="background:none;border:none;color:red;cursor:pointer">🗑️</button>
        </form>
    </li>';
}
$content .= '</ul>';
include __DIR__ . '/../layout.php';