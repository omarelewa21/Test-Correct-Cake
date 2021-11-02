<?php if(isset($_SESSION['Auth']['User']['general_text2speech_price'])) {?>

    <tr>
        <th><?= __("Voorlees")?>
        <?= __("functionaliteit")?>
        </th>
        <?php if($this->request->data['has_text2speech']){ ?>
            <td>
            <?= __("Is toegekend én")?><br /><?=$this->Form->input('active_text2speech', ['type' => 'checkbox', 'style' => 'width:20px;', 'label' => false, 'div' => false])?> <?= __("mag hier")?> <b><?= __("momenteel")?></b> <?= __("gebruik van maken")?>

            </td>
        <?php }else{ ?>
            <td>
                <?=$this->Form->input('text2speech', ['type' => 'checkbox', 'style' => 'width:20px;', 'label' => false, 'div' => false])?> <?= __("Toekennen")?>
            </td>
        <script>
            $('#UserText2speech').on('change',function(){
                if($('#UserText2speech').is(':checked')){
                    if(confirm('<?= __("Let op, de voorleesfunctionaliteit brengt extra kosten met zich mee, hier wordt elk schooljaar")?>' + '<? echo $_SESSION['Auth']['User']['general_text2speech_price']?>' + '<?= __("euro (excl btw) voor in rekening gebracht.")?>')){
                        return true;
                    }
                    else{
                        $('#UserText2speech').attr('checked',false);
                    }
                }
                return true;
            });
        </script>
        <?php } ?>
    </tr>

<?php }?>