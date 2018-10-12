<div class="popup-head">Optie toevoegen</div>
<div class="popup-content">
    <table class="table table-striped" id="tableAddOptions">
        <tr>
            <th>Antwoord</th>
            <td>
                <input type="text" id="multiSelecRightOption" />
            </td>
        </tr>
    </tr>
</table>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="addItems();">
        Toevoegen
    </a>
</div>

<script type="text/javascript">
    function addItems() {

        window.result = '[' + $('#multiSelecRightOption').val() + ']';

        $('.redactor-editor:first').focus();
        setTimeout(function() {
            $('#QuestionQuestion').redactor('caret.setOffset', 50);
        }, 500);

        setTimeout(function() {
            $('#QuestionQuestion').redactor('insert.html', window.result);
        }, 1000);

        setTimeout(function() {
            Popup.closeLast();
        },1500);
    }
</script>