<div class="popup-head">Toets aanmaken</div>
<div class="popup-content">

    <?

    $levels = [];
    $levelyears = [];

    foreach ($education_levels as $education_level) {
        $levels[$education_level['id']] = $education_level['name'];
        $levelyears[$education_level['id']] = $education_level['max_years'];
    }

    if(empty($subjects)) {
        ?>
        <center>
            Er zijn nog geen vakken aan uw account gekoppeld, hierdoor kunt u geen toets aanmaken.
        </center>
        <?
    }else {
        ?>

        <?= $this->Form->create('Test') ?>
        <table class="table">
            <tr>
                <th width="140">
                    Titel
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
                    <?= $this->Form->input('abbreviation', array('style' => 'width: 145px', 'label' => false, 'verify' => 'notempty max-length-5', 'maxlength' => 5)) ?>
                </td>
            </tr>
            <tr>
                <th width="140">
                    Type
                </th>
                <td>
                    <?= $this->Form->input('test_kind_id', array('style' => 'width: 282px', 'label' => false, 'options' => $kinds, 'value' => 3)) ?>
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
                    <?= $this->Form->input('education_level_id', array('style' => 'width: 282px', 'label' => false, 'options' => $levels, 'onchange' => 'updateEducationYears();')) ?>
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

            <?php if($is_open_source_content_creator): ?>
              <tr>
                <th width="140">
                  Open source toets
                </th>
                <td>
                  <?= $this->Form->input('is_open_source_content',array('label'=>false, 'type'=>'checkbox','div'=>false,'checked'=>true)) ?>
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
                        'type' => 'textarea',
                        'label' => false
                    ]) ?>
                </td>
            </tr>
        </table>
        <?= $this->Form->end(); ?>
        <?
    }
    ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <?
    if(!empty($subjects)) {
        ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddTest">
            Toets aanmaken
        </a>
        <?
    }
    ?>
</div>

<script type="text/javascript">
    $('#TestAddForm').formify(
        {
            confirm : $('#btnAddTest'),
            onsuccess : function(result) {

                Navigation.load('/tests/view/' + result.uuid);
                Popup.closeLast();
                Notify.notify("Toets aangemaakt", "info");
                setTimeout(function() {
                    Popup.load('/questions/add_custom/test/' + result.uuid, 800);
                }, 1000);
            },
            onfailure : function(result) {

                if(result == 'unique_name') {
                    Notify.notify("De gekozen titel is al in gebruik. Gebruik een unieke titel.", "error");
                }else{
                    Notify.notify("Toets kon niet worden aangemaakt", "error");
                }
            }
        }
    );

    function updateEducationYears() {
        var val = $('#TestEducationLevelId').val();
        var years = 0;
        <?php
        foreach($levelyears as $year => $years) {
            ?>
            if(val == '<?=$year?>') {
                years = '<?=$years?>';
            }
            <?php
        }
        ?>

        $("#TestEducationLevelYear option").remove();

        for(var i = 1; i <= years; i++) {
            $("#TestEducationLevelYear").append('<option value="' + i + '">' + i + '</option>');
        }
    }

    updateEducationYears();
</script>
