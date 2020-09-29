<a href="#" class="btn highlight" id="btnHandIn" onclick="Navigation.back();">
    Terug
</a>

<br clear="all" />
<?
foreach($answers as $answer) {

    ?>

    <div class="block">
        <div class="block-head">Vraag</div>
        <div class="block-content" id="participant_question_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>">

        </div>
    </div>

    <div class="block" style="border-left: 3px solid #197cb4;">
        <div class="block-head">Antwoordmodel</div>
        <div class="block-content" id="participant_question_answer_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>">

        </div>
    </div>

    <div class="block" style="float:left; width:calc(100% - 250px);">
        <div class="block-head">
            Antwoord
        </div>
        <div id="participant_answer_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['uuid']?>" class="block-content">
            Laden..
        </div>
    </div>

    <div class="block" style="float:right; width: 230px; border-left: 3px solid #3D9D36">
        <div class="block-head">Score</div>
        <div class="block-content" id="score_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>">
            --
        </div>
    </div>

    <br clear="all" />
    <script type="text/javascript">
        $('#participant_question_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>').load('/questions/preview_single_load/<?=$answer['answer']['question']['uuid']?>');
        $('#participant_question_answer_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>').load('/questions/preview_answer_load/<?=$answer['answer']['question']['uuid']?>');
        $('#participant_answer_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['uuid']?>').load('/test_takes/rate_teacher_answer/<?=$answer['answer']['testparticipant']['uuid']?>/<?=$answer['answer']['question']['uuid']?>',
            function() {
                $('#score_<?=$answer['answer']['testparticipant']['uuid']?><?=$answer['answer']['question']['uuid']?>').load('/test_takes/rate_teacher_score/<?=$answer['answer']['testparticipant']['uuid']?>/<?=$answer['answer']['question']['uuid']?>');
            }
        );
    </script>
    <?
}
?>