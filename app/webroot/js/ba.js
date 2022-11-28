!function(t) {
    var e = {};
    function n(o) {
        if (e[o])
            return e[o].exports;
        var s = e[o] = {
            i: o,
            l: !1,
            exports: {}
        };
        return t[o].call(s.exports, s, s.exports, n), s.l = !0, s.exports
    }
    n.m = t,
        n.c = e,
        n.d = function(t, e, o) {
            n.o(t, e) || Object.defineProperty(t, e, {
                enumerable: !0,
                get: o
            })
        },
        n.r = function(t) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
                value: "Module"
            }),
                Object.defineProperty(t, "__esModule", {
                    value: !0
                })
        },
        n.t = function(t, e) {
            if (1 & e && (t = n(t)), 8 & e)
                return t;
            if (4 & e && "object" == typeof t && t && t.__esModule)
                return t;
            var o = Object.create(null);
            if (n.r(o), Object.defineProperty(o, "default", {
                enumerable: !0,
                value: t
            }), 2 & e && "string" != typeof t)
                for (var s in t)
                    n.d(o, s, function(e) {
                        return t[e]
                    }.bind(null, s));
            return o
        },
        n.n = function(t) {
            var e = t && t.__esModule ? function() {
                return t.default
            } : function() {
                return t
            };
            return n.d(e, "a", e), e
        },
        n.o = function(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        },
        n.p = "https://www.browsealoud.com/modules/3.1.0/",
        n(n.s = 0)
}([function(t, e) {
    !function() {
        function t(t, e) {
            var n = document.createElement("script");
            n.type = "text/javascript",
                n.src = "https://www.browsealoud.com/modules/" + t + "/browsealoud.js",
            null != e && void 0 !== e.integrity && (n.setAttribute("crossorigin", "anonymous"), n.setAttribute("integrity", e.integrity));
            var o = setInterval((function() {
                document.body && (document.body.appendChild(n), clearInterval(o))
            }), 100)
        }
        function e(e) {
            if ("3.1.0" > e)
                t(e);
            else {
                var n = new XMLHttpRequest;
                n.onreadystatechange = function() {
                    if (4 == this.readyState && 200 == this.status) {
                        var o = n.responseText,
                            s = JSON.parse(o);
                        t(e, s)
                    }
                },
                    n.open("GET", "https://www.browsealoud.com/modules/" + e + "/sri.json", !0),
                    n.send()
            }
        }
        function n(t) {
            var e = a ? "https://plus.browsealoud.com/modules/" + t + "/sri.json" : "https://www.browsealoud.com/plus/sri-directives.json",
                n = new XMLHttpRequest;
            n.onreadystatechange = function() {
                if (4 == this.readyState && 200 == this.status) {
                    var e = n.responseText,
                        o = JSON.parse(e);
                    !function(t, e) {
                        var n = document.createElement("script");
                        n.type = "text/javascript",
                            a ? (n.src = "https://plus.browsealoud.com/modules/" + t + "/browsealoud.min.js", n.setAttribute("crossorigin", "anonymous"), n.setAttribute("integrity", e.integrity)) : (n.src = "https://www.browsealoud.com/plus/scripts/" + t + "/ba.js", n.setAttribute("crossorigin", "anonymous"), n.setAttribute("integrity", e["@" + t].integrity));
                        var o = setInterval((function() {
                            document.body && (document.body.appendChild(n), clearInterval(o))
                        }), 100)
                    }(t, o)
                }
            },
                n.open("GET", e, !0),
                n.send()
        }
        function o() {
            var t = "undefined" == typeof _ba_domain ? null : _ba_domain;
            if (null != t && "" != t)
                for (l.push(t), l.push(0 === t.indexOf("www") ? t.substring(location.hostname.indexOf(".") + 1) : "www." + t), s = (o = t.split(".")).length - 1; 1 < s;) {
                    var e = o.slice(-1 * s).join(".");
                    l.push(e),
                        s--
                }
            else {
                l.push(location.host);
                for (var n = -1 < document.URL.indexOf("https") ? 8 : 7, o = document.URL.substring(n, document.URL.indexOf("/", n)).split("."), s = o.length - 1; 1 < s;) {
                    e = o.slice(-1 * s).join(".");
                    l.push(e),
                        s--
                }
                l.push(0 === location.hostname.indexOf("www") ? location.hostname.substring(location.hostname.indexOf(".") + 1) : "www." + location.hostname)
            }
            r()
        }
        function s(t) {
            null != t && (void 0 !== t.version && "" != t.version && "latest" != t.version ? i || u ? n(d.ie) : "3.0.0" > t.version ? n(t.version) : e(t.version) : i || u ? n(d.ie) : e(d.latest))
        }
        function r() {
            var t = "https://plus.browsealoud.com/js/urlinfo/" + l[c] + ".js?"+new Date().getTime(),
                e = new XMLHttpRequest;
            e.onreadystatechange = function() {
                if (4 == this.readyState && 200 == this.status) {
                    var t = e.responseText.replace(/var BrowseAloudUrl\s?=/, "");
                    t = t.replace(";", ""),
                        s(JSON.parse(t))
                }
                4 == this.readyState && 200 != this.status && (console.log(this.readyState, this.status), c < l.length && (c++, r()))
            },
                e.open("GET", t, !0),
                e.send()
        }
        window.toggleBar = function() {
            BrowseAloud.panel.toggleBar(!0)
        };
        var i = !1,
            a = !1;
        if ("pdf.browsealoud.com" == window.location.host || "pdfqa.browsealoud.com" == window.location.host)
            a = !0;
        if (-1 !== navigator.userAgent.indexOf("MSIE") || -1 < navigator.appVersion.indexOf("Trident/"))
            i = !0;
        var u = !1;
        if (/Edge/.test(navigator.userAgent))
            u = !0;
        var l = [],
            c = 0,
            d = {
                ie: "2.6.1",
                latest: "3.1.0"
            };
        !function() {
            var t = new XMLHttpRequest;
            t.onreadystatechange = function() {
                if (4 == this.readyState && 200 == this.status) {
                    var e = t.responseText;
                    d = JSON.parse(e),
                        o()
                }
                4 == this.readyState && 200 != this.status && o()
            },
                t.open("GET", "https://www.browsealoud.com/version.json", !0),
                t.send()
        }()
    }()
}]);

