<?php
require_once 'init.php';

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = "SELECT * FROM tasks WHERE completed = 0 AND TO_DAYS(NOW()) - TO_DAYS(deadline) = 0";

$res = mysqli_query($link, $sql);

if ($res && mysqli_num_rows($res)) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $res = mysqli_query($link, "SELECT * FROM users");

    if ($res && mysqli_num_rows($res)) {
        $users = mysqli_fetch_all($res, MYSQLI_ASSOC);

        foreach ($users as $user) {
            $tasks_message = [];
            $name_user = $user['name'];
            foreach ($tasks as $task) {
                if ($task['id_user'] === $user['id']) {
                    $tasks_message[] = $task;
                }
            }
            if (!empty($tasks_message)) {
                $message = new Swift_Message();
                $message->setSubject("Уведомление от сервиса «Дела в порядке»");
                $message->setFrom(['keks@phpdemo.ru' => 'Дела в порядке']);
                $message->setTo([$user['email'] => $user['name']]);

                $msg_content = include_template('notify.php', ['tasks_message' => $tasks_message, 'name_user' => $name_user]);
                $message->setBody($msg_content, 'text/html');

                $result = $mailer->send($message);

                if ($result) {
                    print("Рассылка успешно отправлена");
                } else {
                    print("Не удалось отправить рассылку: " . $logger->dump());
                }
            }
        }
    }
}
