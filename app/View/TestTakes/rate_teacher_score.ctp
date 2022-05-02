
<?
if(!empty($answer['note'])) {
    ?>
    <a href="#" class="btn highlight" onclick="Popup.load('/answers/show_note/<?=$participant_id?>/<?=$question_id?>', 600); return false;" style="margin-bottom: 10px;">
    <?= __("Notitie inzien")?>
    </a>
    <?
}
?>

<table width="100%" cellpadding="0" cellspacing="0">
    <?
    $student = 1;
    $student1 = null;
    $student2 = null;
    $score = '0.0';
    $teacher = 0;
    $system = 0;
    $ratingId = 0;

    foreach($answer['answer_ratings'] as $rating) {
        ?>
        <tr>
            <th>
                <?
                switch($rating['type']) {
                    case 'SYSTEM' :
                        echo __("Systeem");
                        $score = $rating['rating'];
                        $system = $rating['rating'];
                        break;

                    case 'STUDENT' :

                        echo $rating['user']['name_first'] . ' ';

                        if(!empty($rating['user']['name_suffix'])) {
                            echo $rating['user']['name_suffix'] . ' ';
                        }

                        echo $rating['user']['name'];

                        if($student == 1) {
                            $student1 = $rating['rating'];
                        }else{
                            $student2 = $rating['rating'];
                        }

                        $student ++;
                        break;

                    case 'TEACHER' :
                        echo __("Docent");

                        $teacher = $rating['rating'];
                        $ratingId = getUUID($rating, 'get');

                        break;
                }
                ?>
            </th>
            <td><?=$rating['rating'];?></td>
        </tr>
        <?
    }
    ?>
</table>

<? if($editable) { ?>
    <div id="scoreval_<?=$participant_id.$question_id?>" style="text-align: center; padding:10px">
        <?=$teacher?>
    </div>

    <input type="hidden" id="score_input_<?=$participant_id.$question_id?>" />

    <div id="rating_<?=$participant_id.$question_id?>"></div>

    <script type="text/javascript">
        $(function() {
            $("#rating_<?=$participant_id.$question_id?>").slider({
                value:<?=$teacher?>,
                min: 0,
                max: <?=$question['question']['score']?>,
                step: <?= $question['question']['decimal_score'] == 1 ? 0.5 : 1 ?>,
                slide: function (event, ui) {
                    $("#scoreval_<?=$participant_id.$question_id?>").html(ui.value + ' pt');
                    $('#score_input_<?=$participant_id.$question_id?>').val(ui.value);
                },
                stop: function (event, ui) {
                    $("#rating_<?=$participant_id.$question_id?>").hide();
                    TestTake.saveTeacherRating('<?=getUUID($answer, 'get');?>', $('#score_input_<?=$participant_id.$question_id?>').val(), '<?=$participant_id?>', '<?=$ratingId?>', '<?=$question_id?>');
                }
            });
            $("#scoreval_<?=$participant_id.$question_id?>").html($("#rating_<?=$participant_id.$question_id?>").slider("value") + ' pt');

            <?php

            if(($student1 != $student2 || $student1 == null && $student2 == null) && empty($teacher) && empty($system)) {
                ?>
                $('#questionblock_<?=$participant_id.$question_id?>').show();
                <?php
            }elseif(empty($student1) && empty($teacher) && empty($system)){
                ?>
                $('#questionblock_<?=$participant_id.$question_id?>').show();
                <?php
            }else{
                ?>
                $('#btnShowAll').slideDown();
                <?php
            }
            ?>
        });

        if(<?=$answer['has_feedback_by_this_user'] ? 'true' : 'false'?>){
            TestTake.changeFeedbackButtonText('<?=$participant_id?>', '<?=$question_id?>');
        }
    </script>
<? }?>