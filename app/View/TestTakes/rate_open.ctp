<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

?>
<div style="font-size: 20px;">
    <?= htmlentities($answer['value']) ?>
</div>