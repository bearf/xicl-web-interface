<?php function menu($content_tile_name) { ?>
<?php
global $is_admin;
global $contestname;
// Назначение: отображение меню
// Использование: должен подключаться в каждой странице сайта
if (_settings_show_tournament_menu && 'tournament' == get_site_branch($content_tile_name)): // страницы tournament ?>        
<ul id="menu">
    <li<?='index' == $content_tile_name ? ' class="active"' : ''?> style="border:none;">
        <a href="./index.php">главная</a>
        <span>::<a href="./index.php#about">о турнире</a>, ::<a href="./index.php#notes">новости</a>, ::<a href="./index.php#timetable">расписание</a>, ::<a href="./index.php#partners">партнеры</a></span>
    </li>
    <li<?='rules' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./rules.php">правила</a>
        <span>::<a href="./rules.php#team">команда</a>, ::<a href="./rules.php#problems">задачи</a>, ::<a href="./rules.php#workplace">рабочие&nbsp;места</a>,<br /> ::<a href="./rules.php#behaviour">поведение&nbsp;команд</a>, ::<a href="./rules.php#testing">проверка</a>, ::<a href="./rules.php#standing">результаты</a></span>
    </li>
    <li<?='qualification' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./qualification.php">отборочный тур</a>
        <span>::<a href="./qualification.php#common">проведение</a>, ::<a href="./qualification.php#quotes">квоты</a>, ::<a href="./qualification.php#wildcard">wild-card</a></span>
    <li<?='foreign' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./foreign.php">иногородним</a>
        <span>::<a href="./foreign.php#residence">проживание</a>, ::<a href="./foreign.php#transport">транспорт</a>, ::<a href="./foreign.php#food">питание</a>, ::<a href="./foreign.php#invitation">приглашение</a></span>
    </li>
    <li<?='history' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./history.php">история &amp; архив</a>
        <span>::<a href="./history.php#about">о турнире</a>, ::<a href="./history.php#2013">2013</a>, ::<a href="./history.php#2014">2014</a>, ::<a href="./history.php#2015">2015</a>, ::<a href="./history.php#2016">2016</a></span>
    </li>
    <!--li<?='faq' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./faq.php">вопрос-ответ</a>
        <span>::прямая связь</span>
    </li-->
    <li>
        <a href="http://www.icl.ru">ОАО "ICL-КПО ВС"</a>
        <span>::главная страница компании</span>
    </li>
</ul>
<h2 title="решать задачи">::<a href="./problemset.php">contest</a></h2>
<p class="hint">В разделе &quot;contest&quot; собраны задачи прошлых Турниров ICL, а также тренировочные задачи. Воспользовавшись веб-интерфейсом, вы можете решать их в процессе тренировок. Решение задач Отборочного тура также производится в этом разделе</p>
<?php else: // страницы contest ?>
<ul id="menu">
    <li<?='problemset' == $content_tile_name || 'problem' == $content_tile_name ? ' class="active"' : ''?> style="border:none;">
        <a href="./problemset.php">задачи</a>
        <span>::список всех задач контеста</span>
    </li>
    <?php if ('нет контеста' != $contestname): // todo: делать это по умному ?>
    <li<?='submit' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./submit.php">сдача решений</a>
        <span>::отправить решение на автоматическую проверку</span>
    </li>
    <?php else: ?>
    <li<?='submit' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./submit.php" class="disabled" onclick="return false;">сдача решений</a>
        <span>::нет активного контеста</span>
    </li>
    <?php endif; ?>
<?php if (_permission_allow_print && 'нет контеста' != $contestname): // если разрешена печать заданий - показываем ?>          
    <li<?='printform' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./printform.php">печать решений</a>
        <span>::отправить исходный текст на распечатку</span>
    </li>
<?php endif; //конец проверки возможности печати заданий ?>          
    <li<?='status' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./status.php">статус посылок</a>
        <span>::получить результаты проверки решений</span>
    </li>
    <li<?='standing' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./standing.php">результаты</a>
        <span>::текущая таблица соревнований</span>
    </li>
    <li<?='contest' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./contest.php">контесты</a>
        <span>::список всех контестов в системе</span>
    </li>
<?php if ($is_admin == 1): // проверка на admin-mode ?>        
    <li<?='admininfo' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./admininfo.php">администрирование</a>
        <span>::<a href="./admininfo.php#new_questions">новые вопросы</a>, ::<a href="./admininfo.php#old_questions">старые вопросы</a>, ::<a href="./admininfo.php#print_queue">печать</a></span>
    </li>
<?php endif; // конец проверки на admin-mode ?>
    <li>
        <a href="http://www.icl.ru">ОАО "ICL-КПО ВС"</a>
        <span>::главная страница компании</span>
    </li>
</ul>
    <?php if (_settings_show_tournament_menu): ?>
    <h2 title="читать о турнире">::<a href="./index.php">tournament</a></h2>
    <p class="hint">В разделе &quot;tournament&quot; находится общая информация о Турнире ICL, выложены его правила и архивы прошлых лет. Отправление   регистрационных заявок также производится в этом разделе.</p>
    <?php endif; ?>
<?php endif // конец проверки на текущую ветку сайта ?>
<?php } ?>
