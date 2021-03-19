<?
  $answer = $rating['answer']['json'];
  // var_dump($rating);
  $answer = json_decode($answer, true);
  if(isset($drawing_url)) {
        $answer['answer'] = $drawing_url['url'];
    }
?>

<img src="<?= $answer['answer'] ?>" />

<br />

<div style="padding: 15px 20px; background: #f1f1f1;">
    <?=$answer['additional_text']?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>