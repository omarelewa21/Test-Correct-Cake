<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?>"><?= $question['subtype'] == __("Matching") ? __("Combineervraag") : __("Rubriceer-vraag")?><?=AppHelper::showExternalId($question);?></h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?>
</div>

<div style="font-size: 20px;">
    <?

    $listLeft = [];
    $listRight = [];

    foreach($question['matching_question_answers'] as $option) {
        if($option['type'] == 'LEFT') {
            $listLeft[] = $option;
        }elseif($option['type'] == 'RIGHT') {
            $listRight[] = $option;
        }
    }
    ?>
</div>

<?php

echo sprintf('<div class="answer_container %s">',$citoClass);
    ?>

    <div style="width:300px; padding:20px; float:left;">
        <?
        foreach($listLeft as $item) {
            ?>
            <div style="margin-bottom: 5px; border:1px grey dotted; text-align: center; height:130px; padding:20px;" class="left_item" id="<?=getUUID($item, 'get')?>">
                <strong><?=$item['answer']?></strong>
            </div>
        <?
        }
        ?>
    </div>

    <div style="width:200px; float:left; margin-left: 20px;">
        <?
        shuffle($listRight);

        foreach($listRight as $item) {
            ?>
            <div style="background: grey; padding:10px; margin: 2px;" id="<?=getUUID($item, 'get')?>" class="right_item">
                <?=$item['answer']?>
            </div>
            <?
        }
        ?>
    </div>
</div>
<br clear="all" />

<script>
    $(function() {
        $( ".right_item" ).draggable();
        $( ".left_item" ).droppable({
            drop: function( event, ui ) {
                var left_id = this.id;
                var right_id = ui.helper[0].id;

                $('#Answer' + right_id).val(left_id);

            }
        });
    });
</script>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview('<?=$test_id?>', '<?=$next_question?>');">
            <span class="fa fa-check"></span>
            <?= __("Volgende vraag")?>
        </a>
    </center>
<? } ?>
<?=$this->element('question_styling',['question' => $question]);?>