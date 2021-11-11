<div class="tat-content border-radius-bottom-0 import-heading">
    <div style="display:flex">
        <div style="flex-grow:1">
            <h2 style="margin-top:0"><?= __("Importgegevens van klassen compleet maken")?></h2>
        </div>
        <div style="margin-top:-2px">
            <?php echo $this->element('teacher_complete_user_import_tooltip', array('type' => $lvs_type)) ?>
        </div>
        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast()">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="divider mb24 mt10"></div>
    <div style="display: flex; align-items: center; justify-content: center; font-size: 16px">
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 2)) ?>
    </div>
</div>
<form method="put" id="teacher-complete-user-import-education-level-cluster-class-form">
    <div class="popup-content tat-content body1" style="display:flex; overflow:hidden; padding-top: 10px!important;">
        <div class="import-table-container">
            <table class="tableFixHead">
                <thead
                    style="position: sticky; top: 0; background: white; border-bottom: 2px solid var(--system-base); z-index:3;">

                <tr class="rotate_table_headings">
                    <th class="ed_level_cluster_class_td"><div><?= __("Klas")?></div></th>
                    <?php foreach ($education_levels as $level) { ?>
                    <?php if (!empty($level['education_level'])) { ?>
                        <th class="ed_level_col" width="57px">
                            <div title="<?= $level['education_level']['name'] ?>">
                                <span><?= $level['education_level']['name'] ?></span>
                            </div>
                        </th>
                        <?php } ?>
                    <?php } ?>
                    <th style="margin-left: auto;" width="80px"><div><?= __("Status")?></div></th>
                    <th width="200px"><div><?= __("Leerjaar")?></div></th>
                    <th width="120px"><div><?= __("Gecontroleerd")?></div></th>
                </tr>
                </thead>
                <tbody>
                <?php $checkedCount = 0; ?>
                <tr id="note_row" style="display: none">
                    <td colspan="<?= 4+ count($education_levels);?>" style="width: 100%;">
                        <div class="flex" style="width: 100%; justify-content: center;padding-top: 40px">
                            <span class="note"><?= __("Er hoeven geen niveau’s of leerjaren ingesteld te worden voor klusterklassen. Deze zijn mogelijk al bekend")?>.</span>
                        </div>
                    </td>
                </tr>
                <?php foreach ($classes_list as $schoolClass) { ?>
                    <?php if(
                        !$schoolClass['visible']
                        && !$schoolClass['finalized']
                        &&
                        (
                            !$schoolClass['checked_by_teacher']
                            || $schoolClass['checked_by_teacher'] && $schoolClass['checked_by_teacher_id'] === AuthComponent::user('id')
                        )
                    ){ ?>
                        <tr class="action_rows" style="display: flex;align-items: center">
                            <td class="ed_level_cluster_class_td"><span class="ed_level_cluster_class_span"><?= $schoolClass['name'] ?></span></td>
                            <?php foreach ($education_levels as $eductionLevel) { ?>
                            <?php if (!empty($eductionLevel['education_level'])) { ?>
                                <td width="57px" style="position:relative; align-content: center" class="ed_level_checkbox_cols">
                                    <input
                                        id="radio-class-<?= $schoolClass['id'] ?>-<?= $eductionLevel['education_level']['id'] ?>"
                                        name="class[<?= $schoolClass['id'] ?>][education_level]" type="radio"
                                        class="radio-custom jquery-radio-set-eduction-level-step-2"
                                        value="<?= $eductionLevel['education_level']['id'] ?>"
                                        <?= $eductionLevel['education_level']['id'] == $schoolClass['education_level_id'] ? 'checked' : '' ?>
                                    >
                                    <label
                                        for="radio-class-<?= $schoolClass['id'] ?>-<?= $eductionLevel['education_level']['id'] ?>"
                                        class="radio-custom-label">
                                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                                  fill-rule="evenodd"
                                                  stroke-linecap="round"/>
                                        </svg>
                                    </label>
                                </td>
                                <?php } ?>
                            <?php } ?>
                            <td style="margin-left: auto;" width="80px">
                                <?php if (empty($schoolClass['education_level_year']) || empty($schoolClass['education_level_id'])) { ?>
                                    <span class="import-label label-orange"><?= __("onbekend")?></span>
                                <?php } else if(!empty($schoolClass['checked_by_teacher'])){ ?>
                                    <span class="import-label label-green"><?= __("Ingesteld")?></span>
                                <?php } else { ?>
                                    <span class="import-label label-blue"><?= __("bekend")?></span>
                                <?php } ?>
                            </td>
                            <td width="200px">
                                <div style="display: flex;">
                                    <?php foreach (range(1, 6) as $count) { ?>
                                        <div>
                                            <input id="<?= sprintf('number-%s-%s', $count, $schoolClass['id']) ?>"
                                                   value="<?= $count ?>"
                                                   name="class[<?= $schoolClass['id'] ?>][education_level_year]"
                                                   type="radio"
                                                   class="number-radio jquery-radio-set_school-year"
                                                <?= $schoolClass['education_level_year'] == $count ? 'checked' : '' ?>

                                            >
                                            <label for="<?= sprintf('number-%s-%s', $count, $schoolClass['id']) ?>"
                                                   class="number-radio-label "><span> <?= $count ?></span></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </td>
                            <td width="120px">
                                <input id="<?= sprintf('green-checkbox-%s', $schoolClass['id']) ?>"
                                       class="checkbox-custom jquery-controle"
                                       name="class[<?= $schoolClass['id'] ?>][checked]" type="checkbox"
                                    <?= $schoolClass['checked_by_teacher'] ? 'checked' : '' ?>
                                >
                                <label for="<?= sprintf('green-checkbox-%s', $schoolClass['id']) ?>"
                                       class="checkbox-custom-label checkbox-green">
                                    <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                              fill-rule="evenodd"
                                              stroke-linecap="round"/>
                                    </svg>
                                </label></td>
                        </tr>
                    <?php } else { $checkedCount++;} ?>
                <?php } ?>
                <?php if($checkedCount > 0) { ?>
                    <tr style="margin-top: 10px">
                        <td colspan="<?= 4+ count($education_levels);?>" style="text-align: center; border-bottom: 1px solid var(--blue-grey); padding: 2.5rem 0 0;width:100%;justify-content: center;">
                            <div id="show_checked_classes_button" style="text-align:center;display: inline-flex;width:300px;box-sizing:border-box;align-items: center;cursor:pointer; padding: 0 20px;position:relative; top:1px; background-color:white; border-top-left-radius: 10px;border-top-right-radius: 10px; border-top: solid 1px var(--blue-grey); border-right: solid 1px var(--blue-grey); border-left: solid 1px var(--blue-grey);">
                                <span style="display:flex;flex-grow:1;text-align:center;font-size:16px;font-weight: 700; margin-right: 8px"><?= __("Toon gecontroleerde klassen")?></span>
                                <?= $this->element('chevron', array('style' => 'display:flex;transform:rotate(90deg) scale(0.8);', 'id' => 'checked_classes_svg')) ?>
                            </div>
                        </td>
                    </tr>
                    <?php foreach ($classes_list as $schoolClass) { ?>
                        <?php if(
                            (
                                $schoolClass['checked_by_teacher']
                                && $schoolClass['checked_by_teacher_id'] !== AuthComponent::user('id')
                            )
                            || $schoolClass['finalized']
                            || $schoolClass['visible']
                        ){ ?>
                            <tr class="completed_classes_rows" style="display: none;align-items: center">
                                <td class="ed_level_cluster_class_td">
                                    <span class="ed_level_cluster_class_span"><?= $schoolClass['name'] ?></span>
                                </td>
                                <?php foreach ($education_levels as $eductionLevel) { ?>
                                    <?php if (!empty($eductionLevel['education_level'])) { ?>
                                        <td width="57px" style="position:relative; justify-content: center" class="ed_level_checkbox_cols">
                                            <input disabled
                                                    type="radio"
                                                    class="radio-custom jquery-radio-set-eduction-level-step-2"
                                                    value="<?= $eductionLevel['education_level']['id'] ?>"
                                                <?= $eductionLevel['education_level']['id'] == $schoolClass['education_level_id'] ? 'checked' : '' ?>
                                            >
                                            <label
                                                    for="radio-class-<?= $schoolClass['id'] ?>-<?= $eductionLevel['education_level']['id'] ?>"
                                                    class="radio-custom-label">
                                                <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                                          fill-rule="evenodd"
                                                          stroke-linecap="round"/>
                                                </svg>
                                            </label>
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                                <td style="margin-left: auto" width="80px">
                                    <?php if (empty($schoolClass['education_level_year']) || empty($schoolClass['education_level_id'])) { ?>
                                        <span class="import-label label-orange"><?= __("onbekend")?></span>
                                    <?php } else if(!empty($schoolClass['checked_by_teacher'])){ ?>
                                        <span class="import-label label-green"><?= __("Ingesteld")?></span>
                                    <?php } else { ?>
                                        <span class="import-label label-blue"><?= __("bekend")?></span>
                                    <?php } ?>
                                </td>
                                <td width="200px">
                                    <div style="display: flex;">
                                        <?php foreach (range(1, 6) as $count) { ?>
                                            <div>
                                                <input disabled
                                                       type="radio"
                                                       class="number-radio jquery-radio-set_school-year"
                                                    <?= $schoolClass['education_level_year'] == $count ? 'checked' : '' ?>

                                                >
                                                <label for="<?= sprintf('number-%s-%s', $count, $schoolClass['id']) ?>"
                                                       class="number-radio-label "><span> <?= $count ?></span></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td width="120px">
                                    <input disabled
                                           class="checkbox-custom "
                                           type="checkbox"
                                        <?= $schoolClass['checked_by_admin'] || $schoolClass['checked_by_teacher'] ? 'checked' : '' ?>
                                    >
                                    <label for="<?= sprintf('green-checkbox-%s', $schoolClass['id']) ?>"
                                           class="checkbox-custom-label checkbox-green">
                                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                                  fill-rule="evenodd"
                                                  stroke-linecap="round"/>
                                        </svg>
                                    </label></td>
                            </tr>
                        <?php } else { $checkedCount++;} ?>
                    <?php } ?>
                <?php } ?>

                </tbody>
            </table>
        </div>
        <div style="background-color:white;
                box-shadow: 0 -3px 18px 0 rgba(77, 87, 143, 0.2);
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
                position:absolute;
                bottom:0;
                height:90px;
                width:100%;
                margin-left:-40px;
                display:flex;
                justify-content:center;
                "
        >
            <div id="unsaveReturn" class="notification error" style="display:none;position:absolute;z-index: 10;top: -60px;">
                <span class="body"><?= __("Er zijn onopgeslagen wijzigingen. Weet je zeker dat je terug wilt gaan naar de vorige stap?")?></span>
            </div>
            <div
                style="display:flex; width: 100%; align-items: center; justify-content: space-between; padding: 0 40px;">
                <div style="display:flex; position:relative; align-items:center">
                    <button id="btn-back-to-main-school-class"
                            style="height: 50px ; font-size:18px; font-weight: 700;"
                            class="button text-button button-md">
                        <?= $this->element('chevron-left') ?><span class="ml8"><?= __("Terug naar stamklassen")?></span>
                    </button>
                </div>
                <div style="display:flex;">
                     <span id="teacher-complete-counter"
                           style="line-height:1.5rem; text-align:right; font-size:14px;" class="mr10"></span>
                    <button id="btn-save-education-level-cluster-class" style="height: 50px"
                            class="button primary-button button-md mr10">
                            <?= __("Opslaan")?>
                    </button>
                    <button id="btn-subject-cluster-class" style="height: 50px" class="button cta-button button-md">
                        <span class="mr10"><?= __("Vakken instellen")?></span><?= $this->element('chevron') ?>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <script>
        var itemsHaveBeenChanged = false;
        var itemsSaved = false;
        var unsavedNoticeShown = false;
        $(document).ready(function () {
            if (window.teacherCompleteUserImportClusterSchoolClass !== true) {
                $(document)
                    .on('click', '#btn-save-education-level-cluster-class', function (e) {
                        e.preventDefault();
                        $.ajax({
                            method: 'PUT',
                            data: $('#teacher-complete-user-import-education-level-cluster-class-form').serialize(),
                            url: 'users/teacher_complete_user_import_education_level_cluster_class',
                            dataType: 'json',
                            success: function (data) {
                                if (typeof data.error !== 'undefined') {
                                    var error;
                                    for (const property in data.error) {
                                        error = data.error[property];
                                    }
                                    Notify.notify(error, 'error');
                                    return;
                                }

                                var msg = buildNotificationMessage(data.result.count);

                                itemsSaved = true;
                                $('#unsaveReturn').fadeOut();
                                Notify.notify(msg)
                            },
                        });
                    })
                    .on('click', '#btn-subject-cluster-class', function (e) {
                        e.preventDefault();
                        $.ajax({
                            method: 'PUT',
                            data: $('#teacher-complete-user-import-education-level-cluster-class-form').serialize(),
                            url: 'users/teacher_complete_user_import_education_level_cluster_class',
                            dataType: 'json',
                            success: function (data) {
                                if (typeof data.error !== 'undefined') {
                                    var error;
                                    for (const property in data.error) {
                                        error = data.error[property];
                                    }
                                    Notify.notify(error, 'error');
                                    return;
                                }

                                var msg = buildNotificationMessage(data.result.count);

                                Notify.notify(msg, 8000)
                                Popup.closeLast();
                                if(data.result.done){
                                    Notify.notify('<?= __("Super!<br/>Alle gegevens zijn verwerkt en je kunt nu aan de slag met toetsen")?>');
                                    Navigation.refresh();
                                } else {
                                    window.setTimeout(function () {
                                        Popup.load('users/teacher_complete_user_import_subject_cluster_class', 1080);
                                    }, 500);
                                }
                            },
                        });
                    })
                    .on('click', '#btn-back-to-main-school-class', function (e) {
                        e.preventDefault();
                        if (itemsHaveBeenChanged && !itemsSaved) {
                            if (!unsavedNoticeShown) {
                                $('#unsaveReturn').show();
                                unsavedNoticeShown = true;
                                return false;
                            }
                        }
                        Popup.closeLast();
                        window.setTimeout(function () {
                            Popup.load('users/teacher_complete_user_import_main_school_class', 1080);
                        }, 500);
                    })
                    .on('click', '.jquery-radio-set-eduction-level-step-2, .jquery-radio-set_school-year', function (e) {
                        $(this).closest('tr').find('.jquery-controle').attr('checked', true).trigger('change');
                        $(this).closest('tr').find('span.import-label').removeClass(['label-orange', 'label-blue']).addClass('label-green').html('ingesteld');
                    });

                window.teacherCompleteUserImportClusterSchoolClass = true;

            }
            $('.jquery-controle').change(function (e) {
                updateTeacherCompleteClusterClassCounter();
            });
            function checkedAllClasses() {
                return $('.jquery-controle').length === $('.jquery-controle:checked').length;
            }

            function buildNotificationMessage(result) {
                var msg = '<?= __("Gegevens voor 1 klas opgeslagen.")?>';
                if (result !== 1) {
                    msg = '<?= __("Gegevens voor ")?>' + result + '<?= __(" klassen opgeslagen.")?>';
                }
                if (!checkedAllClasses()) {
                    msg += '<br/><strong>' + '<?= __("Let op!")?>'  + '</strong>' + '<?= __("Niet alle gegevens zijn ingevuld. Je klassen worden pas zichtbaar als de wizard volledig is afgerond.")?>'
                }
                return msg;
            }

            function updateTeacherCompleteClusterClassCounter() {
                var aantal = $('.jquery-controle').length;
                var gevinked = $('.jquery-controle:checked').length;
                $('#teacher-complete-counter').html('<span style="font-size:16px;font-weight:bold">' + gevinked + '</span>/' + aantal + '<br/>clusterklassen compleet');
            }
            updateTeacherCompleteClusterClassCounter();

            changeClassColumnWidth();

            if ($('.action_rows').length === 0) {
                $('#note_row').show();
            }

            $('.action_rows').on('change', function() {
                itemsHaveBeenChanged = true;
                itemsSaved = false;
                unsavedNoticeShown = false;
                $('#unsaveReturn').fadeOut();
            })
        });

        $('#show_checked_classes_button').click(function() {
            if($(this).hasClass('open')) {
                $('.completed_classes_rows').hide();
                $(this).find('span').text('<?= __("Toon gecontroleerde klassen")?>');
                $('#checked_classes_svg').css({'transform': 'rotate(90deg) scale(.8)'});
                $(this).toggleClass('open');
            } else {
                $('.completed_classes_rows').css('display','flex');
                $(this).find('span').text('<?= __("Verberg gecontroleerde klassen")?>');
                $('#checked_classes_svg').css({'transform': 'rotate(-90deg) scale(.8)'});
                $(this).toggleClass('open');
            }
        });

        function changeClassColumnWidth() {
            $('.completed_classes_rows').css('display', 'flex');
            var spans = document.querySelectorAll('.ed_level_cluster_class_span');

            setTimeout(function() {
                var width = 100;
                spans.forEach(function(span) {
                    if (span.offsetWidth > width) {
                        width = span.offsetWidth+10;
                    }
                })
                document.querySelectorAll('.ed_level_cluster_class_td').forEach(function(td) {
                    td.width = width;
                })
                $('.completed_classes_rows').hide();
            },100)
        }
    </script>
