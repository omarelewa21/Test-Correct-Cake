<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);


?>
<div class="cke_editor_overlay" ></div>
<?=$this->Form->input('answer'.$participantIdentifier, ['type' => 'textarea', 'style' => 'width:99%; height:70px; margin-top:20px;', 'label' => false, 'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $answer['value'])])?>
<script type="text/javascript">
    var readOnlyForWsc = true;
    <?php if(AuthComponent::user('school_location.allow_wsc')===1){ ?>
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.config.removePlugins = 'scayt,wsc';
        CKEDITOR.on('instanceReady', function(event) {
            var editor = event.editor;
            var instance = WEBSPELLCHECKER.init({
                container: editor.window.getFrame() ? editor.window.getFrame().$ : editor.element.$,
                spellcheckLang: '<?=$lang?>',
                localization: 'nl'
            });
            try {
                instance.setLang('<?=$lang?>');
            } finally {
                Overlay.overCkeditor4('.cke_editor_overlay',editor);
            }
        });
        readOnlyForWsc = false;
    <?php } ?>

    var editor<?=$participantIdentifier;?> = CKEDITOR.replace( 'answer<?=$participantIdentifier;?>', {
        toolbar : [ [ ] ],
        readOnly : readOnlyForWsc,
        extraPlugins: 'wordcount,notification,autogrow,ckeditor_wiris',
        removePlugins: 'resize',
        autoGrow_onStartup : true,
        contentsCss : '/ckeditor/rate.css'
    });

    editor<?=$participantIdentifier;?>.on('instanceReady',function(){
        setTimeout(function(){editor<?=$participantIdentifier;?>.execCommand( 'autogrow' )},1000);
        //setTimeout(function(){editor<?//=$participantIdentifier;?>//.setReadOnly()},5000);
    });



</script>
<style>
    #cke_answer<?=$participantIdentifier;?>>.cke_inner>.cke_top{
        display: none;
    }
</style>