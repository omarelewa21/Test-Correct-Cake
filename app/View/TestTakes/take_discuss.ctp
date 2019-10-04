<div id="buttons">

</div>

<h1>Vraag beoordelen</h1>

<div class="block">
    <div class="block-head">Antwoord</div>
    <div class="block-content" id="div_answer">

    </div>
</div>

<div class="block" style="width:250px; margin: 0px auto;" id="divRate">
    <div class="block-head">Beoordeling</div>
    <div class="block-content" id="div_rating">

        <div class="mr25 ml25" style="font-size:30px; text-align:center; border:2px solid #eeeeee;border-radius:8px;display:flex;align-items:center;justify-content: center;" id="divScore"> <span style="color:#3D9D36;">?</span> </div>
        

        <div class="mt35 mb5" id="slider"></div>

        <input type="hidden" id="answerRating" value="0" />
    </div>
</div>
<div class="mt10 mb10" style="margin-left:auto;margin-right:auto;width:250px;text-align:center">
    <a href="#" class="btn highlight" onclick="saveRating()">Beoordeling opslaan</a>
</div>

<script>
    var rated = false;
    $(function() {
        jQuery("#divScore").height(jQuery("#divScore").width()*0.75);
        $( "#slider" ).slider({
            value: 0,
            min: 0,
            max: <?=$rating['answer']['question']['score']?>,
            step: <?= $rating['answer']['question']['decimal_score'] == 1 ? 0.5 : 1 ?>,
            slide: function( event, ui ) {
                rated = true;
                $( "#divScore" ).html( ui.value + ' pt' );
                $('#answerRating').val( ui.value );
            },
            stop: function(event,ui){
                if($("#divScore span").html() == '?'){
                    $("#divScore span").html(ui.value);
                }
            },
        });
        jQuery("#slider").on('slidestart',function(event,ui){
            rated = true;
        });

        // $( "#divScore" ).html( $( "#slider" ).slider( "value" ) + ' pt' );
        $('#answerRating').val( $( "#slider" ).slider( "value" ) );

        Loading.discard = true;
        $('#div_answer').load('/test_takes/rate/<?=$take_id?>');
    });


    TestTake.discussingQuestionId = <?=$rating['answer']['question_id']?>;
    TestTake.startHeartBeat('rating');

    function saveRating(){
        if(!rated){
            Notify.notify('Je dient eerst een beoordeling te geven','error');
        }else{
            TestTake.saveRating();
        }
    }
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
        rated = true;
    }
</script>

