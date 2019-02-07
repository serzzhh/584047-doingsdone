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
function count_projects($tasks, $project_name)
{
  $count = 0;
  foreach ($tasks as $value) {
    if ($value["project"] === $project_name) {
      $count += 1;
    }
  }
  return $count;
}
?>
