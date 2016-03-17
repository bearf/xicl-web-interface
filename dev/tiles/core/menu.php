<?php function menu($content_tile_name) { ?>
<?php
global $is_admin;
global $contestname;
// ����������: ����������� ����
// �������������: ������ ������������ � ������ �������� �����
if (_settings_show_tournament_menu && 'tournament' == get_site_branch($content_tile_name)): // �������� tournament ?>        
<ul id="menu">
    <li<?='index' == $content_tile_name ? ' class="active"' : ''?> style="border:none;">
        <a href="./index.php">�������</a>
        <span>::<a href="./index.php#about">� �������</a>, ::<a href="./index.php#notes">�������</a>, ::<a href="./index.php#timetable">����������</a>, ::<a href="./index.php#partners">��������</a></span>
    </li>
    <li<?='rules' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./rules.php">�������</a>
        <span>::<a href="./rules.php#team">�������</a>, ::<a href="./rules.php#problems">������</a>, ::<a href="./rules.php#workplace">�������&nbsp;�����</a>,<br /> ::<a href="./rules.php#behaviour">���������&nbsp;������</a>, ::<a href="./rules.php#testing">��������</a>, ::<a href="./rules.php#standing">����������</a></span>
    </li>
    <li<?='qualification' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./qualification.php">���������� ���</a>
        <span>::<a href="./qualification.php#common">����������</a>, ::<a href="./qualification.php#quotes">�����</a>, ::<a href="./qualification.php#wildcard">wild-card</a></span>
    <li<?='foreign' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./foreign.php">�����������</a>
        <span>::<a href="./foreign.php#residence">����������</a>, ::<a href="./foreign.php#transport">���������</a>, ::<a href="./foreign.php#food">�������</a>, ::<a href="./foreign.php#invitation">�����������</a></span>
    </li>
    <li<?='history' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./history.php">������� &amp; �����</a>
        <span>::<a href="./history.php#about">� �������</a>, ::<a href="./history.php#2013">2013</a>, ::<a href="./history.php#2014">2014</a>, ::<a href="./history.php#2015">2015</a>, ::<a href="./history.php#2016">2016</a></span>
    </li>
    <!--li<?='faq' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./faq.php">������-�����</a>
        <span>::������ �����</span>
    </li-->
    <li>
        <a href="http://www.icl.ru">��� "ICL-��� ��"</a>
        <span>::������� �������� ��������</span>
    </li>
</ul>
<h2 title="������ ������">::<a href="./problemset.php">contest</a></h2>
<p class="hint">� ������� &quot;contest&quot; ������� ������ ������� �������� ICL, � ����� ������������� ������. ���������������� ���-�����������, �� ������ ������ �� � �������� ����������. ������� ����� ����������� ���� ����� ������������ � ���� �������</p>
<?php else: // �������� contest ?>
<ul id="menu">
    <li<?='problemset' == $content_tile_name || 'problem' == $content_tile_name ? ' class="active"' : ''?> style="border:none;">
        <a href="./problemset.php">������</a>
        <span>::������ ���� ����� ��������</span>
    </li>
    <?php if ('��� ��������' != $contestname): // todo: ������ ��� �� ������ ?>
    <li<?='submit' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./submit.php">����� �������</a>
        <span>::��������� ������� �� �������������� ��������</span>
    </li>
    <?php else: ?>
    <li<?='submit' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./submit.php" class="disabled" onclick="return false;">����� �������</a>
        <span>::��� ��������� ��������</span>
    </li>
    <?php endif; ?>
<?php if (_permission_allow_print && '��� ��������' != $contestname): // ���� ��������� ������ ������� - ���������� ?>          
    <li<?='printform' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./printform.php">������ �������</a>
        <span>::��������� �������� ����� �� ����������</span>
    </li>
<?php endif; //����� �������� ����������� ������ ������� ?>          
    <li<?='status' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./status.php">������ �������</a>
        <span>::�������� ���������� �������� �������</span>
    </li>
    <li<?='standing' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./standing.php">����������</a>
        <span>::������� ������� ������������</span>
    </li>
    <li<?='contest' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./contest.php">��������</a>
        <span>::������ ���� ��������� � �������</span>
    </li>
<?php if ($is_admin == 1): // �������� �� admin-mode ?>        
    <li<?='admininfo' == $content_tile_name ? ' class="active"' : ''?>>
        <a href="./admininfo.php">�����������������</a>
        <span>::<a href="./admininfo.php#new_questions">����� �������</a>, ::<a href="./admininfo.php#old_questions">������ �������</a>, ::<a href="./admininfo.php#print_queue">������</a></span>
    </li>
<?php endif; // ����� �������� �� admin-mode ?>
    <li>
        <a href="http://www.icl.ru">��� "ICL-��� ��"</a>
        <span>::������� �������� ��������</span>
    </li>
</ul>
    <?php if (_settings_show_tournament_menu): ?>
    <h2 title="������ � �������">::<a href="./index.php">tournament</a></h2>
    <p class="hint">� ������� &quot;tournament&quot; ��������� ����� ���������� � ������� ICL, �������� ��� ������� � ������ ������� ���. �����������   ��������������� ������ ����� ������������ � ���� �������.</p>
    <?php endif; ?>
<?php endif // ����� �������� �� ������� ����� ����� ?>
<?php } ?>
