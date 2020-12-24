<link href="/css/filepond.css" rel="stylesheet">
<div class="popup-head">Toets uploaden</div>
<div class="popup-content">

    <div class=" " id="FileTestBlock">
        <div id="FileTestContainer" style="display:none;overflow:scroll;padding: 8px;">
            Een moment dit kan even duren...
            <h4 style="color:green;" id="wistjedatjes"></h4>
        </div>
        <?= $this->Form->create('FileTest', array('id' => 'FileTestForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment')) ?>
        <div class="block-content" id="testsContainer">
            <table class='table'>               
                <tr>
                    <td><label>Niveau</label></td>
                    <td>
                        <?= $this->Form->input('education_level_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $educationLevelOptions)) ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Jaar</label></td>
                    <td>
                        <?= $this->Form->input('education_level_year', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [])) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Vak</label>
                    </td>
                    <td>
                        <?= $this->Form->input('subject', array('id' => 'subject', 'value' => '', 'label' => false, 'verify' => 'notempty')) ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Type toets</label></td>
                    <td>
                        <?= $this->Form->input('test_kind_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $testKindOptions)) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Toets naam</label>
                    </td>
                    <td>
                        <?= $this->Form->input('name', array('id' => 'name', 'value' => '', 'label' => false, 'verify' => 'notempty')) ?>
                    </td>
                </tr>


                <tr>
                    <td>Kies één of meerdere bestanden en <br>klik rechts om te uploaden</td>
                    <td>
                        <?= $this->Form->input('form_id', array('type' => 'hidden', 'label' => false, 'div' => false, 'value' => $form_id)) ?>
                        <?= $this->Form->input('file.', array('type' => 'file', 'multiple', 'label' => false, 'div' => false, 'onchange' => 'makeFileList()')) ?>  
                        <script>
                            [
                                {supported: 'Symbol' in window, fill: 'https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser-polyfill.min.js'},
                                {supported: 'Promise' in window, fill: 'https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js'},
                                {supported: 'fetch' in window, fill: 'https://cdn.jsdelivr.net/npm/fetch-polyfill@0.8.2/fetch.min.js'},
                                {supported: 'CustomEvent' in window && 'log10' in Math && 'sign' in Math && 'assign' in Object && 'from' in Array &&
                                            ['find', 'findIndex', 'some', 'includes'].reduce(function (previous, prop) {
                                        return (prop in Array.prototype) ? previous : false;
                                    }, true), fill: 'https://unpkg.com/filepond-polyfill/dist/filepond-polyfill.js'}
                            ].forEach(function (p) {
                                if (p.supported)
                                    return;
                                document.write('<script src="' + p.fill + '"><\/script>');
                            });
                        </script>

                    </td>
                </tr>
                <tr>
                    <td><label>Correctiemodel toegevoegd?</label></td>
                    <td>
                        <?= $this->Form->input('correctiemodel', array('id' => 'correctiemodel', 'type' => 'select', 'label' => false, 'div' => false, 'options' => [-1 => 'Maak een keuze', 0 => 'Nee, dat heb ik nog niet gedaan', 1 => 'Ja, die zit erbij'])) ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Een enkele of meerdere toetsen?</label></td>
                    <td>
                        <?= $this->Form->input('multiple', array('id' => 'multiple', 'type' => 'select', 'label' => false, 'div' => false, 'options' => [-1 => 'Maak een keuze', 0 => 'Eén enkele toets ', 1 => 'Meerdere toetsen'])) ?>
                    </td>
                </tr>
            </table>

            <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
        </div>
        <div class="block-footer">
            <a href="#" id="submitbutton" class="btn highlight mt5 mr5 pull-right" onclick="handleSubmit()">
                Toets uploaden
            </a>
            <a href="#" id="cancelbutton" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();
                    Navigation.refresh();">
                Annuleer
            </a>
        </div>
        <?= $this->Form->end ?>
    </div>
</div>

<style>
    .text-danger {
        color : #d9534f;
    }
</style>
<script src="/js/filepond.js"></script>
<script src="/js/filepond_metadata.js"></script>
<script>

                var uploaded = [];
                var canSubmit = false;
                $('#submitbutton').addClass('disabled');

                function handleUploadError(error) {
                    clearTimeout(wistjedatjeTimer);
                    Notify.notify(error, 'error');
                    $('#FileTestContainer').hide();
                    $('#FileTestForm').show();
                }

                function handleUploadResponse(data) {
                    clearTimeout(wistjedatjeTimer);
                    Notify.notify(data, 'info');
                    Navigation.refresh();
                    Popup.closeLast();
                }
                ;

                let wistjedatjeTime = 10 * 1000;
                var wistjedatjeTimer
                var wistjedatjeNr = 0;
                let wistjedatjes = [
                    'Wist je dat je met de co-learning module de studenten zelf de toetsen kunt laten nakijken..',
                    'Wist je dat we ook tekenvragen aanbieden waarme de student een tekening kan maken op z\'n device..',
                    'Wist je dat we nu ook infoschermen kennen waarmee je de student informatie kunt verschaffen over de komende vragen in de toets...',
                    'Wist je dat we een voorleesfunctie hebben waarmee studenten de tekst van de toets voorgelezen kunnen krijgen...'
                ];
                let wistjedatjesEl = jQuery('#wistjedatjes');

                let educationLevels = JSON.parse('<?php echo json_encode($educationLevels) ?>');


                function handleSubmit() {

                    if ($('#correctiemodel').val() == "-1") {
                        window.parent.handleUploadError("Er dient een correctiemodel mee gestuurd te worden");
                        return false;
                    }

                    if ($('#multiple').val() == "-1") {
                        window.parent.handleUploadError("Er kan maximaal 1 toets per keer geupload worden");
                        return false;
                    }

                    if ($('#subject').val() == "") {
                        window.parent.handleUploadError("U heeft geen vaknaam ingevuld");
                        return false;
                    }

                    if ($('#name').val() == "") {
                        window.parent.handleUploadError("U heeft geen toetsnaam ingevuld");
                        return false;
                    }

                    if (!canSubmit) {

                        window.parent.handleUploadError("Niet alle bestanden zijn geupload of er zijn geen bestanden gekozen");
                        return false;

                    } else {
                        $('#FileTestBlock').height($('#FileTestBlock').height()).css('overflow', 'scroll').css('padding', '8px');
                        $('#FileTestContainer').show();
                        $('#FileTestForm').hide().submit();
                        showWistJeDatJe();
                    }

                }

                function showWistJeDatJe() {
                    if (wistjedatjeNr >= wistjedatjes.length) {
                        wistjedatjeNr = 0;
                    }
                    wistjedatjesEl.html(wistjedatjes[wistjedatjeNr]);
                    wistjedatjeNr++;
                    setWistjedatjesTimer();
                }

                function setWistjedatjesTimer() {
                    wistjedatjeTimer = setTimeout(function () {
                        showWistJeDatJe();
                    },
                            wistjedatjeTime);
                }

                var educationLevelSelect = jQuery('#FileTestEducationLevelId');
                var educationLevelYearSelect = jQuery('#FileTestEducationLevelYear');

                function FileTestSetup() {
                    $(document).ready(function () {
                        jQuery('body')
                                .on('change', '#FileTestEducationLevelId', function () {
                                    var elId = $(this).val();
                                    if (elId) {
                                        var maxYears = educationLevels.find(level => level.id == elId)['max_years'];
                                        educationLevelYearSelect.find('option').remove();
                                        for (i = 1; i <= maxYears; i++) {
                                            educationLevelYearSelect.append('<option value="' + i + '">' + i + '</option>');
                                        }
                                        ;
                                    }
                                    console.log('changed');
                                });
                    });
                    FileTestSetupRun = true;
                }

                if (typeof FileTestSetupRun == 'undefined' || !FileTestSetupRun) {
                    FileTestSetup();
                }

                educationLevelSelect.trigger('change');

// filepond stuff

                $(document).ready(function () {

                    FilePond.registerPlugin(FilePondPluginFileMetadata);
// Get a reference to the file input element
                    const inputElement = document.querySelector('input[type="file"]');

// Create the FilePond instance
                    const pond = FilePond.create(inputElement, {
                        allowMultiple: true,
                        allowReorder: true,
                        allowRevert: false,
                        allowRemove: true
                    });

                    FilePond.setOptions({
                        server: '/FileManagement/upload_test',
                        instantUpload: false,
                        checkValidity: true,
                        onaddfile: function (error, file) {
                            uploaded[file.id] = file.filename
                            var canSubmit = false;
                            $('#submitbutton').addClass('disabled');
                            console.log('cant submit');
                        },
                        onprocessfile: function (error, file) {
                            if (error === null)
                                console.log('File processed: [' + error + ']   file: [' + file.id + ']');
                            console.log('cant submit ' + canSubmit);
                        },
                        onremovefile: function (error, file) {
                            if (file.id in uploaded)
                                delete uploaded[file.id];
                        },
                        onprocessfiles: function () {
                            console.log('all files done');
                            canSubmit = true;
                            console.log('cant submit!');
                            $('#submitbutton').removeClass('disabled');
                        },
                        fileMetadataObject: {
                            'form_id': '<?= $form_id; ?>'
                        }
                    });

// Easy console access for testing purposes
                    window.pond = pond;
                });
</script>
