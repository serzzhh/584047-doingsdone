<?php
require_once 'init.php';

$page_content = include_template('reg.php', []);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST['signup'];
    $errors = [];

    $req_fields = ['email', 'password', 'name'];
    $email = mysqli_real_escape_string($link, $form['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "E-mail введён некорректно";
    }

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле надо заполнить";
        }
    }

    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors[] = 'Пользователь с этим email уже зарегистрирован';
        }
        else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users ( email, name, password) VALUES (?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password]);
            $res = mysqli_stmt_execute($stmt);
        }

        if ($res && empty($errors)) {
            header("Location: /");
            exit();
        }
    }
    else {
      $page_content = include_template('reg.php', ['errors' => $errors, 'form' => $form]);
    }
}

$layout_content = include_template('layout-unauth.php', [
    'content'    => $page_content,
    'title'      => 'Doingsdone | Регистрация'
]);

print($layout_content);
