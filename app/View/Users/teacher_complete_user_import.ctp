<style>
    .tableFixHead {
        display: block;
        max-height: 400px;
        overflow-y: scroll;
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
        <div>
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
    <div style="display: flex; align-items: center; justify-content: center; font-size: 16px">
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 2)) ?>
    </div>
    <div style="display: flex; align-items: center; justify-content: center; font-size: 16px">
        <?php echo $this->element('teacher_complete_user_import_steps', array('step' => 3)) ?>
    </div>
</div>
<div class="popup-content tat-content body1" style="display:flex; position:relative;">
    <div style="display:flex; flex-grow:1; flex-direction: column; width:50%; padding-right: 10px; padding-bottom:60px">
        <table class="tableFixHead">
            <thead style="position: sticky; top: 0; background: white; border-bottom: 2px solid var(--system-base)">

            <tr>
                <th>Klas</th>
                <?php foreach($education_levels as $level) { ?>
                <th><?= $level['education_level']['name'] ?></th>

                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($classes_list as $className) { ?>
            <tr>
                <td><?= $className ?></td>
                <?php foreach ($education_levels as $eductionLevel) { ?>
                <td style="position:relative; align-content: center"> <input id="radio-class-<?= className ?>-<?= $eductionLevel['education_level']['id'] ?>" name="class_<?= $className ?>" type="radio" class="radio-custom" value="<?= $eductionLevel['education_level']['id'] ?>">
                    <label for="radio-class-<?= className ?>-<?= $eductionLevel['education_level']['id'] ?>" class="radio-custom-label">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label></td>
                <?php } ?>
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
        <div style="display:flex; width: 100%; align-items: center; justify-content: space-between; padding: 0 40px;">
            <div style="display:flex; position:relative; align-items:center">
                <div style="display:flex; position:relative;">
                    <input id="radio-1" name="radio" type="radio" class="radio-custom">
                    <label for="radio-1" class="radio-custom-label">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label>

                    <input id="radio-2" name="radio" type="radio" class="radio-custom">
                    <label for="radio-2" class="radio-custom-label">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label>
                    <input id="radio-3" name="radio" type="radio" class="radio-custom">
                    <label for="radio-3" class="radio-custom-label">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label>

                </div>
                <div style="display:flex; position:relative;">
                    <input id="checkbox-1" class="checkbox-custom" name="checkbox" type="checkbox" checked>
                    <label for="checkbox-1" class="checkbox-custom-label">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label>

                    <input id="checkbox-2" class="checkbox-custom" name="checkbox" type="checkbox">
                    <label for="checkbox-2" class="checkbox-custom-label checkbox-green">
                        <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                                  fill-rule="evenodd"
                                  stroke-linecap="round"/>
                        </svg>
                    </label>
                </div>

                <div style="display: flex;">
                    <span class="import-label label-blue">bekend</span>
                    <span class="import-label label-orange">onbekend</span>
                    <span class="import-label label-green">ingesteld</span>
                </div>

                <div style="display: flex;">
                    <div>
                        <input id="number-1" name="numbers" type="radio" class="number-radio">
                        <label for="number-1" class="number-radio-label"><span>1</span></label>
                    </div>
                    <div>
                        <input id="number-2" name="numbers" type="radio" class="number-radio">
                        <label for="number-2" class="number-radio-label"><span>2</span></label>
                    </div>
                    <div>
                        <input id="number-3" name="numbers" type="radio" class="number-radio">
                        <label for="number-3" class="number-radio-label"><span>3</span></label>
                    </div>
                    <div>
                        <input id="number-4" name="numbers" type="radio" class="number-radio">
                        <label for="number-4" class="number-radio-label"><span>4</span></label>
                    </div>
                    <div>
                        <input id="number-5" name="numbers" type="radio" class="number-radio">
                        <label for="number-5" class="number-radio-label"><span>5</span></label>
                    </div>
                </div>

                <div>
                    <button class="button primary-button" style="height: 40px; width: 40px;padding:0!important;">
                        <?php echo $this->element('plus') ?>
                    </button>
                </div>

            </div>
            <div style="display:flex;">
                <button style="height: 50px" class="button cta-button button-md">
                    Opslaan
                </button>
            </div>
        </div>

    </div>
</div>
<script>

</script>
