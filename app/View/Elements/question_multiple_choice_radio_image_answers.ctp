<?php
    $rating = (isset($rating)) ? $rating : false;
    foreach($radioOptions as $key => $value){
?>
<div class="multiple_choice_radio_image_container">
    <div class="multiple_choice_radio_image_container_name"></div>
    <div><?=$value?></div>
</div>
<?php
    }
?>

<div id="radioContainer">
    <?php
            $radioList = [];
            $label = '<div>';
    foreach($radioOptions as $key => $value){
    $radioList[$key] = '<span> </span>';
    }
    echo $this->Form->input('Question.'.getUUID($question, 'get'), [
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
    ]);
    ?>
</div>


<script>
    var items = [];
    $('#radioContainer div').each(function(i){
        var container = $('.multiple_choice_radio_image_container_name').eq(i);
        $(this).prependTo(container);
    });
    $('#radioContainer').remove();
</script>

<style>

</style>