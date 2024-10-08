<div class="popup-head"><?= __("Optie toevoegen")?></div>
<div class="popup-content">
    <table class="table table-striped" id="tableAddOptions">
        <tr>
            <th><?= __("Juiste antwoord")?></th>
            <td>
                <input type="text" id="multiSelecRightOption" disabled="disabled" />
            </td>
        </tr>
        <tr>
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
        <tr style="display:none;">
            <th><?= __("Foutief antwoord")?></th>
            <td>
                <input type="text" class="multiSelectOption" />
            </td>
        </tr>
</table>

<br />
<center>
    <a href="#" class="btn highlight" onclick="$('#tableAddOptions tr:hidden').first().fadeIn()">
    <?= __("Optie toevoegen")?>
    </a>
</center>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="addItems();">
    <?= __("Toevoegen")?>
    </a>
</div>

<script type="text/javascript">

    var selection = $('#QuestionQuestion').redactor('selection.getText');

    if(selection != '' && selection.slice(-1) == ' ') {
        selection = selection.substr(0, selection.length - 1);
    }

    $('#multiSelecRightOption').val(selection);

    $('#QuestionQuestion').redactor('selection.replaceSelection', '-' + selection);
    $('#QuestionQuestion').redactor('code.sync');

    function addItems() {

        var result = '[' + $('#multiSelecRightOption').val();

        $.each($('.multiSelectOption'), function() {

            if($(this).val() != '') {
                result += '|' + $(this).val();
            }
        });

        result += ']';

        $('.redactor-editor:first').focus();

        var code = $('#QuestionQuestion').redactor('code.get');

        // console.log(code);
        code = code.replace('-' + $('#multiSelecRightOption').val(), result);
        // console.log(code);
        // console.log(result);
        $('#QuestionQuestion').redactor('code.set', code);
        Popup.closeLast();
    }
</script>