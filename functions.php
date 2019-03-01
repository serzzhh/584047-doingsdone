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

/**
 * Счетчик задач для проекта
 *
 * @param array $tasks список задач
 * @param int $project_id id проекта
 *
 * @return int количество задач
 */
function count_projects($tasks, $project_id) {
  $count = 0;
  foreach ($tasks as $value) {
    if ($value["id_project"] === $project_id) {
      $count += 1;
    }
  }
  return $count;
}

/**
 * Считает разницу переданной и текущей дат
 *
 * @param string $date дата
 *
 * @return int количество часов
 */
function count_hours ($date) {
  $cur_date = strtotime(date("d.m.Y H:i"));
  $task_date = strtotime($date);
  $diff = ($task_date - $cur_date) / 3600;
  return $diff;
}

/**
 * Создает массив на основе готового SQL запроса и переданных данных
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array результ SQL запроса
 */
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
