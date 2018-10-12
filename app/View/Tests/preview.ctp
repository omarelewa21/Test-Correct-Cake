<div id="test_progress">
    <?
    $i = 0;
    foreach($questions as $questions) {

        if($question_index == $i) {
            $class = 'active';
        }else{
            $class = 'grey';
        }

        $i++;

        ?>
        <div class="question <?=$class?>" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=($i - 1)?>);"><?=$i?></div>
    <?
    }
    ?>
    <br clear="all" />
</div>


<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>

<iframe id="attachmentFrame" frameborder="0" scrolling="true" style="-webkit-overflow-scrolling: touch"></iframe>
<div id="attachmentFade"></div>

<div id="question_load"></div>

<script type="text/javascript">
    TestPreview.loadQuestion(<?=$test_id?>, <?=$question_index?>);
</script>