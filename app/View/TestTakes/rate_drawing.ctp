<?
  $answer = $rating['answer']['json'];
  // var_dump($rating);
  $answer = json_decode($answer, true);
?>

<img src="<?= $answer['answer'] ?>" />

<br />

<div style="padding: 15px 20px; background: #f1f1f1;">
    <?=$answer['additional_text']?>
</div>
