<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

?>
<?=$this->Form->input('answer'.$question_id, ['type' => 'textarea', 'style' => 'width:99%; height:70px; margin-top:20px;', 'label' => false, 'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $answer['value'])])?>
<script type="text/javascript">

    var editor = $('#answer<?=$question_id;?>').ckeditor({   toolbar : [ [ ] ],
        readOnly : true,
        extraPlugins : 'wordcount,notification,autogrow',
        autoGrow_onStartup : true
    } );

    editor.trigger('focus');


</script>
<style>
    #cke_answer<?=$question_id;?>>.cke_inner>.cke_top{
        display: none;
    }
</style>