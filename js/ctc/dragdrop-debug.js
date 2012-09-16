
T_demo.util.DragDrop = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

T_demo.util.DragDrop.prototype = {

    id: null,
    config: null,

    dragElId: null,

    handleElId: null,

    invalidHandleTypes: null,

    invalidHandleIds: null,

    invalidHandleClasses: null,

    startPageX: 0,
    startPageY: 0,

    groups: null,

    locked: false,

    lock: function() { this.locked = true; },

    unlock: function() { this.locked = false; },

    isTarget: true,

    padding: null,
    _domRef: null,

    __ygDragDrop: true,

    constrainX: false,
    constrainY: false,

    minX: 0,

    maxX: 0,

    minY: 0,

    maxY: 0,

    maintainOffset: false,

    xTicks: null,

    yTicks: null,

    primaryButtonOnly: true,

    available: false,

    b4StartDrag: function(x, y) { },

    startDrag: function(x, y) { /* override this */ },

    b4Drag: function(e) { },

    onDrag: function(e) { /* override this */ },

    onDragEnter: function(e, id) { /* override this */ },

    b4DragOver: function(e) { },

    onDragOver: function(e, id) { /* override this */ },

    b4DragOut: function(e) { },

    onDragOut: function(e, id) { /* override this */ },

    b4DragDrop: function(e) { },

    onDragDrop: function(e, id) { /* override this */ },

    b4EndDrag: function(e) { },

    endDrag: function(e) { /* override this */ },

    b4MouseDown: function(e) {  },

    onMouseDown: function(e) { /* override this */ },

    onMouseUp: function(e) { /* override this */ },

    onAvailable: function () {
        this.logger.log("onAvailable (base)");
    },

    getEl: function() {
        if (!this._domRef) {
            this._domRef = T_demo.util.Dom.get(this.id);
        }

        return this._domRef;
    },

    getDragEl: function() {
        return T_demo.util.Dom.get(this.dragElId);
    },

    init: function(id, sGroup, config) {
        this.initTarget(id, sGroup, config);
        T_demo.util.Event.addListener(this.id, "mousedown",
                                          this.handleMouseDown, this, true);
    },

    initTarget: function(id, sGroup, config) {

        // configuration attributes
        this.config = config || {};

        // create a local reference to the drag and drop manager
        this.DDM = T_demo.util.DDM;
        // initialize the groups array
        this.groups = {};

        // set the id
        this.id = id;

        this.addToGroup((sGroup) ? sGroup : "default");
        this.handleElId = id;

        T_demo.util.Event.onAvailable(id, this.handleOnAvailable, this, true);

        this.logger = (T_demo.widget.LogWriter) ?
                new T_demo.widget.LogWriter(this.toString()) : T_demo;

        this.setDragElId(id);

        this.invalidHandleTypes = { A: "A" };
        this.invalidHandleIds = {};
        this.invalidHandleClasses = [];

        this.applyConfig();
    },

    applyConfig: function() {

        this.padding           = this.config.padding || [0, 0, 0, 0];
        this.isTarget          = (this.config.isTarget !== false);
        this.maintainOffset    = (this.config.maintainOffset);
        this.primaryButtonOnly = (this.config.primaryButtonOnly !== false);

    },

    handleOnAvailable: function() {
        this.logger.log("handleOnAvailable");
        this.available = true;
        this.resetConstraints();
        this.onAvailable();
    },

    setPadding: function(iTop, iRight, iBot, iLeft) {
        // this.padding = [iLeft, iRight, iTop, iBot];
        if (!iRight && 0 !== iRight) {
            this.padding = [iTop, iTop, iTop, iTop];
        } else if (!iBot && 0 !== iBot) {
            this.padding = [iTop, iRight, iTop, iRight];
        } else {
            this.padding = [iTop, iRight, iBot, iLeft];
        }
    },

    setInitPosition: function(diffX, diffY) {
        var el = this.getEl();

        if (!this.DDM.verifyEl(el)) {
            this.logger.log(this.id + " element is broken");
            return;
        }

        var dx = diffX || 0;
        var dy = diffY || 0;

        var p = T_demo.util.Dom.getXY( el );

        this.initPageX = p[0] - dx;
        this.initPageY = p[1] - dy;

        this.lastPageX = p[0];
        this.lastPageY = p[1];

        this.logger.log(this.id + " inital position: " + this.initPageX +
                ", " + this.initPageY);


        this.setStartPosition(p);
    },

    setStartPosition: function(pos) {
        var p = pos || T_demo.util.Dom.getXY( this.getEl() );
        this.deltaSetXY = null;

        this.startPageX = p[0];
        this.startPageY = p[1];
    },

    addToGroup: function(sGroup) {
        this.groups[sGroup] = true;
        this.DDM.regDragDrop(this, sGroup);
    },

    removeFromGroup: function(sGroup) {
        this.logger.log("Removing from group: " + sGroup);
        if (this.groups[sGroup]) {
            delete this.groups[sGroup];
        }

        this.DDM.removeDDFromGroup(this, sGroup);
    },

    setDragElId: function(id) {
        this.dragElId = id;
    },

    setHandleElId: function(id) {
        this.handleElId = id;
        this.DDM.regHandle(this.id, id);
    },

    setOuterHandleElId: function(id) {
        this.logger.log("Adding outer handle event: " + id);
        T_demo.util.Event.addListener(id, "mousedown",
                this.handleMouseDown, this, true);
        this.setHandleElId(id);
    },

    unreg: function() {
        this.logger.log("DragDrop obj cleanup " + this.id);
        T_demo.util.Event.removeListener(this.id, "mousedown",
                this.handleMouseDown);
        this._domRef = null;
        this.DDM._remove(this);
    },

    isLocked: function() {
        return (this.DDM.isLocked() || this.locked);
    },

    handleMouseDown: function(e, oDD) {

        this.logger.log("isLocked: " + this.isLocked());

        var EU = T_demo.util.Event;

        var button = e.which || e.button;
        this.logger.log("button: " + button);

        if (this.primaryButtonOnly && button > 1) {
            this.logger.log("Mousedown was not produced by the primary button");
            return;
        }

        if (this.isLocked()) {
            this.logger.log("Drag and drop is disabled, aborting");
            return;
        }

        this.logger.log("mousedown " + this.id);
        this.DDM.refreshCache(this.groups);
        var pt = new T_demo.util.Point(EU.getPageX(e), EU.getPageY(e));
        if ( this.DDM.isOverTarget(pt, this) )  {

            this.logger.log("click is over target");

            //  check to see if the handle was clicked
            var srcEl = EU.getTarget(e);

            if (this.isValidHandleChild(srcEl) &&
                    (this.id == this.handleElId ||
                     this.DDM.handleWasClicked(srcEl, this.id)) ) {

                this.logger.log("click was a valid handle");

                // set the initial element position
                this.setStartPosition();

                this.logger.log("firing onMouseDown events");


                this.b4MouseDown(e);
                this.onMouseDown(e);
                this.DDM.handleMouseDown(e, this);

                this.DDM.stopEvent(e);
            }
        }
    },

    addInvalidHandleType: function(tagName) {
        var type = tagName.toUpperCase();
        this.invalidHandleTypes[type] = type;
    },

    addInvalidHandleId: function(id) {
        this.invalidHandleIds[id] = id;
    },


    addInvalidHandleClass: function(cssClass) {
        this.invalidHandleClasses.push(cssClass);
    },

    removeInvalidHandleType: function(tagName) {
        var type = tagName.toUpperCase();
        // this.invalidHandleTypes[type] = null;
        delete this.invalidHandleTypes[type];
    },

    removeInvalidHandleId: function(id) {
        delete this.invalidHandleIds[id];
    },

    removeInvalidHandleClass: function(cssClass) {
        for (var i=0, len=this.invalidHandleClasses.length; i<len; ++i) {
            if (this.invalidHandleClasses[i] == cssClass) {
                delete this.invalidHandleClasses[i];
            }
        }
    },

    isValidHandleChild: function(node) {

        var valid = true;
        // var n = (node.nodeName == "#text") ? node.parentNode : node;
        var nodeName;
        try {
            nodeName = node.nodeName.toUpperCase();
        } catch(e) {
            nodeName = node.nodeName;
        }
        valid = valid && !this.invalidHandleTypes[nodeName];
        valid = valid && !this.invalidHandleIds[node.id];

        for (var i=0, len=this.invalidHandleClasses.length; valid && i<len; ++i) {
            valid = !T_demo.util.Dom.hasClass(node, this.invalidHandleClasses[i]);
        }

        this.logger.log("Valid handle? ... " + valid);

        return valid;

    },

    setXTicks: function(iStartX, iTickSize) {
        this.xTicks = [];
        this.xTickSize = iTickSize;

        var tickMap = {};

        for (var i = this.initPageX; i >= this.minX; i = i - iTickSize) {
            if (!tickMap[i]) {
                this.xTicks[this.xTicks.length] = i;
                tickMap[i] = true;
            }
        }

        for (i = this.initPageX; i <= this.maxX; i = i + iTickSize) {
            if (!tickMap[i]) {
                this.xTicks[this.xTicks.length] = i;
                tickMap[i] = true;
            }
        }

        this.xTicks.sort(this.DDM.numericSort) ;
        this.logger.log("xTicks: " + this.xTicks.join());
    },

    setYTicks: function(iStartY, iTickSize) {
        // this.logger.log("setYTicks: " + iStartY + ", " + iTickSize
               // + ", " + this.initPageY + ", " + this.minY + ", " + this.maxY );
        this.yTicks = [];
        this.yTickSize = iTickSize;

        var tickMap = {};

        for (var i = this.initPageY; i >= this.minY; i = i - iTickSize) {
            if (!tickMap[i]) {
                this.yTicks[this.yTicks.length] = i;
                tickMap[i] = true;
            }
        }

        for (i = this.initPageY; i <= this.maxY; i = i + iTickSize) {
            if (!tickMap[i]) {
                this.yTicks[this.yTicks.length] = i;
                tickMap[i] = true;
            }
        }

        this.yTicks.sort(this.DDM.numericSort) ;
        this.logger.log("yTicks: " + this.yTicks.join());
    },

    setXConstraint: function(iLeft, iRight, iTickSize) {
        this.leftConstraint = iLeft;
        this.rightConstraint = iRight;

        this.minX = this.initPageX - iLeft;
        this.maxX = this.initPageX + iRight;
        if (iTickSize) { this.setXTicks(this.initPageX, iTickSize); }

        this.constrainX = true;
        this.logger.log("initPageX:" + this.initPageX + " minX:" + this.minX +
                " maxX:" + this.maxX);
    },

    clearConstraints: function() {
        this.logger.log("Clearing constraints");
        this.constrainX = false;
        this.constrainY = false;
        this.clearTicks();
    },

    clearTicks: function() {
        this.logger.log("Clearing ticks");
        this.xTicks = null;
        this.yTicks = null;
        this.xTickSize = 0;
        this.yTickSize = 0;
    },

    setYConstraint: function(iUp, iDown, iTickSize) {
        this.logger.log("setYConstraint: " + iUp + "," + iDown + "," + iTickSize);
        this.topConstraint = iUp;
        this.bottomConstraint = iDown;

        this.minY = this.initPageY - iUp;
        this.maxY = this.initPageY + iDown;
        if (iTickSize) { this.setYTicks(this.initPageY, iTickSize); }

        this.constrainY = true;

        this.logger.log("initPageY:" + this.initPageY + " minY:" + this.minY +
                " maxY:" + this.maxY);
    },

    resetConstraints: function() {

        this.logger.log("resetConstraints");

        // Maintain offsets if necessary
        if (this.initPageX || this.initPageX === 0) {
            this.logger.log("init pagexy: " + this.initPageX + ", " +
                               this.initPageY);
            this.logger.log("last pagexy: " + this.lastPageX + ", " +
                               this.lastPageY);
            // figure out how much this thing has moved
            var dx = (this.maintainOffset) ? this.lastPageX - this.initPageX : 0;
            var dy = (this.maintainOffset) ? this.lastPageY - this.initPageY : 0;

            this.setInitPosition(dx, dy);

        // This is the first time we have detected the element's position
        } else {
            this.setInitPosition();
        }

        if (this.constrainX) {
            this.setXConstraint( this.leftConstraint,
                                 this.rightConstraint,
                                 this.xTickSize        );
        }

        if (this.constrainY) {
            this.setYConstraint( this.topConstraint,
                                 this.bottomConstraint,
                                 this.yTickSize         );
        }
    },

    getTick: function(val, tickArray) {

        if (!tickArray) {
            return val;
        } else if (tickArray[0] >= val) {
            // The value is lower than the first tick, so we return the first
            // tick.
            return tickArray[0];
        } else {
            for (var i=0, len=tickArray.length; i<len; ++i) {
                var next = i + 1;
                if (tickArray[next] && tickArray[next] >= val) {
                    var diff1 = val - tickArray[i];
                    var diff2 = tickArray[next] - val;
                    return (diff2 > diff1) ? tickArray[i] : tickArray[next];
                }
            }

            // The value is larger than the last tick, so we return the last
            // tick.
            return tickArray[tickArray.length - 1];
        }
    },

    toString: function() {
        return ("DragDrop " + this.id);
    }

};

if (!T_demo.util.DragDropMgr) {

    T_demo.util.DragDropMgr = new function() {

        this.ids = {};

        this.handleIds = {};

        this.dragCurrent = null;

        this.dragOvers = {};

        this.logger = null;

        this.deltaX = 0;

        this.deltaY = 0;

        this.preventDefault = true;

        this.stopPropagation = true;

        this.initalized = false;

        this.locked = false;

        this.init = function() {
            this.logger = (T_demo.widget.LogWriter) ?
                new T_demo.widget.LogWriter("DragDropMgr") : T_demo;
            this.initialized = true;
        };

        this.POINT     = 0;

        this.INTERSECT = 1;

        this.mode = this.POINT;

        this._execOnAll = function(sMethod, args) {
            for (var i in this.ids) {
                for (var j in this.ids[i]) {
                    var oDD = this.ids[i][j];
                    if (! this.isTypeOfDD(oDD)) {
                        continue;
                    }
                    oDD[sMethod].apply(oDD, args);
                }
            }
        };

        this._onLoad = function() {

            this.init();

            this.logger.log("DDM onload");

            var EU = T_demo.util.Event;

            EU.on(document, "mouseup",   this.handleMouseUp, this, true);
            EU.on(document, "mousemove", this.handleMouseMove, this, true);
            EU.on(window,   "unload",    this._onUnload, this, true);
            EU.on(window,   "resize",    this._onResize, this, true);
            // EU.on(window,   "mouseout",    this._test);

        };

        this._onResize = function(e) {
            this.logger.log("window resize");
            this._execOnAll("resetConstraints", []);
        };

        this.lock = function() { this.locked = true; };

        this.unlock = function() { this.locked = false; };

        this.isLocked = function() { return this.locked; };

        this.locationCache = {};

        this.useCache = true;

        this.clickPixelThresh = 3;

        this.clickTimeThresh = 1000;

        this.dragThreshMet = false;

        this.clickTimeout = null;

        this.startX = 0;

        this.startY = 0;

        this.regDragDrop = function(oDD, sGroup) {
            if (!this.initialized) { this.init(); }

            if (!this.ids[sGroup]) {
                this.ids[sGroup] = {};
            }
            this.ids[sGroup][oDD.id] = oDD;
        };

        this.removeDDFromGroup = function(oDD, sGroup) {
            if (!this.ids[sGroup]) {
                this.ids[sGroup] = {};
            }

            var obj = this.ids[sGroup];
            if (obj && obj[oDD.id]) {
                delete obj[oDD.id];
            }
        };

        this._remove = function(oDD) {
            for (var g in oDD.groups) {
                if (g && this.ids[g][oDD.id]) {
                    delete this.ids[g][oDD.id];
                    //this.logger.log("NEW LEN " + this.ids.length, "warn");
                }
            }
            delete this.handleIds[oDD.id];
        };

        this.regHandle = function(sDDId, sHandleId) {
            if (!this.handleIds[sDDId]) {
                this.handleIds[sDDId] = {};
            }
            this.handleIds[sDDId][sHandleId] = sHandleId;
        };

        this.isDragDrop = function(id) {
            return ( this.getDDById(id) ) ? true : false;
        };

        this.getRelated = function(p_oDD, bTargetsOnly) {
            var oDDs = [];
            for (var i in p_oDD.groups) {
                for (j in this.ids[i]) {
                    var dd = this.ids[i][j];
                    if (! this.isTypeOfDD(dd)) {
                        continue;
                    }
                    if (!bTargetsOnly || dd.isTarget) {
                        oDDs[oDDs.length] = dd;
                    }
                }
            }

            return oDDs;
        };

        this.isLegalTarget = function (oDD, oTargetDD) {
            var targets = this.getRelated(oDD, true);
            for (var i=0, len=targets.length;i<len;++i) {
                if (targets[i].id == oTargetDD.id) {
                    return true;
                }
            }

            return false;
        };

        this.isTypeOfDD = function (oDD) {
            return (oDD && oDD.__ygDragDrop);
        };

        this.isHandle = function(sDDId, sHandleId) {
            return ( this.handleIds[sDDId] &&
                            this.handleIds[sDDId][sHandleId] );
        };

        this.getDDById = function(id) {
            for (var i in this.ids) {
                if (this.ids[i][id]) {
                    return this.ids[i][id];
                }
            }
            return null;
        };

        this.handleMouseDown = function(e, oDD) {

            this.currentTarget = T_demo.util.Event.getTarget(e);

            this.logger.log("mousedown - adding event handlers");
            this.dragCurrent = oDD;

            var el = oDD.getEl();

            // track start position
            this.startX = T_demo.util.Event.getPageX(e);
            this.startY = T_demo.util.Event.getPageY(e);

            this.deltaX = this.startX - el.offsetLeft;
            this.deltaY = this.startY - el.offsetTop;

            this.dragThreshMet = false;

            this.clickTimeout = setTimeout(
                    function() {
                        var DDM = T_demo.util.DDM;
                        DDM.startDrag(DDM.startX, DDM.startY);
                    },
                    this.clickTimeThresh );
        };

        this.startDrag = function(x, y) {
            this.logger.log("firing drag start events");
            clearTimeout(this.clickTimeout);
            if (this.dragCurrent) {
                this.dragCurrent.b4StartDrag(x, y);
                this.dragCurrent.startDrag(x, y);
            }
            this.dragThreshMet = true;
        };

        this.handleMouseUp = function(e) {

            if (! this.dragCurrent) {
                return;
            }

            clearTimeout(this.clickTimeout);

            if (this.dragThreshMet) {
                this.logger.log("mouseup detected - completing drag");
                this.fireEvents(e, true);
            } else {
                this.logger.log("drag threshold not met");
            }

            this.stopDrag(e);

            this.stopEvent(e);
        };

        this.stopEvent = function(e) {
            if (this.stopPropagation) {
                T_demo.util.Event.stopPropagation(e);
            }

            if (this.preventDefault) {
                T_demo.util.Event.preventDefault(e);
            }
        };

        this.stopDrag = function(e) {
            if (this.dragCurrent) {
                if (this.dragThreshMet) {
                    this.logger.log("firing endDrag events");
                    this.dragCurrent.b4EndDrag(e);
                    this.dragCurrent.endDrag(e);
                }

                this.logger.log("firing mouseUp event");
                this.dragCurrent.onMouseUp(e);
            }

            this.dragCurrent = null;
            this.dragOvers = {};
        };


        this.handleMouseMove = function(e) {
            //this.logger.log("handlemousemove");
            if (! this.dragCurrent) {
                // this.logger.log("no current drag obj");
                return true;
            }

            if (T_demo.util.Event.isIE && !e.button) {
                this.logger.log("button failure");
                this.stopEvent(e);
                return this.handleMouseUp(e);
            }

            if (!this.dragThreshMet) {
                var diffX = Math.abs(this.startX - T_demo.util.Event.getPageX(e));
                var diffY = Math.abs(this.startY - T_demo.util.Event.getPageY(e));
                // this.logger.log("diffX: " + diffX + "diffY: " + diffY);
                if (diffX > this.clickPixelThresh ||
                            diffY > this.clickPixelThresh) {
                    this.logger.log("pixel threshold met");
                    this.startDrag(this.startX, this.startY);
                }
            }

            if (this.dragThreshMet) {
                this.dragCurrent.b4Drag(e);
                this.dragCurrent.onDrag(e);
                this.fireEvents(e, false);
            }

            this.stopEvent(e);

            return true;
        };

        this.fireEvents = function(e, isDrop) {
            var dc = this.dragCurrent;
            if (!dc || dc.isLocked()) {
                return;
            }

            var x = T_demo.util.Event.getPageX(e);
            var y = T_demo.util.Event.getPageY(e);
            var pt = new T_demo.util.Point(x,y);

            // cache the previous dragOver array
            var oldOvers = [];

            var outEvts   = [];
            var overEvts  = [];
            var dropEvts  = [];
            var enterEvts = [];


            for (var i in this.dragOvers) {

                var ddo = this.dragOvers[i];

                if (! this.isTypeOfDD(ddo)) {
                    continue;
                }

                if (! this.isOverTarget(pt, ddo, this.mode)) {
                    outEvts.push( ddo );
                }

                oldOvers[i] = true;
                delete this.dragOvers[i];
            }

            for (var sGroup in dc.groups) {
                // this.logger.log("Processing group " + sGroup);

                if ("string" != typeof sGroup) {
                    continue;
                }

                for (i in this.ids[sGroup]) {
                    var oDD = this.ids[sGroup][i];
                    if (! this.isTypeOfDD(oDD)) {
                        continue;
                    }

                    if (oDD.isTarget && !oDD.isLocked() && oDD != dc) {
                        if (this.isOverTarget(pt, oDD, this.mode)) {
                            // look for drop interactions
                            if (isDrop) {
                                dropEvts.push( oDD );
                            // look for drag enter and drag over interactions
                            } else {

                                // initial drag over: dragEnter fires
                                if (!oldOvers[oDD.id]) {
                                    enterEvts.push( oDD );
                                // subsequent drag overs: dragOver fires
                                } else {
                                    overEvts.push( oDD );
                                }

                                this.dragOvers[oDD.id] = oDD;
                            }
                        }
                    }
                }
            }

            if (this.mode) {
                if (outEvts.length) {
                    this.logger.log(dc.id+" onDragOut: " + outEvts);
                    dc.b4DragOut(e, outEvts);
                    dc.onDragOut(e, outEvts);
                }

                if (enterEvts.length) {
                    this.logger.log(dc.id+" onDragEnter: " + enterEvts);
                    dc.onDragEnter(e, enterEvts);
                }

                if (overEvts.length) {
                    this.logger.log(dc.id+" onDragOver: " + overEvts);
                    dc.b4DragOver(e, overEvts);
                    dc.onDragOver(e, overEvts);
                }

                if (dropEvts.length) {
                    this.logger.log(dc.id+" onDragDrop: " + dropEvts);
                    dc.b4DragDrop(e, dropEvts);
                    dc.onDragDrop(e, dropEvts);
                }

            } else {
                // fire dragout events
                var len = 0;
                for (i=0, len=outEvts.length; i<len; ++i) {
                    this.logger.log(dc.id+" onDragOut: " + outEvts[i].id);
                    dc.b4DragOut(e, outEvts[i].id);
                    dc.onDragOut(e, outEvts[i].id);
                }

                // fire enter events
                for (i=0,len=enterEvts.length; i<len; ++i) {
                    this.logger.log(dc.id + " onDragEnter " + enterEvts[i].id);
                    // dc.b4DragEnter(e, oDD.id);
                    dc.onDragEnter(e, enterEvts[i].id);
                }

                // fire over events
                for (i=0,len=overEvts.length; i<len; ++i) {
                    this.logger.log(dc.id + " onDragOver " + overEvts[i].id);
                    dc.b4DragOver(e, overEvts[i].id);
                    dc.onDragOver(e, overEvts[i].id);
                }

                // fire drop events
                for (i=0, len=dropEvts.length; i<len; ++i) {
                    this.logger.log(dc.id + " dropped on " + dropEvts[i].id);
                    dc.b4DragDrop(e, dropEvts[i].id);
                    dc.onDragDrop(e, dropEvts[i].id);
                }

            }

        };

        this.getBestMatch = function(dds) {
            var winner = null;

            var len = dds.length;

            if (len == 1) {
                winner = dds[0];
            } else {
                // Loop through the targeted items
                for (var i=0; i<len; ++i) {
                    var dd = dds[i];
                    // If the cursor is over the object, it wins.  If the
                    // cursor is over multiple matches, the first one we come
                    // to wins.
                    if (dd.cursorIsOver) {
                        winner = dd;
                        break;
                    // Otherwise the object with the most overlap wins
                    } else {
                        if (!winner ||
                            winner.overlap.getArea() < dd.overlap.getArea()) {
                            winner = dd;
                        }
                    }
                }
            }

            return winner;
        };

        this.refreshCache = function(groups) {
            this.logger.log("refreshing element location cache");
            for (sGroup in groups) {
                if ("string" != typeof sGroup) {
                    continue;
                }
                for (i in this.ids[sGroup]) {
                    var oDD = this.ids[sGroup][i];

                    if (this.isTypeOfDD(oDD)) {
                    // if (this.isTypeOfDD(oDD) && oDD.isTarget) {
                        var loc = this.getLocation(oDD);
                        if (loc) {
                            this.locationCache[oDD.id] = loc;
                        } else {
                            delete this.locationCache[oDD.id];
                            this.logger.log("Could not get the loc for " + oDD.id,
                                    "warn");
                            // this will unregister the drag and drop object if
                            // the element is not in a usable state
                            // oDD.unreg();
                        }
                    }
                }
            }
        };

        this.verifyEl = function(el) {
            try {
                if (el) {
                    var parent = el.offsetParent;
                    if (parent) {
                        return true;
                    }
                }
            } catch(e) {
                this.logger.log("detected problem with an element");
            }

            return false;
        };

        this.getLocation = function(oDD) {
            if (! this.isTypeOfDD(oDD)) {
                this.logger.log(oDD + " is not a DD obj");
                return null;
            }

            var el = oDD.getEl();

            var aPos = null;
            try {
                aPos= T_demo.util.Dom.getXY(el);
            } catch (e) { }

            if (!aPos) {
                return null;
            }

            x1 = aPos[0];
            x2 = x1 + el.offsetWidth;

            y1 = aPos[1];
            y2 = y1 + el.offsetHeight;

            var t = y1 - oDD.padding[0];
            var r = x2 + oDD.padding[1];
            var b = y2 + oDD.padding[2];
            var l = x1 - oDD.padding[3];

            return new T_demo.util.Region( t, r, b, l );

        };

        this.isOverTarget = function(pt, oTarget, intersect) {
            // use cache if available
            var loc = this.locationCache[oTarget.id];
            if (!loc || !this.useCache) {
                this.logger.log("cache not populated");
                loc = this.getLocation(oTarget);
                this.locationCache[oTarget.id] = loc;

                this.logger.log("cache: " + loc);
            }

            if (!loc) {
                return false;
            }

            oTarget.cursorIsOver = loc.contains( pt );

            var dc = this.dragCurrent;
            if (!dc || !dc.getTargetCoord ||
                    (!intersect && !dc.constrainX && !dc.constrainY)) {
                return oTarget.cursorIsOver;
            }

            oTarget.overlap = null;
            var pos = dc.getTargetCoord(pt.x, pt.y);

            var el = dc.getDragEl();
            var curRegion = new T_demo.util.Region( pos.y,
                                                   pos.x + el.offsetWidth,
                                                   pos.y + el.offsetHeight,
                                                   pos.x );

            var overlap = curRegion.intersect(loc);

            if (overlap) {
                oTarget.overlap = overlap;
                return (intersect) ? true : oTarget.cursorIsOver;
            } else {
                return false;
            }
        };

        /**
         * @private
         */
        this._onUnload = function(e, me) {
            this.unregAll();
        };

        this.unregAll = function() {
            this.logger.log("unregister all");

            if (this.dragCurrent) {
                this.stopDrag();
                this.dragCurrent = null;
            }

            this._execOnAll("unreg", []);

            for (i in this.elementCache) {
                delete this.elementCache[i];
            }

            this.elementCache = {};
            this.ids = {};
        };

        this.elementCache = {};

        this.getElWrapper = function(id) {
            var oWrapper = this.elementCache[id];
            if (!oWrapper || !oWrapper.el) {
                oWrapper = this.elementCache[id] =
                    new this.ElementWrapper(T_demo.util.Dom.get(id));
            }
            return oWrapper;
        };

        this.getElement = function(id) {
            return T_demo.util.Dom.get(id);
        };

        this.getCss = function(id) {
            var el = T_demo.util.Dom.get(id);
            return (el) ? el.style : null;
        };

        this.ElementWrapper = function(el) {
                this.el = el || null;
                this.id = this.el && el.id;
                this.css = this.el && el.style;
            };

        this.getPosX = function(el) {
            return T_demo.util.Dom.getX(el);
        };
        this.getPosY = function(el) {
            return T_demo.util.Dom.getY(el);
        };

        this.swapNode = function(n1, n2) {
            if (n1.swapNode) {
                n1.swapNode(n2);
            } else {
                // the node reference order for the swap is a little tricky.
                var p = n2.parentNode;
                var s = n2.nextSibling;
                n1.parentNode.replaceChild(n2, n1);
                p.insertBefore(n1,s);
            }
        };

        this.getScroll = function () {
            var t, l;
            if (document.documentElement && document.documentElement.scrollTop) {
                t = document.documentElement.scrollTop;
                l = document.documentElement.scrollLeft;
            } else if (document.body) {
                t = document.body.scrollTop;
                l = document.body.scrollLeft;
            }
            return { top: t, left: l };
        };

        this.getStyle = function(el, styleProp) {
            return T_demo.util.Dom.getStyle(el, styleProp);
        };

        this.getScrollTop = function () { return this.getScroll().top; };

        this.getScrollLeft = function () { return this.getScroll().left; };

        this.moveToEl = function (moveEl, targetEl) {
            var aCoord = T_demo.util.Dom.getXY(targetEl);
            this.logger.log("moveToEl: " + aCoord);
            T_demo.util.Dom.setXY(moveEl, aCoord);
        };

        this.getClientHeight = function() {
            return T_demo.util.Dom.getClientHeight();
        };

        this.getClientWidth = function() {
            return T_demo.util.Dom.getClientWidth();
        };

        this.numericSort = function(a, b) { return (a - b); };

        this._timeoutCount = 0;

        this._addListeners = function() {
            if ( T_demo.util.Event && document ) {
                this._onLoad();
            } else {
                if (this._timeoutCount > 1000) {
                    this.logger.log("DragDrop requires the Event Utility");
                } else {
                    var DDM = T_demo.util.DDM;
                    setTimeout( function() { DDM._addListeners(); }, 10);
                    if (document && document.body) {
                        this._timeoutCount += 1;
                    }
                }
            }
        };

        this.handleWasClicked = function(node, id) {
            if (this.isHandle(id, node.id)) {
                this.logger.log("clicked node is a handle");
                return true;
            } else {
                // check to see if this is a text node child of the one we want
                var p = node.parentNode;
                // this.logger.log("p: " + p);

                while (p) {
                    if (this.isHandle(id, p.id)) {
                        return true;
                    } else {
                        this.logger.log(p.id + " is not a handle");
                        p = p.parentNode;
                    }
                }
            }

            return false;
        };

    } ();

    // shorter alias, save a few bytes
    T_demo.util.DDM = T_demo.util.DragDropMgr;
    T_demo.util.DDM._addListeners();

}

T_demo.util.DD = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

// T_demo.util.DD.prototype = new T_demo.util.DragDrop();
T_demo.extend(T_demo.util.DD, T_demo.util.DragDrop);

T_demo.util.DD.prototype.scroll = true;

T_demo.util.DD.prototype.autoOffset = function(iPageX, iPageY) {
    // var el = this.getEl();
    // var aCoord = T_demo.util.Dom.getXY(el);
    // var x = iPageX - aCoord[0];
    // var y = iPageY - aCoord[1];
    var x = iPageX - this.startPageX;
    var y = iPageY - this.startPageY;
    this.setDelta(x, y);
    // this.logger.log("autoOffset el pos: " + aCoord + ", delta: " + x + "," + y);
};

T_demo.util.DD.prototype.setDelta = function(iDeltaX, iDeltaY) {
    this.deltaX = iDeltaX;
    this.deltaY = iDeltaY;
    this.logger.log("deltaX:" + this.deltaX + ", deltaY:" + this.deltaY);
};


T_demo.util.DD.prototype.setDragElPos = function(iPageX, iPageY) {

    var el = this.getDragEl();

    this.alignElWithMouse(el, iPageX, iPageY);
};

T_demo.util.DD.prototype.alignElWithMouse = function(el, iPageX, iPageY) {
    var oCoord = this.getTargetCoord(iPageX, iPageY);
    if (!this.deltaSetXY) {
        var aCoord = [oCoord.x, oCoord.y];
        T_demo.util.Dom.setXY(el, aCoord);
        var newLeft = parseInt( T_demo.util.Dom.getStyle(el, "left"), 10 );
        var newTop  = parseInt( T_demo.util.Dom.getStyle(el, "top" ), 10 );

        this.deltaSetXY = [ newLeft - oCoord.x, newTop - oCoord.y ];

    } else {
        T_demo.util.Dom.setStyle(el, "left", (oCoord.x + this.deltaSetXY[0]) + "px");
        T_demo.util.Dom.setStyle(el, "top",  (oCoord.y + this.deltaSetXY[1]) + "px");
    }


    this.cachePosition(oCoord.x, oCoord.y);

    this.autoScroll(oCoord.x, oCoord.y, el.offsetHeight, el.offsetWidth);
};

T_demo.util.DD.prototype.cachePosition = function(iPageX, iPageY) {
    if (iPageX) {
        this.lastPageX = iPageX;
        this.lastPageY = iPageY;
    } else {
        var aCoord = T_demo.util.Dom.getXY(this.getEl());
        this.lastPageX = aCoord[0];
        this.lastPageY = aCoord[1];
    }
};

T_demo.util.DD.prototype.autoScroll = function(x, y, h, w) {

    if (this.scroll) {
        // The client height
        var clientH = this.DDM.getClientHeight();

        // The client width
        var clientW = this.DDM.getClientWidth();

        // The amt scrolled down
        var st = this.DDM.getScrollTop();

        // The amt scrolled right
        var sl = this.DDM.getScrollLeft();

        // Location of the bottom of the element
        var bot = h + y;

        // Location of the right of the element
        var right = w + x;

        var toBot = (clientH + st - y - this.deltaY);

        var toRight = (clientW + sl - x - this.deltaX);

        var thresh = 40;

        var scrAmt = (document.all) ? 80 : 30;

        if ( bot > clientH && toBot < thresh ) {
            window.scrollTo(sl, st + scrAmt);
        }

        if ( y < st && st > 0 && y - st < thresh ) {
            window.scrollTo(sl, st - scrAmt);
        }

        if ( right > clientW && toRight < thresh ) {
            window.scrollTo(sl + scrAmt, st);
        }

        if ( x < sl && sl > 0 && x - sl < thresh ) {
            window.scrollTo(sl - scrAmt, st);
        }
    }
};

T_demo.util.DD.prototype.getTargetCoord = function(iPageX, iPageY) {

    var x = iPageX - this.deltaX;
    var y = iPageY - this.deltaY;

    if (this.constrainX) {
        if (x < this.minX) { x = this.minX; }
        if (x > this.maxX) { x = this.maxX; }
    }

    if (this.constrainY) {
        if (y < this.minY) { y = this.minY; }
        if (y > this.maxY) { y = this.maxY; }
    }

    x = this.getTick(x, this.xTicks);
    y = this.getTick(y, this.yTicks);

    return {x:x, y:y};
};

T_demo.util.DD.prototype.applyConfig = function() {
    T_demo.util.DD.superclass.applyConfig.call(this);
    this.scroll = (this.config.scroll !== false);
};

T_demo.util.DD.prototype.b4MouseDown = function(e) {
    // this.resetConstraints();
    this.autoOffset(T_demo.util.Event.getPageX(e),
                        T_demo.util.Event.getPageY(e));
};

T_demo.util.DD.prototype.b4Drag = function(e) {
    this.setDragElPos(T_demo.util.Event.getPageX(e),
                        T_demo.util.Event.getPageY(e));
};

T_demo.util.DD.prototype.toString = function() {
    return ("DD " + this.id);
};

T_demo.util.DDProxy = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
        this.initFrame();
    }
};

T_demo.extend(T_demo.util.DDProxy, T_demo.util.DD);

T_demo.util.DDProxy.dragElId = "ygddfdiv";

T_demo.util.DDProxy.prototype.resizeFrame = true;

T_demo.util.DDProxy.prototype.centerFrame = false;

T_demo.util.DDProxy.prototype.createFrame = function() {
    var self = this;
    var body = document.body;

    if (!body || !body.firstChild) {
        setTimeout( function() { self.createFrame(); }, 50 );
        return;
    }

    var div = this.getDragEl();

    if (!div) {
        div    = document.createElement("div");
        div.id = this.dragElId;
        var s  = div.style;

        s.position   = "absolute";
        s.visibility = "hidden";
        s.cursor     = "move";
        s.border     = "2px solid #aaa";
        s.zIndex     = 999;

        body.insertBefore(div, body.firstChild);
    }
};

T_demo.util.DDProxy.prototype.initFrame = function() {

    this.createFrame();

};

T_demo.util.DDProxy.prototype.applyConfig = function() {
    this.logger.log("DDProxy applyConfig");
    T_demo.util.DDProxy.superclass.applyConfig.call(this);

    this.resizeFrame = (this.config.resizeFrame !== false);
    this.centerFrame = (this.config.centerFrame);
    this.setDragElId(this.config.dragElId || T_demo.util.DDProxy.dragElId);

    //this.logger.log("dragElId: " + this.dragElId);
};

T_demo.util.DDProxy.prototype.showFrame = function(iPageX, iPageY) {
    var el = this.getEl();
    var dragEl = this.getDragEl();
    var s = dragEl.style;

    this._resizeProxy();

    if (this.centerFrame) {
        this.setDelta( Math.round(parseInt(s.width,  10)/2),
                       Math.round(parseInt(s.height, 10)/2) );
    }

    this.setDragElPos(iPageX, iPageY);

    T_demo.util.Dom.setStyle(dragEl, "visibility", "visible");
};

T_demo.util.DDProxy.prototype._resizeProxy = function() {
    if (this.resizeFrame) {
        var DOM    = T_demo.util.Dom;
        var el     = this.getEl();
        var dragEl = this.getDragEl();

        var bt = parseInt( DOM.getStyle(dragEl, "borderTopWidth"    ), 10);
        var br = parseInt( DOM.getStyle(dragEl, "borderRightWidth"  ), 10);
        var bb = parseInt( DOM.getStyle(dragEl, "borderBottomWidth" ), 10);
        var bl = parseInt( DOM.getStyle(dragEl, "borderLeftWidth"   ), 10);

        if (isNaN(bt)) { bt = 0; }
        if (isNaN(br)) { br = 0; }
        if (isNaN(bb)) { bb = 0; }
        if (isNaN(bl)) { bl = 0; }

        this.logger.log("proxy size: " + bt + "  " + br + " " + bb + " " + bl);

        var newWidth  = Math.max(0, el.offsetWidth  - br - bl);
        var newHeight = Math.max(0, el.offsetHeight - bt - bb);

        this.logger.log("Resizing proxy element");

        DOM.setStyle( dragEl, "width",  newWidth  + "px" );
        DOM.setStyle( dragEl, "height", newHeight + "px" );
    }
};

// overrides T_demo.util.DragDrop
T_demo.util.DDProxy.prototype.b4MouseDown = function(e) {
    var x = T_demo.util.Event.getPageX(e);
    var y = T_demo.util.Event.getPageY(e);
    this.autoOffset(x, y);
    this.setDragElPos(x, y);
};

// overrides T_demo.util.DragDrop
T_demo.util.DDProxy.prototype.b4StartDrag = function(x, y) {
    // show the drag frame
    this.logger.log("start drag show frame, x: " + x + ", y: " + y);
    this.showFrame(x, y);
};

// overrides T_demo.util.DragDrop
T_demo.util.DDProxy.prototype.b4EndDrag = function(e) {
    this.logger.log(this.id + " b4EndDrag");
    T_demo.util.Dom.setStyle(this.getDragEl(), "visibility", "hidden");
};

T_demo.util.DDProxy.prototype.endDrag = function(e) {
    var DOM = T_demo.util.Dom;
    this.logger.log(this.id + " endDrag");
    var lel = this.getEl();
    var del = this.getDragEl();

    DOM.setStyle(del, "visibility", "");
    DOM.setStyle(lel, "visibility", "hidden");
    T_demo.util.DDM.moveToEl(lel, del);
    //del.style.visibility = "hidden";
    DOM.setStyle(del, "visibility", "hidden");
    //lel.style.visibility = "";
    DOM.setStyle(lel, "visibility", "");
};

T_demo.util.DDProxy.prototype.toString = function() {
    return ("DDProxy " + this.id);
};


T_demo.util.DDTarget = function(id, sGroup, config) {
    if (id) {
        this.initTarget(id, sGroup, config);
    }
};

// T_demo.util.DDTarget.prototype = new T_demo.util.DragDrop();
T_demo.extend(T_demo.util.DDTarget, T_demo.util.DragDrop);

T_demo.util.DDTarget.prototype.toString = function() {
    return ("DDTarget " + this.id);
};

