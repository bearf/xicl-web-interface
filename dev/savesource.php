<?php
require_once('./config/require.php');

        $q = $mysqli_->prepare('update user set'
            .' source=?'
            .' where login=?');
        // todo: check if s for source?
        $q->bind_param('ss', $_GET['source'], $_GET['login']);
        $q->execute();


?>{ result:true, login:'<?=$_GET['login']?>', source:'<?=$_GET['source']?>' }