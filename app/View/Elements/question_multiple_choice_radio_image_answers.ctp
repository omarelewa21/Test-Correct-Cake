<?php
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
    $radioList[$key] = ' ';
    }
    echo $this->Form->input('Question.'.$question['id'], [
    'type' => 'radio',
    'legend'=> false,
    'label' => false,
    'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
    'class' => 'multiple_choice_option single_choice_option input_radio_'.$question['id'],
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
    .multiple_choice_radio_image_container {
        display:inline-block;
        float:left;
        margin-bottom:25px;
        margin-right:35px;
    }
    .multiple_choice_radio_image_container_name {
        text-align:center;
    }
</style>