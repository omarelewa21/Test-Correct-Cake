<div id="SharedSections">
<div id="buttons">

</div>

<h1>Toetsen</h1>

</div>
<div class="block autoheight">
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="sharedSectionsTable">
            <thead>
            <tr>
              <th></th>
                <th width="250">Sectie</th>
                <th >School locatie</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>


        <script type="text/javascript">
            $('#schoolsTable').tablefy({
                'source' : '/shared_sections/load',
                'filters' : null,
                'container' : $('#sharedSectionsTable')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
