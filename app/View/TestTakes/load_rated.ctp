<?
foreach($test_takes as $test_take) {
    ?>

   <tr><td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
       <td>
           <?
           foreach($test_take['school_classes'] as $class) {
               echo $class['name'].'<br />';
           }
           ?>
       </td>
       <td>
           <?
           foreach($test_take['invigilator_users'] as $user) {
               echo $user['name_first']. ' ' . $user['name_suffix'] . ' ' . $user['name']. '<br />';
           }
           ?>
       </td>
       <td>
           <?=$test_take['test']['question_count']?>
       </td>
       <td>
           <?=$test_take['user']['name_first']?>
           <?=$test_take['user']['name_suffix']?>
           <?=$test_take['user']['name']?>
       </td>
       <td><?=$test_take['test']['subject']['name']?></td>
       <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
       <td>
           <?
           if($test_take['retake'] == 0) {
               ?>
               <div class="label label-info">Standaard</div>
               <?
           }else{
               ?>
               <div class="label label-warning">Inhaaltoets</div>
               <?
           }
           ?>
       </td>
       <td><?=$test_take['weight']?></td>
       <td><?=$test_take['participants_taken']?></td>
       <td><?=$test_take['participants_not_taken']?></td>
        <td class="nopadding" width="100">
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view/<?=$test_take['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>