<?php function content($data) { ?>
<?php global $authorized; ?>

<h3>�������� ���: #<?=_data('submitid');?>&nbsp;::<a href="./status.php?contest=<?=_data('contestid')?><?=-1 != _data('top') ? '&amp;top='._data('top') : ''?><?=-1 != _data('topuserid') ? '&amp;userid='._data('topuserid') : ''?>">� ������ �������</a></h3>

<p>
<label style="color:#bbb;">�����:</label> <?=userlink(_data('nickname'), _data('userid'))?><br />
<label style="color:#bbb;">����� �����:</label> <?=_data('submitdate')?><br />
<label style="color:#bbb;">����:</label> <?=_data('language')?><br />
<label style="color:#bbb;">���������:</label> <?=_data('submitmessage')?><br />
</p>

<div class="wrapper">

    <p class="code" style="width:auto;float:left;"><?=preg_replace('/\</', '&lt;', _data('source'));?></p>

</div>

<?php } ?>
