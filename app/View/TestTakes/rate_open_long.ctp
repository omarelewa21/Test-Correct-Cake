<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

?>
<?=$this->Form->input('answer'.$participantIdentifier, ['type' => 'textarea', 'style' => 'width:99%; height:70px; margin-top:20px;', 'label' => false, 'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $answer['value'])])?>
<script type="text/javascript">

    var editor<?=$participantIdentifier;?> = CKEDITOR.replace( 'answer<?=$participantIdentifier;?>', {
        toolbar : [ [ ] ],
        readOnly : true,
        extraPlugins: 'wordcount,notification,autogrow',
        removePlugins: 'resize',
        autoGrow_onStartup : true
    });

    editor<?=$participantIdentifier;?>.on('instanceReady',function(){
        setTimeout(function(){editor<?=$participantIdentifier;?>.execCommand( 'autogrow' )},1000);
    });




</script>
<style>
    #cke_answer<?=$participantIdentifier;?>>.cke_inner>.cke_top{
        display: none;
    }
</style>