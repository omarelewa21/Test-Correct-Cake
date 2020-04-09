<?
foreach($files as $file){
    ?>
    <tr>
        <td>
            <?=$this->Time->format($file['created_at'],'%e %b \'%y om %H:%M', true, 'Europe/Amsterdam')?>
        </td>
        <td>
            <?= $file['school_location']['name']?>
        </td>
        <td>
            <?= $file['school_location']['customer_code']?>
        </td>
        <td>
            <?= sprintf('%s %s %s',$file['user']['name_first'], $file['user']['name_suffix'],$file['user']['name']) ?>
        </td>
        <td>
            <?= json_decode($file['typedetails'])->class ?>
        </td>
        <td>
            <?=$file['status']['name']?>
        </td>
        <td>
            <?
                if(isset($file['handler']) && $file['handler']){
                ?>
                   <?=sprintf('%s %s %s',$file['handler']['name_first'], $file['handler']['name_suffix'],$file['handler']['name'])?>
                <?
                }
                else {
                ?>
                    -
                <?php
                }
            ?>
        </td>
        <td>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/file_management/view_classupload/<?=$file['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>
