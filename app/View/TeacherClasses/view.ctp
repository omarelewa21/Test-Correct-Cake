<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Klas: <?=$class['name']?></h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Naam</th>
                <td width="35%"><?=$class['name']?></td>
                <th width="15%">Vak(ken)</th>
                <td width="35%"><?php
                    $classList = [];
                    foreach($user['teacher'] as $teacher){
                        if($teacher['class_id'] == $class['id']){
                            $classList[] = $teacher['subject']['name'];
                        }
                    }
                    echo implode(', ',$classList);
                ?></td>
            </tr>
            <tr>
                <th width="15%">Locatie</th>
                <td width="35%"><?=$class['school_location']['name']?></td>
                <th width="15%">Niveau</th>
                <td width="35%"><?=$class['education_level_year']?> <?=$class['education_level']['name']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Studenten</div>
    <div class="block-content students">
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('.students').load('/teacher_classes/load_students/<?=getUUID($class, 'get');?>/<?=getUUID($class["school_location"], 'get')?>?' + new Date().getTime());
    });
</script>