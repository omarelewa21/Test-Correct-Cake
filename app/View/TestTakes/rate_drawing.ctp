<?
  $answer = $rating['answer']['json'];

  $answer = json_decode($answer, true);
  if(isset($drawing_url)) {
        $answer['answer'] = $drawing_url['url'];
    }
?>

<div>
    <? if(isset($backgroundImage) && $backgroundImage){ ?>
        <img src="<?= $backgroundImage ?>" class="position-absolute w-500" style="height: 320px !important"/>
        <img src="<?= $answer['answer'] ?>" class="position-relative w-500" style="height: 320px !important" />
    <? }else{ ?>
        <img src="<?= $answer['answer'] ?>" />
    <? } ?>
</div>

<br />

<div style="padding: 15px 20px; background: #f1f1f1;">
    <?=$answer['additional_text']?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>