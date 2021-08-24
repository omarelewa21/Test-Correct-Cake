var supportsES6 = function() {
    try {
        new Function("(a = 0) => a");
        return true;
    }
    catch (err) {
        var notice = document.createElement("div");
        notice.innerHTML = `
        <div class="notification warning" style="background-color: #FFEDEB; padding:5px; padding-left:10px; border-color: #EBCCC8;">
            <div class="body">
                <p style="color: red">` + $.i18n('U gebruikt een verouderde browser. Deze browser wordt niet meer ondersteund vanaf 2 april 2021. Kies een andere browser of doe een upgrade') + `.</p>
            </div>
        </div>`;

        var notes = document.getElementsByClassName("notes")[0];
        if(typeof notes == 'undefined'){
            var notes = document.getElementById("container");
        }
        notes.insertBefore(notice, notes.firstChild);
        return false;                   
    }
}();
