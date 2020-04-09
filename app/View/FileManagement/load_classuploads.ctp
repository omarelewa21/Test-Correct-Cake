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
            <?= json_decode($file['typedetails'])->class ?>
        </td>
        <td>
            <?=$file['status']['name']?>
        </td>

    </tr>
    <?
}
?>
