<?php function combobox($id, $value, $label, $url, $onselect = '') { ?>

<div id="<?php echo $id; ?>Container" class="mosaic-inputs-combobox-outer-container">
    <div id="<?php echo $id; ?>DropdownBox" class="mosaic-widgets-dropdown-container">

        <div class="mosaic-widgets-dropdown-dropdown">
            <div class="mosaic-widgets-dropdown-loading">&nbsp;</div>
        </div>

        <div class="mosaic-widgets-dropdown-empty-results">
            Совпадений не найдено
            <span>
                Вы можете <a href="javascript:(function() { jQuery(document.getElementById('${idMosaicCombobox}Container')).find('.mosaic-inputs-combobox-button').click(); })();">повторить поиск</a>
            </span>
        </div>

        <div class="mosaic-widgets-dropdown-hint mosaic-widgets-dropdown-hint-invisible"></div>

        <script>
            mosaic.widgets.dropdown('<?php echo $id; ?>DropdownBox').apply({
                    'hint'      : true
            });
        </script>
    </div>

    <div class="mosaic-inputs-combobox-container">

        <input type="hidden"
               id="<?php echo $id; ?>"
               value="<?php echo $value; ?>"
               name="<?php echo $id; ?>"
                />

        <div class="mosaic-inputs-combobox-value"><?php echo $label; ?></div>

        <div class="mosaic-inputs-combobox-editable-area">
            <div class="mosaic-inputs-combobox-editable-area-background"></div>
            <input
                    type="text"
                    name="<?php echo $id; ?>Edit"
                    autocomplete="off"
                    class="mosaic-inputs-combobox"
                    value="<?php echo $label; ?>"
                    />
        </div>

        <a href="javascript:void(0);" class="mosaic-inputs-combobox-button">
            search
        </a>

    </div><!-- inner container -->

    <script>
        mosaic.inputs.combobox('<?php echo $id; ?>').apply({
                'typing'        : true
            ,   'cancelOnExit'  : false
            ,   'filter'        : function(combobox, query, handler) {
                    $.getJSON((function(mask, placeholder) {
                        return  mask.indexOf(placeholder) > -1
                                    ? mask.replace(placeholder, query)
                                    : [mask, query].join('')
                                    ;
                    })(
                            '<?php echo $url; ?>'
                        ,   '%q'
                    ), handler);
                }
            ,   'onselect'      : function(combobox, label, value) {
                    <?php echo $onselect; ?>
                }
        });
    </script>

</div><!-- outer container -->

<?php } ?>