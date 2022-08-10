<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);


?>
<div class="cke_editor_overlay_<?=$participantIdentifier;?>" ></div>
<?=$this->Form->input('answer'.$participantIdentifier, ['type' => 'textarea',
                                                        'style' => 'width:99%; height:70px; margin-top:20px;',
                                                        'label' => false,
                                                        'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $answer['value']),
                                                        'tabindex'=>'-1'])
?>
<script type="text/javascript">
    var readOnlyForWsc = true;
    var spellcheckAvailable = false;
    var lang = '<?=$lang?>'

    <?php if($spellCheckAvailable){ ?>
        readOnlyForWsc = false;
        spellcheckAvailable = true;
    <?php } ?>

    CkeditorTlcMethods.initRateOpenLong('<?=$participantIdentifier;?>',spellcheckAvailable,readOnlyForWsc,lang);
</script>
<style>
    #cke_answer<?=$participantIdentifier;?>>.cke_inner>.cke_top{
        display: none;
    }
</style>