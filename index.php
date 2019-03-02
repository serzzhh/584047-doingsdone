<?php
  require_once 'init.php';

  if (isset($_GET["project_id"])) {
      $id_count = get_res($link, "SELECT id_project FROM tasks WHERE id_project = ? LIMIT 1", $_GET["project_id"]);
      if (!$id_count) {
          http_response_code(404);
      }
  }
  if (isset($_GET['check'])) {
      $sql = "UPDATE tasks SET completed = NOT completed, date_complete = NOW() WHERE id = ?";
      $stmt = db_get_prepare_stmt($link, $sql, [$_GET['task_id']]);
      $res = mysqli_stmt_execute($stmt);

      if ($res) {
          header("Location: /");
      } else {
          print("Ошибка запроса: " . mysqli_error($link));
      }
  }
  if (isset($_SESSION['user'])) {
      $page_content = include_template('index.php', ['tasks' => $tasks, 'show_completed' => $show_completed, 'check' => $check, 'sort' => $sort]);
      $layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
      print($layout_content);
  } else {
      header("Location: guest.php");
  }
