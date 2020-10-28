<?php
    $randId = sprintf('%s%s',$question['id'],uniqid());
    $rating = (isset($rating)) ? $rating : false;
    foreach($radioOptions as $key => $value){
?>
<div class="multiple_choice_radio_image_container a_<?=$randId?>">
    <div class="multiple_choice_radio_image_container_name"></div>
    <div><?=$value?></div>
</div>
<?php
    }
?>

<div id="radioContainer_<?=$randId?>">
    <?php
            $radioList = [];
            $label = '<div>';
    foreach($radioOptions as $key => $value){
    $radioList[$key] = '<span> </span>';
    }
    $random = '';
    if($rating){
        // we need a random string here as with rating by a teacher and per question, every student has the same name attribute
        // 'Question.'.$question['id']
        // and if that is the case the radio buttons interact and get broken
        $length = 10;
        $random = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
    }
    echo $this->Form->input('Question.'.getUUID($question, 'get').$random, [
    'type' => 'radio',
    'legend'=> false,
    'label' => false,
    'disabled' => $rating,
    'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
    'class' => 'multiple_choice_option multiple_choice_option_radio single_choice_option input_radio_'.getUUID($question, 'get'),
    'default'=> $default,
    'before' => $label,
    'separator' => '</div>'.$label,
    'after' => '</div>',
    'options' => $radioList,
    'value' => $default,
    ]);
    ?>
</div>
<br style="clear:both;"/><br/>


<script>
    $('#radioContainer_<?=$randId?> div').each(function(i){
        var container = $('.a_<?=$randId?> .multiple_choice_radio_image_container_name').eq(i);
        $(this).prependTo(container);
    });
    $('#radioContainer_<?=$randId?>').remove();
</script>

<style>

</style>