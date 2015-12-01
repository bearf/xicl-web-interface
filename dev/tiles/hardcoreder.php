<?php
function content($data) {
    $users = _data('users');
?>
<style type="text/css">

    .column
    { float:left;width:42%;margin:0 2%; }
   

    .column select
    { width:100%; } 

    .column-content
    { margin:10px 0;
      border:1px #bbb solid;
      padding:10px;
      white-space:pre; }

</style>

<div id="hardcoreder">

    <div class="column left-column">
	<select name="leftuser" onchange="submitUser('leftuser');">
            <?php
            $index = 0;
            while (list($key, $f) = each($users)):
                $index++; 
                if (isset($_SESSION['leftuser']) && $f->ID==$_SESSION['leftuser']):
                ?>
                    <option selected="selected" value="<?=$f->ID?>"><?=$f->NickName?></option>
                <?php
                else:
                ?>
                    <option value="<?=$f->ID?>"><?=$f->NickName?></option>
                <?php
                endif;
            endwhile;
            $users = _data('users');
            ?>
        </select>
        <div class="column-content">
        </div>
    </div><!-- left-column -->

    <div class="column right-column">
	<select name="rightuser" onchange="submitUser('rightuser');">
            <?php
            $index = 0;
            while (list($key, $f) = each($users)):
                $index++;
                if (isset($_SESSION['rightuser']) && $f->ID==$_SESSION['rightuser']):
                ?>
                    <option selected="selected" value="<?=$f->ID?>"><?=$f->NickName?></option>
                <?php
                else:
                ?>
                    <option value="<?=$f->ID?>"><?=$f->NickName?></option>
                <?php
                endif;
            endwhile;
            ?>
        </select>
        <div class="column-content">
        </div>
    </div><!-- right-column -->

    <script type="text/javascript">
        function submitUser(inputId) {
            var params = {}; params[inputId] = $('[name=' + inputId + ']').val();
            $.get('<?=ServerRoot?>ajax/hardcorederuser.php', params);
        }
    </script>

<script type="text/javascript">
    jQuery(function($) {
        var load = function(inputId, $to) {
            var params = {};
            params.userid = $('[name=' + inputId + ']').val();
            if (!params.userid) { return false; }
            $to.load('<?=ServerRoot?>getsource.php?userid=' + params.userid, function(data) {
            });
        }; 
        var handler = function() {
            load('leftuser', $('.left-column .column-content')); 
            load('rightuser', $('.right-column .column-content')); 
            setTimeout(handler, 1000);
        };
        handler();
    });
</script>

</div><!-- hardcoreder -->
<?php
}
?>
