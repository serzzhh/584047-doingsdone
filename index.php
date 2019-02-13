<?php
  require_once 'init.php';

  if (!$con) {
  print("Ошибка подключения: " . mysqli_connect_error());
  }
  else {
    require_once 'data.php';
  }

  $page_content = include_template('main.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
  $layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
  print($layout_content);
?>
