<div
    style="padding: 0 20px;align-items: center; box-sizing: border-box; font-family: Nunito, sans-serif; box-shadow: 0 3px 8px 0 rgba(4,31,116,.3);  ;height:70px;color:white; background:linear-gradient(90deg, rgba(0, 77, 245, 1) 0%, rgba(71, 129, 255, 1) 100%); position: fixed; top:0; width:100%; left:0; display:flex;  justify-content:end"
    class="footer">
    <div>
        <h2><?= $question_type ?></h2>
    </div>
    <div style="margin-left:auto">
        <span style="font-size:14px; font-weight: normal"><?= __('test') ?>:</span>
        <span style="font-size:14px; font-weight: bold;"><?= $test_name ?></span>
    </div>
</div>

<script>
    (function () {
        $('#header, #tiles').hide();
    })()

</script>
