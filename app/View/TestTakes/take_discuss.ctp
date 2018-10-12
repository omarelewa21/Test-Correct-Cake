<div id="buttons">
    <a href="#" class="btn highlight mr2" onclick="TestTake.saveRating();">Beoordeling opslaan</a>
</div>

<h1>Vraag beoordelen</h1>

<div class="block">
    <div class="block-head">Antwoord</div>
    <div class="block-content" id="div_answer">

    </div>
</div>

<div class="block" style="width:500px; margin: 0px auto;" id="divRate">
    <div class="block-head">Beoordeling</div>
    <div class="block-content" id="div_rating">

        <div style="font-size:30px; text-align:center; margin-bottom:35px;" id="divScore"></div>
        
        <table width="448" style="position:absolute;height:30px; margin-left:2px;">
            <tr>
                <?
                if($rating['answer']['question']['decimal_score'] == 1) {
                    ?>
                    <td style="border-bottom: 1px solid #689237;">&nbsp;</td>
                    <?
                }else{
                    ?>
                    <td style="border-bottom: 1px solid #689237; border-right: 1px solid #689237;">&nbsp;</td>
                    <?
                }

                $options = $rating['answer']['question']['score'];

                for($i = 1; $i < $options; $i++) {
                    ?>
                    <td style="border-right: 1px solid #689237; border-bottom: 1px solid #689237;">&nbsp;</td>
                    <?

                    if($rating['answer']['question']['decimal_score'] == 1) {
                        ?>
                        <td style="border-bottom: 1px solid #689237;">&nbsp;</td>
                        <?
                    }
                }

                if($rating['answer']['question']['decimal_score'] == 1) {
                ?>
                <td style="border-right: 1px solid #689237; border-bottom: 1px solid #689237;">&nbsp;</td>
                <? } ?>
            </tr>
            <tr>
                <td style="border-right: 1px solid #689237;">&nbsp;</td>

                <?
                $options = $rating['answer']['question']['score'];

                for($i = 1; $i < $options; $i++) {
                    ?>
                    <td style="border-right: 1px solid #689237;">&nbsp;</td>
                    <?

                    if($rating['answer']['question']['decimal_score'] == 1) {
                        ?>
                        <td style="border-right: 1px solid #689237;">&nbsp;</td>
                    <?
                    }
                }

                if($rating['answer']['question']['decimal_score'] == 1) {
                ?>
                <td style="border-left: 1px solid #689237; border-right: 1px solid #689237;">&nbsp;</td>
                <? } ?>
            </tr>
        </table>
        <div id="slider"></div>

        <br /><br />

        <input type="hidden" id="answerRating" value="0" />
    </div>
</div>
<script>
    $(function() {
        $( "#slider" ).slider({
            value: 0,
            min: 0,
            max: <?=$rating['answer']['question']['score']?>,
            step: <?= $rating['answer']['question']['decimal_score'] == 1 ? 0.5 : 1 ?>,
            slide: function( event, ui ) {
                $( "#divScore" ).html( ui.value + ' pt' );
                $('#answerRating').val( ui.value );
            }
        });

        $( "#divScore" ).html( $( "#slider" ).slider( "value" ) + ' pt' );
        $('#answerRating').val( $( "#slider" ).slider( "value" ) );

        Loading.discard = true;
        $('#div_answer').load('/test_takes/rate/<?=$take_id?>');
    });

    TestTake.discussingQuestionId = <?=$rating['answer']['question_id']?>;
    TestTake.startHeartBeat('rating');
</script>

<script type="text/javascript">
    function toggleOption(e) {

        if($(e).hasClass('fa-remove')) {
            $(e).removeClass('fa-remove').addClass('fa-check').css({
                'color' : 'green'
            });
        }else{
            $(e).removeClass('fa-check fa-question').addClass('fa-remove').css({
                'color' : 'red'
            });
        }

        var ok = 0;

        $.each($('.toggleOption'), function() {
            if($(this).hasClass('fa-check')) {
                ok++;
            }
        });

        $( "#slider" ).slider( "value", (<?=$rating['answer']['question']['score']?> / $('.toggleOption').length) * ok );
        $( "#divScore" ).html( $( "#slider" ).slider( "value" ) + ' pt' );
        $('#answerRating').val($( "#slider" ).slider( "value" ));
    }
</script>

<style type="text/css">
    .ui-widget-content {
        border: none;
        background: none;
        outline: none;
    }

    .ui-slider-horizontal .ui-state-default {
        background: url(/img/handle.png) no-repeat scroll 50% 50%;
        height: 64px;
        top: -30px;
        border: none;
    }
</style>