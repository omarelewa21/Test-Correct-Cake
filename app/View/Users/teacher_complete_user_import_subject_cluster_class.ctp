<div class="tat-content border-radius-bottom-0 import-heading">
    <div style="display:flex">
        <div style="flex-grow:1">
            <h2 style="margin-top:0">Importgegevens van klassen compleet maken</h2>
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
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 3)) ?>
    </div>
</div>
<form method="put" id="teacher-complete-user-import-subject-cluster-class-form">
    <div class="popup-content tat-content body1" style="display:flex; overflow:hidden">
        <div class="import-table-container">
            <table class="tableFixHead">
                <thead
                    style="position: sticky; top: 0; background: white; border-bottom: 2px solid var(--system-base); z-index:1;">

                <tr style="display: flex; align-items: center">
                    <th width="200px">Klas</th>
                    <?php foreach ($subjects as $subject) {
                        $subjectId = $subject['id'];
                        $subjectName = $subject['abbreviation'] ?  $subject['abbreviation'] : substr($subject['name'], 0,3);
                        ?>

                        <th
                            class="subject-column-<?= $subjectId ?>"
                            width="60px"
                            title="<?= $subject['name'] ?>"
                                <?= in_array($subjectId, $taught_subjects) ? '' : 'style="display:none"'; ?>
                        ><?= $subjectName ?></th>

                    <?php } ?>
                    <th class="add-class-button-column" width="130px">
                        <div style="display:inline-flex; justify-content: space-between">
                            <select id="add-subject-list" class="primary-select-box mr8">
                                <?php foreach ($subjects as $subject) {
                                    $subjectId = $subject['id'];
                                    $subjectName = $subject['abbreviation'] ?  $subject['abbreviation'] : substr($subject['name'], 0,3);
                                    ?>
                                    <?= (!in_array($subjectId, $taught_subjects)) ? sprintf('<option value="%s">%s</option>', $subjectId, $subjectName) : ''; ?>

                                <?php } ?>

                            </select>

                            <button id="btn-add-subject" class="button secondary-button"
                                    style="height: 40px; min-width: 40px;padding:0!important;">
                                <?php echo $this->element('plus') ?>
                            </button>
                        </div>
                    </th>
                    <th style="margin-left: auto;" width="80px">Status</th>
                    <th width="120px">Gecontroleerd</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($classes_list as $schoolClass) { ?>
                    <tr style="display: flex;align-items: center">
                        <td width="200px"><?= $schoolClass['name'] ?></td>

                        <?php foreach ($subjects as $subject) {
                            $subjectId = $subject['id'];
                            $subjectName = $subject['abbreviation'] ?  $subject['abbreviation'] : substr($subject['name'], 0,3);
                            ?>
                            <td
                                width="60px"
                                class="subject-column-<?= $subjectId ?>"
                                style="position:relative; align-content: center; <?= in_array($subjectId,
                                    $taught_subjects) ? '' : 'display:none'; ?>"
                            >
                                <?php if ($schoolClass['is_main_school_class'] == 1) { ?>
                                    <div style="display:flex; position:relative;">
                                        <input
                                            id="checkbox-<?= $schoolClass['id'] ?>-<?= $subjectId ?>"
                                            class="checkbox-custom jquery-radio-set-eduction-level-step-3"
                                            name="teacher[<?= $schoolClass['id'] ?>][<?= $subjectId ?>]"
                                            type="checkbox"
                                            <?= array_key_exists($schoolClass['id'],
                                                $teacher_entries) && in_array($subjectId, $teacher_entries[$schoolClass['id']]) ? 'checked' : ''; ?>
                                        >
                                        <label for="checkbox-<?= $schoolClass['id'] ?>-<?= $subjectId ?>"
                                               class="checkbox-custom-label">
                                            <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8"
                                                      fill="none"
                                                      fill-rule="evenodd"
                                                      stroke-linecap="round"/>
                                            </svg>
                                        </label>
                                    </div>
                                <?php } else { ?>
                                    <input
                                        id="radio-class-<?= $schoolClass['id'] ?>-<?= $subjectId ?>"
                                        name="teacher[<?= $schoolClass['id'] ?>]"
                                        type="radio"
                                        class="radio-custom jquery-radio-set-eduction-level-step-3"
                                        value="<?= $subjectId ?>"
                                        <?= array_key_exists($schoolClass['id'],
                                            $teacher_entries) && in_array($subjectId,$teacher_entries[$schoolClass['id']])  ? 'checked' : ''; ?>
                                    >
                                    <label
                                        for="radio-class-<?= $schoolClass['id'] ?>-<?= $subjectId ?>"
                                        class="radio-custom-label">
                                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8"
                                                  fill="none"
                                                  fill-rule="evenodd"
                                                  stroke-linecap="round"/>
                                        </svg>
                                    </label>
                                <?php } ?>
                            </td>
                        <?php } ?>
                        <td class="add-class-filler-column" width="130px">&nbsp;</td>
                        <td style="margin-left: auto;" width="80px">
                            <?php if (!array_key_exists($schoolClass['id'], $teacher_entries)) { ?>
                                <span class="import-label label-orange">onbekend</span>
                            <?php } else if(!empty($checked_by_teacher[$schoolClass['id']])){ ?>
                                <span class="import-label label-green">Ingesteld</span>
                            <?php } else { ?>
                                <span class="import-label label-blue">bekend</span>
                            <?php } ?>
                        </td>
                        <td width="120px">
                            <input id="<?= sprintf('green-checkbox-%s-%s', $schoolClass['id'], $subjectId) ?>"
                                   class="checkbox-custom jquery-subject-complete-counter"
                                   name="class[<?= $schoolClass['id'] ?>][checked]"
                                   type="checkbox"
                                <?= array_key_exists($schoolClass['id'],$checked_by_teacher) && $checked_by_teacher[$schoolClass['id']] ? 'checked' : '' ?>
                            >
                            <label for="<?= sprintf('green-checkbox-%s-%s', $schoolClass['id'], $subjectId) ?>"
                                   class="checkbox-custom-label checkbox-green">
                                <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                          fill-rule="evenodd"
                                          stroke-linecap="round"/>
                                </svg>
                            </label></td>
                    </tr>
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
                margin-left:-40px; display:flex"
        >
            <div
                style="display:flex; width: 100%; align-items: center; justify-content: space-between; padding: 0 40px;">
                <div style="display:flex; position:relative; align-items:center">
                    <!--                    <div style="display:flex; position:relative;">-->
                    <!--                        <input id="checkbox-1" class="checkbox-custom" name="checkbox" type="checkbox" checked>-->
                    <!--                        <label for="checkbox-1" class="checkbox-custom-label">-->
                    <!--                            <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">-->
                    <!--                                <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"-->
                    <!--                                      fill-rule="evenodd"-->
                    <!--                                      stroke-linecap="round"/>-->
                    <!--                            </svg>-->
                    <!--                        </label>-->
                    <!---->
                    <!---->
                    <!--                    </div>-->
                    <!---->
                    <!--                    <div style="display: flex;">-->
                    <!--                        <span class="import-label label-blue">bekend</span>-->
                    <!--                        <span class="import-label label-orange">onbekend</span>-->
                    <!--                        <span class="import-label label-green">ingesteld</span>-->
                    <!--                    </div>-->


                    <button id="btn-back-to-eduction-level" class="button text-button"
                            style="font-size:18px; font-weight:bold;">
                        <?php echo $this->element('chevron-left') ?> Terug naar niveau &amp; leerjaar
                    </button>


                </div>
                <div style="display:flex;">
                    <span id="teacher-subject-complete-counter"
                          style="line-height:1.5rem; text-align:right; font-size:14px;" class="mr10"></span>
                    <button id="btn-save-subject-cluster-class" style="height: 50px"
                            class="button cta-button button-md">
                        Opslaan
                    </button>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            if (window.teacherCompleteUserImportSubjectClusterClass !== true) {
                $(document)
                    .on('click', '#btn-save-subject-cluster-class', function (e) {
                        e.preventDefault();
                        $.ajax({
                            method: 'PUT',
                            data: $('#teacher-complete-user-import-subject-cluster-class-form').serialize(),
                            url: 'users/teacher_complete_user_import_subject_cluster_class',
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

                                var msg = 'Gegevens voor 1 koppeling opgeslagen.';
                                if (data.result.count !== 1) {
                                    msg = 'Gegevens voor ' + data.result.count + ' koppelingen opgeslagen.';
                                }

                                Notify.notify(msg)

                                if(data.result.done){
                                    Notify.notify('Super!<br/>Alle gegevens zijn verwerkt en je kunt nu aan de slag met toetsen');
                                    Popup.closeLast();
                                    Navigation.refresh();
                                }
                                // Popup.closeLast();
                            },
                        });
                    })
                    .on('click', '#btn-back-to-eduction-level', function (e) {
                        e.preventDefault();
                        Popup.closeLast();
                        window.setTimeout(function () {
                            Popup.load('users/teacher_complete_user_import_education_level_cluster_class', 1080);
                        }, 500);
                    })

                    .on('click', '.jquery-radio-set-eduction-level-step-3', function (e) {
                        $(this).closest('tr').find('.jquery-subject-complete-counter').attr('checked', true).trigger('change');
                        $(this).closest('tr').find('span.import-label').removeClass(['label-orange', 'label-blue']).addClass('label-green').html('ingesteld');
                    })
                    .on('click', '#btn-add-subject', function (e) {
                        e.preventDefault();
                        var subjectIdToShow = $('#add-subject-list').val();
                        var columnSelector = $('.subject-column-' + subjectIdToShow).css('display', 'inline-block');
                        $("#add-subject-list option[value='" + subjectIdToShow + "']").remove();
                        checkDisplaySelectBox();
                    });




                window.teacherCompleteUserImportSubjectClusterClass = true;


            }
            function updateTeacherSubjectCompleteCounter() {
                var aantal = $('.jquery-subject-complete-counter').length;
                var gevinked = $('.jquery-subject-complete-counter:checked').length;
                $('#teacher-subject-complete-counter').html('<span style="font-size:16px;font-weight:bold">' + gevinked + '</span>/' + aantal + '<br/>vakken compleet');
            }

            $('.jquery-subject-complete-counter').change(function (e) {
                updateTeacherSubjectCompleteCounter();
            });
            function checkDisplaySelectBox(){
                if (document.getElementById('add-subject-list').options.length === 0){
                    $('#add-subject-list').hide();
                    $('#btn-add-subject').hide();
                    document.querySelector('.add-class-button-column').setAttribute('width', 0);
                    document.querySelectorAll('.add-class-filler-column').forEach(function(el) {
                        el.setAttribute('width', 0);
                    });
                }
            }

            checkDisplaySelectBox();

            updateTeacherSubjectCompleteCounter();
        });
    </script>
