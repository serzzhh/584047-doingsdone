<?php
  $show_complete_tasks = rand(0, 1);
  $projects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
  $tasks = [
     [
       "text" => "Собеседование в IT компании",
       "date" => "01.12.2019",
       "project" => "Работа",
       "completed" => false
     ],
     [
       "text" => "Выполнить тестовое задание",
       "date" => "25.12.2019",
       "project" => "Работа",
       "completed" => false
     ],
     [
       "text" => "Сделать задание первого раздела",
       "date" => "21.12.2019",
       "project" => "Учеба",
       "completed" => true
     ],
     [
       "text" => "Встреча с другом",
       "date" => "22.12.2019",
       "project" => "Входящие",
       "completed" => false
     ],
     [
       "text" => "Купить корм для кота",
       "date" => "08.02.2019",
       "project" => "Домашние дела",
       "completed" => false
     ],
     [
       "text" => "Заказать пиццу",
       "date" => null,
       "project" => "Домашние дела",
       "completed" => false
     ]
  ];
?>
