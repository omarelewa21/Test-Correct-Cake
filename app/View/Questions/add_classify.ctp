<div class="popup-head"><?= __("Rubriceervraag")?></div>
<div class="popup-content">
    <?=$this->Form->create('Question')?>

    <table class="table mb15">
        <tr>
            <th width="10%">
            <?= __("Punten")?>
            </th>
            <td width="110">
                <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => 5, 'label' => false, 'verify' => 'notempty'))?>
            </td>
            <td>
                <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  <?= __("Bespreken in de klas")?> <br />
                <? if($owner == 'test') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag vastzetten")?><br /><? } ?>
                <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Halve punten mogelijk")?><br />
                <?php if(!$is_open_source_content_creator): ?>
                    <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'checked' => true, 'div' => false))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span><br />
                <?php endif; ?>
            </td>

            <td width="230">
                <?=$this->Form->input('note_type', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [
                    'NONE' => __("Geen kladblok"),
                    'TEXT' => __("Tekstvlak"),
                    'DRAWING' => __("Tekenvlak")
                ]))?>
            </td>
        </tr>
    </table>

    <div class="tabs">
        <a href="#" class="btn grey highlight" page="question" tabs="add_question">
        <?= __("Vraag")?>
        </a>

        <a href="#" class="btn grey" page="options" tabs="add_question">
        <?= __("Antwoorden")?>
        </a>
        <? if($owner != 'group') { ?>
            <a href="#" class="btn grey" page="sources" tabs="add_question">
            <?= __("Bronnen")?>
            </a>
        <? } ?>

        <a href="#" class="btn grey" page="attainments" tabs="add_question">
        <?= __("Eindtermen")?>
        </a>

        <a href="#" class="btn grey" page="tags" tabs="add_question">
        <?= __("Tags")?>
        </a>

        <a href="#" class="btn grey" page="rtti" tabs="add_question">
        <?= __("Taxonomie")?>
        </a>

        <br clear="all" />
    </div>

    <div page="question" class="page active" tabs="add_question">
        <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
    </div>

    <div page="options" class="page" tabs="add_question">

        <div class="alert alert-info">
        <?= __("Per item links zijn er meerdere mogelijkheden, voeréén optie per regel in onder \"Mogelijkheden\".")?>
        </div>

        <table class="table" id="tableClassifyOptions">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?= __("Onderwerp")?></th>
                    <th><?= __("Mogelijkheden")?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?
                for($i = 0; $i < 50; $i++) {
                    ?>
                    <tr style="<?=($i == 0) ? '' : 'display:none;' ?>">
                        <td valign="top">
                            <span class="fa fa-arrows"></span>
                        </td>
                        <td valign="top">
                            <?=$this->Form->input('', array('type' => 'hidden', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][order]', 'value' => $i, 'class' => 'order'))?>
                            <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][left]'))?>
                        </td>
                        <td valign="top">
                            <?=$this->Form->input('', array('style' => 'width: 300px; height:53px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][right]', 'type' => 'textarea'))?>
                        </td>
                        <td valign="top">
                            <a href="#" class="btn red small" onclick="Questions.removeClassifyOption(this);">
                                <span class="fa fa-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </tbody>
        </table>

        <center>
            <a href="#" class="btn highlight small inline-block" onclick="Questions.addClassifyOption();">
                <span class="fa fa-plus"></span>
                <?= __("Optie toevoegen")?>
            </a>
        </center>
    </div>

    <div page="attainments" class="page" tabs="add_question">
        <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
    </div>

    <?=$this->element('question_tab_rtti',[]); ?>

    <div page="tags" class="page" tabs="add_question">
        <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;'))?>
    </div>

    <?=$this->Form->end();?>

    <div page="sources" class="page" tabs="add_question"></div>

</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('ClassifyQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Vraag opslaan")?>
    </a>
</div>

<script type="text/javascript">
    <? if($owner != 'group') { ?>
        Questions.loadAddAttachments();
    <? } ?>

    $('#QuestionAttainments').select2();
    $('#QuestionTags').select2({
        tags : true,
        ajax: {
            url: "/questions/tags",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        }
    });

    $('#tableClassifyOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateClassifyOrder();
        }
    });
    $('#QuestionQuestion').ckeditor({

        filebrowserUploadUrl: '/custom/uploader.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/custom/uploader.php?command=QuickUpload&type=Images',
        toolbar: [
            { name: 'clipboard', items: [ 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'insert', items: [ 'addImage', 'Table' ] },
            { name: 'editing', items: [ 'Scayt', ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] }
        ],
        stylesSet: [
            /* Inline Styles */
            { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
            { name: 'Cited Work', element: 'cite' },
            { name: 'Inline Quotation', element: 'q' },

            /* Object Styles */
            {
                name: 'Special Container',
                element: 'div',
                styles: {
                    padding: '5px 10px',
                    background: '#eee',
                    border: '1px solid #ccc'
                }
            },
            {
                name: 'Compact table',
                element: 'table',
                attributes: {
                    cellpadding: '5',
                    cellspacing: '0',
                    border: '1',
                    bordercolor: '#ccc'
                },
                styles: {
                    'border-collapse': 'collapse'
                }
            },
            { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
            { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
        ]
    });
</script>
