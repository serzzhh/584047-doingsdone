<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks): ?>checked<?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
  <?php if (isset($_GET["project_id"])): ?>
   <?php $id_count = get_res($link, "SELECT COUNT(*) AS count FROM tasks WHERE id_project = ?", $_GET["project_id"]);
    if ($id_count[0]["count"] < 1): ?>
      <p>ошибка 404</p>
      <?php http_response_code(404); ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php if (http_response_code() === 200): ?>
    <?php foreach ($tasks as $key => $value): ?>
      <?php if ($show_complete_tasks || !$value["completed"]): ?>
        <?php if (!isset($_GET["project_id"]) || $_GET["project_id"] == $value["id_project"]): ?>
        <tr class="tasks__item task <?php if ($value["completed"]): ?>task--completed<?php endif; ?><?php if (count_hours($value["deadline"]) <= 24): ?>task--important<?php endif; ?>">
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                    <span class="checkbox__text"><?=htmlspecialchars($value["name"]);?></span>
                </label>
            </td>

            <td class="task__file">
                <a class="download-link" href="#">Home.psd</a>
            </td>

            <td class="task__date"><?=$value["deadline"];?></td>
        </tr>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</table>
