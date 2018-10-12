<?
foreach($users as $user) {
    ?>
    <tr>
        <th><?=!empty($user['external_id']) ? $user['external_id'] : '-' ?></th>
        <th><?=$user['name_first']?></th>
        <th><?=$user['name']?><?= !empty($user['name_suffix']) ? ', ' . $user['name_suffix'] : '' ?></th>
        <th>
            <?
            if(isset($user['main_school_classes']) && !empty($user['main_school_classes'])) {
                echo $user['main_school_classes'][0]['name'];
            }else{
                echo '?';
            }
            ?>
        </th>
        <?
        foreach($subjects as $subject) {

            $color = '#eeeeee;';
            $content = '';
            $title = "";

            if(isset($user['average_ratings'])) {
                foreach ($user['average_ratings'] as $user_rating) {

                    if ($user_rating['subject_id'] == $subject['id']) {

                        $title = $subject['name'];

                        $percentage = (100 / $user_rating['global_average']) * $user_rating['rating'];
                        $content = round($user_rating['rating'], 1);

                        if ($percentage > 105) {
                            $color = '#14df37';
                        } elseif ($percentage < 95) {
                            $color = '#bd7878';
                        } else {
                            $color = '#83dc92';
                        }
                    }
                }
            }

            echo '<td align=center title="' . $title . '" style="background:'.$color.'">'.$content.'</td>';
        }
        ?>
        <td class="nopadding" width="30">
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/analyses/student/<?=$user['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>