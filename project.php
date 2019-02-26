<?php
require_once 'init.php';

$page_content = include_template('project.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $form = $_POST;
  $errors = [];

	if (empty($form['name'])) {
    $errors['name'] = 'Это поле надо заполнить';
	}

  $sql = "SELECT name FROM projects WHERE name = '$form[name]' && id_user = $_SESSION[user][id] LIMIT 1";
	$res = mysqli_query($link, $sql);

	if (mysqli_num_rows($res) > 0) {
    $errors['name'] = 'Такой проект уже существует';
  }

  if (count($errors)) {
		$page_content = include_template('project.php', ['form' => $form, 'errors' => $errors, 'projects' => $projects]);
	}
  else {
    $sql = 'INSERT INTO projects (name, id_user) VALUES (?, ?)';

     $stmt = db_get_prepare_stmt($link, $sql, [$form["name"], $_SESSION['user']['id']]);
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
