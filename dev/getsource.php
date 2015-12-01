<?php
require_once('./config/require.php');
// запрос инфы о доступных языках
//$r = $mysqli_->query('SELECT L.LangID, L.Desc, L.Ext FROM lang L where L.langID=1 or L.langID>6 order by LangID');
$r = $mysqli_->query('SELECT U.Source FROM User U where U.id='.$_GET['userid']);
$f= $r->fetch_object();
$r->close();
// конец запроса инфы о доступных языках
?><div><?=$f->Source?></div>
