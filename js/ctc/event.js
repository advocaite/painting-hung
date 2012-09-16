T_demo.util.CustomEvent = function(type, oScope, silent) {
    this.type = type;

    this.scope = oScope || window;

    this.silent = silent;

    this.subscribers = [];

    if (!this.silent) {
    }
};

T_demo.util.CustomEvent.prototype = {
    subscribe: function(fn, obj, bOverride) {

        this.subscribers.push( new T_demo.util.Subscriber(fn, obj, bOverride) );
    },

    unsubscribe: function(fn, obj) {
        var found = false;
        for (var i=0, len=this.subscribers.length; i<len; ++i) {
            var s = this.subscribers[i];
            if (s && s.contains(fn, obj)) {
                this._delete(i);
                found = true;
            }
        }

        return found;
    },

    fire: function() {
        var len=this.subscribers.length;
        if (!len && this.silent) {
            return;
        }

        var args = [];

        for (var i=0; i<arguments.length; ++i) {
            args.push(arguments[i]);
        }

        if (!this.silent) {
        }

        for (i=0; i<len; ++i) {
            var s = this.subscribers[i];
            if (s) {
                if (!this.silent) {
                }
                var scope = (s.override) ? s.obj : this.scope;
                s.fn.call(scope, this.type, args, s.obj);
            }
        }
    },

    unsubscribeAll: function() {
        for (var i=0, len=this.subscribers.length; i<len; ++i) {
            this._delete(len - 1 - i);
        }
    },

    _delete: function(index) {
        var s = this.subscribers[index];
        if (s) {
            delete s.fn;
            delete s.obj;
        }

        this.subscribers.splice(index, 1);
    },

    toString: function() {
         return "CustomEvent: " + "'" + this.type  + "', " + 
             "scope: " + this.scope;

    }
};

T_demo.util.Subscriber = function(fn, obj, bOverride) {
    this.fn = fn;
    this.obj = obj || null;
    this.override = (bOverride);
};

T_demo.util.Subscriber.prototype.contains = function(fn, obj) {
    return (this.fn == fn && this.obj == obj);
};

T_demo.util.Subscriber.prototype.toString = function() {
    return "Subscriber { obj: " + (this.obj || "")  + 
           ", override: " +  (this.override || "no") + " }";
};
if (!T_demo.util.Event) {

    T_demo.util.Event = function() {
        var loadComplete =  false;

        var listeners = [];
        var delayedListeners = [];

        var unloadListeners = [];

        var legacyEvents = [];

        var legacyHandlers = [];

        var retryCount = 0;

        var onAvailStack = [];
        var legacyMap = [];

        var counter = 0;

        return { // PREPROCESS

            POLL_RETRYS: 200,

            POLL_INTERVAL: 50,

            EL: 0,

            TYPE: 1,

            FN: 2,

            WFN: 3,

            SCOPE: 3,

            ADJ_SCOPE: 4,

            isSafari: (/Safari|Konqueror|KHTML/gi).test(navigator.userAgent),

            isIE: (!this.isSafari && !navigator.userAgent.match(/opera/gi) && 
                    navigator.userAgent.match(/msie/gi)),
            addDelayedListener: function(el, sType, fn, oScope, bOverride) {
                delayedListeners[delayedListeners.length] =
                    [el, sType, fn, oScope, bOverride];
                if (loadComplete) {
                    retryCount = this.POLL_RETRYS;
                    this.startTimeout(0);
                    // this._tryPreloadAttach();
                }
            },

            startTimeout: function(interval) {
                var i = (interval || interval === 0) ? interval : this.POLL_INTERVAL;
                var self = this;
                var callback = function() { self._tryPreloadAttach(); };
                this.timeout = setTimeout(callback, i);
            },

            onAvailable: function(p_id, p_fn, p_obj, p_override) {
                onAvailStack.push( { id:       p_id, 
                                     fn:       p_fn, 
                                     obj:      p_obj, 
                                     override: p_override } );

                retryCount = this.POLL_RETRYS;
                this.startTimeout(0);
                // this._tryPreloadAttach();
            },

            addListener: function(el, sType, fn, oScope, bOverride) {

                if (!fn || !fn.call) {
                    return false;
                }

                // The el argument can be an array of elements or element ids.
                if ( this._isValidCollection(el)) {
                    var ok = true;
                    for (var i=0,len=el.length; i<len; ++i) {
                        ok = ( this.on(el[i], 
                                       sType, 
                                       fn, 
                                       oScope, 
                                       bOverride) && ok );
                    }
                    return ok;

                } else if (typeof el == "string") {
                    var oEl = this.getEl(el);
                    if (loadComplete && oEl) {
                        el = oEl;
                    } else {
                        // defer adding the event until onload fires
                        this.addDelayedListener(el, 
                                                sType, 
                                                fn, 
                                                oScope, 
                                                bOverride);

                        return true;
                    }
                }

                if (!el) {
                    return false;
                }

                if ("unload" == sType && oScope !== this) {
                    unloadListeners[unloadListeners.length] =
                            [el, sType, fn, oScope, bOverride];
                    return true;
                }
                var scope = (bOverride) ? oScope : el;

                var wrappedFn = function(e) {
                        return fn.call(scope, T_demo.util.Event.getEvent(e), 
                                oScope);
                    };

                var li = [el, sType, fn, wrappedFn, scope];
                var index = listeners.length;
                listeners[index] = li;

                if (this.useLegacyEvent(el, sType)) {
                    var legacyIndex = this.getLegacyIndex(el, sType);

                    if ( legacyIndex == -1 || 
                                el != legacyEvents[legacyIndex][0] ) {

                        legacyIndex = legacyEvents.length;
                        legacyMap[el.id + sType] = legacyIndex;

                        legacyEvents[legacyIndex] = 
                            [el, sType, el["on" + sType]];
                        legacyHandlers[legacyIndex] = [];

                        el["on" + sType] = 
                            function(e) {
                                T_demo.util.Event.fireLegacyEvent(
                                    T_demo.util.Event.getEvent(e), legacyIndex);
                            };
                    }
                    legacyHandlers[legacyIndex].push(index);

                // DOM2 Event model
                } else if (el.addEventListener) {
                    el.addEventListener(sType, wrappedFn, false);
                // IE
                } else if (el.attachEvent) {
                    el.attachEvent("on" + sType, wrappedFn);
                }

                return true;
                
            },

            fireLegacyEvent: function(e, legacyIndex) {
                var ok = true;

                var le = legacyHandlers[legacyIndex];
                for (var i=0,len=le.length; i<len; ++i) {
                    var index = le[i];
                    if (index) {
                        var li = listeners[index];
                        if ( li && li[this.WFN] ) {
                            var scope = li[this.ADJ_SCOPE];
                            var ret = li[this.WFN].call(scope, e);
                            ok = (ok && ret);
                        } else {
                            delete le[i];
                        }
                    }
                }

                return ok;
            },

            getLegacyIndex: function(el, sType) {

                var key = this.generateId(el) + sType;
                if (typeof legacyMap[key] == "undefined") { 
                    return -1;
                } else {
                    return legacyMap[key];
                }

            },

            useLegacyEvent: function(el, sType) {

                if (!el.addEventListener && !el.attachEvent) {
                    return true;
                } else if (this.isSafari) {
                    if ("click" == sType || "dblclick" == sType) {
                        return true;
                    }
                }

                return false;
            },
                    
            removeListener: function(el, sType, fn, index) {

                if (!fn || !fn.call) {
                    return false;
                }

                // The el argument can be a string
                if (typeof el == "string") {
                    el = this.getEl(el);
                // The el argument can be an array of elements or element ids.
                } else if ( this._isValidCollection(el)) {
                    var ok = true;
                    for (var i=0,len=el.length; i<len; ++i) {
                        ok = ( this.removeListener(el[i], sType, fn) && ok );
                    }
                    return ok;
                }

                if ("unload" == sType) {

                    for (i=0, len=unloadListeners.length; i<len; i++) {
                        var li = unloadListeners[i];
                        if (li && 
                            li[0] == el && 
                            li[1] == sType && 
                            li[2] == fn) {
                                unloadListeners.splice(i, 1);
                                return true;
                        }
                    }

                    return false;
                }

                var cacheItem = null;
  
                if ("undefined" == typeof index) {
                    index = this._getCacheIndex(el, sType, fn);
                }

                if (index >= 0) {
                    cacheItem = listeners[index];
                }

                if (!el || !cacheItem) {
                    return false;
                }

                if (el.removeEventListener) {
                    el.removeEventListener(sType, cacheItem[this.WFN], false);
                } else if (el.detachEvent) {
                    el.detachEvent("on" + sType, cacheItem[this.WFN]);
                }

                // removed the wrapped handler
                delete listeners[index][this.WFN];
                delete listeners[index][this.FN];
                listeners.splice(index, 1);

                return true;

            },

            getTarget: function(ev, resolveTextNode) {
                var t = ev.target || ev.srcElement;
                return this.resolveTextNode(t);
            },

            resolveTextNode: function(node) {
                if (node && node.nodeName && 
                        "#TEXT" == node.nodeName.toUpperCase()) {
                    return node.parentNode;
                } else {
                    return node;
                }
            },

            getPageX: function(ev) {
                var x = ev.pageX;
                if (!x && 0 !== x) {
                    x = ev.clientX || 0;

                    if ( this.isIE ) {
                        x += this._getScrollLeft();
                    }
                }

                return x;
            },

            getPageY: function(ev) {
                var y = ev.pageY;
                if (!y && 0 !== y) {
                    y = ev.clientY || 0;

                    if ( this.isIE ) {
                        y += this._getScrollTop();
                    }
                }

                return y;
            },

            getXY: function(ev) {
                return [this.getPageX(ev), this.getPageY(ev)];
            },

            getRelatedTarget: function(ev) {
                var t = ev.relatedTarget;
                if (!t) {
                    if (ev.type == "mouseout") {
                        t = ev.toElement;
                    } else if (ev.type == "mouseover") {
                        t = ev.fromElement;
                    }
                }

                return this.resolveTextNode(t);
            },

            getTime: function(ev) {
                if (!ev.time) {
                    var t = new Date().getTime();
                    try {
                        ev.time = t;
                    } catch(e) { 
                        // can't set the time property  
                        return t;
                    }
                }

                return ev.time;
            },

            stopEvent: function(ev) {
                this.stopPropagation(ev);
                this.preventDefault(ev);
            },

            stopPropagation: function(ev) {
                if (ev.stopPropagation) {
                    ev.stopPropagation();
                } else {
                    ev.cancelBubble = true;
                }
            },

            preventDefault: function(ev) {
                if (ev.preventDefault) {
                    ev.preventDefault();
                } else {
                    ev.returnValue = false;
                }
            },
             
            getEvent: function(e) {
                var ev = e || window.event;

                if (!ev) {
                    var c = this.getEvent.caller;
                    while (c) {
                        ev = c.arguments[0];
                        if (ev && Event == ev.constructor) {
                            break;
                        }
                        c = c.caller;
                    }
                }

                return ev;
            },

            getCharCode: function(ev) {
                return ev.charCode || ((ev.type == "keypress") ? ev.keyCode : 0);
            },

            _getCacheIndex: function(el, sType, fn) {
                for (var i=0,len=listeners.length; i<len; ++i) {
                    var li = listeners[i];
                    if ( li                 && 
                         li[this.FN] == fn  && 
                         li[this.EL] == el  && 
                         li[this.TYPE] == sType ) {
                        return i;
                    }
                }

                return -1;
            },

            generateId: function(el) {
                var id = el.id;

                if (!id) {
                    id = "yuievtautoid-" + counter;
                    ++counter;
                    el.id = id;
                }

                return id;
            },

            _isValidCollection: function(o) {

                return ( o                    && // o is something
                         o.length             && // o is indexed
                         typeof o != "string" && // o is not a string
                         !o.tagName           && // o is not an HTML element
                         !o.alert             && // o is not a window
                         typeof o[0] != "undefined" );

            },

            elCache: {},

            getEl: function(id) {
                return document.getElementById(id);
            },

            clearCache: function() { },

            _load: function(e) {
                loadComplete = true;
            },

            _tryPreloadAttach: function() {

                if (this.locked) {
                    return false;
                }

                this.locked = true;

                var tryAgain = !loadComplete;
                if (!tryAgain) {
                    tryAgain = (retryCount > 0);
                }

                // Delayed listeners
                var stillDelayed = [];

                for (var i=0,len=delayedListeners.length; i<len; ++i) {
                    var d = delayedListeners[i];
                    if (d) {

                        // el will be null if document.getElementById did not
                        // work
                        var el = this.getEl(d[this.EL]);

                        if (el) {
                            this.on(el, d[this.TYPE], d[this.FN], 
                                    d[this.SCOPE], d[this.ADJ_SCOPE]);
                            delete delayedListeners[i];
                        } else {
                            stillDelayed.push(d);
                        }
                    }
                }

                delayedListeners = stillDelayed;

                // onAvailable
                var notAvail = [];
                for (i=0,len=onAvailStack.length; i<len ; ++i) {
                    var item = onAvailStack[i];
                    if (item) {
                        el = this.getEl(item.id);

                        if (el) {
                            var scope = (item.override) ? item.obj : el;
                            item.fn.call(scope, item.obj);
                            delete onAvailStack[i];
                        } else {
                            notAvail.push(item);
                        }
                    }
                }

                retryCount = (stillDelayed.length === 0 && 
                                    notAvail.length === 0) ? 0 : retryCount - 1;

                if (tryAgain) {
                    this.startTimeout();
                }

                this.locked = false;

                return true;

            },
            purgeElement: function(el, recurse, sType) {
                var elListeners = this.getListeners(el, sType);
                if (elListeners) {
                    for (var i=0,len=elListeners.length; i<len ; ++i) {
                        var l = elListeners[i];
                        // can't use the index on the changing collection
                        //this.removeListener(el, l.type, l.fn, l.index);
                        this.removeListener(el, l.type, l.fn);
                    }
                }

                if (recurse && el && el.childNodes) {
                    for (i=0,len=el.childNodes.length; i<len ; ++i) {
                        this.purgeElement(el.childNodes[i], recurse, sType);
                    }
                }
            },

            getListeners: function(el, sType) {
                var elListeners = [];
                if (listeners && listeners.length > 0) {
                    for (var i=0,len=listeners.length; i<len ; ++i) {
                        var l = listeners[i];
                        if ( l  && l[this.EL] === el && 
                                (!sType || sType === l[this.TYPE]) ) {
                            elListeners.push({
                                type:   l[this.TYPE],
                                fn:     l[this.FN],
                                obj:    l[this.SCOPE],
                                adjust: l[this.ADJ_SCOPE],
                                index:  i
                            });
                        }
                    }
                }

                return (elListeners.length) ? elListeners : null;
            },

            _unload: function(e, me) {
                for (var i=0,len=unloadListeners.length; i<len; ++i) {
                    var l = unloadListeners[i];
                    if (l) {
                        var scope = (l[this.ADJ_SCOPE]) ? l[this.SCOPE]: window;
                        l[this.FN].call(scope, this.getEvent(e), l[this.SCOPE] );
                    }
                }

                if (listeners && listeners.length > 0) {
                    //for (i=0,len=listeners.length; i<len ; ++i) {
                    var j = listeners.length;
                    while (j) {
                        var index = j-1;
                        l = listeners[index];
                        if (l) {
                            this.removeListener(l[this.EL], l[this.TYPE], 
                                    l[this.FN], index);
                        } 

                        j = j - 1;
                    }

                    this.clearCache();
                }


                for (i=0,len=legacyEvents.length; i<len; ++i) {
                    // dereference the element
                    delete legacyEvents[i][0];
                    // delete the array item
                    delete legacyEvents[i];
                }

            },

            _getScrollLeft: function() {
                return this._getScroll()[1];
            },

            _getScrollTop: function() {
                return this._getScroll()[0];
            },

            _getScroll: function() {
                var dd = document.documentElement, db = document.body;
                if (dd && dd.scrollTop) {
                    return [dd.scrollTop, dd.scrollLeft];
                } else if (db) {
                    return [db.scrollTop, db.scrollLeft];
                } else {
                    return [0, 0];
                }
            }
        };
    } ();

    T_demo.util.Event.on = T_demo.util.Event.addListener;

    if (document && document.body) {
        T_demo.util.Event._load();
    } else {
        T_demo.util.Event.on(window, "load", T_demo.util.Event._load, 
                T_demo.util.Event, true);
    }

    T_demo.util.Event.on(window, "unload", T_demo.util.Event._unload, 
                T_demo.util.Event, true);

    T_demo.util.Event._tryPreloadAttach();

}

