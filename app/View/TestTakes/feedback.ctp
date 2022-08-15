<div class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 2rem!important;">
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
<? if($data['mode'] === 'write'){?>
<? if($data['has_feedback']){?>
        <div class="input-group">
            <? foreach($data['answer']['feedback'] as $feedback){ ?>
                <textarea class="wsc_disabled" <?= $data['mode'] === 'read' ? 'readonly' : 'onkeyup="calcMaxLength();"'?> id="message_<?= getUUID($data['answer'], 'get') ?>" width="200px" height="260px" style="min-height: 260px; line-height: 1.5rem" autofocus maxlength="240"><?= $feedback['message'] ?></textarea>
            <? } ?>
        </div>
<? }else{ ?>
        <div class="input-group">
            <textarea class="wsc_disabled" <?= $data['mode'] === 'read' ? 'readonly' : 'onkeyup="calcMaxLength();"'?> id="message_<?= getUUID($data['answer'], 'get') ?>" width="200px" height="260px" autofocus maxlength="240"></textarea>
        </div>
<? } ?>
<? }else{ ?>
    <div class="input-group">
        <div style="display: block; border: 1px solid #d1d1d1; padding:10px; width: 600px; height: 260px;overflow: auto;">
        <? foreach($data['answer']['feedback'] as $feedback){ ?>
            <?= $feedback['message'] ?>
        <? } ?>
        </div>
    </div>
<? } ?>
    <div>
<? if($data['mode'] === 'write'){ ?>
    <div class="popup-footer tat-footer pt16" style="padding: 0!important; padding-top: 1rem!important;">
        <div style="display: flex;align-items:center;justify-content:flex-end;width: 100%">
            <div class="body2">
                <button href="#" class="btn button button-sm cta-button pull-right" onclick="saveFeedback('<?= getUUID($data['answer'], 'get') ?>')">
                    <span><?= __("Opslaan")?></span>
                </button>

                <? if($data['has_feedback']){
                    foreach($data['answer']['feedback'] as $feedback){
                    ?>
                    <button href="#" class="btn button button-sm mr5 pull-right" style="color: red; background: white;" onclick="removeFeedback('<?=getUUID($feedback, 'get')?>');" selid="cancel-btn">
                        <?= __("Verwijderen")?>
                    </button>
                <? }}else{?>
                    <button href="#" class="btn button button-sm mr5 pull-right" style="color: #041f74; background: white;" onclick="Popup.closeLast();" selid="cancel-btn">
                        <?= __("Annuleren")?>
                    </button>
                <? } ?>

            </div>
        </div>
    </div>
<? } ?>


<script>
    function calcMaxLength() {
        var max = $('#message_<?= getUUID($data['answer'], 'get') ?>').attr('maxlength');
        var chars = $('#message_<?= getUUID($data['answer'], 'get') ?>').val().length;

        if(chars === 0){
            $('#barInputLength').css({'color': '#337ab7'});
        }else{
            $('#barInputLength').css({'color': 'white'});
        }
        $('#barInputLength').css({
            'width': ((100 / max) * chars) + '%'
        }).html(chars + '/240 ' + '<?= __('tekens') ?>' );
    }

    function saveFeedback(answer_id){
        CKEDITOR.instances['message_'+answer_id].updateElement();
        $.post(
            "test_takes/saveFeedback",
            {
                answer_id: answer_id,
                message:$('#message_'+answer_id).val()
            },
            function (data, status) {
                data = JSON.parse(data)
                if (data.status) {
                    Notify.notify("<?= __('feedback opgeslagen') ?>", 'info');
                    Popup.closeLast();
                    TestTake.changeFeedbackButtonText('<?= getUUID($data['answer']['test_participant'], 'get') ?>', '<?= getUUID($data['answer']['question'], 'get') ?>');
                } else {
                    Notify.notify("<?= __('feedback is niet opgeslagen') ?>", 'error');
                }
            }
        );
    }

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
                    TestTake.changeFeedbackButtonText('<?= getUUID($data['answer']['test_participant'], 'get') ?>', '<?= getUUID($data['answer']['question'], 'get') ?>', true);
                } else {
                    Notify.notify("<?= __('feedback kan niet worden verwijderd') ?>", 'error');
                }
            });
        });
    }


    if('<?= $data['mode'] === 'write' ? 'true' : false ?>'){
        $(document).ready(function(){
            var editor = CKEDITOR.instances['message_<?= getUUID($data['answer'], 'get') ?>'];
            if (editor) {
                editor.destroy(true)
            }
            var editor<?= str_replace('-','_',getUUID($data['answer'], 'get')) ?> = CKEDITOR.replace('message_<?= getUUID($data['answer'], 'get') ?>', {
                toolbar: [
                    { name: 'clipboard', items: [ 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                    { name: 'insert', items: [ 'Table' ] },
                    '/',
                    { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
                    { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] }
                ],
                bodyClass: 'wsc_disabled',
                stylesSet: [
                    /* Inline Styles */
                    { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
                    { name: 'Cited Work', element: 'cite' },
                    { name: 'Inline Quotation', element: 'q' },

                    /* Object Styles */
                    {
                        name: 'Special Container',
                        element: 'div',
                        styles: {
                            padding: '5px 10px',
                            background: '#eee',
                            border: '1px solid #ccc'
                        }
                    },
                    {
                        name: 'Compact table',
                        element: 'table',
                        attributes: {
                            cellpadding: '5',
                            cellspacing: '0',
                            border: '1',
                            bordercolor: '#ccc'
                        },
                        styles: {
                            'border-collapse': 'collapse'
                        }
                    },
                    { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
                    { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
                ]
            })
            //editor<?//= str_replace('-','_',getUUID($data['answer'], 'get')) ?>//.on('instanceReady', function(editor) {
            //    setTimeout(function(){
            //        editor.editor.document.$.activeElement.setAttribute('data-wsc',"false");
            //    },100);
            //});
        });
    }else{
        $(document).ready(function(){
            if(!document.querySelector('#message_<?= getUUID($data['answer'], 'get') ?>')){
                return;
            }
            var editor = CKEDITOR.instances['message_<?= getUUID($data['answer'], 'get') ?>'];
            if (editor) {
                editor.destroy(true)
            }
            var editor<?= str_replace('-','_',getUUID($data['answer'], 'get')) ?> = CKEDITOR.replace('message_<?= getUUID($data['answer'], 'get') ?>', {
                readOnly : true,
                toolbar: [
                    { name: 'clipboard', items: [ 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                    { name: 'insert', items: [ 'Table' ] },
                    '/',
                    { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
                    { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] }
                ],
                bodyClass: 'wsc_disabled',
                stylesSet: [
                    /* Inline Styles */
                    { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
                    { name: 'Cited Work', element: 'cite' },
                    { name: 'Inline Quotation', element: 'q' },

                    /* Object Styles */
                    {
                        name: 'Special Container',
                        element: 'div',
                        styles: {
                            padding: '5px 10px',
                            background: '#eee',
                            border: '1px solid #ccc'
                        }
                    },
                    {
                        name: 'Compact table',
                        element: 'table',
                        attributes: {
                            cellpadding: '5',
                            cellspacing: '0',
                            border: '1',
                            bordercolor: '#ccc'
                        },
                        styles: {
                            'border-collapse': 'collapse'
                        }
                    },
                    { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
                    { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
                ]
            })
        });

    }

</script>

<? if ($data['mode'] === 'read'){ ?>
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

<? if(sizeof($data['answer']['feedback']) < 2){?>
    <style>
        textarea {
            min-height: 200px !important;
            line-height: 1.5rem
        }
    </style>
<?}else{?>
    <style>
        textarea {
            min-height: 100px !important;
            line-height: 1rem
        }
    </style>
<? } ?>