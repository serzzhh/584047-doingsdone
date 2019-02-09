<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
function count_projects($tasks, $project_name) {
  $count = 0;
  foreach ($tasks as $value) {
    if ($value["project"] === $project_name) {
      $count += 1;
    }
  }
  return $count;
}
function count_hours ($date) {
  $cur_date = strtotime(date("d.m.Y H:i"));
  $task_date = strtotime($date);
  $diff = ($task_date - $cur_date) / 3600;
  return $diff;
}
?>
