function setCookie(c_name, value, expiredays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate()+expiredays);
    document.cookie=c_name+ '=' + escape(value) +
        ((expiredays == null) ? '' : ';expires=' + exdate.toGMTString());
}
function calcScrollTop() {
    return document.documentElement.scrollTop;
}

function calcIEHeight() {
    return document.documentElement.clientHeight-47 + calcScrollTop();
}

function toggleHeader() {
    setCookie('noheader', jQuery('body').hasClass('noheader') ? 'no' : 'yes', 30);
    jQuery('body').toggleClass('noheader');
    if (window.resizeEditor) { window.resizeEditor(); }
}

function toggleLeft() {
    setCookie('noleft', jQuery('body').hasClass('noleft') ? 'no' : 'yes', 30);
    jQuery('body').toggleClass('noleft');
    $('#control').css('marginLeft', '');
    defMargin(true);
    if (window.resizeEditor) { window.resizeEditor(); }
}

function defMargin(value) {
    value = !!value
    if (!value) {
        return $('#control').data('defMargin');
    } else {
        $('#control').data('defMargin', parseInt($('#control').css('marginLeft')));
    }
}

function toggle(id, anchor) {
    var element = document.getElementById(id);
    if (!element) { return; }
    if ('none' == element.style.display) {
        element.style.display = 'block';
        anchor.innerHTML = 'скрыть';
    } else {
        element.style.display = 'none';
        anchor.innerHTML = 'показать';
    }
}

function toggleMail(container, messageId) {
    if (!jQuery(container).hasClass('text-shown')) {
        jQuery(container).find('a').hide(200);
        jQuery(container).find('.mail-text').show(200);
    } else {
        jQuery(container).find('a').show(200);
        jQuery(container).find('.mail-text').hide(200);
    }
    jQuery(container).toggleClass('text-shown');

    if (messageId) {
        jQuery.get('./readmessage.php',
            {'messageid': messageId},
            function (data, status) {
                if ('success' === status && jQuery(data).size() && 'true' == jQuery(data).html()) { 
                    jQuery(container).parent().parent().removeClass('unread'); 
                    jQuery('.message-count').each(function() {
                        var val = jQuery(this).html().substr(1);
                        val = parseInt(val);
                        if (val > 0) {
                            val--;
                            if (val > 0) {
                                jQuery(this).html('(' + val + ')');
                            } else {
                                jQuery(this).html('');
                            }
                        } 
                    });
                }
            }
        );
    }
}

function closeMessage() {
    jQuery('#message-form').fadeOut(200);
    jQuery('#message-loading').css('display','none');
}

function showMessage(userName, userId) {
    var w = document.getElementById('message-form');
    if (!w.index) { w.index = 1; } else { w.index++; }
    jQuery('#message-send').removeAttr('disabled');
    jQuery('#message-text').val('');
    jQuery('#message-to').val(userId);
    jQuery('#message-to-username').html(userName);
    jQuery('#message-form').fadeIn(200);
    jQuery('#message-text').focus();
}

function sendMessage() {
    var touser = jQuery('#message-to').val();
    var text = jQuery('#message-text').val();
    jQuery('#message-send').attr('disabled', true);
    jQuery('#message-loading').fadeIn(200);
    var index = document.getElementById('message-form').index;
    jQuery.get('./sendmessage.php?callback=?',
        {'touser': touser, 'text': text},
        function (data, status) {
            var _index = document.getElementById('message-form').index;
            if ('success' === status && jQuery(data).size() && 'true' == jQuery(data).html()) { 
                if (index == _index) {
                    jQuery('#message-loading').fadeOut(200, function() { messageSuccess(index); });
                } else {
                    messageSuccess(index);
                }
            } else {
                if (index == _index) {
                    jQuery('#message-send').removeAttr('disabled');
                    jQuery('#message-loading').fadeOut(200);
                }
                messageError();
            }
        }
    );
}

function messageSuccess(index) {
    var _index = document.getElementById('message-form').index;
    jQuery('#message-success').fadeIn(200);
    if (index == _index) {
        window.setTimeout('jQuery(\'#message-success\').fadeOut(200, function() { closeMessage(); });', 1000);
    } else {
        window.setTimeout('jQuery(\'#message-success\').fadeOut(200);', 1000);
    }
}

function messageError() {
    jQuery('#message-error').fadeIn(200);
    window.setTimeout('jQuery(\'#message-error\').fadeOut(200);', 1000);
}
