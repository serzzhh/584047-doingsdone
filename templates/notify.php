<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<?php $value = count($tasks_message) > 1 ? "запланированы задачи" : "запланирована задача"; ?>
<p>Уважаемый, <?=$name_user?>. У вас <?=$value ?></p>

    <?php foreach ($tasks_message as $task): ?>
        <p><?=$task['name'] ?> на <?=date("d.m.Y", strtotime($task['deadline'])) ?> </p>
    <?php endforeach; ?>
</body>
</html>
