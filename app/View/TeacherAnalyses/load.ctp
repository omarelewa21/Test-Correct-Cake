<?
foreach($classes as $class) {
    ?>
            <tr>
                <td><?=$class['name']?></td>
                <td class="nopadding">
                    <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/teacher_analyses/view/<?=getUUID($class, 'get');?>');">
                        <span class="fa fa-folder-open-o"></span>
                    </a>

                </td>
            </tr>
            <?
}
?>
