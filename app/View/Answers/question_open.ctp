<?= $this->element('take_attachments', ['question' => $question]); ?>

<h1>Open vraag [<?= $question['score'] ?>pt]<?=AppHelper::showExternalId($question);?></h1>
<?= $this->element('take_question', ['question' => $question]) ?>

<?
if(empty($answerJson)) {
$value = "";
}else{
$value = $answerJson['value'];
}
?>

<br clear="all" />
<?= $this->Form->create('Answer') ?>
<? if($question['subtype'] == 'short') { ?>
<?= $this->Form->input('answer', ['type' => 'textarea', 'spellcheck' => 'false', 'style' => 'width:99%; height:14px; margin-top:20px;', 'maxlength' => '140', 'label' => false, 'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $value), 'onkeyup' => 'calcMaxLength(this);']) ?>
<div class="progress" style="margin: 10px 0px 0px 0px; background: white;">
    <div class="progress-bar" id="barInputLength" role="progressbar" aria-valuemin="0" aria-valuemax="100">
        0/140 tekens
    </div>
</div>
<? }else{ ?>
<?= $this->Form->input('answer', ['type' => 'textarea', 'spellcheck' => 'false', 'style' => 'width:99%; height:300px; margin-top:20px;', 'label' => false, 'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $value), 'onchange' => 'Answer.answerChanged = true;']) ?>
<? } ?>
<?= $this->Form->end(); ?>

<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<? if($question['subtype'] !== 'short') {?>
<script type="text/javascript">
    $('textarea').ckeditor({

      removePlugins : 'pastefromword,advanced,simpleuploads,dropoff,copyformatting,image,pastetext,uploadwidget,uploadimage',
      extraPlugins : 'blockimagepaste,quicktable,ckeditor_wiris',
      // ImageUpload : false,

        toolbar: [
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList' ] },
            { name: 'insert', items: [ 'Table' ] },
            // { name: 'editing', items: ['EqnEditor' ] },
            { name: 'styles', items: ['Font', 'FontSize' ] },
            {name: 'wirisplugins', items: ['ckeditor_wiris_formulaEditor', 'ckeditor_wiris_formulaEditorChemistry']}
        ]
    });

    Answer.answerChanged = false;

    for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].on('change', function () {
            Answer.answerChanged = true;

        });
    }
</script>
<?
}
?>
<? if($question['subtype'] == 'medium'): ?>
    <script type="text/javascript">
        // $('textarea').redactor({
        //     buttons: ['bold', 'italic', 'orderedlist'],
        //     pastePlainText: true,
        //     plugins: ['table', 'scriptbuttons'],
        //     changeCallback : function(){
        //         Answer.answerChanged = true;
        //     }
        // });

        // Answer.answerChanged = false;
    </script>
<? endif; ?>
