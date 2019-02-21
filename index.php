<?php
  require_once 'init.php';

  if (isset($_GET["project_id"])) {
   $id_count = get_res($link, "SELECT id_project FROM tasks WHERE id_project = ? LIMIT 1", $_GET["project_id"]);
    if (!$id_count) {
      http_response_code(404);
    }
  }

  $page_content = include_template('main.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
  $layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
  print($layout_content);
