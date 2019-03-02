<?php
require_once 'init.php';

$page_content = include_template('project.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];
    $name = $form['name'];
    $id_user = $_SESSION['user']['id'];

    if (empty($form['name'])) {
        $errors['name'] = 'Это поле надо заполнить';
    } else {
        $sql = 'SELECT name FROM projects WHERE name = ? && id_user = ? LIMIT 1';
        $stmt = db_get_prepare_stmt($link, $sql, [$name, $id_user]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        if ($count) {
            $errors['name'] = 'Такой проект уже существует';
        }
    }

    if (empty($errors)) {
        $sql = 'INSERT INTO projects (name, id_user) VALUES (?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, [$name, $id_user]);
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