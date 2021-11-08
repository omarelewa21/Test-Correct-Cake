<?
$answers = $rating['answer']['json'];
$answers = json_decode($answers, true);

$options = [];

foreach($answers as $id => $answer) {
    $options[] = $answer;
}

$citoClass = '';
if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
echo sprintf('<div class="answer_container %s">',$citoClass);


?>

<table class="table" id="tableMultiChoiceOptions">
    <thead>
    <tr>
        <th width="30"></th>
        <th width="40">&nbsp;</th>
        <th width="40">St. 1</th>
        <th width="40">St. 2</th>
        <th><?= __("Reden")?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><span class="fa fa-<?=$options[0] == 1 ? 'check-' : ''?>square-o"></span></td>
        <td>A</td>
        <td><?= __('J') ?></td>
        <td><?= __('J') ?></td>
        <td><?= __("Juiste reden")?></td>
    </tr>
    <tr>
        <td><span class="fa fa-<?=$options[1] == 1 ? 'check-' : ''?>square-o"></span></td>
        <td>B</td>
        <td><?= __('J') ?></td>
        <td><?= __('J') ?></td>
        <td><?= __("Onjuiste reden")?></td>
    </tr>
    <tr>
        <td><span class="fa fa-<?=$options[2] == 1 ? 'check-' : ''?>square-o"></span></td>
        <td>C</td>
        <td><?= __('J') ?></td>
        <td><?= __('O') ?></td>
        <td>-</td>
    </tr>
    <tr>
        <td><span class="fa fa-<?=$options[3] == 1 ? 'check-' : ''?>square-o"></span></td>
        <td>D</td>
        <td><?= __('O') ?></td>
        <td><?= __('J') ?></td>
        <td>-</td>
    </tr>
    <tr>
        <td><span class="fa fa-<?=$options[4] == 1 ? 'check-' : ''?>square-o"></span></td>
        <td>E</td>
        <td><?= __('O') ?></td>
        <td><?= __('O') ?></td>
        <td>-</td>
    </tr>
    </tbody>
</table>
</div>
<?=$this->element('question_styling',['question' => $question]);?>