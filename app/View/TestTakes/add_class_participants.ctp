<div class="popup-head">Studenten toevoegen</div>
<div class="popup-content">
    <div id="classesContainer" style="height:300px; overflow: auto;">
        <?=$this->Form->create('Student')?>
            <table class="table table-striped" id="classesTable">
                <thead>
                <tr>
                    <th width="20"></th>
                    <th>Student</th>
                    <th><a href="#" class="btn highlight pull-right small mr2" onclick="$('.class_student').attr({'checked' : 'checked'});">Selecteer alle</a>
                        <a href="#" class="btn highlight pull-right small mr2" onclick="$('.class_student').removeAttr('checked');">De-selecteer alle</a></th>
                </tr>
                </thead>
                <tbody>
                <?
                foreach($students as $id => $student) {
                    ?>
                    <tr>
                        <td width="20">
                            <?=$this->Form->input($student['id'], array('type' => 'checkbox', 'value' => 1, 'label' => false, 'class' => 'class_student'))?>
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
        Annuleer
    </a>

    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="TestTake.addSelectedStudents(<?=$class_id?>);"">
        <span class="fa fa-check"></span>
        Geselecteerde toevoegen
    </a>
</div>
