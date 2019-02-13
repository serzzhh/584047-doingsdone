INSERT INTO projects (name, id_user)
VALUES
  ("Входящие", 2),
  ("Учеба", 1),
  ("Работа", 2),
  ("Домашние дела", 1),
  ("Авто", 2);

INSERT INTO users (name, email, password)
VALUES
  ("player", "player@ya.ru", "11111"),
  ("pasha24", "pasha24@mail.ru", "12345");

INSERT INTO tasks (name, completed, deadline, id_user, id_project)
VALUES
  ("Собеседование в IT компании", 0, "2019-12-01", 2, 3),
  ("Выполнить тестовое задание", 0, "2019-12-25", 2, 3),
  ("Сделать задание первого раздела", 1, "2019-12-21", 1, 2),
  ("Встреча с другом", 0, "2019-12-22", 2, 1),
  ("Купить корм для кота", 0, "2019-02-08", 1, 4),
  ("Заказать пиццу", 0, null, 1, 4);

SELECT * FROM projects WHERE id_user = 1;
SELECT * FROM tasks WHERE id_project = 4;

UPDATE tasks SET completed = 1
  WHERE id_user = 2 && name = "Встреча с другом";

UPDATE tasks SET name = "Сделать задание второго раздела"
  WHERE id = 3;
