<!DOCTYPE html>
<html lang="ru"><head>

    <!-- META -->
    <meta charset="windows-1251" />
    <meta name="Author" content="����� ��������" />
    <meta name="Keywords" content="���������� ����������������, ��������� �� �����������, ������ �� ����������������, �������, ���������, ����������������, �����������, ������, ICL, ������" />
    
    <!-- TITLE -->    
    <title>XI ICL - XI �������� ��������� ������ �� ���������������� ����� ��������� � ���������� ���������� ���������</title>
    
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
<h3 style="margin-bottom:16px;">������� &quot;<?=_data('orderteamname')?>&quot;&nbsp;::<a href=".">�� �������</a><?php if (1 == $is_admin):?>&nbsp;&nbsp;::<a href="./orders.php">� ������ ������</a>&nbsp;&nbsp;::<a href="./order.php<?=array_key_exists('orderid', $_GET) ? '?orderid='.$_GET['orderid'] : ''?>">������� �������</a><?php endif; ?></h3>

<table class="enter">
    <tr><td class="label"><label>�����</label></td>
        <td id="city-messages"><?=_data('city')?></td></tr>
    <tr><td id="studyplace-messages"><label for="studyplace">�������� �������� ���������</td>
        <td><?=_data('studyplace')?></td></tr>
    <tr><td id="address-messages"><label for="address">����� �������� ���������</label></td>
        <td><?=_data('address')?></td></tr>
    <tr><td id="phone-messages"><label for="phone">������� �������� ���������</label></td>
        <td><?=_data('phone')?></td></tr>
    <tr><td id="fax-messages"><label for="fax">���� �������� ���������</label></td>
        <td><?=_data('fax')?></td></tr>
    <tr><td id="contactname-messages"><label for="contactname">���������� ����</td></td>
        <td><?=_data('contactname')?></td></tr>
    <tr><td id="contactphone-messages"><label for="contactphone">������� ����������� ����</label></td>
        <td><?=_data('contactphone')?></td></tr>
    <tr><td id="contactmail-messages"><label for="contactmail">e-mail ����������� ����</label></td>
        <td><?=_data('contactmail')?></td></tr>
</table>

<div id="contestant1-outer">
<h3>�������� #1</h3>
<div id="contestant1" class="inner"><table class="enter">
    <tr><td id="contestant1name-messages"><label for="contestant1name">���</td></td>
        <td><?=_data('contestant1name')?></td></tr>
    <tr><td id="contestant1studyplace-messages"><label for="contestant1studyplace">����� �����</label></td>
        <td><?=_data('contestant1studyplace')?></td></tr>
    <tr><td id="contestant1classcourse-messages"><label for="contestant1classcourse">�����/����</label></td>
        <td><?=_data('contestant1classcourse')?></td></tr>
    <tr><td id="contestant1birthdate-messages"><label for="contestant1birthdate">���� ��������</td></td>
        <td><?=_data('contestant1birthdate')?></td></tr>
    <tr><td id="contestant1address-messages"><label for="contestant1address">�����</label></td>
        <td><?=_data('contestant1address')?></td></tr>
    <tr><td id="contestant1inn-messages"><label for="contestant1inn">���</label></td>
        <td><?=_data('contestant1inn')?></td></tr>
    <tr><td id="contestant1passportno-messages"><label for="contestant1passportno">����� � no ��������</td></td>
        <td><?=_data('contestant1passportno')?></td></tr>
    <tr><td id="contestant1passportplace-messages"><label for="contestant1passportplace">��� ����� �������</label></td>
        <td><?=_data('contestant1passportplace')?></td></tr>
    <tr><td id="contestant1passportdate-messages"><label for="contestant1passportdate">����� ����� �������</label></td>
        <td><?=_data('contestant1passportdate')?></td></tr>
</table></div>
</div>

<div id="contestant2-outer">
<h3>�������� #2</h3>
<div id="contestant2" class="inner"><table class="enter">
    <tr><td id="contestant2name-messages"><label for="contestant2name">���</td></td>
        <td><?=_data('contestant2name')?></td></tr>
    <tr><td id="contestant2studyplace-messages"><label for="contestant2studyplace">����� �����</label></td>
        <td><?=_data('contestant2studyplace')?></td></tr>
    <tr><td id="contestant2classcourse-messages"><label for="contestant2classcourse">�����/����</label></td>
        <td><?=_data('contestant2classcourse')?></td></tr>
    <tr><td id="contestant2birthdate-messages"><label for="contestant2birthdate">���� ��������</td></td>
        <td><?=_data('contestant2birthdate')?></td></tr>
    <tr><td id="contestant2address-messages"><label for="contestant2address">�����</label></td>
        <td><?=_data('contestant2address')?></td></tr>
    <tr><td id="contestant2inn-messages"><label for="contestant2inn">���</label></td>
        <td><?=_data('contestant2inn')?></td></tr>
    <tr><td id="contestant2passportno-messages"><label for="contestant2passportno">����� � no ��������</td></td>
        <td><?=_data('contestant2passportno')?></td></tr>
    <tr><td id="contestant2passportplace-messages"><label for="contestant2passportplace">��� ����� �������</label></td>
        <td><?=_data('contestant2passportplace')?></td></tr>
    <tr><td id="contestant2passportdate-messages"><label for="contestant2passportdate">����� ����� �������</label></td>
        <td><?=_data('contestant2passportdate')?></td></tr>
</table></div>
</div>

<div id="contestant3-outer">
<h3>�������� #3</h3>
<div id="contestant3" class="inner"><table class="enter">
    <tr><td id="contestant3name-messages"><label for="contestant3name">���</td></td>
        <td><?=_data('contestant3name')?></td></tr>
    <tr><td id="contestant3studyplace-messages"><label for="contestant3studyplace">����� �����</label></td>
        <td><?=_data('contestant3studyplace')?></td></tr>
    <tr><td id="contestant3classcourse-messages"><label for="contestant3classcourse">�����/����</label></td>
        <td><?=_data('contestant3classcourse')?></td></tr>
    <tr><td id="contestant3birthdate-messages"><label for="contestant3birthdate">���� ��������</td></td>
        <td><?=_data('contestant3birthdate')?></td></tr>
    <tr><td id="contestant3address-messages"><label for="contestant3address">�����</label></td>
        <td><?=_data('contestant3address')?></td></tr>
    <tr><td id="contestant3inn-messages"><label for="contestant3inn">���</label></td>
        <td><?=_data('contestant3inn')?></td></tr>
    <tr><td id="contestant3passportno-messages"><label for="contestant3passportno">����� � no ��������</td></td>
        <td><?=_data('contestant3passportno')?></td></tr>
    <tr><td id="contestant3passportplace-messages"><label for="contestant3passportplace">��� ����� �������</label></td>
        <td><?=_data('contestant3passportplace')?></td></tr>
    <tr><td id="contestant3passportdate-messages"><label for="contestant3passportdate">����� ����� �������</label></td>
        <td><?=_data('contestant3passportdate')?></td></tr>
</table></div>
</div>

<div id="head-outer">
<h3>������������ �������</h3>
<div id="head" class="inner"><table class="enter">
    <tr><td id="headname-messages"><label for="headname">���</td></td>
        <td><?=_data('headname')?></td></tr>
    <tr><td id="headpost-messages"><label for="headpost">���������</label></td>
        <td><?=_data('headpost')?></td></tr>
    <tr><td id="headbirthdate-messages"><label for="headbirthdate">���� ��������</td></td>
        <td><?=_data('headbirthdate')?></td></tr>
    <tr><td id="headaddress-messages"><label for="headaddress">�����</label></td>
        <td><?=_data('headaddress')?></td></tr>
    <tr><td id="headinn-messages"><label for="headinn">���</label></td>
        <td><?=_data('headinn')?></td></tr>
    <tr><td id="headpassportno-messages"><label for="headpassportno">����� � no</td></td>
        <td><?=_data('headpassportno')?></td></tr>
    <tr><td id="headpassportplace-messages"><label for="headpassportplace">��� �����</label></td>
        <td><?=_data('headpassportplace')?></td></tr>
    <tr><td id="headpassportdate-messages"><label for="headpassportdate">����� �����</label></td>
        <td><?=_data('headpassportdate')?></td></tr>
</table></div>
</div>

<div id="coach-outer">
<h3>������ �������</h3>
<div id="coach" class="inner"><table class="enter">
    <tr><td id="coachname-messages"><label for="coachname">���</td></td>
        <td><?=_data('coachname')?></td></tr>
    <tr><td id="coachpost-messages"><label for="coachpost">���������</label></td>
        <td><?=_data('coachpost')?></td></tr>
    <tr><td id="coachbirthdate-messages"><label for="coachbirthdate">���� ��������</td></td>
        <td><?=_data('coachbirthdate')?></td></tr>
    <tr><td id="coachaddress-messages"><label for="coachaddress">�����</label></td>
        <td><?=_data('coachaddress')?></td></tr>
    <tr><td id="coachinn-messages"><label for="coachinn">���</label></td>
        <td><?=_data('coachinn')?></td></tr>
    <tr><td id="coachpassportno-messages"><label for="coachpassportno">����� � no</td></td>
        <td><?=_data('coachpassportno')?></td></tr>
    <tr><td id="coachpassportplace-messages"><label for="coachpassportplace">��� �����</label></td>
        <td><?=_data('coachpassportplace')?></td></tr>
    <tr><td id="coachpassportdate-messages"><label for="coachpassportdate">����� �����</label></td>
        <td><?=_data('coachpassportdate')?></td></tr>
</table></div>                                                     
</div>

</form>
</body>
</html>

