<style>
    .tableFixHead {
        display: block;
        max-height: 400px;
        overflow: auto;
    }

    .tableFixHead thead, .tableFixHead tbody {
        display: table;
        width: 100%;
    }

    .tableFixHead thead th {
        font-weight: bold;
    }


    .checkbox-custom, .radio-custom, .number-radio {
        opacity: 0;
        position: absolute;
    }

    .checkbox-custom, .checkbox-custom-label, .radio-custom, .radio-custom-label, .number-radio, .number-radio-label {
        display: inline-block;
        vertical-align: middle;
        margin: 4px;
        cursor: pointer;
        line-height: 18px;
    }

    .checkbox-custom-label, .radio-custom-label, .number-radio-label {
        position: relative;
    }

    .checkbox-custom + .checkbox-custom-label:before, .radio-custom + .radio-custom-label:before, .number-radio + .number-radio-label:before {
        content: '';
        background: var(--off-white);
        border: 1px solid var(--blue-grey);
        display: inline-block;
        vertical-align: middle;
        width: 18px;
        height: 18px;
        padding: 2px;
        text-align: center;
    }

    .checkbox-custom + .checkbox-custom-label:before {
        border-radius: 8px;
    }

    .radio-custom + .radio-custom-label:before, .number-radio + .number-radio-label:before {
        border-radius: 50%;
    }

    .checkbox-custom:checked + .checkbox-custom-label:before, .radio-custom:checked + .radio-custom-label:before, .number-radio:checked + .number-radio-label:before {
        content: '';
        background: var(--primary);
        border-color: var(--primary);
    }

    .checkbox-custom:checked:disabled + .checkbox-custom-label:before, .radio-custom:checked:disabled + .radio-custom-label:before, .number-radio:checked:disabled + .number-radio-label:before {
        content: '';
        background: var(--mid-grey);
        border-color: var(--mid-grey);
    }

    .checkbox-custom:checked + .checkbox-custom-label.checkbox-green:before {
        background: var(--cta-primary);
        border-color: var(--cta-primary);
    }

    .checkbox-custom:checked:disabled + .checkbox-custom-label.checkbox-green:before {
        background: var(--mid-grey);
        border-color: var(--mid-grey);
    }

    .checkbox-custom-label svg, .radio-custom-label svg {
        color: var(--off-white);
        position: absolute;
        left: 5px;
        top: 7px;
    }

    .checkbox-custom:checked + .checkbox-custom-label svg, .radio-custom:checked + .radio-custom-label svg {
        color: white;
    }

    .number-radio:checked + .number-radio-label span {
        color: white;
    }

    .number-radio-label span {
        position: absolute;
        font-size: 12px;
        left: 8px;
        top: 6px;
        line-height: 12px;
    }

    .import-label {
        font-size: 10px;
        font-weight: bold;
        padding: 6px 8px;
        line-height: 12px;
        text-transform: uppercase;
        border-radius: 4px;
        max-height: 24px;
    }

    .import-label.label-blue {
        color: var(--system-base);
        background-color: var(--system-secondary);
    }

    .import-label.label-orange {
        color: white;
        background-color: var(--orange);
    }

    .import-label.label-green {
        color: white;
        background-color: var(--cta-primary);
    }


</style>

<div class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;">
    <div style="display:flex">
        <div style="flex-grow:1">
            <h2 style="margin-top:0">Importgegevens van klassen compleet maken</h2>
        </div>
        <div style="margin-top:-2px">
            <?php echo $this->element('teacher_complete_user_import_tooltip') ?>
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
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 1)) ?>
    </div>

</div>
<form method="put" id="teacher-complete-user-import-main-school-class">
    <div class="popup-content tat-content body1" style="display:flex; overflow:hidden">
        <div
            style="display:flex; flex-grow:1; flex-direction: column; width:50%; padding-right: 10px; padding-bottom:60px">
            <table class="tableFixHead">
                <thead
                    style="position: sticky; top: 0; background: white; border-bottom: 2px solid var(--system-base); z-index:1;">

                <tr>
                    <th width="200px">Klas</th>
                    <?php foreach ($education_levels as $level) { ?>
                        <?php if (!empty($level['education_level'])) { ?>
                        <th width="80px"><?= $level['education_level']['name'] ?></th>
                        <?php } ?>

                    <?php } ?>
                    <th width="80px">Status</th>

                    <th width="150px">Gecontrolleerd</th>
                </tr>
                </thead>
                <tbody>
                <?php $checkedCount = 0; ?>
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
                        <tr>
                            <td width="200px"><?= $schoolClass['name'] ?> </td>
                            <?php foreach ($education_levels as $eductionLevel) { ?>
                            <?php if (!empty($eductionLevel['education_level'])) { ?>
                                <td width="80px" style="position:relative; align-content: center"><input
                                        id="radio-class-<?= $schoolClass['id'] ?>-<?= $eductionLevel['education_level']['id'] ?>"
                                        name="class[<?= $schoolClass['id'] ?>][education_level]" type="radio"
                                        class="radio-custom jquery-radio-set-eduction-level"
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
                            <td width="80px">
                                <?php if (empty($schoolClass['education_level_id'])) { ?>
                                    <span class="import-label label-orange">onbekend</span>
                                <?php } else if(!empty($schoolClass['checked_by_teacher'])){ ?>
                                    <span class="import-label label-green">Ingesteld</span>
                                <?php } else { ?>
                                    <span class="import-label label-blue">bekend</span>
                                <?php } ?>

                            </td>

                            <td width="150px">
                                <input
                                    id="<?= sprintf('green-checkbox-%s', $schoolClass['id']) ?>"
                                    class="checkbox-custom jquery-controle"
                                    name="class[<?= $schoolClass['id'] ?>][checked]"
                                    type="checkbox"
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
                    <?php } else { $checkedCount++; }?>
                <?php } ?>

                <?php if($checkedCount > 0) { ?>
                <tr>
                    <td colspan="<?= 3+ count($education_levels);?>">Reeds eerder gecontroleerde klassen</td>
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
                            <tr>
                                <td width="200px"><?= $schoolClass['name'] ?> </td>
                                <?php foreach ($education_levels as $eductionLevel) { ?>
                                    <?php if (!empty($eductionLevel['education_level'])) { ?>
                                        <td width="80px" style="position:relative; align-content: center"><input disabled
                                                    type="radio"
                                                    class="radio-custom jquery-radio-set-eduction-level"
                                                <?= $eductionLevel['education_level']['id'] == $schoolClass['education_level_id'] ? 'checked' : '' ?>
                                            >
                                            <label
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
                                <td width="80px">
                                    <?php if (empty($schoolClass['education_level_id'])) { ?>
                                        <span class="import-label label-orange">onbekend</span>
                                    <?php } else if(!empty($schoolClass['checked_by_teacher'])){ ?>
                                        <span class="import-label label-green">Ingesteld</span>
                                    <?php } else { ?>
                                        <span class="import-label label-blue">bekend</span>
                                    <?php } ?>

                                </td>

                                <td width="150px">
                                    <input disabled
                                            class="checkbox-custom jquery-controle"
                                            type="checkbox"
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
                        <?php } else { $checkedCount++; }?>
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
                margin-left:-40px; display:flex"
        >
            <div
                style="display:flex; width: 100%; align-items: center; justify-content: flex-end; padding: 0 40px;">

                <div style="display:flex;">
                     <span id="teacher-complete-counter"
                           style="line-height:1.5rem; text-align:right; font-size:14px;" class="mr10"></span>
                    <button id="btn-save-teacher-complete-user-import-main-school-class" style="height: 50px"
                            class="button primary-button button-md mr10">
                        Opslaan
                    </button>
                    <button id="btn-go-to-teacher-complete-user-import-education-level-cluster-class"
                            style="height: 50px" class="button cta-button button-md">
                        Clusterklassen instellen <?= $this->element('chevron') ?>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            if (window.teacherCompleteUserImportMainSchoolClass !== true) {
                $(document)
                    .on('click', '#btn-save-teacher-complete-user-import-main-school-class', function (e) {
                        e.preventDefault();

                        $.ajax({
                            method: 'PUT',
                            data: $('#teacher-complete-user-import-main-school-class').serialize(),
                            url: 'users/teacher_complete_user_import_main_school_class',
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

                                var msg = 'Gegevens voor 1 klas opgeslagen.';
                                if (data.result.count !== 1) {
                                    msg = 'Gegevens voor ' + data.result.count + ' klassen opgeslagen.';
                                }
                                Notify.notify(msg)
                            },

                        });
                    })
                    .on('click', '#btn-go-to-teacher-complete-user-import-education-level-cluster-class', function (e) {
                            e.preventDefault();
                            $.ajax({
                                method: 'PUT',
                                data: $('#teacher-complete-user-import-main-school-class').serialize(),
                                url: 'users/teacher_complete_user_import_main_school_class',
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
                                    var msg = 'Gegevens voor 1 klas opgeslagen.';
                                    if (data.result.count !== 1) {
                                        msg = 'Gegevens voor ' + data.result.count + ' klassen opgeslagen.';
                                    }
                                    Notify.notify(msg)
                                    Popup.closeLast();
                                    if(data.result.done){
                                        Notify.notify('Super!<br/>Alle gegevens zijn verwerkt en je kunt nu aan de slag met toetsen');
                                        Popup.closeLast();
                                        Navigation.refresh();
                                    } else {
                                        window.setTimeout(function () {
                                            Popup.load('users/teacher_complete_user_import_education_level_cluster_class', 1080);
                                        }, 500);
                                    }
                                }
                            });
                        }
                    )
                    .on('click', '.jquery-radio-set-eduction-level', function (e) {
                        $(this).closest('tr').find('.jquery-controle').attr('checked', true).trigger('change');
                        $(this).closest('tr').find('span.import-label').removeClass(['label-orange', 'label-blue']).addClass('label-green').html('ingesteld');
                    });

                window.teacherCompleteUserImportMainSchoolClass = true;

            }
            function updateTeacherCompleteCounter() {
                var aantal = $('.jquery-controle').length;
                var gevinked = $('.jquery-controle:checked').length;
                $('#teacher-complete-counter').html('<span style="font-size:16px;font-weight:bold">' + gevinked + '</span>/' + aantal + '<br/>stamklassen compleet');
            }
            $('.jquery-controle').change(function (e) {
                updateTeacherCompleteCounter();
            });


            updateTeacherCompleteCounter();
        });
    </script>
