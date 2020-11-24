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
            <?= $file['typedetails']['subject'] ?>
        </td>
        <td>
            <?= $file['typedetails']['name'] ?>
        </td>
        <td>
            <span class="mr5 color-indicator <?=$file['status']['colorcode']?>"></span>
            <?=$file['status']['name']?>
        </td>
        <td>
            <?
                if(isset($file['typedetails']['colorcode']) && $file['typedetails']['colorcode']){
                    ?><span class="mr5 color-indicator <?=$file['typedetails']['colorcode']?>"></span><?php
                }
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
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/file_management/view_testupload/<?=getUUID($file, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>
