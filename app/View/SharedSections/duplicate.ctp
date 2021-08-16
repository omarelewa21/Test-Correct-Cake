
<div class="popup-head">Toets dupliceren</div>
<div class="popup-content">
    <?=$this->Form->create('Test') ?>
        <table class="table mb15">
            <tr>
                <th width="140">
                    Huidige naam
                </th>
                <td>
                    <?= $test['name']?>
                </td>
            </tr>

            <tr>
                <th width="140">
                    Nieuwe naam
                </th>
                <td>
                    <?=$this->Form->input('name', array('style' => 'width: 270px', 'label' => false, 'verify' => 'notempty', 'value' => '')) ?>
                </td>
            </tr>

            <tr>
                <th width="140">
                    Vak
                </th>
                <td>
                    <?= $this->Form->input('subject_id', array('style' => 'width:280px', 'label' => false, 'options' => $subjects, 'multiple' => false, 'class' => 'takers_select')) ?>
                </td>
            </tr>
        </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnDuplicateTest">
        Dupliceren
    </a>
</div>

<script type="text/javascript">

    $('#TestDuplicateForm').formify({
        confirm: $('#btnDuplicateTest'),
        confirmPopup: false,
        onsuccess: function (result) {
            Notify.notify("De toets is gedupliceerd", "info");
            Navigation.load('/tests/view/'+result.test_id);
            Popup.closeLast();
        },
        onfailure: function (result) {
            // console.log(result);

            for (var i = result.length - 1; i >= 0; i--) {
                Notify.notify(result[i], "error");
            }
        }
    });

</script>