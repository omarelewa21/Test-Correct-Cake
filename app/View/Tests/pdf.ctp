<style>
    * {
        font-family: Arial;
        font-size: 20px;
    }

    img {
        max-width:100%;
        max-height:600px;
        height: auto !important;
    }
</style>
<center>
    <img src="http://testportal.test-correct.nl/img/logo_full.jpg" width="200" />
    <h1><?=$test['name']?></h1>
    <h1><?=$test['education_level_year']?> <?=$test['education_level']['name']?> - <?=$test['subject']['name']?></h1>
</center>

<?
if(!empty($test['introduction'])) {
    ?>
    <strong>Belangrijk</strong><br />
    <?
    echo nl2br($test['introduction']);
}?>

<div style="page-break-after: always;"></div>

<?
$i = 0;

foreach($questions as $question) {

    $i++;
    if($question['type'] == 'GroupQuestion') {
        $type = 'group';
    }else{

        $pattern = '/src\s*=\s*"(.+?)"/';
        $matches = array();
        preg_match_all($pattern, $question['html'], $matches);

        foreach ($matches[1] as $match) {
            $question['html'] = str_replace($match, ($match.'&pdf='.sha1('true')), $question['html']);
        }

        ?>

        <?php if($question['type'] === 'DrawingQuestion' || (isset($question['grid']) && $question['grid'] > 0)): ?>
            <div style="page-break-after: always;"></div>
        <?php endif; ?>

        <table cellspacing="0" cellpadding="0" width="100%" >
            <tbody>
                <tr>
                    <td width="100%" valign="top" style="font-size: 11px;">
                        <?=$question['score']?>pt
                    </td>
                </tr>
                <tr>
                    <td width="100" valign="top" style="font-size: 24px;" class="question-html">
                        <?=$i?> &nbsp; <?=$question['html']?>
                    </td>
                </tr>
                <tr>
                    <td width="100%">

                        <?php if($question['type'] === 'DrawingQuestion'): ?>
                            <?php if($question['answer_background_image'] !== null): ?>
                                <?= '<img width="100%" src="'.$question['answer_background_image'].'" alt="backgrounod image">'; ?>
                            <?php elseif($question['grid'] > 0): ?>
                                <?php 
                                    if($question['grid'] < 4){
                                        $width = $question['grid'] * 2; 
                                    } else {
                                        $width = (($question['grid']*2)+1);
                                    }

                                    $padding = (((1080/($width+3))/1080)*100) .'%';
                                ?>

                                <?php for ($j=0; $j <= $question['grid']; $j++): ?>
                                    <?php for ($k=0; $k <= $width+1; $k++): ?>
                                        <div
                                            style="display: flex;float: left;margin: 0%;border: 1px solid #dbdbdb; width: <?=$padding;?>; padding-bottom:<?=$padding;?>;">
                                        </div>
                                    <?php endfor; ?>
                                    <span style="clear:both;">
                                <?php endfor; ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br /><br />

        <?
    }
}

$i = 0;

$usedAttachments = [];
$questionSet = false;

foreach($questions as $question) {

    $i++;
    if (count($question['attachments']) > 0) {
        ?>
        <!-- <h2>Bijlages vraag #<?= $i ?></h2> -->
        <?
        $a = 0;
        foreach ($question['attachments'] as $attachment) {

            if(!strpos($attachment['title'], '.pdf') && !$questionSet){
                ?>
                <h2>Bijlages vraag #<?= $i ?></h2>
                <?

                $questionSet = true;
            }
            
            // print_r($attachment);

            if($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['mp3', 'wav', 'jpg', 'peg', 'png'])) {
                $a++;
                if(!isset($usedAttachments[$attachment['id']])) {
                    $usedAttachments[$attachment['id']] = $i;
                    ?>
                    Bijlage #<?=$a?><br />
                    <?
                    if($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['mp3', 'wav'])) {
                        echo 'Vraag je docent naar het geluidsfragment van deze vraag.<br /><br />';
                    }elseif($attachment['type'] == 'file' && in_array(substr($attachment['title'], -3), ['jpg', 'peg', 'png'])) {
                        ?>
                        <img src="<?= $attachment['data'] ?>" style=" max-width: 100%; max-height:600px"/>
                        <div style="page-break-after: always;"></div>
                        <?
                    }
                }else{
                    if (!empty($attachment['data']) && strstr($attachment['data'], 'image')) {
                        if ($a == 1) {
                            echo 'Zelfde als in vraag #' . $usedAttachments[$attachment['id']];
                        }
                    }
                }
            }elseif($attachment['type'] == 'video') {
                echo 'Video: ' . $attachment['link'] . '<br /><br />';
            }
        }
        ?>
        <br/><br/>
    <?
    }

    $questionSet = false;
}
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
        console.log('ready');
        $(".question-html").find('img').each(function(){
            var src = $(this).attr('src');
            src += '&pdf=true';
            $(this).attr('src',src);

            console.log($(this));
        });
    });
    alert('here');
    
</script>