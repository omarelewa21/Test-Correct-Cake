var Overlay = {
    zIndex: 120,

    overCkeditor4: function (selector,editor,repeat = false) {
        var overlay = document.querySelector(selector);
        if(!overlay&&!repeat){
            return setTimeout(Overlay.overCkeditor4(selector,editor,true),1000);
        }
        if(!overlay){
            return;
        }
        // overlay.style.border =  '1px solid red';
        overlay.style.zIndex = this.zIndex;
        overlay.style.position = 'absolute';
        var containerId = editor.container.$.id;
        setTimeout(function(){
            if(!document.querySelector('#'+containerId)&&!repeat){
                return setTimeout(Overlay.overCkeditor4(selector,editor,true),1000);
            }
            if(!document.querySelector('#'+containerId)){
                return ;
            }
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

