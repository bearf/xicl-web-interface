<?php
require_once('./config/require.php');
// ������ ���� � ��������� ������
//$r = $mysqli_->query('SELECT L.LangID, L.Desc, L.Ext FROM lang L where L.langID=1 or L.langID>6 order by LangID');
$r = $mysqli_->query('SELECT U.Source FROM User U where U.id='.$_GET['userid']);
$f= $r->fetch_object();
$r->close();
// ����� ������� ���� � ��������� ������
?><div><?=$f->Source?></div>
