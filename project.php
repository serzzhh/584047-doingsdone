<?php
require_once 'init.php';
if (!isset($_SESSION['user'])) {
header("Location: /auth.php");
}
$page_content = include_template('project.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $form = $_POST;
    $errors = [];
    $project_name = trim($form['name']);
    $id_user = $_SESSION['user']['id'];

    if (empty($project_name)) {
        $errors['name'] = 'Это поле надо заполнить';
    } elseif (iconv_strlen($project_name) > 50) {
        $errors['name'] = 'Введите не более 50 символов';
    } else {
        $sql = 'SELECT name FROM projects WHERE name = ? && id_user = ? LIMIT 1';
        $stmt = db_get_prepare_stmt($link, $sql, [$project_name, $id_user]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        if ($count) {
            $errors['name'] = 'Такой проект уже существует';
        }
    }

    if (empty($errors)) {
        $sql = 'INSERT INTO projects (name, id_user) VALUES (?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, [$project_name, $id_user]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: /");
        } else {
            print("Ошибка запроса: " . mysqli_error($link));
        }
    } else {
        $page_content = include_template('project.php', ['form' => $form, 'errors' => $errors, 'projects' => $projects]);
    }
}
$layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
print($layout_content);
