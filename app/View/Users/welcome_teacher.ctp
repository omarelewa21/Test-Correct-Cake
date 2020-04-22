<div>
    <div style="margin:auto;margin-top:100px;width:65%;text-align:left;">
        <h2 style="text-align:center;">
            i.v.m. de huidige ontwikkelingen rondom het coronavirus stellen wij ons platform kosteloos ter
            beschikking voor alle docenten en alle lesgroepen binnen uw school tot het einde van dit schooljaar
        </h2>
        <div>
            <table width="100%">
                <tr>
                    <td style="text-align:center;width:33%">
                        <iframe width="220" height="177" src="https://www.youtube.com/embed/Y_yi0H4vGlA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <Br />
                        Klassen toevoegen
                    </td>
                    <td style="text-align:center;width:33%">
                        <iframe width="220" height="177" src="https://www.youtube.com/embed/YIHZUMHSQeo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <Br />
                        Wachtwoord van student aanpassen
                    </td>
                    <td style="text-align:center;width:33%">
                        <iframe width="220" height="177" src="https://www.youtube.com/embed/-AQyzBffjKs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <Br />
                        Toets uploaden
                    </td>
                </tr>
            </table>
            <p style="text-align:center;">
                <strong>
                    Let op: tot 15 mei kunt u gratis al uw toetsen in word/pdf bij ons aanleveren. Deze zetten wij kosteloos voor u klaar in uw itembank.<sup>*</sup>
                </strong>
            </p>
            <p style="text-align:center;">
                <small>
                    <sup>*</sup>
                    Vanaf 15 mei is het aanleveren van uw toetsen in word/pdf een betaalde dienst. *: verwerking van aangeleverde toetsen gebeurt op first come first serve basis. Wij van Test-Correct spannen ons in om de beste service te verlenen. Wij behouden te allen tijde het recht de verwerking van toetsen die worden aangeleverd te weigeren.
                </small>
            </p>
        </div>
    </div>
</div>

<h1 style="text-align:center; margin-top: 50px;">Meteen naar:</h1>
<div style="text-align:center;">
    <div style="display:inline-block;">
        <?php
            if(AuthComponent::user('isToetsenbakker') == true){
        ?>
            <span class="blue">
            <div class="tile btn pull-left defaultMenuButton plus" onclick="Navigation.load('file_management/testuploads');">
            Te verwerken toetsen
            </div></span>
        <?php }else{ ?>

            <span class="blue">
            <div class="tile btn pull-left defaultMenuButton plus" onclick="Popup.load('/tests/add', 1000);">
            Toets construeren
            </div></span>

            <span class="blue"><div class="tile tile-surveilleren" onclick="Navigation.load('/test_takes/surveillance');">
            Surveilleren
            </div></span>

            <span class="blue"><div class="tile tile-nakijken" onclick="Navigation.load('/test_takes/to_rate');">
            Nakijken
            </div></span>
        <?php } ?>
    </div>

</div>

<script>
    HelpHero.identify("<?=AuthComponent::user('id')?>", {
        name: "<?=AuthComponent::user('name')?>",
        name_first: "<?=AuthComponent::user('name_first')?>",
        name_suffic: "<?=AuthComponent::user('name_suffix')?>"
    });

    if(jQuery("#supportLinkUserMenu").length != 1){
        jQuery("#user_menu").append('<a id="supportLinkUserMenu" href="https://support.test-correct.nl" target="_blank" class="btn white mt5" > Supportpagina</a>');
    <?
        if(AuthComponent::user('isToetsenbakker') == true){
                ?>
            jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" > Te verwerken toetsen</a>');
        <?php
        }else {
                ?>
            jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" >Uploaden toets</a>');
        <?php
        }
            ?>
    };
</script>