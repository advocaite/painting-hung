window.T_demo = window.T_demo || {};

T_demo.namespace = function(ns) {

    if (!ns || !ns.length) {
        return null;
    }

    var levels = ns.split(".");
    var nsobj = T_demo;

    for (var i=(levels[0] == "T_demo") ? 1 : 0; i<levels.length; ++i) {
        nsobj[levels[i]] = nsobj[levels[i]] || {};
        nsobj = nsobj[levels[i]];
    }

    return nsobj;
};

T_demo.log = function(sMsg, sCategory, sSource) {
    var l = T_demo.widget.Logger;
    if(l && l.log) {
        return l.log(sMsg, sCategory, sSource);
    } else {
        return false;
    }
};

T_demo.extend = function(subclass, superclass) {
    var f = function() {};
    f.prototype = superclass.prototype;
    subclass.prototype = new f();
    subclass.prototype.constructor = subclass;
    subclass.superclass = superclass.prototype;
    if (superclass.prototype.constructor == Object.prototype.constructor) {
        superclass.prototype.constructor = superclass;
    }
};

T_demo.namespace("util");
T_demo.namespace("widget");
T_demo.namespace("example");

