<div class="popup-head">
    <?= __("Inzien door Studenten") ?>
</div>
<div class="popup-content">
    <div class="alert alert-info">
        <?= __("Mogelijkheid voor Studenten om het resultaat van hun eigen toets in te zien.") ?>
    </div>

    <?= $this->Form->create('TestTake') ?>
    <div style="display: flex; align-items: center; gap: 0.5rem;margin-top:0.5rem">
        <label class="switch">
            <?= $this->Form->input('active', [
                'type'     => 'checkbox',
                'checked'  => !empty($take['show_results']),
                'onchange' => 'updateSpecifyDate(this);',
                'label'    => false,
                'div'      => false,
                'style'    => 'width:20px;',
            ]) ?>
            <span class="slider round"></span>
        </label>

        <span><?= __("Mogelijkheid tot inzien activeren") ?></span>
    </div>

    <div class="well well-sm mt10"
         style="
             margin-bottom: 0;
             text-align: center;
         <?= !isset($take['show_results']) ? 'display: none;' : '' ?>
             "
         id="specifyDate"
    >
        <?= __("Inzien mogelijk tot") ?><br/>
        <?= $this->Form->input('show_results', [
            'type'  => 'text', 'class' => 'mt5',
            'value' => $take['show_results'], 'label' => false,
            'value' => (empty($take['show_results']) || is_null($take['show_results']))
                ? date('d-m-Y H:i', strtotime('+ 20 min'))
                : date('d-m-Y H:i', strtotime($take['show_results']))
        ])
        ?>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top:0.5rem">
        <label class="switch">
            <?= $this->Form->input('show_grades', [
                'class'   => 'mt10',
                'type'    => 'checkbox',
                'checked' => $take['show_grades'],
                'label'   => false,
                'div'     => false,
                'style'   => 'width:20px;',
            ]) ?>
            <span class="slider round"></span>
        </label>

        <span><?= __("Toon cijfers") ?></span>
    </div>

    <?php if (AuthComponent::user('school_location.feature_settings.allow_new_reviewing')) { ?>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top:0.5rem">
            <label class="switch">
                <?= $this->Form->input('show_correction_model', [
                    'class'   => 'mt10',
                    'type'    => 'checkbox',
                    'checked' => $take['show_correction_model'],
                    'label'   => false,
                    'div'     => false,
                    'style'   => 'width:20px;',
                ]) ?>
                <span class="slider round"></span>
            </label>

            <span><?= __("Toon antwoordmodel") ?></span>
        </div>
    <?php } ?>

    <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        <?= __("Sluiten") ?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        <?= __("Toepassen") ?>
    </a>
</div>

<script type="text/javascript">

    function updateSpecifyDate(e) {
        if ($(e).is(':checked')) {
            $('#specifyDate').show();
        } else {
            $('#specifyDate').hide();
        }
    }

    function NietLatenInzien() {
        Popup.closeLast();
        TestTake.loadDetails(this, '<?=getUUID($take, 'get');?>');
    }

    $('#TestTakeShowResults').datetimepicker({
        format: 'd-m-Y H:i'
    });

    $('#TestTakeUpdateShowResultsForm').formify(
        {
            confirm: $('#btnSave'),
            onsuccess: function (result) {
                Popup.closeLast();
                TestTake.loadDetails(this, '<?=getUUID($take, 'get');?>');
            },
            onfailure: function (result) {

            }
        }
    );
</script>