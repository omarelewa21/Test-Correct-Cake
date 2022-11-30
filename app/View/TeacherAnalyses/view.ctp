<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("Klas")?>: <?=$class['name']?></h1>

<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%"><?= __("Naam")?></th>
                <td width="35%"><?=$class['name']?></td>
                <th width="15%"><?= __("Vak(ken)")?></th>
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
                <th width="15%"><?= __("Locatie")?></th>
                <td width="35%"><?=$class['school_location']['name']?></td>
                <th width="15%"><?= __("Niveau")?></th>
                <td width="35%"><?=$class['education_level_year']?> <?=$class['education_level']['name']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Studenten")?></div>
    <div class="block-content students">
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('.students').load('/teacher_analyses/load_students/<?=getUUID($class, 'get');?>/<?=getUUID($class["school_location"], 'get')?>?' + new Date().getTime());
    });
</script>
