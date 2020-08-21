<?
foreach($classes as $class) {
    ?>
    <tr onclick="Navigation.load('/analyses/school_class/<?=getUUID($class, 'get');?>')">
        <td><?=$class['name']?></td>
        <td>
            <?
            if(empty($class['mentor_users'])) {
                echo 'Geen';
            }else{
                $name = substr(strtoupper($class['mentor_users'][0]['name_first']), 0, 1) . '. ';
                $name .= !empty($class['mentor_users'][0]['name_suffix']) ? $class['mentor_users'][0]['name_suffix'] : '';
                $name .= $class['mentor_users'][0]['name'];

                echo $name;
            }
            ?>
        </td>
        <td><?=count($class['student_users'])?></td>
        <?
        foreach($subjects as $subject) {

            $color = '#eeeeee;';
            $content = '';

            foreach($class['subjects'] as $class_subject) {

                if($class_subject['id'] == $subject['id'] && !empty($class_subject['average'])) {
                    $percentage = (100 / $class_subject['global_average']) * $class_subject['average'];

                    if($percentage > 105) {
                        $color = '#14df37';
                        $content = '<img src="/img/smiley-1.png" />';
                    }elseif($percentage < 95) {
                        $color = '#bd7878';
                        $content = '<img src="/img/smiley-3.png" />';
                    }else{
                        $color = '#83dc92';
                        $content = '<img src="/img/smiley-2.png" />';
                    }
                }
            }

            echo '<td align=center style="background:'.$color.'">'.$content.'</td>';
        }
        ?>
        <td>
            <a href="#" class="btn white pull-right dropblock-left">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}