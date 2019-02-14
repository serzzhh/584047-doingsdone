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
function count_projects($tasks, $project_id) {
  $count = 0;
  foreach ($tasks as $value) {
    if ($value["id_project"] === $project_id) {
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
function get_res ($link, $sql, $data = []) {
$stmt = db_get_prepare_stmt($link, $sql, [$data]);
  mysqli_stmt_execute($stmt);
  if ($res = mysqli_stmt_get_result($stmt)) {
			$res = mysqli_fetch_all($res, MYSQLI_ASSOC);
      return $res;
		}
    else {
  			print("Ошибка запроса: " . mysqli_error($link));
  		}
}
