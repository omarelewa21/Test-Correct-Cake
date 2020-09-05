<table class="multiple_choice_radio_image_table">
    <tr>

        <?php
            $radioList = [];
            $label = '<td>';
            foreach($radioOptions as $key => $value){
                $radioList[$key] = ' ';
            }
            echo $this->Form->input('Question.'.getUUID($question, 'get'), [
                    'type' => 'radio',
                    'legend'=> false,
                    'label' => false,
                    'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
                    'class' => 'multiple_choice_option single_choice_option input_radio_'.getUUID($question, 'get'),
                    'default'=> $default,
                    'before' => $label,
                    'separator' => '</td>'.$label,
                    'after' => '</td>',
                    'options' => $radioList,
                    ]);
    echo '</tr>
    <tr>';
            foreach($radioOptions as $key => $value){
                echo '<td>'.$value.'</td>';
            }
        ?>
    </tr>
</table>
<br />

<style>
    .multiple_choice_radio_image_table td {
        text-align: center;
    }
</style>