<?php
$show_completed =  isset($_GET['show_completed']) ? $_GET['show_completed'] : 0;
$check =  isset($_GET['check']) ? $_GET['check'] : 0;
$sort =  isset($_GET['sort']) ? $_GET['sort'] : '';

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user']['id'];
    $sql1 = "SELECT * FROM projects WHERE id_user = ?";
    $sql2 = "SELECT * FROM tasks WHERE id_user = ?";
    $sql_today = "SELECT * FROM tasks WHERE id_user = ? AND TO_DAYS(NOW()) - TO_DAYS(deadline) = 0";
    $sql_tomorrow = "SELECT * FROM tasks WHERE id_user = ? AND TO_DAYS(NOW()) + 1 - TO_DAYS(deadline) = 0";
    $sql_expired = "SELECT * FROM tasks WHERE id_user = ? AND completed = 0 AND TO_DAYS(NOW()) - TO_DAYS(deadline) > 0";
    $projects = get_res($link, $sql1, $id);
    $tasks_all = get_res($link, $sql2, $id);
    switch ($sort) {
        case 'today': {
            $tasks = get_res($link, $sql_today, $id);
            break;
        }
        case 'tomorrow': {
            $tasks = get_res($link, $sql_tomorrow, $id);
            break;
        }
        case 'expired': {
            $tasks = get_res($link, $sql_expired, $id);
            break;
        }
        default:
            $tasks = $tasks_all;
    }
}
