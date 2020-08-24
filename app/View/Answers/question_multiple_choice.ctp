<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
    <h1>
        <?
        if($question['subtype'] == 'TrueFalse') {
            ?>Juist / Onjuist<?
        }elseif($question['subtype'] == 'ARQ') {
            ?>ARQ<?
        }else{
            ?>Meerkeuze<?
        }
        ?>
        [<?=$question['score']?>pt]
    </h1>

<?php

    $useRadio = false;
    $radioOptions = [];
    $default = 0;
    if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $useRadio = true;
        $label = '<div class="radio_'.$question['id'].'">';
    }

    shuffle($question['multiple_choice_question_answers']);
?>

<div style="font-size: 20px;">
    <?=$this->element('take_question', ['question' => $question]);?>

    <?

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

        if(isset($answerJson[$answer['id']])) {
            if($answerJson[$answer['id']] == 1){
                    $checked = true;
                    $default = $answer['id'];
            } else {
                $checked = false;
            }
        }

        if(!$useRadio){

            echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked,
                'label' => false,
                'class' => 'multiple_choice_option input_'.$question['id'].' input_'.$answer['id'],
                'onchange' => 'checkMaxSelections(this)'
            ]).'&nbsp;'.$answer['answer'].'</div><br />';
        }
        else {
            $radioOptions[$answer['id']] = ' '.$answer['answer'];
            echo '
                        <span style="display:none">'.$this->Form->input('Answer.'.$answer['id'], [
                            'value' => 1,
                            'div' => false,
                            'type' => 'checkbox',
                            'checked' => $checked,
                            'label' => false,
                            'class' => 'multiple_choice_option input_'.$question['id'].' checkbox_radio_'.$answer['id'],
                        ])
                        .'</span>';
        }

        $first = false;
    }

    if($useRadio){
        echo $this->Form->input('Question.'.$question['id'], [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option single_choice_option input_radio_'.$question['id'],
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
            'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
            'after' => '</div>',
            'options' => $radioOptions,
            ]).'<br/>';
    }
    ?>
</div>



<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<? if($useRadio) { ?>
    <script type="text/javascript">

        $('.input_radio_<?=$question['id']?>').click(function() {
            var checkbox = $('.checkbox_radio_'+$(this).val());
            var newChecked = !checkbox.is(':checked');
            $('.input_<?=$question['id']?>').prop('checked',false);
            $(this).prop('checked' , newChecked);
            checkbox.prop('checked',newChecked);
            Answer.answerChanged = true;
        });

        Answer.answerChanged = false;
    </script>
<? }else{ ?>
    <script type="text/javascript">
        $('input_.<?=$question['id']?>').click(function() {
            Answer.answerChanged = true;
        });

        Answer.answerChanged = false;
    </script>
<? } ?>

<script type="text/javascript">
    function checkMaxSelections(e) {
        if( $('.input_<?=$question['id']?>:checked').length > <?=$question['selectable_answers']?> ) {
            $(e).prop( "checked", false);
        }
    }
</script>
