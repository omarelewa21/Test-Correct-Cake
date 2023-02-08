<div class="popup-head"><?= __("info.Info Message")?></div>
<div class="popup-content">
    <?=$this->Form->create('Info') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("info.Tonen vanaf")?>
            </th>
            <td>
                <?=$this->Form->input('show_from', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => 'Y-m-d H:i')) ?>
            </td>
            <th>
                <?= __("info.Tonen tot")?>
            </th>
            <td>
                <?=$this->Form->input('show_until', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => 'Y-m-d H:i')) ?>
            </td>
        </tr>
        <tr aria-rowspan="2">
            <th rowspan="1" width="130" valign="top">
               <?= __("info.Status")?>
            </th>
            <td rowspan="1" valign="top">
                <?=$this->Form->input('status', array('style' => 'width: 185px', 'label' => false, 'options' => $statuses, 'selected' => getUUID($info['status'], 'get'))) ?>
            </td>

            <th rowspan="2" valign="top"><?= __("Tonen aan")?></th>
            <td rowspan="2">
                <?=$this->Form->input('for_all', array('type' => 'checkbox', 'value' => 1, 'onClick' => 'formModifier();', 'label' => false, 'div' => false, 'checked' => $info['for_all'] ? 'checked' : ''))?>  <?= __("info.Everybody")?> <br />
                <?=$this->Form->input('roles', ['options' => $roles, 'style' => 'width:200px;', 'multiple' => true, 'label' => false, 'disabled' => true]) ?>
            </td>
        </tr>
        <tr>
            <th rowspan="1" width="130" valign="top">
                <?= __("info.Type")?>
            </th>
            <td rowspan="1" valign="top">
                <?=$this->Form->input('type', array('style' => 'width: 185px', 'onChange' => 'formModifier();',  'label' => false, 'options' => $types, 'selected' => getUUID($info['type'], 'get'))) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">
            <?= __("info.Title nl")?>
            </th>
            <th colspan="2">
            <?= __("info.Title en")?>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('title_nl', array('div' => false, 'label' => false,  'verify' => 'notempty','autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
            </td>
            <td colspan="2">
                <?=$this->Form->input('title_en', array('div' => false, 'label' => false,  'verify' => 'notempty','autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <?= __("info.Content nl")?>
            </th>
            <th colspan="2">
                <?= __("info.Content en")?>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('content_nl', array('style' => 'width:100%; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off','verify'=> 'notempty')); ?>
            </td>
            <td colspan="2">
                <?=$this->Form->input('content_en', array('style' => 'width:100%; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off','verify'=> 'notempty')); ?>
            </td>
        </tr>

    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    jQuery('#InfoContentNl').ckeditor({});
    jQuery('#InfoContentEn').ckeditor({});

    function formModifier() {
        var infoRoles = jQuery('#InfoRoles');


        infoRoles.attr(
            'disabled',
            jQuery('#InfoForAll').is(':checked')
        );

        if (jQuery('#InfoType').find(":selected").val() === 'NEW_FEATURE') {
            infoRoles.hide();
            infoRoles.val(1);
        } else {
            infoRoles.show();
        }
    }

    formModifier();

    jQuery('#InfoEditForm').formify(
        {
            confirm: jQuery('#btnSave'),
            onsuccess: function (result) {
                Popup.closeLast();
                Notify.notify('<?= __("Info message gewijzigd")?>', "info");
                Navigation.refresh()
            },
            onfailure: function (result) {
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );

</script>