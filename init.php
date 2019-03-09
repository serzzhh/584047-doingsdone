<?php
require_once 'config/db.php';
$link = mysqli_init();
mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
$con = mysqli_real_connect($link, $db['host'], $db['user'], $db['password'], $db['dbname']);

if (!$con) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    session_start();
    date_default_timezone_set('Europe/Moscow');
    require_once 'vendor/autoload.php';
    require_once 'functions.php';
    require_once 'mysql_helper.php';
    mysqli_set_charset($link, "utf8");
    $id = $_SESSION['user']['id'];
    $sort =  isset($_GET['sort']) ? $_GET['sort'] : '';
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
