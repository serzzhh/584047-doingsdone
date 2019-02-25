<?php
require_once 'init.php';

$page_content = include_template('add.php', ['projects' => $projects]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $task = $_POST;
  $errors = [];

	if (empty($task['name'])) {
    $errors['name'] = 'Это поле надо заполнить';
	}

  if (strtotime($task["date"]) < strtotime(date("d.m.Y")) && !empty($task["date"])) {
    $errors['date'] = 'Дата должна быть больше или равна текущей';
  }

  if (isset($_FILES['preview'])) {
		$tmp_name = $_FILES['preview']['tmp_name'];
		$path = $_FILES['preview']['name'];

    move_uploaded_file($tmp_name, $path);
		$task['preview'] = $path;
	}

  if (count($errors)) {
		$page_content = include_template('add.php', ['task' => $task, 'errors' => $errors, 'projects' => $projects]);
	}
  else {
    $sql = 'INSERT INTO tasks (name, deadline, id_user, id_project, file) VALUES (?, ?, 1, ?, ?)';

     $stmt = db_get_prepare_stmt($link, $sql, [$task["name"], date("Y-m-d",strtotime($task["date"])), $task["project"], $task['preview']]);
     $res = mysqli_stmt_execute($stmt);

    if ($res) {
      header("Location: /");
    }
    else {
         print("Ошибка запроса: " . mysqli_error($link));
    }
  }
}
$layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
print($layout_content);
