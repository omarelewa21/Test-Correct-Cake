<span id="edit_view">
<div class="popup-head">Toets wijzigen</div>
<div class="popup-content">
    <?= $this->Form->create('Test') ?>
    <?
    $levels = [];
    $levelyears = [];

    foreach ($education_levels as $education_level) {
        $levels[$education_level['uuid']] = $education_level['name'];
        $levelyears[$education_level['uuid']] = $education_level['max_years'];
    }
    ?>
    <table class="table mb15">
        <tr>
            <th width="140">
                Omschrijving
            </th>
            <td>
                <?= $this->Form->input('name', array('style' => 'width: 270px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="140">
                Afkorting
            </th>
            <td>
                <div style="float:right; margin-top:4px; margin-right:20px;">
                    (max 5 karakters)
                </div>
                <?= $this->Form->input('abbreviation', array('style' => 'width: 145px', 'label' => false, 'verify' => 'notempty max-length-5')) ?>
            </td>
        </tr>
        <tr>
            <th width="140">
                Type
            </th>
            <td>
                <?= $this->Form->input('test_kind_id', array('style' => 'width: 282px', 'label' => false, 'options' => $kinds)) ?>
            </td>
            <th width="140">
                Vak
            </th>
            <td>
                <?= $this->Form->input('subject_id', array('style' => 'width: 282px', 'label' => false, 'options' => $subjects)) ?>
            </td>
        </tr>
        <tr>
            <th width="140">
                Niveau
            </th>
            <td>
                <?= $this->Form->input('education_level_id', array('selected' => $current_education_level_uuid,'style' => 'width: 282px', 'label' => false, 'options' => $levels, 'onchange' => 'updateEducationYears();')) ?>
            </td>
            <th width="140">
                Niveau-jaar
            </th>
            <td>
                <?= $this->Form->input('education_level_year', array('style' => 'width: 282px', 'label' => false, 'type' => 'select')) ?>
            </td>
        </tr>
        <tr>
            <th width="140">
                Periode
            </th>
            <td>
                <?= $this->Form->input('period_id', array('style' => 'width: 282px', 'label' => false, 'options' => $periods)) ?>
            </td>
            <th width="140">
                Vragen shuffelen
            </th>
            <td>
                <?= $this->Form->input('shuffle', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false)) ?>
                Shuffle vragen tijdens afname
            </td>
        </tr>
        <?php if ($is_open_source_content_creator): ?>
            <tr>
                <th width="140">
                    Open source toets
                </th>
                <td>
                    <?= $this->Form->input('is_open_source_content', array('label' => false, 'type' => 'checkbox', 'div' => false)) ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th colspan="4">Introductie-tekst</th>
        </tr>
        <tr>
            <td colspan="4">
                <?= $this->Form->input('introduction', [
                    'style' => 'width:920px; height:100px',
                    'type'  => 'textarea',
                    'label' => false
                ]) ?>
            </td>
        </tr>
    </table>
    <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right display-none" id="btnEditTest">
        Toets wijzigen
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnChooseEditTest">
        Toets wijzigen
    </a>
</div>
</span>
<span id="choose_view" class="display-none">
    <div class="popup-head">Type aanpassing kiezen</div>
        <div class="popup-content">
            Wil je de huidige toets verbeteren, of een nieuwe toets maken?
        </div>
        <div class="popup-content">
            <div id="edit_test_type_update" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 150px;;cursor:pointer">
                <h4 class="mt1 mb2">Ik verbeter de huidige toets</h4>
                <div>
                De toets wordt aangepast. Er worden duplicaten aangemaakt van deze toets en alle vragen die eronder vallen
                </div>
            </div>
            <div id="edit_test_type_copy" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 150px;;cursor:pointer">
                <h4 class="mt1 mb2">Ik ben met een nieuwe toets bezig</h4>
                <div>
                De toets wordt gedupliceer en de aanpassingen worden in het duplicaat doorgevoerd. Let op! Het vak en/of het niveau van alle vragen die aan deze toets zijn gekoppeld wordt ook aangepast.
                </div>
             </div>
        </div>
        <div class="popup-footer">
            <div>
                <a href="#" id="edit_test_type_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">Bevestigen</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="showEditView()">Annuleren</a>
            </div>
        </div>
</span>
<script type="text/javascript" src="/js/formhandler.js?<?= time() ?>"></script>
<script type="text/javascript">
    $('#TestEditForm').formify(
        {
            confirm: $('#btnEditTest'),
            onsuccess: function (result) {
                Popup.closeLast();
                Navigation.refresh();
                Notify.notify("Toets gewijzigd", "info");
            },
            onfailure: function (result) {
                if (result == 'unique_name') {
                    Notify.notify("De gekozen titel is al in gebruik. Gebruik een unieke titel.", "error");
                } else {
                    Notify.notify("Fout bij het wijzigen van de toets", "error");
                }
            }
        }
    );

    $(document).ready(function(){
        showEditView();
        $('body').on('click','#btnChooseEditTest',function(){
            sendFormOrChooseType();
        })
        Test.editTestChooseTypeEvents('TestEditForm','btnEditTest','<?=$test_id?>');
        fillParamBag();
    });

    function showChooseView(){
        $('#edit_view').hide();
        $('#choose_view').show();
        $('#popup_' + Popup.index).css({
            'margin-left': (0 - (650 / 2)) + 'px',
            'margin-top': (0 - (400 / 2)) + 'px',
            'width': 650 + 'px',
            'height': 400 + 'px',
            'zIndex': Popup.zIndex
        });
    }
    function showEditView(){
        $('#choose_view').hide();
        $('#edit_view').show();
        $('#popup_' + Popup.index).css({
            'margin-left': (0 - (800 / 2)) + 'px',
            'margin-top': (0 - (800 / 2)) + 'px',
            'width': 1000 + 'px',
            'height': 600 + 'px',
            'zIndex': Popup.zIndex
        });
    }

    function fillParamBag(){
        Test.paramBag.subjectId = $('#TestSubjectId').val();
        Test.paramBag.educationLevelId = $('#TestEducationLevelId').val();
        Test.paramBag.educationLevelYear = $('#TestEducationLevelYear').val();
    }

    function sendFormOrChooseType(){
        if(Test.paramBag.subjectId != $('#TestSubjectId').val()){
            showChooseView();
            return;
        }
        if(Test.paramBag.educationLevelId != $('#TestEducationLevelId').val()){
            showChooseView();
            return;
        }
        if(Test.paramBag.educationLevelYear && Test.paramBag.educationLevelYear != $('#TestEducationLevelYear').val()){
            showChooseView();
            return;
        }
        $('#btnEditTest').click();
    }

    function updateEducationYears() {
        var val = $('#TestEducationLevelId').val();
        var oldVal = $('#TestEducationLevelYear').val();
        var years = 0;

        if (oldVal == '' || oldVal == null) {
            oldVal = '<?=$this->request->data['Test']['education_level_year']?>';
        }

        <?php
        foreach($levelyears as $year => $years) {
        ?>
        if (val == '<?=$year?>') {
            years = '<?=$years?>';
        }
        <?php
        }
        ?>

        $("#TestEducationLevelYear option").remove();

        for (var i = 1; i <= years; i++) {
            $("#TestEducationLevelYear").append('<option value="' + i + '">' + i + '</option>');
        }

        $("#TestEducationLevelYear").val(oldVal);
    }

    updateEducationYears();
</script>
