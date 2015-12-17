<?php function before($content_tile_name = null) { ?>
<!DOCTYPE html>
<html lang="ru"><head>

    <!-- META -->
    <meta charset="windows-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="спортивное программирование, олимпиада по информатике, турнир по программированию, контест, олимпиада, программирование, информатика, задача, ICL, турнир" />

    <base href="<?=ServerRoot?>" />

    <!-- TITLE -->
    <title>XVI Открытый командный турнир по программированию среди студентов и школьников Республики Татарстан</title>
    
    <!-- FAVICON -->
    
    <!-- CSS -->
    <link type="text/css" rel="stylesheet" href="<?=ServerRoot?>css/style.2015.12.10.17.35.css" />
    <link type="text/css" rel="stylesheet" href="<?=ServerRoot?>css/markup.2015.12.10.17.35.css" />
    <link type="text/css" rel="stylesheet" href="<?=ServerRoot?>css/content.2012.03.29.13.04.css" />
    <link type="text/css" rel="stylesheet" href="./css/mosaic.widgets.dropdown.css" />
    <link type="text/css" rel="stylesheet" href="./css/mosaic.inputs.combobox.css" />

    <!-- JS LIBS -->  
    <!--script src="http://www.google.com/jsapi"></script-->  
    <!--script type="text/javascript">  
  
        // Load jQuery  
        google.load('jquery', '1.4.4'); // latest version
        google.setOnLoadCallback(function() {  
            // Your code goes here.  
        });  
         
    </script--><!-- end of JS LIBS -->
    <script type="text/javascript" src="./js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="./js/editarea/edit_area_full.js"></script>
    
    <!-- JS MODULES -->
    <script type="text/javascript" src="<?=ServerRoot?>js/monitor.js"></script>
    <script type="text/javascript" src="<?=ServerRoot?>js/utils.2011.04.01.js"></script>
    <script src="./js/KIR.js"></script>
    <script src="./js/KIR.utils.js"></script>
    <script src="./js/KIR.DOM.js"></script>
    <script src="./js/KIR.core.js"></script>
    <script src="./js/KIR.events.js"></script>
    <script src="./js/KIR.controls.js"></script>
    <script src="./js/KIR.validator.js"></script>

    <script src="./js/inside.js"></script>
    <script src="./js/inside.code.js"></script>
    <script src="./js/inside.core.js"></script>
    <script src="./js/inside.core.js.js"></script>
    <script src="./js/inside.core.util.js"></script>

    <script src="./js/mosaic.js"></script>
    <script src="./js/mosaic.widgets.js"></script>
    <script src="./js/mosaic.widgets.dropdown.js"></script>
    <script src="./js/mosaic.widgets.scrollpane.js"></script>
    <script src="./js/mosaic.inputs.js"></script>
    <script src="./js/mosaic.inputs.combobox.js"></script>

    <!-- end of JS MODULES -->

    <script>
        $(function() {
            KIR.validator().parse(document.body);
        });
    </script>

</head><body class="<?=$content_tile_name?><?=isset($_COOKIE['noheader']) && 'yes' == $_COOKIE['noheader'] || !isset($_COOKIE['noheader']) ? ' noheader' : ''?><?='standing' == $content_tile_name || 'table' == $content_tile_name || (isset($_COOKIE['noleft']) && 'yes' == $_COOKIE['noleft']) ? ' noleft' : ''?>">
<?php } ?>
