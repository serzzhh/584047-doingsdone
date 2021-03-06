<?php
require_once 'init.php';
if (!isset($_SESSION['user'])) {
    header("Location: /guest.php");
}

$show_completed =  isset($_GET['show_completed']) ? $_GET['show_completed'] : 0;
$check =  isset($_GET['check']) ? $_GET['check'] : 0;

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
$search = $_GET['search'] ?? '';
if ($search) {
    $sql = "SELECT * FROM tasks WHERE id_user = ? AND MATCH (name) AGAINST (?)";

    $stmt = db_get_prepare_stmt($link, $sql, [$id, $search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
$page_content = include_template('index.php', ['tasks' => $tasks, 'show_completed' => $show_completed, 'check' => $check, 'sort' => $sort]);
$layout_content = include_template('layout.php', ['title' => "Дела в Порядке", 'content' => $page_content, 'projects' => $projects, 'tasks' => $tasks_all]);
print($layout_content);
