<!DOCTYPE html>
<html lang="ru"><head>

    <!-- META -->
    <meta charset="windows-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="спортивное программирование, олимпиада по информатике, турнир по программированию, контест, олимпиада, программирование, информатика, задача, ICL, турнир" />
    
    <!-- TITLE -->    
    <title>XI ICL - XI Открытый командный турнир по программированию среди студентов и школьников Республики Татарстан</title>
    
    <!-- FAVICON -->
    
    <!-- CSS -->
    <link href="./../css/style.2015.02.09.12.11.css" type="text/css" rel="stylesheet" />
    <link href="./../css/markup.2015.02.09.12.11.css" type="text/css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="./css/content.2011.04.14.css" />
    <link type="text/css" rel="stylesheet" href="./css/validator/validator.2010.01.12.css" />
    <link type="text/css" rel="stylesheet" href="./css/print.2011.03.23.css" />

    <!-- JS LIBS -->  
    <script src="http://www.google.com/jsapi"></script>  
    <script type="text/javascript">  
  
        // Load jQuery  
        google.load('jquery', '1.4.4'); // latest version
        google.setOnLoadCallback(function() {  
            // Your code goes here.  
        });  
         
    </script><!-- end of JS LIBS -->
    
    <!-- JS MODULES -->
    <script type="text/javascript" src="./js/monitor.js"></script>
    <script type="text/javascript" src="./js/utils.2011.04.01.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.DOM.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.utils.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.core.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.events.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.controls.js"></script>
    <script type="text/javascript" src="./js/validator/KIR.validator.js"></script>
    <!-- end of JS MODULES -->

</head>
<body>
<form name="editinfo" action="./order.php" method="post">
<h3 style="margin-bottom:16px;">команда &quot;<?=_data('orderteamname')?>&quot;&nbsp;::<a href=".">на главную</a><?php if (1 == $is_admin):?>&nbsp;&nbsp;::<a href="./orders.php">к списку заявок</a>&nbsp;&nbsp;::<a href="./order.php<?=array_key_exists('orderid', $_GET) ? '?orderid='.$_GET['orderid'] : ''?>">обычный вариант</a><?php endif; ?></h3>

<table class="enter">
    <tr><td class="label"><label>город</label></td>
        <td id="city-messages"><?=_data('city')?></td></tr>
    <tr><td id="studyplace-messages"><label for="studyplace">название учебного заведения</td>
        <td><?=_data('studyplace')?></td></tr>
    <tr><td id="address-messages"><label for="address">адрес учебного заведения</label></td>
        <td><?=_data('address')?></td></tr>
    <tr><td id="phone-messages"><label for="phone">телефон учебного заведения</label></td>
        <td><?=_data('phone')?></td></tr>
    <tr><td id="fax-messages"><label for="fax">факс учебного заведения</label></td>
        <td><?=_data('fax')?></td></tr>
    <tr><td id="contactname-messages"><label for="contactname">контактное лицо</td></td>
        <td><?=_data('contactname')?></td></tr>
    <tr><td id="contactphone-messages"><label for="contactphone">телефон контактного лица</label></td>
        <td><?=_data('contactphone')?></td></tr>
    <tr><td id="contactmail-messages"><label for="contactmail">e-mail контактного лица</label></td>
        <td><?=_data('contactmail')?></td></tr>
</table>

<div id="contestant1-outer">
<h3>Участник #1</h3>
<div id="contestant1" class="inner"><table class="enter">
    <tr><td id="contestant1name-messages"><label for="contestant1name">ФИО</td></td>
        <td><?=_data('contestant1name')?></td></tr>
    <tr><td id="contestant1studyplace-messages"><label for="contestant1studyplace">место учебы</label></td>
        <td><?=_data('contestant1studyplace')?></td></tr>
    <tr><td id="contestant1classcourse-messages"><label for="contestant1classcourse">класс/курс</label></td>
        <td><?=_data('contestant1classcourse')?></td></tr>
    <tr><td id="contestant1birthdate-messages"><label for="contestant1birthdate">дата рождения</td></td>
        <td><?=_data('contestant1birthdate')?></td></tr>
    <tr><td id="contestant1address-messages"><label for="contestant1address">адрес</label></td>
        <td><?=_data('contestant1address')?></td></tr>
    <tr><td id="contestant1inn-messages"><label for="contestant1inn">инн</label></td>
        <td><?=_data('contestant1inn')?></td></tr>
    <tr><td id="contestant1passportno-messages"><label for="contestant1passportno">серия и no паспорта</td></td>
        <td><?=_data('contestant1passportno')?></td></tr>
    <tr><td id="contestant1passportplace-messages"><label for="contestant1passportplace">кем выдан паспорт</label></td>
        <td><?=_data('contestant1passportplace')?></td></tr>
    <tr><td id="contestant1passportdate-messages"><label for="contestant1passportdate">когда выдан паспорт</label></td>
        <td><?=_data('contestant1passportdate')?></td></tr>
</table></div>
</div>

<div id="contestant2-outer">
<h3>Участник #2</h3>
<div id="contestant2" class="inner"><table class="enter">
    <tr><td id="contestant2name-messages"><label for="contestant2name">ФИО</td></td>
        <td><?=_data('contestant2name')?></td></tr>
    <tr><td id="contestant2studyplace-messages"><label for="contestant2studyplace">место учебы</label></td>
        <td><?=_data('contestant2studyplace')?></td></tr>
    <tr><td id="contestant2classcourse-messages"><label for="contestant2classcourse">класс/курс</label></td>
        <td><?=_data('contestant2classcourse')?></td></tr>
    <tr><td id="contestant2birthdate-messages"><label for="contestant2birthdate">дата рождения</td></td>
        <td><?=_data('contestant2birthdate')?></td></tr>
    <tr><td id="contestant2address-messages"><label for="contestant2address">адрес</label></td>
        <td><?=_data('contestant2address')?></td></tr>
    <tr><td id="contestant2inn-messages"><label for="contestant2inn">инн</label></td>
        <td><?=_data('contestant2inn')?></td></tr>
    <tr><td id="contestant2passportno-messages"><label for="contestant2passportno">серия и no паспорта</td></td>
        <td><?=_data('contestant2passportno')?></td></tr>
    <tr><td id="contestant2passportplace-messages"><label for="contestant2passportplace">кем выдан паспорт</label></td>
        <td><?=_data('contestant2passportplace')?></td></tr>
    <tr><td id="contestant2passportdate-messages"><label for="contestant2passportdate">когда выдан паспорт</label></td>
        <td><?=_data('contestant2passportdate')?></td></tr>
</table></div>
</div>

<div id="contestant3-outer">
<h3>Участник #3</h3>
<div id="contestant3" class="inner"><table class="enter">
    <tr><td id="contestant3name-messages"><label for="contestant3name">ФИО</td></td>
        <td><?=_data('contestant3name')?></td></tr>
    <tr><td id="contestant3studyplace-messages"><label for="contestant3studyplace">место учебы</label></td>
        <td><?=_data('contestant3studyplace')?></td></tr>
    <tr><td id="contestant3classcourse-messages"><label for="contestant3classcourse">класс/курс</label></td>
        <td><?=_data('contestant3classcourse')?></td></tr>
    <tr><td id="contestant3birthdate-messages"><label for="contestant3birthdate">дата рождения</td></td>
        <td><?=_data('contestant3birthdate')?></td></tr>
    <tr><td id="contestant3address-messages"><label for="contestant3address">адрес</label></td>
        <td><?=_data('contestant3address')?></td></tr>
    <tr><td id="contestant3inn-messages"><label for="contestant3inn">инн</label></td>
        <td><?=_data('contestant3inn')?></td></tr>
    <tr><td id="contestant3passportno-messages"><label for="contestant3passportno">серия и no паспорта</td></td>
        <td><?=_data('contestant3passportno')?></td></tr>
    <tr><td id="contestant3passportplace-messages"><label for="contestant3passportplace">кем выдан паспорт</label></td>
        <td><?=_data('contestant3passportplace')?></td></tr>
    <tr><td id="contestant3passportdate-messages"><label for="contestant3passportdate">когда выдан паспорт</label></td>
        <td><?=_data('contestant3passportdate')?></td></tr>
</table></div>
</div>

<div id="head-outer">
<h3>Руководитель команды</h3>
<div id="head" class="inner"><table class="enter">
    <tr><td id="headname-messages"><label for="headname">ФИО</td></td>
        <td><?=_data('headname')?></td></tr>
    <tr><td id="headpost-messages"><label for="headpost">должность</label></td>
        <td><?=_data('headpost')?></td></tr>
    <tr><td id="headbirthdate-messages"><label for="headbirthdate">дата рождения</td></td>
        <td><?=_data('headbirthdate')?></td></tr>
    <tr><td id="headaddress-messages"><label for="headaddress">адрес</label></td>
        <td><?=_data('headaddress')?></td></tr>
    <tr><td id="headinn-messages"><label for="headinn">инн</label></td>
        <td><?=_data('headinn')?></td></tr>
    <tr><td id="headpassportno-messages"><label for="headpassportno">серия и no</td></td>
        <td><?=_data('headpassportno')?></td></tr>
    <tr><td id="headpassportplace-messages"><label for="headpassportplace">кем выдан</label></td>
        <td><?=_data('headpassportplace')?></td></tr>
    <tr><td id="headpassportdate-messages"><label for="headpassportdate">когда выдан</label></td>
        <td><?=_data('headpassportdate')?></td></tr>
</table></div>
</div>

<div id="coach-outer">
<h3>Тренер команды</h3>
<div id="coach" class="inner"><table class="enter">
    <tr><td id="coachname-messages"><label for="coachname">ФИО</td></td>
        <td><?=_data('coachname')?></td></tr>
    <tr><td id="coachpost-messages"><label for="coachpost">должность</label></td>
        <td><?=_data('coachpost')?></td></tr>
    <tr><td id="coachbirthdate-messages"><label for="coachbirthdate">дата рождения</td></td>
        <td><?=_data('coachbirthdate')?></td></tr>
    <tr><td id="coachaddress-messages"><label for="coachaddress">адрес</label></td>
        <td><?=_data('coachaddress')?></td></tr>
    <tr><td id="coachinn-messages"><label for="coachinn">инн</label></td>
        <td><?=_data('coachinn')?></td></tr>
    <tr><td id="coachpassportno-messages"><label for="coachpassportno">серия и no</td></td>
        <td><?=_data('coachpassportno')?></td></tr>
    <tr><td id="coachpassportplace-messages"><label for="coachpassportplace">кем выдан</label></td>
        <td><?=_data('coachpassportplace')?></td></tr>
    <tr><td id="coachpassportdate-messages"><label for="coachpassportdate">когда выдан</label></td>
        <td><?=_data('coachpassportdate')?></td></tr>
</table></div>                                                     
</div>

</form>
</body>
</html>

