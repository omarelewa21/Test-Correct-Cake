<div class="block">
    <div class="block-head"><?= __("Vraag")?> #<?=$i?> <?= __("voorbeeld")?></div>
    <div class="block-content" id="question_preview_<?=$question['question']['id']?>">
    <?= __("Laden..")?>
    </div>
</div>

<div class="block" style="border-left: 3px solid #197cb4;">
    <div class="block-head"><?= __("Vraag")?> #<?=$i?> <?= __("antwoordmodel")?></div>
    <div class="block-content" id="question_answer_preview_<?=$question['question']['id']?>">
    <?= __("Laden..")?>
    </div>
</div>

<div class="block" style="width:280px; float:right;">
    <div class="block-head">
    <?= __("Score")?>
    </div>
    <div class="block-content" id="score_<?=$participant_id?><?=$question['question']['id']?>">
    <?= __("Laden..")?>
    </div>
</div>

<div class="block" style="width:calc(100% - 300px); margin-bottom: 100px; border-left: 3px solid #3D9D36">
    <div class="block-head"><?= __("Vraag")?> #<?=$i?> <?= __("antwoord")?></div>
    <div class="block-content" id="question_answer_<?=$question['question']['id']?>">
    <?= __("Laden..")?>
    </div>
</div>