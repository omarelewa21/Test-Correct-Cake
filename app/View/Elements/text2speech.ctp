<?php if(isset($_SESSION['Auth']['User']['general_text2speech_price'])) {?>

    <tr>
        <th>Voorlees
            functionaliteit
        </th>
        <?php if($this->request->data['has_text2speech']){ ?>
            <td>
                Is toegekend én<br /><?=$this->Form->input('active_text2speech', ['type' => 'checkbox', 'style' => 'width:20px;', 'label' => false, 'div' => false])?> mag hier <b>momenteel</b> gebruik van maken

            </td>
        <?php }else{ ?>
            <td>
                <?=$this->Form->input('text2speech', ['type' => 'checkbox', 'style' => 'width:20px;', 'label' => false, 'div' => false])?> Toekennen
            </td>
        <script>
            $('#UserText2speech').on('change',function(){
                if($('#UserText2speech').is(':checked')){
                    if(confirm("Let op, de voorleesfunctionaliteit brengt extra kosten met zich mee, hier wordt jaarlijks <? echo $_SESSION['Auth']['User']['general_text2speech_price']?> euro (excl btw) voor in rekening gebracht.")){
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