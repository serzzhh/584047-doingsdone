<?php
require_once 'init.php';

$page_content = include_template('reg.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup']['email']) && isset($_POST['signup']['name']) && isset($_POST['signup']['password'])) {
    $form = $_POST['signup'];
    $errors = [];
    $form['name'] = trim($form['name']);

    $req_fields = ['email', 'password', 'name'];
    $form['email'] = trim(mysqli_real_escape_string($link, $form['email']));

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле надо заполнить";
        }
    }

    if (iconv_strlen($form['name']) > 64 && !isset($errors['name'])) {
        $errors['name'] = 'Введите не более 64 символов';
    }

    if (iconv_strlen($form['email']) > 128) {
        $errors['email'] = 'Введите не более 128 символов';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) && !isset($errors['email'])) {
        $errors['email'] = "E-mail введён некорректно";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email']]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        if ($count) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (iconv_strlen($form['password']) > 64 || iconv_strlen($form['password']) < 6) {
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
