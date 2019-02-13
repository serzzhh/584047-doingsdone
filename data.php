<?php
  $show_complete_tasks = rand(0, 1);

  $id = 1;
  $sql1 = "SELECT * FROM projects WHERE id_user = ?";
  $sql2 = "SELECT * FROM tasks WHERE id_user = ?";
  $projects = get_res($link, $sql1, $id);
  $tasks = get_res($link, $sql2, $id);
