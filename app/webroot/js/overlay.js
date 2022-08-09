var Overlay = {
    zIndex: 120,

    overCkeditor4: function (selector,editor) {
        var overlay = document.querySelector(selector);
        // overlay.style.border =  '1px solid red';
        overlay.style.zIndex = this.zIndex;
        overlay.style.position = 'absolute';
        var containerId = editor.container.$.id;
        setTimeout(function(){
            overlay.style.width = document.querySelector('#'+containerId).clientWidth+'px';
            overlay.style.height = document.querySelector('#'+containerId).clientHeight+'px';
        },1000);
        overlay.addEventListener('mousemove', function (evt) {
            evt.stopPropagation();
        }, false);

        overlay.addEventListener('mousedown', function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
        }, false);

        overlay.addEventListener('mouseup', function (evt) {
            evt.stopPropagation();
        }, false);

        overlay.addEventListener('mousewheel', function (evt) {
            evt.stopPropagation();
        }, false);

    }


};

