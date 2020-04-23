<?
foreach($sections as $section) {
    ?>
    <tr>
        <td><?=$section['name']?></td>
        <td>
            <?
            foreach($section['school_locations'] as $location) {
                echo $location['name'].'<br />';
            }
            ?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="section_<?=$section['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/sections/view/<?=$section['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="section_<?=$section['id']?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/sections/edit/<?=$section['id']?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="Section.delete(<?=$section['id']?>);">
                    <span class="fa fa-remove mr5"></span>
                    Verwijderen
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>