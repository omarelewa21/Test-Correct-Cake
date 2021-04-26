<div class="popup-head"><?= __("Studenten toevoegen")?></div>
<div class="popup-content">
    <div id="classesContainer" style="height:300px; overflow: auto;">
        <?=$this->Form->create('Student')?>
            <table class="table table-striped" id="classesTable">
                <thead>
                <tr>
                    <th width="20"></th>
                    <th><?= __("Student")?></th>
                    <th><a href="#" class="btn highlight pull-right small mr2" onclick="selectAllStudents();"><?= __("Selecteer alle")?></a>
                        <a href="#" class="btn highlight pull-right small mr2" onclick="deselectAllStudents();"><?= __("De-selecteer alle")?></a></th>
                </tr>
                </thead>
                <tbody>
                <?
                foreach($students as $id => $student) {
                    ?>
                    <tr>
                        <td width="20">
                            <?=$this->Form->input(getUUID($student, 'get'), array('type' => 'checkbox', 'value' => 1, 'label' => false, 'class' => 'class_student'))?>
                        </td>
                        <td colspan="2"><?=$student['name_first']?>
                            <?=$student['name_suffix']?>
                            <?=$student['name']?>
                        </td>
                    </tr>
                <?
                }
                ?>
                </tbody>
            </table>
        <?=$this->Form->end()?>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>

    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="TestTake.addSelectedStudents('<?=$class_id?>');"">
        <span class="fa fa-check"></span>
        <?= __("Geselecteerde toevoegen")?>
    </a>
</div>

<script>
    function selectAllStudents(){
        $('.class_student').each(function(i){
           if(!$(this).is(':checked')){
               $(this).trigger('click');
           }
        });
    }

    function deselectAllStudents(){
        $('.class_student').each(function(i){
            if($(this).is(':checked')){
                $(this).trigger('click');
            }
        });
    }
</script>
