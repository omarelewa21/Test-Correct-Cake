<div class="popup-head">Vraag voorbeeld</div>
<div class="popup-content">

    <div class="tabs">
        <?
        $i = 1;
        $first = true;
        foreach($questions as $question) {
            ?>
            <a href="#" class="btn grey <?=$first ? 'highlight' : ''?>" page="question_preview_<?=$question['question_id']?>" tabs="groep_question_preview">
                Vraag <?=$i?>
            </a>
            <?
            $first = false;
            $i++;
        }
        ?>

        <br clear="all">
    </div>

    <?
    $first = true;
    foreach($questions as $question) {
        ?>
        <div class="page <?=$first ? 'active' : ''?>" page="question_preview_<?=$question['question_id']?>" tabs="groep_question_preview" id="question_preview_<?=$question['question_id']?>"></div>
        <script type="text/javascript">
            $('#question_preview_<?=$question['question_id']?>').load('/questions/preview_single_load/<?=$question['question_id']?>');
        </script>
        <?
        $first = false;
    }
    ?>

</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
</div>

<script>
    $(function () {
        $('#groupTab').tab();
    })
</script>
<?=$this->element('question_styling',['question' => $question]);?>