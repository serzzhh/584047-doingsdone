<?php
require_once 'init.php';
if (isset($_SESSION['user'])) {
    $page_content = include_template('add.php', ['projects' => $projects]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['date'])) {
        $task = $_POST;
        $errors = [];
        $task_name = trim($task['name']);

        if (empty($task_name)) {
            $errors['name'] = 'Это поле надо заполнить';
        } elseif (iconv_strlen($task_name) > 128) {
            $errors['name'] = 'Введите не более 128 символов';
        }

        if (strtotime($task["date"]) < strtotime(date("d.m.Y")) && !empty($task["date"])) {
            $errors['date'] = 'Дата должна быть больше или равна текущей';
        }

        if (!isset($task['project'])) {
            $errors['project'] = 'Добавьте проект';
        } else {
          $index = array_search($task["project"], array_column($projects, 'name'));
          if ($index !== false) {
              $task["project"] = $projects[$index]['id'];
          } else {
              $errors['project'] = 'Выберите проект из списка';
          }
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
                $stmt = db_get_prepare_stmt($link, $sql, [$task_name, $_SESSION['user']['id'], $task["project"], $task['preview']]);
            } else {
                $sql = 'INSERT INTO tasks (deadline, name, id_user, id_project, file) VALUES (?, ?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($link, $sql, [date("Y-m-d", strtotime($task["date"])), $task_name, $_SESSION['user']['id'], $task["project"], $task['preview']]);
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
} else {
    header("Location: guest.php");
}
