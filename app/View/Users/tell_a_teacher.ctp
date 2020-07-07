<div class="popup-head"><i class='fa fa-bullhorn' style='color:#FF3333;font-weight:bold;'></i> Nodig een collega uit!</div>
<div class="popup-content">
    Nodig een collega uit en hij/zij krijgt direct een welkomstmail met logingegevens. Test-Correct in gebruik door meerdere docenten op uw school levert de volgende voordelen:
    <ul>
        <li>Samen overleggen over de voortgang van jouw studenten</li>
        <li>Samen betekent sneller leren</li>
        <li>Gebruik van elkaars toetsen en toetsvragen</li>
        <li>Uw studenten krijgen een beter inzicht in het kennen en kunnen</li>
    </ul>
    Vergeet niet je collega te informeren dat jij hem/haar hebt uitgenodigd.
    <?=$this->Form->create('User') ?>
    <table class="table">
        <thead>
        <tr>
            <th width="130">
                Voornaam
            </th>
            <th>
                Tussenvoegsel
            </th>
            <th>
                Achternaam
            </th>
            <th>
                E-mailadres
            </th>
        </tr>
        </thead>
        <tbody id="tellATeacherTableBody">
        <tr>
            <td>
                <input name="data[User][name_first][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
            </td>
            <td>
                <input name="data[User][name_suffix][]" style="width: 100%" verify="" type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
            </td>
            <td>
                <input name="data[User][name][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

            </td>
            <td>
                <input name="data[User][email][]" style="width: 100%" verify="" class='verify-email' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

            </td>
        </tr>
        <tr>
            <td>
                <input name="data[User][name_first][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
            </td>
            <td>
                <input name="data[User][name_suffix][]" style="width: 100%" verify="" type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
            </td>
            <td>
                <input name="data[User][name][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

            </td>
            <td>
                <input name="data[User][email][]" style="width: 100%" verify="" class='verify-email' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

            </td>
        </tr>
        </tbody>

    </table>
    <?=$this->Form->end();?>
</div>
<table style="display:none" id="rowTemplate">
    <tr>
        <td>
            <input name="data[User][name_first][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
        </td>
        <td>
            <input name="data[User][name_suffix][]" style="width: 100%" verify="" type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">
        </td>
        <td>
            <input name="data[User][name][]" style="width: 100%" verify="" class='verify-notempty' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

        </td>
        <td>
            <input name="data[User][email][]" style="width: 100%" verify="" class='verify-email' type="text" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off">

        </td>
    </tr>

</table>


<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Uitnodigen
    </a>
</div>

<script type="text/javascript">


            $('#tellATeacherTableBody').on('keydown','input',function(){
                var tr = $(this).parents('tr:first');
                if(tr.is(':last-child')){
                    $('#tellATeacherTableBody').append($('#rowTemplate tr:first').clone());
                }
            });
        tellATeacherTableJs = true;

    $('#UserTellATeacherForm').formify(
        {
            confirm : $('#btnAddUser'),
            onbeforesubmit: function(){
              $('#tellATeacherTableBody tr').each(function(){
                 var allEmpty = true;
                 $(this).find(':input').each(function(){
                    if($(this).val() !== ''){
                        allEmpty = false;
                    }
                 });
                 if(allEmpty == false){
                     $(this).find('.verify-email').attr('verify','email');
                     $(this).find('.verify-notempty').attr('verify','notempty');
                 }
              });
            },
            onaftersubmit:function(){
              $('#tellATeacherTableBody .verify-email').attr('verify','');
                $('#tellATeacherTableBody .verify-notempty').attr('verify','');
            },
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Super bedankt!<br />We hebben "+$('#UserTellATeacherForm #UserNameFirst').val()+" uitgenodigd voor Test-Correct", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if(result.error == 'username') {
                    Notify.notify("Er is al een collega met dit e-mailadres bij ons bekend", "error");
                } else if (result.error == 'user_roles'){
                    Notify.notify('U kunt een collega pas uitnodigen nadat er een actuele periode is aangemaakt.','error')
                }
                else{
                    Notify.notify("Collega kon niet worden uitgenodigd", "error");
                }
            }
        }
    );
</script>