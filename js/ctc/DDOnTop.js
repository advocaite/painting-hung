T_demo.example.DDOnTop = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
        this.logger = this.logger || T_demo;
    }
};

// T_demo.example.DDOnTop.prototype = new T_demo.util.DD();
T_demo.extend(T_demo.example.DDOnTop, T_demo.util.DD);

/**
 * The inital z-index of the element, stored so we can restore it later
 *
 * @type int
 */
T_demo.example.DDOnTop.prototype.origZ = 0;

T_demo.example.DDOnTop.prototype.startDrag = function(x, y) {
    this.logger.log(this.id + " startDrag");

    var style = this.getEl().style;

    // store the original z-index
    this.origZ = style.zIndex;

    // The z-index needs to be set very high so the element will indeed be on top
    style.zIndex = 999;
};

T_demo.example.DDOnTop.prototype.endDrag = function(e) {
    this.logger.log(this.id + " endDrag");

    // restore the original z-index
    this.getEl().style.zIndex = this.origZ;
};
