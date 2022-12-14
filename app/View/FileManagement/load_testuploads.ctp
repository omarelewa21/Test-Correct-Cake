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
            <?= $file['subject']['name'] ?? $file['typedetails']['subject'] ?>
        </td>
        <td>
            <?=$file['typedetails']['name'] ?>
        </td>
        <td>
            <?=$file['status']['parent']['name']?>
        </td>

    </tr>
    <?
}
?>
