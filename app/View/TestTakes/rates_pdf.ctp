
<style>
    * {
        font-family: Helvetica;
    }

    table {
        page-break-inside: avoid;
    }
</style>

<div style="margin:0 auto; width:80%;page-break-inside: avoid;">
    <center>
        <img src="http://testportal.test-correct.nl/img/logo_full.jpg" width="200" />
    </center>
    <h1>Cijferlijst <?=$take['test']['name']?></h1>

    <?
    if(count($take['school_classes']) == 1) {
        echo 'Klas: ' . $take['school_classes'][0]['name'];
    }else{
        echo 'Klassen: ';

        foreach($take['school_classes'] as $class) {
            echo $class['name'] . '&nbsp;&nbsp;';
        }
    }
    ?>

    <table width="100%" cellpadding="10" cellspacing="0" border="1" >
        <tr>
            <th align="left">Student</th>
            <th align="left">Cijfer</th>
        </tr>
        <?
        foreach($participants as $participant) {
            ?>
            <tr>
                <td>
                    <?=$participant['user']['name_first']?>
                    <?=$participant['user']['name_suffix']?>
                    <?=$participant['user']['name']?>
                </td>
                <td>
                    <?=empty($participant['rating']) ? '-' : $participant['rating'] ?>
                </td>
            </tr>
            <?
        }
        ?>
    </table>
</div>