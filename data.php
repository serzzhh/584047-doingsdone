<?php
$show_completed =  isset($_GET['show_completed']) ? $_GET['show_completed'] : 0;
$check =  isset($_GET['check']) ? $_GET['check'] : 0;

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user']['id'];
    $sql1 = "SELECT * FROM projects WHERE id_user = ?";
    $sql2 = "SELECT * FROM tasks WHERE id_user = ?";
    $projects = get_res($link, $sql1, $id);
    $tasks = get_res($link, $sql2, $id);
}
