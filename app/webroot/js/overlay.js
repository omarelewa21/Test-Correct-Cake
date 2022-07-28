var Overlay = {
    zIndex: 120,
    mouse: {},

    overCkeditor4: function (selector,editor) {
        var overlay = document.querySelector(selector,editor);
        // overlay.style.border =  '1px solid red';
        overlay.style.zIndex = this.zIndex;
        overlay.style.position = 'absolute';
        setTimeout(function(){
            overlay.style.width = document.querySelector('.cke_inner').clientWidth+'px';
            overlay.style.height = document.querySelector('.cke_inner').clientHeight+'px';
        },1000);
        overlay.addEventListener('mousemove', function (evt) {
            evt.stopPropagation();

            var rect = overlay.getBoundingClientRect();
            // mouse.x = evt.clientX - (rect.left + 300);
            // mouse.y = evt.clientY - rect.top;
            // mouse.hover = mouse.x > 0;
            // if (mouse.y < 23) {
            //     timeHover = Math.floor((mouse.x / (width - 300)) * timeNow);
            // } else {
            //     timeHover = Math.floor(mouse.x / scale + scroll.time);
            // }
        }, false);

        overlay.addEventListener('mousedown', function (evt) {
            evt.stopPropagation();
            evt.preventDefault();

            if (evt.button !== 0 || mouse.click || mouse.down || ! mouse.hover)
                return;

            // mouse.click = true;
        }, false);

        overlay.addEventListener('mouseup', function (evt) {
            evt.stopPropagation();

            // if (evt.button !== 0 || ! mouse.down)
            //     return;

            // mouse.down = false;
            // mouse.up = true;
        }, false);

        overlay.addEventListener('mouseleave', function (evt) {
            // mouse.hover = false;
            // timeHover = 0;
            // if (! mouse.down)
            //     return;
            //
            // mouse.down = false;
            // mouse.up = true;
        }, false);

        overlay.addEventListener('mousewheel', function (evt) {
            evt.stopPropagation();

            // if (! mouse.hover)
            //     return;
            //
            // scroll.time = Math.max(0, Math.min(timeNow - capacity, Math.floor(scroll.time + evt.deltaX / scale)));
            // if (evt.deltaX < 0) {
            //     scroll.auto = false;
            // } else if (Math.abs((scroll.time + capacity) - timeNow) < 16) {
            //     scroll.auto = true;
            // }
        }, false);


    }


};

