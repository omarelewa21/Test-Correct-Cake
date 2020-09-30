<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Studenten import</h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Locatie</th>
                <td width="35%"><?=$school_location['name']?></td>
                <th width="15%">Klas</th>
                <td width="35%">
                    <select id="class_id">
                        <?php
                        foreach($classes as $id => $name){
                        $selected = ($id == $class_id) ? 'selected="selected"' : '';
                        ?><option value="<?=$id?>" <?=$selected?>><?=$name?></option><?php
                        }
                    ?>
                    </select></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Data
        <button id="setDefaultHeading">Zet default kolom headers!!</button>
    </div>
    <div class="duplicate" id="duplicates-data-errors"></div>
    <div class="error" id="column-errors"></div>
    <div class="error" id="missing-data-errors"></div>
    <div class="block-content">


    <div >
            <div>
                <textarea rows="1" id="excelPasteBox" placeholder="Plak je excel data hier..."></textarea>
            </div>
            <hr class="showAfterProcess"/>
            <div id="output">
            </div>
            <hr class="showAfterProcess"/>
            <a href="#" id="exportJsonData" style="display:none" class="btn highlight inline-block showAfterProcess" >Studenten importeren</a>
            <textarea style="display:none !important" rows="20" id="jsonDataDump" placeholder="JSON Data will appear here..."></textarea>
        </div>
    </div>
</div>

<style>
    textarea#excelPasteBox {
        width: 60%;
        padding: 8px;
    }

    hr {

        background: #dddddd;
        color: #dddddd;
        border:none;
        color:transparent;
        height: 2px;
    }

    textarea#jsonDataDump {
        width: 90%;
        padding: 8px;
    }

    th > div {
        border: thin #d3d3d3 dashed;
        padding: 2px;
    }

    td > div {
        padding: 2px;
    }

    .ignored {
        background-color: #d3d3d3;
        color: grey;
    }

    th.ignored > div:before {
        content: "Column Ignored";
    }
    table#excelDataTable tr td {
        position:relative;
    }
    table#excelDataTable .dynatable-head,
    table#excelDataTable .dynatable-head div{
        border: none;
        font-size:0;
    }
    #dynatable-record-count-excelDataTable {
        display : none;
        text-align: right;
    }
    .showAfterProcess {
        display:none;
    }
    .deleteicon {
        display:block;
        position:absolute;
        z-index:10;
        left:-10px;
        top:8px;
        color:#f54c53;
        font-size: 13px;
        line-height: 26px;
        cursor: pointer;
    }
    .duplicate {
        background-color: dodgerblue;
    }

    .error {
        background: indianred;
    }

</style>

<script type="text/javascript">
    if(typeof window.importPageHasBeenLoadedBefore == 'undefined'){
        window.importPageHasBeenLoadedBefore = true;
        var jsonObj;

        $('#setDefaultHeading').on('click', function () {
            $('.selectbox-update').each((index, el) => {
                var nr = $(el).data('nr');
                $(el).children().eq(nr).prop('selected', true);
                $(el).trigger('change');

            });
        })

        $(document).on('keypress', 'textarea#jsonDataDump', function(e) {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('keypress', 'textarea#excelPasteBox', function(e) {
            if (e.ctrlKey !== true && e.key != 'v') {
                e.preventDefault();
                e.stopPropagation();
            }
        })
        .on('paste', 'textarea#excelPasteBox', function(e) {
            e.preventDefault();
            try {
                parsePastedData(e);
            }
            catch(e){
                Notify.notify('Er is iets fout gegaan bij het omzetten,<br />Probeer het nogmaals en als het probleem zich voor blijft doen, neem dan contact met ons op.', 'error');
            }
        })
        .on('change','.selectbox-update',function(){
            var val = $(this).val();
            var index = $(this).data('nr');
            var td = $("table#excelDataTable th:eq("+index+") div").text(val);
        })
        .on('click', 'a#exportJsonData', function() {
            uploadData()
        })
        .on('click', 'table#excelDataTable td', function(e) {
            makeElementEditable(this);
        })
        .on('mouseenter','table#excelDataTable tr',function(){
            var span = '<span class="deleteicon"><i class="fa fa-minus-circle"></i></span>';
            $(this).find('td:first').append(span);
            $('tr.rowToDelete').removeClass('rowToDelete');
        })
        .on('click','.deleteicon',function(){
            $(this).parents('tr:first').addClass('rowToDelete');
            Popup.message({
                btnOk: 'Ja',
                btnCancel: 'Annuleer',
                title: 'Weet u het zeker?',
                message: 'Weet u zeker dat u deze rij wil verwijderen?'
            }, function() {
                $('.rowToDelete').remove();
                var studentCount = $('table#excelDataTable tbody tr').length;
                if(studentCount < 1){
                    $('.showAfterProcess').hide();
                    $("#output").empty();
                }else {
                    setStudentCountOnButton($('table#excelDataTable tbody tr').length);
                }
            });
        })
        .on('mouseleave','table#excelDataTable tr',function(e){
            $('.deleteicon').remove();
        });

        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://s3.amazonaws.com/dynatable-docs-assets/js/jquery.dynatable.js';
        document.getElementsByTagName('head')[0].appendChild(script);

        function setStudentCountOnButton(nr){
            $('#dynatable-record-count-excelDataTable').remove();
            $('#exportJsonData').text(nr+' studenten importeren?');
        }

        var dbFields = [
            {'column' :  'external_id','name' : 'stamnummer'},
            {'column' : 'name_first','name' : 'voornaam'},
            {'column' : 'name_suffix','name' : 'tussenvoegsel'},
            {'column' : 'name','name':'achternaam'},
            {'column' : 'username', 'name' : 'email'},
        ];

        var createSelectbox = function(value, index){
            var options = [];
            dbFields.forEach(function(obj,index){
                options.push(
                    '<option value="'+obj['column']+'">'+obj['name']+'</option>'
                );
            });
            options.push('<option value="'+value+'" selected="selected">negeren</option>');
            return '<select class="selectbox-update" data-nr="'+index+'">'+options.join('')+'</option>';
        }

        var customCellWriter = function(column, record) {
            var html = column.attributeWriter(record),
                td = '<td';
            if (column.hidden || column.textAlign) {
                td += ' style="';
                if (column.hidden) {
                    td += 'display: none;';
                }
                if (column.textAlign) {
                    td += 'text-align: ' + column.textAlign + ';';
                }
                td += '"';
            }
            return td + '><div>' + html + '<\/td>';
        };
        var makeElementEditable = function(element) {
            $('div', element).attr('contenteditable', true);
            $(element).focusout(function() {
                $('div', element).attr('contenteditable', false);
            });
            $(element).keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    $('div', element).attr('contenteditable', false);
                    $(document).focus();
                }
            });
            $('div', element).on('paste', function(e) {
                e.preventDefault();
            });
        };

        var uploadData = function(){
            Loading.show();
            var $rows = $('table#excelDataTable').find('tr:not(:hidden)');
            var headers = [];
            var data = [];
            $($rows.shift()).find('th:not(:empty):not([data-attr-ignore])').each(function() {
                headers.push($(this).text().toLowerCase());
            });
            $rows.each(function() {
                var $td = $(this).find('td:not([data-attr-ignore])');
                var h = {};
                headers.forEach(function(header, i) {
                    h[header] = $td.eq(i).text().replace(/\n/g,'');
                });
                data.push(h);
            });

            $.post('/school_classes/doImport/<?= $location_id?>/' + $('#class_id').val(),
                {data:data},
                function(response) {
                    Loading.hide();
                    response = JSON.parse(response);
                    if(response['status'] == true) {
                        jQuery('#output').html('');
                        jQuery('.showAfterProcess').hide();
                        Notify.notify('De studenten zijn succesvol geimporteerd', 'success');
                        Navigation.back();
                    }else {
                        debugger;
                        var missingHeaders = [];
                        var dataMissingHeaders = [];
                        var hasDuplicates = false;
                        // vul de cellen waarvan ik een foutmelding kan vinden met een rode kleur.
                        Object.keys(response.data).forEach(key => {

                            let d, row_nr, header;
                            [d, row_nr, header] = key.split('.');
                            var column_nr = headers.indexOf(header)
                            var placeholder = parseInt(row_nr) + 1;
                            var row_selector = 'tr:not(:hidden):eq(' + placeholder + ')';
                            if (column_nr > -1) {
                                var columns_selector = 'td:eq(' + (parseInt(column_nr)) + ')';
                                $('table#excelDataTable').find(row_selector).find(columns_selector).addClass('error')
                                if (!dataMissingHeaders.includes(header)) {
                                    dataMissingHeaders.push(header);
                                }

                            } else if (header === 'duplicate') {
                                hasDuplicates = true;
                                $('table#excelDataTable').find(row_selector).addClass('duplicate')

                            } else {
                                if (!missingHeaders.includes(header)) {
                                    missingHeaders.push(header);
                                }
                            }

                        });

                        $('#duplicates-data-errors, #missing-data-errors, #column-errors').html('');
                        if (hasDuplicates) {
                            $('#duplicates-data-errors').html('<ul><li>De import bevat duplicaten (conflicten gemarkeerd als blauw)</li></ul>');
                        }

                        if (dataMissingHeaders.length) {
                            let errorMsg = dataMissingHeaders.map(header => {
                                let field = dbFields.find(field => {
                                    return field.column == header
                                })
                                return 'De kolom [' + field.name + '] bevat waarden die reeds in de database voorkomen, (conflicten gemarkeerd in rood).';
                            })
                            $('#missing-data-errors').html('<ul><li>' + errorMsg.join('</li><li>') + '</ul>');
                        }


                        if (missingHeaders.length) {
                            let errorMsg = missingHeaders.map(header => {
                                let field = dbFields.find(field => {
                                    return field.column == header
                                })
                                return 'De kolom ' + field.name + ' is verplicht.';
                            })
                            $('#column-errors').html('<ul><li>' + errorMsg.join('</li><li>') + '</ul>');
                        }
                        //Notify.notify(response.data.join('<br />'), 'error');
                    }
                }
            );
            var jsonString = JSON.stringify(data, null, 2);

            $('textarea#jsonDataDump').val(jsonString);
        }


        var parsePastedData = function(e){
            var cb;
            var clipText = '';
            if (window.clipboardData && window.clipboardData.getData) {
                cb = window.clipboardData;
                clipText = cb.getData('Text');
            } else if (e.clipboardData && e.clipboardData.getData) {
                cb = e.clipboardData;
                clipText = cb.getData('text/plain');
            } else {
                cb = e.originalEvent.clipboardData;
                clipText = cb.getData('text/plain');
            }
            var clipRows = clipText.split('\n');
            for (i = 0; i < clipRows.length; i++) {
                clipRows[i] = clipRows[i].split('\t');
            }
            jsonObj = [];
            for (i = 0; i < clipRows.length ; i++) {
                var item = {};
                for (j = 0; j < clipRows[i].length; j++) {
                    if (clipRows[i][j] != '\r') {
                        if (clipRows[i][j].length !== 0) {
                            item[j] = clipRows[i][j];
                        }
                        else{
                            item[j] = '';
                        }
                    }
                }
                var filled = false;
                Object.keys(item).forEach(function (key) {
                    if(item[key] != ''){
                        filled = true;
                    }// key
                });
                if(filled) {
                    jsonObj.push(item);
                }
            }
            $('textarea#jsonDataDump').val('');
            var tablePlaceHolder = document.getElementById('output');
            tablePlaceHolder.innerHTML = '';
            var table = document.createElement('table');
            table.id = 'excelDataTable';
            table.className = 'table';
            var header = table.createTHead();

            var row = header.insertRow(0);
            var keys = [];
            for (var i = 0; i < jsonObj.length; i++) {
                var obj = jsonObj[i];
                for (var j in obj) {
                    if ($.inArray(j, keys) == -1) {
                        keys.push(j);
                    }
                }
            }
            keys.forEach(function(value, index) {
                var headerCell = document.createElement('th');

                headerCell.innerHTML = '<div>' + value + '<\/div>';
                $(headerCell).click(function() {
                    makeElementEditable(this);
                });
                $(headerCell).keyup(function(e) {
                    var ignoredClass = 'ignored';
                    var ignoredAttr = 'data-attr-ignore';
                    var columnCells = $('td, th', table).filter(':nth-child(' + ($(this).index() + 1) + ')');
                    $(this).removeAttr(ignoredAttr);
                    $(columnCells).each(function() {
                        $(this).removeClass(ignoredClass);
                        $(this).removeAttr(ignoredAttr);
                    });
                    if ($(this).is(':empty') || $(this).text().trim() === '') {
                        $(this).attr(ignoredAttr, '');
                        $(columnCells).each(function() {
                            $(this).addClass(ignoredClass);
                            $(this).attr(ignoredAttr, '');
                        });
                    }
                });
                var cell = row.insertCell(index);
                cell.parentNode.insertBefore(headerCell, cell);
                cell.parentNode.removeChild(cell);
            });
            tablePlaceHolder.appendChild(table);
            var excelDynaTable = $('table#excelDataTable').dynatable({
                features: {
                    paginate: false,
                    search: false,
                    recordCount: true,
                    sort: false
                },

                dataset: {
                    records: jsonObj,

                },
                writers: {
                    _cellWriter: customCellWriter
                },

            });
            var processComplete = function(){
                var _table = document.createElement('table');//$('<table></table>');
                _table.className ='table';
                var _header = _table.createTHead();
                var _row = _header.insertRow(0);
                $('#output').prepend(_table);
                var l = $("table#excelDataTable tbody:first tr:first td").length;
                for(i=0;i<l;i++){
                    var td = $("table#excelDataTable th:eq("+i+")");
                    var nr = $(td).text();
                    var _headerCell = document.createElement('th');
                    var selectBox = createSelectbox(nr, i)
                    _headerCell.innerHTML = selectBox;
                    var _cell = _row.insertCell(i);
                    _cell.parentNode.insertBefore(_headerCell, _cell);
                    _cell.parentNode.removeChild(_cell);
                    var width = $(td).width();
                    $(_headerCell).width(width);
                };
                $(".showAfterProcess").show();
                setStudentCountOnButton(jsonObj.length);

            };
            excelDynaTable.on('dynatable:afterProcess', processComplete());
        }
    }


    $(document).ready(function(){
        jQuery.fn.pop = [].pop;
        jQuery.fn.shift = [].shift;
    })

</script>
