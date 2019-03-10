<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
  <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

  <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
      <a href="/" class="tasks-switch__item <?php if ($sort === ''): ?>tasks-switch__item--active <?php endif; ?>">Все задачи</a>
      <a href="/?sort=today" class="tasks-switch__item <?php if ($sort === 'today'): ?>tasks-switch__item--active <?php endif; ?>">Повестка дня</a>
      <a href="/?sort=tomorrow" class="tasks-switch__item <?php if ($sort === 'tomorrow'): ?>tasks-switch__item--active <?php endif; ?>">Завтра</a>
      <a href="/?sort=expired" class="tasks-switch__item <?php if ($sort === 'expired'): ?>tasks-switch__item--active <?php endif; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
      <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_completed): ?>checked<?php endif; ?>>
      <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
  <?php if (empty($tasks) && isset($_GET['search'])): ?>
    <p>Ничего не найдено по вашему запросу</p>
  <?php endif; ?>
  <?php if (http_response_code() === 200): ?>
    <?php foreach ($tasks as $key => $value): ?>
      <?php if ($show_completed || !$value["completed"]): ?>
        <?php if (!isset($_GET["project_id"]) || $_GET["project_id"] == $value["id_project"]): ?>
        <tr class="tasks__item task <?php if ($value["completed"]): ?>task--completed <?php endif; ?><?php if (count_hours($value["deadline"]) <= 24 && !is_null($value["deadline"])): ?>task--important<?php endif; ?>">
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$value["id"];?>">
                    <span class="checkbox__text"><?=htmlspecialchars($value["name"]);?></span>
                </label>
            </td>
            <td class="task__file">
              <?php if (!empty($value["file"])): ?>
                <a class="download-link" href="<?=$value["file"]; ?>"></a>
              <?php endif; ?>
            </td>

            <td class="task__date"><?=isset($value["deadline"]) ? date("d.m.Y", strtotime($value["deadline"])) : "";?></td>
        </tr>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</table>
