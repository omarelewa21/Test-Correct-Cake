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

    .checkbox-custom:checked + .checkbox-custom-label.checkbox-green:before {
        background: var(--cta-primary);
        border-color: var(--cta-primary);
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
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 3)) ?>
    </div>
</div>
<form method="put" id="teacher-complete-user-import-subject-cluster-class-form">
    <div class="popup-content tat-content body1" style="display:flex; overflow:hidden">
        <div
            style="display:flex; flex-grow:1; flex-direction: column; width:50%; padding-right: 10px; padding-bottom:60px">
            <table class="tableFixHead">
                <thead
                    style="position: sticky; top: 0; background: white; border-bottom: 2px solid var(--system-base); z-index:1;">

                <tr>
                    <th width="200px">Klas</th>
                    <?php foreach ($subjects as $subjectId => $subjectName) { ?>
                        <th width="80px"><?= $subjectName ?></th>

                    <?php } ?>
                    <th>
                        <button class="button text-button" style="height: 40px; width: 40px;padding:0!important;">
                            <?php echo $this->element('plus') ?>
                        </button>
                    </th>
                    <th width="80px">Status</th>

                    <th width="150px">Gecontrolleerd</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($classes_list as $schoolClass) { ?>
                    <tr>
                        <td width="200px"><?= $schoolClass['name'] ?></td>

                        <?php foreach ($subjects as $subjectId => $subjectName) { ?>
                            <td width="80px" style="position:relative; align-content: center">
                                <?php if ($schoolClass['is_main_school_class'] == 1) { ?>
                                    <div style="display:flex; position:relative;">
                                        <input
                                            id="checkbox-<?= $schoolClass['id'] ?>-<?= $subjectId ?>"
                                            class="checkbox-custom jquery-radio-set-eduction-level"
                                            name="teacher[<?= $schoolClass['id'] ?>][<?= $subjectId ?>]"
                                            type="checkbox"
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
                                        class="radio-custom jquery-radio-set-eduction-level"
                                        value="<?= $subjectId ?>">
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
                        <td>&nbsp;</td>
                        <td width="80px"><span class="import-label label-blue">bekend</span></td>


                        <td width="150px"><input id="<?= sprintf('checkbox-%s-%s', $schoolClass['id'], $subjectId) ?>"
                                                 class="checkbox-custom jquery-controle"
                                                 name="class[<?= $schoolClass['id'] ?>][<?=$subjectId?>]" type="checkbox">
                            <label for="<?= sprintf('checkbox-%s-%s', $schoolClass['id'], $subjectId) ?>"
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
                                var msg = 'Gegevens voor 1 koppeling opgeslagen.';
                                if (data.result.count !== 1) {
                                    msg = 'Gegevens voor ' + data.result.count + ' koppelingen opgeslagen.';
                                }

                                Notify.notify(msg)
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

                    .on('click', '.jquery-radio-set-eduction-level', function (e) {
                        $(this).closest('tr').find('.jquery-controle').attr('checked', true);
                    });
                window.teacherCompleteUserImportSubjectClusterClass = true;
            }
        });
    </script>
