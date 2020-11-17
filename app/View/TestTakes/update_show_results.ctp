<div class="popup-head">
    Inzien door Studenten
</div>
<div class="popup-content">
    <div class="alert alert-info">
        Mogelijkheid voor Studenten om het resultaat van hun eigen toets in te zien.
    </div>

    <?=$this->Form->create('TestTake')?>
    <?=$this->Form->input('active', ['type' => 'checkbox', 'checked' => !empty($take['show_results']), 'label' => ' Mogelijkheid tot inzien activeren', 'onchange' => 'updateSpecifyDate(this);'])?>

    <div class="well well-sm mt10" style="margin-bottom: 0px; text-align: center; <?=!isset($take['show_results']) ? 'display: none;' : '' ?>" id="specifyDate">
        Inzien mogelijk tot<br />
        <?=$this->Form->input('show_results', ['type' => 'text', 'class' => 'mt5', 'value' => $take['show_results'] , 'label' => false, 'value' => date('d-m-Y H:i', strtotime('+ 20 min'))])?>
    </div>
    <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="NietLatenInzien(); ">
        Niet laten inzien
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Toepassen
    </a>
</div>

<script type="text/javascript">

    function updateSpecifyDate(e) {
        if($(e).is(':checked')) {
            $('#specifyDate').show();
        }else{
            $('#specifyDate').hide();
        }
    }

    $('#TestTakeShowResults').datetimepicker({
        format:'d-m-Y H:i'
    });

    function NietLatenInzien(){
        $('#TestTakeActive').removeAttr('checked');
        $('#btnSave').click();
    }

    $('#TestTakeUpdateShowResultsForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Navigation.load('/test_takes/to_rate');
            },
            onfailure : function(result) {

            }
        }
    );
</script>