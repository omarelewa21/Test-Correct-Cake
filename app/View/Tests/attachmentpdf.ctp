<style>
    * {
        font-family: Arial;
        font-size: 20px;
    }
</style>
<!-- <center>
    <img src="http://testportal.test-correct.nl/img/logo_full.jpg" width="200" />
</center>

<div style="page-break-after: always;"></div> -->

<?
    if($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['mp3', 'wav', 'jpg', 'peg', 'png'])) {
        $a++;
            if($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['mp3', 'wav'])) {
                echo 'Vraag je docent naar het geluidsfragment van deze vraag.<br /><br />';
            }elseif($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['jpg', 'peg', 'png'])) {
                ?>
                <img src="<?= $attachment['data'] ?>" style="max-width: 100%; max-height:600px"/>
                <div style="page-break-after: always;"></div>
                <?
            }
    }elseif($attachment['type'] == 'video') {
        echo 'Video: ' . $attachment['link'];
    }
?>