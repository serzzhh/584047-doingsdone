<?php
  require_once 'init.php';

  $page_content = include_template('main.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'link' => $link]);
  $layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks]);
  print($layout_content);
?>
