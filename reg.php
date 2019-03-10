<?php
require_once 'init.php';

$page_content = include_template('reg.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $form = [];
    $req_fields = ['email', 'password', 'name'];

    foreach ($req_fields as $field) {
        if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) {
            $form[$field] = trim($_POST[$field]);
        } else {
            $errors[$field] = 'Это поле необходимо заполнить';
        }
    }

    if (!isset($errors['name']) && iconv_strlen($form['name']) > 64) {
        $errors['name'] = 'Введите не более 64 символов';
    }

    if (!isset($errors['email']) && iconv_strlen($form['email']) > 128) {
        $errors['email'] = 'Введите не более 128 символов';
    } elseif (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail введён некорректно";
    } elseif (!isset($errors['email'])) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email']]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        if ($count) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (!isset($errors['password']) && (iconv_strlen($form['password']) > 64 || iconv_strlen($form['password']) < 6)) {
        $errors['password'] = 'Введите от 6 до 64 символов';
    }

    if (empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users ( email, name, password) VALUES (?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /");
            exit();
        }
    } else {
        $page_content = include_template('reg.php', ['errors' => $errors, 'form' => $form]);
    }
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'title'      => 'Doingsdone | Регистрация'
]);

print($layout_content);
