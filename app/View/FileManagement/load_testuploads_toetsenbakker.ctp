<?
foreach($files as $file){
    ?>
    <tr>
        <td>
            <?=$this->Time->format($file['created_at'],'%e %b \'%y om %H:%M', true, 'Europe/Amsterdam')?>
        </td>
        <td>
            <?= sprintf('%s %s %s',$file['user']['name_first'], $file['user']['name_suffix'],$file['user']['name']) ?>
        </td>
        <td>
            <?= $file['typedetails']['subject'] ?>
        </td>
        <td>
            <?= $file['typedetails']['name'] ?>
        </td>
        <td>
            <?=$file['status']['name']?>
        </td>
        <td>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/file_management/view_testupload/<?=$file['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>
