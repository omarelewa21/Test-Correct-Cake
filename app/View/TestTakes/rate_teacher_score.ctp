
<?
if(!empty($answer['note'])) {
    ?>
    <a href="#" class="btn highlight" onclick="Popup.load('/answers/show_note/<?=$participant_id?>/<?=$question_id?>', 600); return false;" style="margin-bottom: 10px;">
        Notitie inzien
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
                        echo 'Systeem';
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
                        echo 'Docent';

                        $teacher = $rating['rating'];
                        $ratingId = $rating['id'];

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
                    var score = $('#score_input_<?=$participant_id.$question_id?>').val();

                    <?
                    //fix for bug TC-18
                    //value of score is empty if slider is not dragged
                    //if it is empty, we don't want to update the score
                    ?>
                    if (score != '') {
                        $("#rating_<?=$participant_id.$question_id?>").hide();
                        TestTake.saveTeacherRating(<?=$answer['id']?>, score, <?=$participant_id?>, <?=$ratingId?>, <?=$question_id?>);
                    }
                }
            });
            $("#scoreval_<?=$participant_id.$question_id?>").html($("#rating_<?=$participant_id.$question_id?>").slider("value") + ' pt');

            <?

            if(($student1 != $student2 || $student1 == null && $student2 == null) && empty($teacher) && empty($system)) {
                ?>
                $('#questionblock_<?=$participant_id.$question_id?>').show();
                <?
            }elseif(empty($student1) && empty($teacher) && empty($system)){
                ?>
                $('#questionblock_<?=$participant_id.$question_id?>').show();
                <?
            }else{
                ?>
                $('#btnShowAll').slideDown();
                <?
            }
            ?>
        });
    </script>
<? }?>