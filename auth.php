<?php
require_once 'init.php';
if (isset($_SESSION['user'])) {
header("Location: /");
}

$page_content = include_template('auth.php', []);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $form = [];
    $req_fields = ['email', 'password'];
    foreach ($req_fields as $field) {
        if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) {
            $form[$field] = trim($_POST[$field]);
        } else {
            $errors[$field] = 'Это поле необходимо заполнить';
        }
    }

    if (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail введён некорректно";
    } elseif (!isset($errors['email'])) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email']]);
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
    }

    if (count($errors)) {
        $page_content = include_template('auth.php', ['form' => $form, 'errors' => $errors]);
    } else {
        header("Location: /");
    }
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'projects' => [],
    'title'      => 'Doingsdone',
]);

print($layout_content);
