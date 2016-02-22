<?php
function content($data) {
$msg = _data('msg');
$contests = _data('contests');
$langs = _data('langs');
$login = _data('login');
$pass = _data('pass');
$lang = _data('lang');
$contest = _data('contest');
$problem = _data('problem');
$solve = _data('solve');
global $is_admin;
// todo: check stripslashes
?>
<?php
if ('' != $msg):
?>
          <p class="message"><?=$msg?></p>
            <hr />
<?php
endif;
?>
          <form name="frmsubmit" action="./submit.php" method="POST">
            <table class="enter" style="width:100%;">
              <tr>
                <td>Пользователь</td>
                <td>
                  <input type="text" name="login" maxlen="20" size="20" value="<?=$login?>">
                </td>
              </tr>
              <tr>
                <td>Пароль</td>
                <td>
                 <input type="password" name="pass" maxlen="20" size="20" value="<?=$pass?>">
               </td>
             </tr>
             <?php if (1 == $is_admin): ?>
              <tr>
                <td></td>
                <td>
                    <?php $checked = isset($_POST['btn_submit']) ? isset($_POST['detached']) : true; ?>
                 <input style="width:auto;" type="checkbox" name="detached" <?php echo $checked ? 'checked' : ''; ?>> отправка в режиме администратора (посылка не видна обычным пользователям)
               </td>
             </tr>
             <?php endif; ?>
             <tr>
               <td>Задача</td>
               <td>
                 <input type="text" name="problem" maxlen="6" size="20" value="<?=$problem?>">
               </td>
             </tr>
             <tr>
               <td>Контест</td>
               <td>
                 <select name="contest">
<?php
$index = 0;
while (list($key, $f) = each($contests)):
    $index++;
    if ($f->ContestID==$contest || $f->ContestID!=$contest && $index==1):
?>
                   <option selected="selected" value="<?=$f->ContestID?>"><?=$f->Name?></option>
<?php
    else:
?>
                   <option value="<?=$f->ContestID?>"><?=$f->Name?></option>
<?php
    endif;
endwhile;
?>
                 </select>
                </td>
              </tr>
              <tr>
                <td>Язык</td>
                <td>
                  <select name="lang" id="lang" onchange="$('#changeLang').val('true');$('#btn_submit').click();">
<?php
$index = 0;
while (list($key, $f) = each($langs)):
    $index++;
    if ($f->LangID==$lang || $f->LangID!=$lang && (!isset($_COOKIE['lastlangid']) && $index==1 || isset($_COOKIE['lastlangid'])&&$f->LangID==$_COOKIE['lastlangid'])):
?>
                    <option title="<?=$f->Ext?>" selected value="<?=$f->LangID?>"><?=$f->Desc?></option>
<?php
    else:
?>
                    <option title="<?=$f->Ext?>" value="<?=$f->LangID?>"><?=$f->Desc?></option>
<?php
    endif;
endwhile;
?>
                  </select>
                </td>
              </tr>
              <tr id="solutionRow">
                <td class="top">Решение</td>
                <td style="width:100%;">
                  <textarea
                    wrap="virtual"
                    name="solve"
                    rows="15"
                    id="solve"
                    style="width:100%;"
                    cols="20"><?=get_magic_quotes_gpc() ? stripslashes($solve) : $solve?></textarea>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="c">
                  <input type="submit" class="submit" name="btn_submit" id="btn_submit" value="послать" onclick="setCookie('lastlangid',document.getElementById('lang').value,30)">
                  <input type="hidden" name="changeLang" id="changeLang" value="" />
                </td>
              </tr>
            </table>
          </form>
          
    <script language="javascript" type="text/javascript">
        (function($) {
            var
                    _BOTTOM_MARGIN = 64
                ;
            window.resizeEditor = function(e) {
                //console.log($('#solutionRow').position().top);
                $('#frame_solve, #solve').css({
                    'height':   $(window).height() 
                                    - $('#solutionRow').position().top
                                    - $('#contacts').height()
                                    - $('#header').height()
                                    - $('#timeleft').height()
                                    - _BOTTOM_MARGIN
                });
            };
            
            window.applyHighlight = function() {
                //console.log($('#lang').find('option[value=' + $('#lang').val() +']').attr('title'));
                editAreaLoader.init({
        	           id : "solve"		// textarea id
	               ,   syntax: $('#lang').find('option[selected]').attr('title')			// syntax to be uses for highgliting
	               ,   start_highlight: true		// to display with highlight mode on start-up
	               ,   allow_toggle: false
	               ,   font_size: 8
                });
            };
            
            $(function() { 
                window.applyHighlight('cpp'); 
                window.resizeEditor();
                /*var handler = function() {
                    if ('' != $('[name=login]').val()) {
                        var params = {}; 
                        params.login = $('[name=login]').val();
                        params.source = editAreaLoader.getValue('solve');
                    	$.get('<?=ServerRoot?>/savesource.php', params);
                    }
                    window.setTimeout(handler, 1000);
                };
                handler();*/
            });
            $(window).resize(window.resizeEditor);
        })(jQuery);
    </script>          
    <style type="text/css">
        #EditAreaArroundInfos_solve
        { display:none; }
    </style>
<?php
}
?>
