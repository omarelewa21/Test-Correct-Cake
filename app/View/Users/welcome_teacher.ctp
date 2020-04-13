<div>
    <div style="margin:auto;margin-top:100px;width:65%;text-align:left;">
        <h2 style="text-align:center;">
            i.v.m. de huidige ontwikkelingen rondom het coronavirus stellen wij ons platform kosteloos ter
            beschikking voor alle docenten en alle lesgroepen binnen uw school tot het einde van dit schooljaar
        </h2>
        <div>
            <p>
                Update 17 maart:<br />
                Test-Correct is geschikt om studenten thuis een toets te laten maken. Met de smart surveillance ziet u
                waar de student zich in de toets bevindt. Het advies is om geen summatieve toetsing te doen, tenzij
                iemand in de nabijheid van de student kan toezien met wie u goede afspraken heeft gemaakt
                (bijvoorbeeld de ouders).
            </p>
            <p>
                <strong>
                    Wij maken Test-Correct kosteloos beschikbaar voor al uw overige lesgroepen tot het einde van dit
                    schooljaar. Ook kunt u kosteloos gebruik maken van onze dienst om toetsmateriaal in uw itembank
                    klaar te zetten, mits u van plan was deze op papier af te nemen.
                </strong>
            </p>
            <p>
                Extra lesgroepen kunt u <a href="mailto:support@test-correct.nl">HIER</a> per e-mail naar ons toezenden.<br/>
                Toetsmateriaal kunt u <a href="https://www.test-correct.nl/toets-uploaden/" target="_blank">HIER</a> uploaden.<br />
                Het openstellen van ons platform geldt voor alle docenten binnen uw organisatie.
                </p>
        </div>
    </div>
</div>

<h1 style="text-align:center; margin-top: 50px;">Meteen naar:</h1>
<div style="text-align:center;">
    <div style="display:inline-block;">
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