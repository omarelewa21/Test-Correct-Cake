
<h1 id="surveillanceTitle"><?= __("Lopende opdrachten")?></h1>

<?

$alerts = 0;
$ipAlerts = 0;;

if(count($takes) == 0) {
    ?>
    <center>
        <?= __("Er zijn geen lopende opdrachten om te surveilleren")?>
    </center>
    <script type="text/javascript">
        window.onbeforeunload = null;
    </script>
    <?
}else {
    ?>

    <div>
        <div class="block" >
            <div class="block-head"><?= __("Opdrachten")?></div>
            <div class="block-content">
                <table class="table table-striped">
                    <tr>
                        <th><?= __("Opdracht")?></th>
                        <?php if($allow_guest_accounts) {?><th width="150">Student inlogtoetscode</th> <?php } ?>
                        <th><?= __("Klas(sen)")?></th>
                        <th><?= __("Datum van")?></th>
                        <th><?= __("Datum tot")?></th>
                        <th width="40"></th>
                        <th width="200"><?= __("Voortgang")?></th>
                        <th width="120"></th>
                        <th></th>
                    </tr>
                    <?php

                    foreach ($takes as $take) {

                        ?>
                        <tr <?= $take[0]['schoolClass']=='' ? '' : 'takesrow="true"' ?> >
                            <td><?= $take[0]['test'] ?></td>
                            <?php if($allow_guest_accounts) {?>
                                <td style="position: relative">
                                    <?php if($take[0]['code']) { ?>
                                        <div class="surveillance_test_code">
                                            <span><?= $take[0]['code'] ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <span></span>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php
                                foreach ($take as $take_item) {
                                    if (isset($take_item['schoolClass'])) {
                                        echo $take_item['schoolClass'] . '<br />';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?= date('d-m-Y', strtotime($take['info']['time_start'])) ?>
                            </td>
                            <td>
                                <?=date('d-m-Y', strtotime($take['info']['time_end'])) ?>
                            </td>
                            <td>
                                <?php if ($allow_inbrowser_testing) { ?>
                                    <a title='<?= __("Browsertoetsen voor iedereen aan/uit")?>'
                                       href="#" id=""
                                       class="btn active <?= $take['info']['allow_inbrowser_testing'] ?  'cta-button' : 'grey' ?> small mr2"
                                       onclick="TestTake.toggleInbrowserTestingForAllParticipants(this,'<?=$take[0]['uuid']?>')">
                                        <span class="fa fa-chrome"></span>
                                    </a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                foreach ($take['info']['school_classes'] as $class) {
                                    ?>
                                    <div class="progress" style="margin-bottom: 0px; height:20px; margin-bottom:1px;">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" id="progress_<?=getUUID($take['info'], 'get')?>_<?=getUUID($class, 'get')?>" aria-valuemin="0" aria-valuemax="100" style=" line-height:22px; font-size:14px;"></div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                            <td align="center" class="nopadding">
                                <a href="#" class="btn highlight small"
                                   onclick="TestTake.setTakeTakenSelector('<?= getUUID($take['info'], 'get') . "',"  . $take['info']['time_dispensation_ids']; ?>);">
                                    <?= __("Innemen")?>
                                </a>
                            </td>
                            <td><a href="#" class="btn white pull-right" onclick="Popup.load('/test_takes/edit/<?= getUUID($take['info'], 'get') ?>', 500);">
                                    <span class="fa fa-folder-open-o"></span>
                                </a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <br clear="all"/>
    </div>

    <?php
}
?>

<?php
$takeUuids = array();
foreach ($takes as $take) {
    $takeUuids[] = getUUID($take['info'], 'get');
}
?>

<script type="text/javascript">

    startPolling(10000);
    window.onbeforeunload = confirmExit;

    function stopPolling(message, title) {
        if (title === undefined) {
            title = '<span class="label-danger" style="display:block">\'<?= __("Hoge server belasting")?>\'</span>';
        }

        if (message === undefined) {
            message = '<?= __("Door de hoge serverbelasting wordt het surveillance scherm tijdelijk niet geupdate.")?>'
        }

        Popup.message({
            title: title,
            message: message
        });
        $('#page_title').html('Surveillance (inactief)');

        clearTimeout(window.surveillanceTimeout);
    }

    function startPolling(interval) {
        clearTimeout(window.surveillanceTimeout);
        window.surveillanceTimeout = setInterval(function () {
            if(document.getElementById('surveillanceTitle')){
                loadData();
            }
            else{
                clearTimeout(window.surveillanceTimeout);
            }
        }, interval);
        $('#page_title').html('Surveillance');

    }

    function confirmExit() {
        return "<?= __('U bent aan het surveilleren, weet u het zeker?')?>";
    }

    function loadData() {
        User.inactive = 0;
        $.getJSON('/test_takes/assessment_open_teacher_data/?' + new Date().getTime(),
            function(response) {


                    document.title = 'Test Correct';



                $.each(response.takes, function(id, percentage) {

                    var widthPercentage = percentage;

                    if(percentage < 15) {
                        widthPercentage = 15;
                    }

                    $('#' + id).css({
                        'width' : widthPercentage + '%'
                    }).html(percentage + '%');
                });


                if (Object.keys(response.takes).length !== document.querySelectorAll('[takesrow]').length) {
                      Navigation.refresh();
                }

            }
        );
    }

    loadData();


    if(TestTake.showProgress) {
        $('#btnSmartBoard').html('<?= __("Naar smartboard weergave")?>');
        $('#alertOrange, #alertRed').hide();
    }else{
        $('#btnSmartBoard').html('<?= __("Naar surveillant weergave")?>');
        $('#blockProgress').hide();
        $('#alertOrange, #alertRed').hide();
    }

    if(typeof(nonDispensationJs) == 'undefined') {

        $(document).on("click", "#test_close_confirm", function () {

            if ($('#test_close_confirm').hasClass("disabled")) {
                return false;
            }

            if (TestTake.testCloseMethod == 'Close all') {

                TestTake.setTakeTakenNoPrompt(TestTake.lastTestSelected);

            } else {

                TestTake.setTakeTakenNonDispensation(TestTake.lastTestSelected, TestTake.lastTestTimeDispensedIds)

            }

            Popup.closeLast();
        });

        $(document).on("click", "#test_close_non_dispensation", function () {
            $('#test_close_non_dispensation').addClass("highlight");
            $('#test_close_confirm').removeClass("disabled");
            $('#test_close_confirm').removeClass("grey");
            $('#test_close_confirm').addClass("blue");
            $('#test_close_all').addClass("grey");
            $('#test_close_all').removeClass("highlight");
            TestTake.testCloseMethod = 'Close non dispensation';
        });

        $(document).on("click", "#test_close_all", function () {
            $('#test_close_all').addClass("highlight");
            $('#test_close_confirm').removeClass("disabled");
            $('#test_close_confirm').removeClass("grey");
            $('#test_close_confirm').addClass("blue");
            $('#test_close_non_dispensation').addClass("grey");
            $('#test_close_non_dispensation').removeClass("highlight");
            TestTake.testCloseMethod = 'Close all';
        });
        nonDispensationJs = true;
    }
</script>
