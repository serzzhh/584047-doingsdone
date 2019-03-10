<?php
require_once 'init.php';
if (!isset($_SESSION['user'])) {
    header("Location: /auth.php");
}
$page_content = include_template('add.php', ['projects' => $projects]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $task = [];
    $errors = [];
    $task['date'] = $_POST['date'];

    if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
        $task['name'] = trim($_POST['name']);
    } else {
        $errors['name'] = 'Это поле необходимо заполнить';
    }

    if (!isset($errors['name']) && iconv_strlen($task['name']) > 128) {
        $errors['name'] = 'Введите не более 128 символов';
    }

    if (!empty($task['date']) && strtotime($task['date']) < strtotime(date("d.m.Y"))) {
        $errors['date'] = 'Дата должна быть больше или равна текущей';
    }

    if (isset($_POST['project'])) {
        $index = array_search($_POST["project"], array_column($projects, 'name'));
        if ($index !== false) {
            $task["project"] = $projects[$index]['id'];
        }
    } else {
        $task["project"] = 0;
    }

    if (count($errors)) {
        $page_content = include_template('add.php', ['task' => $task, 'errors' => $errors, 'projects' => $projects]);
    } else {
        if (isset($_FILES['preview']) && is_uploaded_file($_FILES['preview']['tmp_name'])) {
            $tmp_name = $_FILES['preview']['tmp_name'];
            $path = 'uploads/' . uniqid() . $_FILES['preview']['name'];

            move_uploaded_file($tmp_name, $path);
            $task['preview'] = $path;
        } else {
            $task['preview'] = '';
        }
        if (empty($task['date'])) {
            $sql = 'INSERT INTO tasks (name, id_user, id_project, file) VALUES (?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$task['name'], $_SESSION['user']['id'], $task["project"], $task['preview']]);
        } else {
            $sql = 'INSERT INTO tasks (deadline, name, id_user, id_project, file) VALUES (?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [date("Y-m-d", strtotime($task["date"])), $task['name'], $_SESSION['user']['id'], $task["project"], $task['preview']]);
        }
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: /");
        } else {
            print("Ошибка запроса: " . mysqli_error($link));
        }
    }
}
$layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
print($layout_content);
