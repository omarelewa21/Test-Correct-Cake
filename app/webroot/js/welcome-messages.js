var supportsES6 = function() {
    return true;
    // try {
    //     new Function("(a = 0) => a");
    //     return true;
    // }
    // catch (err) {
    //     var notice = document.createElement("div");
    //     notice.innerHTML = `
    //     <div class="notification warning" style="background-color: #FFEDEB; border-color: #EBCCC8;">
    //         <div class="body">
    //             <p style="color: red">U gebruikt een verouderde browser. Deze browser wordt niet meer ondersteund vanaf 2 april 2021. Kies een andere browser of doe een upgrade.</p>
    //         </div>
    //     </div>`;
    //
    //     var notes = document.getElementsByClassName("notes")[0];
    //     if(typeof notes == 'undefined'){
    //         var notes = document.getElementById("container");
    //     }
    //     notes.prepend(notice);
    //     return false;
    // }
}();
