<?
$mainAttaiments = [
    '' => __("Geen domein")
];

$subAttainments = [];

if(!isset($selectedAttainments)) {
    $selectedAttainments = [];
}

foreach($attainments as $id => $data) {
    $mainAttaiments[$id] = $data['title'];
    if(isset($selectedAttainments)) {
        if (in_array($id, $selectedAttainments)) {
            $subAttainments = $data['attainments'];
        }
    }
}
?>

<strong><?= __("Domein")?></strong>
<?=$this->Form->input('attainments', array('label' => false, 'type' => 'select', 'onchange' => 'updateSubAttainments();', 'options' => $mainAttaiments, 'style' => 'width:750px;', 'value' => $selectedAttainments))?>
<div id="dvSubdomain" style="<?=count($selectedAttainments) < 2 ? 'display:none;' : '' ?>">
    <br /><br />
    <strong><?= __("Subdomein")?></strong>
    <?=$this->Form->input('sub_attainments', array('label' => false, 'type' => 'select', 'options' => $subAttainments, 'style' => 'width:750px;', 'value' => $selectedAttainments))?>
</div>

<script type="text/javascript">
    $('#QuestionAttainments, #QuestionSubAttainments').select2();

    function updateSubAttainments() {
        var value = $('#QuestionAttainments').val();
        if(value == '') {
            $('#dvSubdomain').hide();
        }else{
            $('#dvSubdomain').show();
        }

        $('#QuestionSubAttainments').find('option').remove();
        <?php
        foreach($attainments as $id => $data) {
            ?>
        if(value == '<?=$id?>') {
            <?php
            foreach($data['attainments'] as $sub_id => $sub_title) {
                ?>
            $('#QuestionSubAttainments').append('<option value="<?=$sub_id?>"><?=str_replace("'", "", $sub_title)?></option>');
            <?php
        }
        ?>
        }
        <?php
    }
    ?>

        $('#QuestionSubAttainments').val('').trigger('change');;
    }
</script>
