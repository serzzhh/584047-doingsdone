<?php
require_once 'init.php';

$_SESSION = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($link, $form['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !isset($errors['email'])) {
        $errors['email'] = "E-mail введён некорректно";
    }
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = $res->fetch_array(MYSQLI_ASSOC);
    if (!count($errors) && isset($user)) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        if (!isset($errors['email'])) {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (count($errors)) {
        $page_content = include_template('auth.php', ['form' => $form, 'errors' => $errors]);
    } else {
        header("Location: /");
        exit();
    }
} else {
    if (isset($_SESSION['user'])) {
        $page_content = include_template('main.php', ['user' => $_SESSION['user']]);
    } else {
        $page_content = include_template('auth.php', []);
    }
}


$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'projects' => [],
    'title'      => 'Doingsdone',
]);

print($layout_content);
