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
    echo $this->Form->input('Question.'.$question['id'], [
    'type' => 'radio',
    'legend'=> false,
    'label' => false,
    'disabled' => $rating,
    'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
    'class' => 'multiple_choice_option multiple_choice_option_radio single_choice_option input_radio_'.$question['id'],
    'default'=> $default,
    'before' => $label,
    'separator' => '</div>'.$label,
'after' => '</div>',
'options' => $radioList,
]);
?>
</div>


<script>
    $('#radioContainer_<?=$randId?> div').each(function(i){
        var container = $('.a_<?=$randId?> .multiple_choice_radio_image_container_name').eq(i);
        $(this).prependTo(container);
    });
    $('#radioContainer_<?=$randId?>').remove();
</script>

<style>

</style>