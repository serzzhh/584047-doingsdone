<?php
require_once 'init.php';

$page_content = include_template('reg.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST['signup'];
    $errors = [];

    $req_fields = ['email', 'password', 'name'];
    $email = mysqli_real_escape_string($link, $form['email']);

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле надо заполнить";
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail введён некорректно";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$email]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        if ($count) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users ( email, name, password) VALUES (?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password]);
        $res = mysqli_stmt_execute($stmt);
        if ($res && empty($errors)) {
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
