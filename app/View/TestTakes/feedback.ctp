<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 2rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0; margin-right:20px; font-size: 2rem;">
                <?= __("Feedback bij vraag ") . ($data['q_index'] !== 'noIndex' ? $data['q_index']+1 : '') ?>
            </h2>
        </div>

        <div class="close" style="flex-shrink: 1; display:flex; align-items: center;">
            <div style="cursor:pointer; background: #cedaf3; border-radius: 50%; width: 20px; padding: 2px; text-align:center; margin-right: 16px;">
                <i class="fa fa-question" aria-hidden="true" style="color: #041f74"></i>
            </div>
            <a href="#" onclick="Popup.closeLast();">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="divider mb16 mt16"></div>
    <div class="body2">
        <? if($data['mode'] === 'write'){ ?>
            <p class="ml5"><?= __('Laat feedback bij deze vraag achter voor de student.') ?></p>
        <? }else{?>
            <p class="ml5"><?= __('Dit is de feedback van jouw docent bij deze vraag.') ?></p>
        <? } ?>
        
    </div>
</div>



    
<div class="tat-content body1" style="padding-top: 5px !important;">
    <div class="input-group">
        <textarea <?= $data['mode'] === 'read' ? 'readonly' : 'onkeyup="countCharacters()"'?> id="message" width="200px" height="200px" autofocus maxlength="240"><?= $data['feedback'] ? $data['answer']['feedback']['message'] : '' ?></textarea>
    </div>
    <div>
        <span id="maxCharacters" class="tip"></span>
    </div>
<div>

<? if($data['mode'] === 'write'){ ?>
    <div class="popup-footer tat-footer pt16" style="padding: 0!important; padding-top: 2rem!important;">
        <div style="display: flex;align-items:center;justify-content:flex-end;width: 100%">
            <div class="body2">
                <button href="#" class="btn button button-sm cta-button pull-right" id="btnSave">
                    <span><?= __("Opslaan")?></span>
                </button>
                
                <? if($data['feedback']){ ?>
                    <button href="#" class="btn button button-sm mr5 pull-right" style="color: red; background: white;" onclick="removeFeedback('<?=getUUID($data['answer']['feedback'], 'get')?>');" selid="cancel-btn">
                        <?= __("Verwijderen")?>
                    </button>
                <? }else{?>
                    <button href="#" class="btn button button-sm mr5 pull-right" style="color: #041f74; background: white;" onclick="Popup.closeLast();" selid="cancel-btn">
                        <?= __("Annuleren")?>
                    </button>
                <? } ?>
                
            </div>
        </div>
    </div>
<? } ?>


<script>
    function countCharacters(){
        var max = $('#message').attr('maxlength');
        var chars = $('#message').val().length;

        $('#maxCharacters').html( chars + '<?= __(" van ")?>'+ max + '<?= __(" karakters")?>');

        if (chars >= max) {
            $('#maxCharacters').parent().addClass('notification error ');
            $('#maxCharacters').addClass('black');
        } else {
            $('#maxCharacters').parent().removeClass('notification error black');
            $('#maxCharacters').removeClass('black');
        }
    };

    $( "#btnSave" ).click(function() {
        var data = <?php echo json_encode($data); ?>;
        data.message = $('#message').val();
        $.post(
            "test_takes/saveFeedback",
            data,
            function (data, status) {
                data = JSON.parse(data)
                if (data.status) {
                    Notify.notify("<?= __('feedback opgeslagen') ?>", 'info');
                    Popup.closeLast();
                    changeFeedbackButtonText('<?= getUUID($data['answer']['test_participant'], 'get') ?>', '<?= getUUID($data['answer']['question'], 'get') ?>');
                } else {
                    Notify.notify("<?= __('feedback is niet opgeslagen') ?>", 'error');
                }
            }
        );
    });

    function removeFeedback(feedback_id){
        Popup.message({
            btnOk: '<?= __('Ja') ?>',
            btnCancel: '<?= __('Annuleer') ?>',
            title: '<?= __('Weet u het zeker?') ?>',
            message: '<?= __('Weet u zeker dat u deze feedback wilt verwijderen?') ?>'
        }, function() {
            $.post(
            'test_takes/deleteFeedback/' + feedback_id,
            function (data, status) {
                data = JSON.parse(data)
                if (data.status) {
                    Notify.notify("<?= __('feedback verwijderd') ?>", 'info');
                    Popup.closeLast();
                    changeFeedbackButtonText('<?= getUUID($data['answer']['test_participant'], 'get') ?>', '<?= getUUID($data['answer']['question'], 'get') ?>', true);
                } else {
                    Notify.notify("<?= __('feedback kan niet worden verwijderd') ?>", 'error');
                }
            });
        });
    }

    if(<?= $data['mode'] === 'write' ? 'true' : 'false' ?>){
        $(document).ready(countCharacters());
    }
</script>

<? if ($data['mode'] == 'read'){ ?>
    <style>
        .tat-content textarea {
            background: white !important;
            border: 1px solid #efeeee !important;
        }
        .tat-content textarea:hover {
            border: 1px solid #efeeee !important;
        }

        .body1 {
            min-height: 180px !important;
        }
    </style>
<? } ?>