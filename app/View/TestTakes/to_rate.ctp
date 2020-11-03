<div id="buttons">
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>

    <div class="dropblock" for="filters">
        <?=$this->Form->create('TestTake')?>
            <?
            $retakeOptions = array(
                -1 => 'Alle',
                0 => 'Standaard',
                1 => 'Inhaaltoetsen'
            );
            $archivedOptions = array(
                0 => 'Niet tonen',
                1 => 'Tonen',
            );
            ?>
            <table id="testTakeFilters" class="mb5">
                <tr>
                    <th>Periode</th>
                    <td>
                        <?=$this->Form->input('period_id', array('options' => $periods, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <?=$this->Form->input('retake', array('options' => $retakeOptions, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Gepland van</th>
                    <td>
                        <?=$this->Form->input('time_start_from', array('label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Gepland tot</th>
                    <td>
                        <?=$this->Form->input('time_start_to', array('label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Gearchiveerd</th>
                    <td>
                        <?=$this->Form->input('archived',  array('options' => $archivedOptions, 'label' => false)) ?>
                    </td>
                </tr>
            </table>
        <?=$this->Form->end();?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <br clear="all" />
    </div>
</div>

<h1>Na te kijken</h1>

<div class="block autoheight">
    <div class="block-head">Afgenomen toetsen</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th>Toets</th>
                <th>Klas</th>
                <th>Surveillanten</th>
                <th width="50">Vragen</th>
                <th width="200">Ingepland door</th>
                <th width="120">Vak</th>
                <th width="90">Afname</th>
                <th width="80">Type</th>
                <th width="60">Weging</th>
                <th width="60">Aanwezig</th>
                <th width="60">Afwezig</th>
                <th width="80">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#testsTable').tablefy({
                'source' : '/test_takes/load_to_rate',
                'filters' : $('#TestTakeToRateForm'),
                'container' : $('#testsContainter')
            });

            $('#TestTakeTimeStartFrom, #TestTakeTimeStartTo').datepicker({
                dateFormat: 'dd-mm-yy'
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
<style>
    .jquery-not-archived .jquery-show-not-archived {
        display:block;
    }
    .jquery-not-archived .jquery-show-when-archived {
        display:none;
    }
    .jquery-archived .jquery-show-not-archived {
        display:none;
    }
    .jquery-archived .jquery-show-when-archived {
        display:block;
    }
    .jquery-archived{
        color:grey;
    }


</style>
