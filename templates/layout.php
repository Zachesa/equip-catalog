<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог оборудования</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= asset('assets/style.css') ?>">
</head>
<body>
    <nav>
        <a href="/" class="<?= is_active('/') ?>">Главная</a>
        <a href="/equipment" class="<?= is_active('/equipment') ?>">Оборудование</a>
        <a href="/equipment/create">Добавить</a>
    </nav>

    <main class="container">
        <?= $content ?? '' ?>
    </main>
</body>
</html>