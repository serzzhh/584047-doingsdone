<?php
require_once 'init.php';

$_SESSION = [];

$page_content = include_template('guest.php', []);

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'title'      => 'Doingsdone',
    'background' => 'body-background'
]);

print($layout_content);
