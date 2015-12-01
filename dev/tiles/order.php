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

<script type="text/javascript">
jQuery(document).ready(function() {
    KIR.validator().parse('editinfo');
    <?php if('' == _data('fax')): ?>toggleFax(document.getElementById('toggle-fax'));<?php endif; ?>
    <?php if('' == _data('contactmail')): ?>toggleEmail(document.getElementById('toggle-email'));<?php endif; ?>
    <?php if('' == _data('headname')): ?>toggleBlock(document.getElementById('head-toggle-block'), 'head-outer');<?php endif; ?>
    <?php if('' == _data('coachname')): ?>toggleBlock(document.getElementById('coach-toggle-block'), 'coach-outer');<?php endif; ?>
    <?php if('-' == _data('contestant1inn')): ?>toggleINN(document.getElementById('contestant1-toggle-inn'), 'contestant1');<?php endif; ?>
    <?php if('-' == _data('contestant2inn')): ?>toggleINN(document.getElementById('contestant2-toggle-inn'), 'contestant2');<?php endif; ?>
    <?php if('-' == _data('contestant3inn')): ?>toggleINN(document.getElementById('contestant3-toggle-inn'), 'contestant3');<?php endif; ?>
    <?php if('-' == _data('headinn')): ?>toggleINN(document.getElementById('head-toggle-inn'), 'head');<?php endif; ?>
    <?php if('-' == _data('coachinn')): ?>toggleINN(document.getElementById('coach-toggle-inn'), 'coach');<?php endif; ?>
    <?php if('-' == _data('contestant1passportno')): ?>togglePassport(document.getElementById('contestant1-toggle-passport'), 'contestant1');<?php endif; ?>
    <?php if('-' == _data('contestant2passportno')): ?>togglePassport(document.getElementById('contestant2-toggle-passport'), 'contestant2');<?php endif; ?>
    <?php if('-' == _data('contestant3passportno')): ?>togglePassport(document.getElementById('contestant3-toggle-passport'), 'contestant3');<?php endif; ?>
    <?php if('-' == _data('headpassportno')): ?>togglePassport(document.getElementById('head-toggle-passport'), 'head');<?php endif; ?>
    <?php if('-' == _data('coachpassportno')): ?>togglePassport(document.getElementById('coach-toggle-passport'), 'coach');<?php endif; ?>
    <?php if(1 == $is_admin): ?>jQuery('input').attr('readonly', true);<?php endif;?>
});

function toggleBlock(element, blockid) {
    var block = jQuery('#' + blockid);
    var value = !block.hasClass('none');
    if (!value) {
        jQuery(element).html('���������');
    } else {
        jQuery(element).html('��������');
    }
    jQuery(block).find('input').each(function() {
        if (!jQuery(this).hasClass('disabled')) {
            KIR.core.namespace(this).control.disable(value);
        }
    });
    block.toggleClass('none');
}

function toggleINN(element, prefix) {
    var inn = KIR.core.namespace(prefix + 'inn').control;
    var jquery = jQuery(inn.get_html());
    if (jQuery(inn.get_html()).hasClass('disabled')) {
        jQuery(element).html('� ���� ��� ���');
        jQuery(inn.get_html()).val('');
    } else {
        jQuery(element).html('� ���� ���� ���');
        jQuery(inn.get_html()).val('-');
    }
    inn.disable(!jquery.hasClass('disabled'));
    jquery.toggleClass('disabled');
}

function toggleFax(element) {
    var fax = KIR.core.namespace('fax').control;
    var jquery = jQuery(fax.get_html());
    if (jQuery(fax.get_html()).hasClass('disabled')) {
        jQuery(element).html('���������');
    } else {
        jQuery(element).html('��������');
    }
    fax.disable(!jquery.hasClass('disabled'));
    jquery.toggleClass('disabled');
}

function toggleEmail(element) {
    var email = KIR.core.namespace('contactmail').control;
    var jquery = jQuery(email.get_html());
    if (jQuery(email.get_html()).hasClass('disabled')) {
        jQuery(element).html('���������');
    } else {
        jQuery(element).html('��������');
    }
    email.disable(!jquery.hasClass('disabled'));
    jquery.toggleClass('disabled');
}

function togglePassport(element, prefix) {
    var no = KIR.core.namespace(prefix + 'passportno').control;
    var date = KIR.core.namespace(prefix + 'passportdate').control;
    var place = KIR.core.namespace(prefix + 'passportplace').control;
    var value = !jQuery(no.get_html()).hasClass('disabled');
    if (!value) {
        jQuery(element).html('� ���� ��� ��������');
        jQuery(no.get_html()).val('');
        jQuery(date.get_html()).val('');
        jQuery(place.get_html()).val('');
    } else {
        jQuery(element).html('� ���� ���� �������');
        jQuery(no.get_html()).val('-');
        jQuery(date.get_html()).val('-');
        jQuery(place.get_html()).val('-');
}
    no.disable(value);
    date.disable(value);
    place.disable(value);
    jQuery(no.get_html()).toggleClass('disabled');
    jQuery(date.get_html()).toggleClass('disabled');
    jQuery(place.get_html()).toggleClass('disabled');
}

function toggleVisibility(element, block) {
    block = jQuery('#' + block);
    if (block.hasClass('hidden')) {
        jQuery(element).html('������');
    } else {
        jQuery(element).html('��������');
    }
    block.toggleClass('hidden');
}

function hideWarning() {
    window.setTimeout('jQuery(\'form\').removeClass(\'invalid-form\')', 3000);
}
</script>
</head>
<style>
form { margin:16px; }
h3 { margin:16px 0 0 0; }
.enter td { padding:0 8px 0 0; }
</style>
<body class="<?=1 == $is_admin ? 'admin-mode' : ''?>">
<form name="editinfo" action="./order.php" method="post">
<!--<validator target="editinfo" valid="function(element){jQuery('form').removeClass('invalid-form');}" invalid="function(element){jQuery('form').addClass('invalid-form'); hideWarning(); }" />-->
<h3 style="margin-bottom:16px;">������ ���������&nbsp;&nbsp;::<a href=".">�� �������</a><?php if (1 == $is_admin):?>&nbsp;&nbsp;::<a href="./orders.php">� ������ ������</a>&nbsp;&nbsp;::<a href="./order.print.php<?=array_key_exists('orderid', $_GET) ? '?orderid='.$_GET['orderid'] : ''?>">������� ��� ������</a><?php endif; ?></h3>
<?php if (1 != $is_admin): ?>
    <?php if (_has('message')): ?>
    <p class="message"><?=_data('message')?></p>
    <?php endif; ?>
    <p><strong>��, ��� ����� �����!</strong></p>
    <p>�� ����������, �� ��� ��� ��� �� ���������� ��� ����������. ��������, ���� � ������ �� ���������� ��� ��� ��� ��������, �� ��������������� ���� ����� ��������� (� �����, � ������� ��� ������������ ���� ����� �� ���� �������� ��� ��� - ���� ��� ���� ���������). ����� ���� � ���, ��� � ����� ������� ��� ������� ��� ������������ - ����� ����� ��������� ���� ����.</p>
    <p class="message">�� ��� ��� ��������� ���� ���������� ���������. �����? ��� ����� ������ ������� � ����������, � ��� - � ����������� ������, ���� �� ������� ���� ���������� ������ � ���. �����!</p>
<?php endif; ?>
<h3><?=1 == $is_admin ? _data('orderteamname') : $curteamname?><span class="marker validation-messages"> - ���� ����������� ����������� ����</span><?php if (1 != $is_admin): ?>&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="document.getElementById('submitbtn').click();">���������</a><? endif; ?></h3>
<table class="enter" style="margin-top:16px;">
    <tr><td class="label"><label>�����</label></td>
        <td id="city-messages"><input type="text" name="city" value="<?=_data('city')?>" /></td></tr>
        <!--<validator target="city" required=" - �����������!" maxlength=" - 20 �������� max,20" change="true" place="city-messages" />-->
</table>
<table class="enter">
    <tr><td class="label">&nbsp;</td>                                               
        <td id="studyplace-messages"><label for="studyplace">��������</td></td>
        <td id="address-messages"><label for="address">�����</label></td>
        <td id="phone-messages"><label for="phone">�������</label></td>
        <td id="fax-messages"><label for="fax">����</label></td></tr>
    <tr><td class="label"><label>������� ���������</label></td>
        <td><input type="text" name="studyplace" value="<?=_data('studyplace')?>" /></td>
        <!--<validator target="studyplace" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="studyplace-messages" />-->
        <td><input type="text" name="address" value="<?=_data('address')?>" /></td>
        <!--<validator target="address" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="address-messages" />-->
        <td><input type="text" name="phone" value="<?=_data('phone')?>" /></td>
        <!--<validator target="phone" required=" - �����������!" regex=" - (###)#######,^\([0-9][0-9][0-9]\)[0-9][0-9][0-9][0-9][0-9][0-9][0-9]$" change="true" place="phone-messages" />-->
        <td><input type="text" name="fax" value="<?=_data('fax')?>" /></td>
        <!--<validator target="fax" required=" - �����������!" regex=" - (###)#######,^\([0-9][0-9][0-9]\)[0-9][0-9][0-9][0-9][0-9][0-9][0-9]$" change="true" place="fax-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="toggle-fax" onclick="toggleFax(this);">���������</a></span></td></tr>
    <tr><td class="label">&nbsp;</td>
        <td id="contactname-messages"><label for="contactname">�������</td></td>
        <td id="contactphone-messages"><label for="contactphone">�������</label></td>
        <td id="contactmail-messages"><label for="contactmail">e-mail</label></td></tr>
    <tr><td class="label"><label>���������� ����</label></td>
        <td><input type="text" name="contactname" value="<?=_data('contactname')?>" /></td>
        <!--<validator target="contactname" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="contactname-messages" />-->
        <td><input type="text" name="contactphone" value="<?=_data('contactphone')?>" /></td>
        <!--<validator target="contactphone" required=" - �����������!" regex=" - (###)#######,^\([0-9][0-9][0-9]\)[0-9][0-9][0-9][0-9][0-9][0-9][0-9]$" change="true" place="contactphone-messages" />-->
        <td><input type="text" name="contactmail" value="<?=_data('contactmail')?>" /></td>
        <!--<validator target="contactmail" maxlength=" - 40 �������� max,40" change="true" place="contactmail-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="toggle-email" onclick="toggleEmail(this);">���������</a></span></td></tr>
</table>
<table class="enter">
    <tr><td class="label">&nbsp;</td>
        <td><!--input style="padding:0;margin:0;position:relative;top:4px;" type="checkbox" class="checkbox" name="codegamechallenge" <?=_has('codegamechallenge') && _data('codegamechallenge') ? 'checked="checked"' : ''?> />&nbsp;���� ������� ������ ������� � ������� Code Game Challenge--></td></tr>
</table>
<div id="contestant1-outer" class="hidden">
<h3>�������� #1&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="toggleVisibility(this, 'contestant1-outer');">��������</a></h3>
<div id="contestant1" class="inner"><table class="enter">
    <tr><td>&nbsp;</td>
        <td id="contestant1name-messages"><label for="contestant1name">���</td></td>
        <td id="contestant1studyplace-messages"><label for="contestant1studyplace">����� �����</label></td>
        <td id="contestant1classcourse-messages"><label for="contestant1classcourse">�����/����</label></td>
    <tr><td class="label"><label>�������� #1</label></td>
        <td><input type="text" name="contestant1name" value="<?=_data('contestant1name')?>" /></td>
        <!--<validator target="contestant1name" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="contestant1name-messages" />-->
        <td><input type="text" name="contestant1studyplace" value="<?=_data('contestant1studyplace')?>" /></td>
        <!--<validator target="contestant1studyplace" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant1studyplace-messages" />-->
        <td><input type="text" name="contestant1classcourse" value="<?=_data('contestant1classcourse')?>" /></td>
        <!--<validator target="contestant1classcourse" required=" - �����������!" maxlength=" - �� 1 �� 11,2" numeric=" - �� 1 �� 11" range=" - �� 1 �� 11,1,11" change="true" place="contestant1classcourse-messages" />-->
    <tr><td>&nbsp;</td>
        <td id="contestant1birthdate-messages"><label for="contestant1birthdate">���� ��������</td></td>
        <td id="contestant1address-messages"><label for="contestant1address">�����</label></td>
        <td id="contestant1inn-messages"><label for="contestant1inn">���</label></td></tr>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="contestant1birthdate" value="<?=_data('contestant1birthdate')?>" /></td>
        <!--<validator target="contestant1birthdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.19[8-9][0-9]$" change="true" place="contestant1birthdate-messages" />-->
        <td><input type="text" name="contestant1address" value="<?=_data('contestant1address')?>" /></td>
        <!--<validator target="contestant1address" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant1address-messages" />-->
        <td><input type="text" name="contestant1inn" value="<?=_data('contestant1inn')?>" /></td>
        <!--<validator target="contestant1inn" required=" - �����������" regex=" - 12 ����,^[0-9]*$" maxlength=" - 12 ����,12" minlength=" - 12 ����,12" change="true" place="contestant1inn-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant1-toggle-inn" onclick="toggleINN(this,'contestant1');">� ���� ��� ���</a></span></td></tr>
    <tr><td>&nbsp;</td>
        <td id="contestant1passportno-messages"><label for="contestant1passportno">����� � no</td></td>
        <td id="contestant1passportplace-messages"><label for="contestant1passportplace">��� �����</label></td>
        <td id="contestant1passportdate-messages"><label for="contestant1passportdate">����� �����</label></td></tr>
    <tr><td class="label"><label>�������</label></td>
        <td><input type="text" name="contestant1passportno" value="<?=_data('contestant1passportno')?>" /></td>
        <!--<validator target="contestant1passportno" required=" - �����������!" maxlength=" - 10 ����,10" minlength=" - 10 ����,10" regex=" - �� �����,^[0-9]*$" change="true" place="contestant1passportno-messages" />-->
        <td><input type="text" name="contestant1passportplace" value="<?=_data('contestant1passportplace')?>" /></td>
        <!--<validator target="contestant1passportplace" required=" - �����������!" maxlength=" - 100 �������� max,100" change="true" place="contestant1passportplace-messages" />-->
        <td><input type="text" name="contestant1passportdate" value="<?=_data('contestant1passportdate')?>" /></td>
        <!--<validator target="contestant1passportdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.[1-2][09][0189][0-9]$" change="true" place="contestant1passportdate-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant1-toggle-passport" onclick="togglePassport(this,'contestant1');">� ���� ��� ��������</a></span></td></tr>
</table></div>
</div>
<div id="contestant2-outer" class="hidden">
<h3>�������� #2&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="toggleVisibility(this, 'contestant2-outer');">��������</a></h3>
<div id="contestant2" class="inner"><table class="enter">
    <tr><td>&nbsp;</td>
        <td id="contestant2name-messages"><label for="contestant2name">���</td></td>
        <td id="contestant2studyplace-messages"><label for="contestant2studyplace">����� �����</label></td>
        <td id="contestant2classcourse-messages"><label for="contestant2classcourse">�����/����</label></td>
    <tr><td class="label"><label>�������� #2</label></td>
        <td><input type="text" name="contestant2name" value="<?=_data('contestant2name')?>" /></td>
        <!--<validator target="contestant2name" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="contestant2name-messages" />-->
        <td><input type="text" name="contestant2studyplace" value="<?=_data('contestant2studyplace')?>" /></td>
        <!--<validator target="contestant2studyplace" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant2studyplace-messages" />-->
        <td><input type="text" name="contestant2classcourse" value="<?=_data('contestant2classcourse')?>" /></td>
        <!--<validator target="contestant2classcourse" required=" - �����������!" maxlength=" - �� 1 �� 11,2" numeric=" - �� 1 �� 11" range=" - �� 1 �� 11,1,11" change="true" place="contestant2classcourse-messages" />-->
    <tr><td>&nbsp;</td>
        <td id="contestant2birthdate-messages"><label for="contestant2birthdate">���� ��������</td></td>
        <td id="contestant2address-messages"><label for="contestant2address">�����</label></td>
        <td id="contestant2inn-messages"><label for="contestant2inn">���</label></td></tr>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="contestant2birthdate" value="<?=_data('contestant2birthdate')?>" /></td>
        <!--<validator target="contestant2birthdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.19[8-9][0-9]$" change="true" place="contestant2birthdate-messages" />-->
        <td><input type="text" name="contestant2address" value="<?=_data('contestant2address')?>" /></td>
        <!--<validator target="contestant2address" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant2address-messages" />-->
        <td><input type="text" name="contestant2inn" value="<?=_data('contestant2inn')?>" /></td>
        <!--<validator target="contestant2inn" required=" - �����������" regex=" - 12 ����,^[0-9]*$" maxlength=" - 12 ����,12" minlength=" - 12 ����,12" change="true" place="contestant2inn-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant2-toggle-inn" onclick="toggleINN(this,'contestant2');">� ���� ��� ���</a></span></td></tr>
    <tr><td>&nbsp;</td>
        <td id="contestant2passportno-messages"><label for="contestant2passportno">����� � no</td></td>
        <td id="contestant2passportplace-messages"><label for="contestant2passportplace">��� �����</label></td>
        <td id="contestant2passportdate-messages"><label for="contestant2passportdate">����� �����</label></td></tr>
    <tr><td class="label"><label>�������</label></td>
        <td><input type="text" name="contestant2passportno" value="<?=_data('contestant2passportno')?>" /></td>
        <!--<validator target="contestant2passportno" required=" - �����������!" maxlength=" - 10 ����,10" minlength=" - 10 ����,10" regex=" - �� �����,^[0-9]*$" change="true" place="contestant2passportno-messages" />-->
        <td><input type="text" name="contestant2passportplace" value="<?=_data('contestant2passportplace')?>" /></td>
        <!--<validator target="contestant2passportplace" required=" - �����������!" maxlength=" - 100 �������� max,100" change="true" place="contestant2passportplace-messages" />-->
        <td><input type="text" name="contestant2passportdate" value="<?=_data('contestant2passportdate')?>" /></td>
        <!--<validator target="contestant2passportdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.[1-2][09][0189][0-9]$" change="true" place="contestant2passportdate-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant2-toggle-passport" onclick="togglePassport(this,'contestant2');">� ���� ��� ��������</a></span></td></tr>
</table></div>
</div>
<div id="contestant3-outer" class="hidden">
<h3>�������� #3&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="toggleVisibility(this, 'contestant3-outer');">��������</a></h3>
<div id="contestant3" class="inner"><table class="enter">
    <tr><td>&nbsp;</td>
        <td id="contestant3name-messages"><label for="contestant3name">���</td></td>
        <td id="contestant3studyplace-messages"><label for="contestant3studyplace">����� �����</label></td>
        <td id="contestant3classcourse-messages"><label for="contestant3classcourse">�����/����</label></td>
    <tr><td class="label"><label>�������� #3</label></td>
        <td><input type="text" name="contestant3name" value="<?=_data('contestant3name')?>" /></td>
        <!--<validator target="contestant3name" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="contestant3name-messages" />-->
        <td><input type="text" name="contestant3studyplace" value="<?=_data('contestant3studyplace')?>" /></td>
        <!--<validator target="contestant3studyplace" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant3studyplace-messages" />-->
        <td><input type="text" name="contestant3classcourse" value="<?=_data('contestant3classcourse')?>" /></td>
        <!--<validator target="contestant3classcourse" required=" - �����������!" maxlength=" - �� 1 �� 11,2" numeric=" - �� 1 �� 11" range=" - �� 1 �� 11,1,11" change="true" place="contestant3classcourse-messages" />-->
    <tr><td>&nbsp;</td>
        <td id="contestant3birthdate-messages"><label for="contestant3birthdate">���� ��������</td></td>
        <td id="contestant3address-messages"><label for="contestant3address">�����</label></td>
        <td id="contestant3inn-messages"><label for="contestant3inn">���</label></td></tr>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="contestant3birthdate" value="<?=_data('contestant3birthdate')?>" /></td>
        <!--<validator target="contestant3birthdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.19[8-9][0-9]$" change="true" place="contestant3birthdate-messages" />-->
        <td><input type="text" name="contestant3address" value="<?=_data('contestant3address')?>" /></td>
        <!--<validator target="contestant3address" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="contestant3address-messages" />-->
        <td><input type="text" name="contestant3inn" value="<?=_data('contestant3inn')?>" /></td>
        <!--<validator target="contestant3inn" required=" - �����������" regex=" - 12 ����,^[0-9]*$" maxlength=" - 12 ����,12" minlength=" - 12 ����,12" change="true" place="contestant3inn-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant3-toggle-inn" onclick="toggleINN(this,'contestant3');">� ���� ��� ���</a></span></td></tr>
    <tr><td>&nbsp;</td>
        <td id="contestant3passportno-messages"><label for="contestant3passportno">����� � no</td></td>
        <td id="contestant3passportplace-messages"><label for="contestant3passportplace">��� �����</label></td>
        <td id="contestant3passportdate-messages"><label for="contestant3passportdate">����� �����</label></td></tr>
    <tr><td class="label"><label>�������</label></td>
        <td><input type="text" name="contestant3passportno" value="<?=_data('contestant3passportno')?>" /></td>
        <!--<validator target="contestant3passportno" required=" - �����������!" maxlength=" - 10 ����,10" minlength=" - 10 ����,10" regex=" - �� �����,^[0-9]*$" change="true" place="contestant3passportno-messages" />-->
        <td><input type="text" name="contestant3passportplace" value="<?=_data('contestant3passportplace')?>" /></td>
        <!--<validator target="contestant3passportplace" required=" - �����������!" maxlength=" - 100 �������� max,100" change="true" place="contestant3passportplace-messages" />-->
        <td><input type="text" name="contestant3passportdate" value="<?=_data('contestant3passportdate')?>" /></td>
        <!--<validator target="contestant3passportdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.[1-2][09][0189][0-9]$" change="true" place="contestant3passportdate-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="contestant3-toggle-passport" onclick="togglePassport(this,'contestant3');">� ���� ��� ��������</a></span></td></tr>
</table></div>
</div>
<div id="head-outer">
<h3>������������ �������<span class="none-message"> - �����������</span><span class="visible-message">&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="toggleVisibility(this, 'head-outer');">������</a></span><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>&nbsp;&nbsp;::<a href="javascript:void(0);" id="head-toggle-block" onclick="toggleBlock(this, 'head-outer');">���������</a></span></h3>
<div id="head" class="inner"><table class="enter">
    <tr><td>&nbsp;</td>
        <td id="headname-messages"><label for="headname">���</td></td>
        <td id="headpost-messages"><label for="headpost">���������</label></td>
    <tr><td class="label"><label>������������</label></td>
        <td><input type="text" name="headname" value="<?=_data('headname')?>" /></td>
        <!--<validator target="headname" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="headname-messages" />-->
        <td><input type="text" name="headpost" value="<?=_data('headpost')?>" /></td>
        <!--<validator target="headpost" required=" - �����������!" maxlength=" - 30 �������� max,30" change="true" place="headpost-messages" />-->
    <tr><td>&nbsp;</td>
        <td id="headbirthdate-messages"><label for="headbirthdate">���� ��������</td></td>
        <td id="headaddress-messages"><label for="headaddress">�����</label></td>
        <td id="headinn-messages"><label for="headinn">���</label></td></tr>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="headbirthdate" value="<?=_data('headbirthdate')?>" /></td>
        <!--<validator target="headbirthdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.19[0-9][0-9]$" change="true" place="headbirthdate-messages" />-->
        <td><input type="text" name="headaddress" value="<?=_data('headaddress')?>" /></td>
        <!--<validator target="headaddress" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="headaddress-messages" />-->
        <td><input type="text" name="headinn" value="<?=_data('headinn')?>" /></td>
        <!--<validator target="headinn" required=" - �����������" regex=" - 12 ����,^[0-9]*$" maxlength=" - 12 ����,12" minlength=" - 12 ����,12" change="true" place="headinn-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="head-toggle-inn" onclick="toggleINN(this,'head');">� ���� ��� ���</a></span></td></tr>
    <tr><td>&nbsp;</td>
        <td id="headpassportno-messages"><label for="headpassportno">����� � no</td></td>
        <td id="headpassportplace-messages"><label for="headpassportplace">��� �����</label></td>
        <td id="headpassportdate-messages"><label for="headpassportdate">����� �����</label></td></tr>
    <tr><td class="label"><label>�������</label></td>
        <td><input type="text" name="headpassportno" value="<?=_data('headpassportno')?>" /></td>
        <!--<validator target="headpassportno" required=" - �����������!" maxlength=" - 10 ����,10" minlength=" - 10 ����,10" regex=" - �� �����,^[0-9]*$" change="true" place="headpassportno-messages" />-->
        <td><input type="text" name="headpassportplace" value="<?=_data('headpassportplace')?>" /></td>
        <!--<validator target="headpassportplace" required=" - �����������!" maxlength=" - 100 �������� max,100" change="true" place="headpassportplace-messages" />-->
        <td><input type="text" name="headpassportdate" value="<?=_data('headpassportdate')?>" /></td>
        <!--<validator target="headpassportdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.[1-2][09][0-9][0-9]$" change="true" place="headpassportdate-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="head-toggle-passport" onclick="togglePassport(this,'head');">� ���� ��� ��������</a></span></td></tr>
</table></div>
</div>
<div id="coach-outer">
<h3>������ �������<span class="none-message"> - �����������</span><span class="visible-message">&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="toggleVisibility(this, 'coach-outer');">������</a></span><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>&nbsp;&nbsp;::<a href="javascript:void(0);" id="coach-toggle-block" onclick="toggleBlock(this, 'coach-outer');">���������</a></span></h3>
<div id="coach" class="inner"><table class="enter">
    <tr><td>&nbsp;</td>
        <td id="coachname-messages"><label for="coachname">���</td></td>
        <td id="coachpost-messages"><label for="coachpost">���������</label></td>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="coachname" value="<?=_data('coachname')?>" /></td>
        <!--<validator target="coachname" required=" - �����������!" maxlength=" - 40 �������� max,40" change="true" place="coachname-messages" />-->
        <td><input type="text" name="coachpost" value="<?=_data('coachpost')?>" /></td>
        <!--<validator target="coachpost" required=" - �����������!" maxlength=" - 30 �������� max,30" change="true" place="coachpost-messages" />-->
    <tr><td>&nbsp;</td>
        <td id="coachbirthdate-messages"><label for="coachbirthdate">���� ��������</td></td>
        <td id="coachaddress-messages"><label for="coachaddress">�����</label></td>
        <td id="coachinn-messages"><label for="coachinn">���</label></td></tr>
    <tr><td class="label"><label>������</label></td>
        <td><input type="text" name="coachbirthdate" value="<?=_data('coachbirthdate')?>" /></td>
        <!--<validator target="coachbirthdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.19[0-9][0-9]$" change="true" place="coachbirthdate-messages" />-->
        <td><input type="text" name="coachaddress" value="<?=_data('coachaddress')?>" /></td>
        <!--<validator target="coachaddress" required=" - �����������!" maxlength=" - 50 �������� max,50" change="true" place="coachaddress-messages" />-->
        <td><input type="text" name="coachinn" value="<?=_data('coachinn')?>" /></td>
        <!--<validator target="coachinn" required=" - �����������" regex=" - 12 ����,^[0-9]*$" maxlength=" - 12 ����,12" minlength=" - 12 ����,12" change="true" place="coachinn-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="coach-toggle-inn" onclick="toggleINN(this,'coach');">� ���� ��� ���</a></span></td></tr>
    <tr><td>&nbsp;</td>
        <td id="coachpassportno-messages"><label for="coachpassportno">����� � no</td></td>
        <td id="coachpassportplace-messages"><label for="coachpassportplace">��� �����</label></td>
        <td id="coachpassportdate-messages"><label for="coachpassportdate">����� �����</label></td></tr>
    <tr><td class="label"><label>�������</label></td>
        <td><input type="text" name="coachpassportno" value="<?=_data('coachpassportno')?>" /></td>
        <!--<validator target="coachpassportno" required=" - �����������!" maxlength=" - 10 ����,10" minlength=" - 10 ����,10" regex=" - �� �����,^[0-9]*$" change="true" place="coachpassportno-messages" />-->
        <td><input type="text" name="coachpassportplace" value="<?=_data('coachpassportplace')?>" /></td>
        <!--<validator target="coachpassportplace" required=" - �����������!" maxlength=" - 100 �������� max,100" change="true" place="coachpassportplace-messages" />-->
        <td><input type="text" name="coachpassportdate" value="<?=_data('coachpassportdate')?>" /></td>
        <!--<validator target="coachpassportdate" required=" - �����������!" regex=" - ��.��.����,^[0-3][0-9]\.[0-1][0-9]\.[1-2][09][0-9][0-9]$" change="true" place="coachpassportdate-messages" />-->
        <td><span<?=1 == $is_admin ? ' style="visibility:hidden"' : ''?>>::<a href="javascript:void(0);" id="coach-toggle-passport" onclick="togglePassport(this,'coach');">� ���� ��� ��������</a></span></td></tr>
</table></div>
</div>
<h3><?=1 == $is_admin ? _data('orderteamname') : $curteamname?><?php if (1 != $is_admin): ?>&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="document.getElementById('submitbtn').click();">���������</a><? endif; ?><span class="marker validation-messages" style="visibility:hidden"> - ���� ����������� ����������� ����</span></h3>
<input type="submit" name="submitbtn" id="submitbtn" style="visibility:hidden" value="submit" onclick="return window.KIR.validator('editinfo').validate();" />
</form>
</body>
</html>

