T_demo.util.Dom = function() {
   var ua = navigator.userAgent.toLowerCase();
   var isOpera = (ua.indexOf('opera') > -1);
   var isSafari = (ua.indexOf('safari') > -1);
   var isIE = (window.ActiveXObject);

   var id_counter = 0;
   var util = T_demo.util; // internal shorthand
   var property_cache = {}; // to cache case conversion for set/getStyle

   var toCamel = function(property) {
      var convert = function(prop) {
         var test = /(-[a-z])/i.exec(prop);
         return prop.replace(RegExp.$1, RegExp.$1.substr(1).toUpperCase());
      };

      while(property.indexOf('-') > -1) {
         property = convert(property);
      }

      return property;
      //return property.replace(/-([a-z])/gi, function(m0, m1) {return m1.toUpperCase()}) // cant use function as 2nd arg yet due to safari bug
   };

   var toHyphen = function(property) {
      if (property.indexOf('-') > -1) { // assume hyphen
         return property;
      }

      var converted = '';
      for (var i = 0, len = property.length;i < len; ++i) {
         if (property.charAt(i) == property.charAt(i).toUpperCase()) {
            converted = converted + '-' + property.charAt(i).toLowerCase();
         } else {
            converted = converted + property.charAt(i);
         }
      }

      return converted;
      //return property.replace(/([a-z])([A-Z]+)/g, function(m0, m1, m2) {return (m1 + '-' + m2.toLowerCase())});
   };

   // improve performance by only looking up once
   var cacheConvertedProperties = function(property) {
      property_cache[property] = {
         camel: toCamel(property),
         hyphen: toHyphen(property)
      };
   };

   return {
      get: function(el) {
         if (!el) { return null; } // nothing to work with

         if (typeof el != 'string' && !(el instanceof Array) ) { // assuming HTMLElement or HTMLCollection, so pass back as is
            return el;
         }

         if (typeof el == 'string') { // ID
            return document.getElementById(el);
         }
         else { // array of ID's and/or elements
            var collection = [];
            for (var i = 0, len = el.length; i < len; ++i) {
               collection[collection.length] = util.Dom.get(el[i]);
            }

            return collection;
         }

         return null; // safety, should never happen
      },

      getStyle: function(el, property) {
         var f = function(el) {
            var value = null;
            var dv = document.defaultView;

            if (!property_cache[property]) {
               cacheConvertedProperties(property);
            }

            var camel = property_cache[property]['camel'];
            var hyphen = property_cache[property]['hyphen'];

            if (property == 'opacity' && el.filters) {// IE opacity
               value = 1;
               try {
                  value = el.filters.item('DXImageTransform.Microsoft.Alpha').opacity / 100;
               } catch(e) {
                  try {
                     value = el.filters.item('alpha').opacity / 100;
                  } catch(e) {}
               }
            } else if (el.style[camel]) { // camelCase for valid styles
               value = el.style[camel];
            }
            else if (isIE && el.currentStyle && el.currentStyle[camel]) { // camelCase for currentStyle; isIE to workaround broken Opera 9 currentStyle
               value = el.currentStyle[camel];
            }
            else if ( dv && dv.getComputedStyle ) { // hyphen-case for computedStyle
               var computed = dv.getComputedStyle(el, '');

               if (computed && computed.getPropertyValue(hyphen)) {
                  value = computed.getPropertyValue(hyphen);
               }
            }

            return value;
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      setStyle: function(el, property, val) {
         if (!property_cache[property]) {
            cacheConvertedProperties(property);
         }

         var camel = property_cache[property]['camel'];

         var f = function(el) {
            switch(property) {
               case 'opacity' :
                  if (isIE && typeof el.style.filter == 'string') { // in case not appended
                     el.style.filter = 'alpha(opacity=' + val * 100 + ')';

                     if (!el.currentStyle || !el.currentStyle.hasLayout) {
                        el.style.zoom = 1; // when no layout or cant tell
                     }
                  } else {
                     el.style.opacity = val;
                     el.style['-moz-opacity'] = val;
                     el.style['-khtml-opacity'] = val;
                  }

                  break;
               default :
                  el.style[camel] = val;
            }


         };

         util.Dom.batch(el, f, util.Dom, true);
      },

      getXY: function(el) {
         var f = function(el) {

         // has to be part of document to have pageXY
            if (el.offsetParent === null || this.getStyle(el, 'display') == 'none') {
               return false;
            }

            var parentNode = null;
            var pos = [];
            var box;

            if (el.getBoundingClientRect) { // IE
               box = el.getBoundingClientRect();
               var doc = document;
               if ( !this.inDocument(el) && parent.document != document) {// might be in a frame, need to get its scroll
                  doc = parent.document;

                  if ( !this.isAncestor(doc.documentElement, el) ) {
                     return false;
                  }

               }

               var scrollTop = Math.max(doc.documentElement.scrollTop, doc.body.scrollTop);
               var scrollLeft = Math.max(doc.documentElement.scrollLeft, doc.body.scrollLeft);

               return [box.left + scrollLeft, box.top + scrollTop];
            }
            else { // safari, opera, & gecko
               pos = [el.offsetLeft, el.offsetTop];
               parentNode = el.offsetParent;
               if (parentNode != el) {
                  while (parentNode) {
                     pos[0] += parentNode.offsetLeft;
                     pos[1] += parentNode.offsetTop;
                     parentNode = parentNode.offsetParent;
                  }
               }
               if (isSafari && this.getStyle(el, 'position') == 'absolute' ) { // safari doubles in some cases
                  pos[0] -= document.body.offsetLeft;
                  pos[1] -= document.body.offsetTop;
               }
            }

            if (el.parentNode) { parentNode = el.parentNode; }
            else { parentNode = null; }

            while (parentNode && parentNode.tagName.toUpperCase() != 'BODY' && parentNode.tagName.toUpperCase() != 'HTML')
            { // account for any scrolled ancestors
               if (util.Dom.getStyle(parentNode, 'display') != 'inline') { // work around opera inline scrollLeft/Top bug
                  pos[0] -= parentNode.scrollLeft;
                  pos[1] -= parentNode.scrollTop;
               }

               if (parentNode.parentNode) { parentNode = parentNode.parentNode; }
               else { parentNode = null; }
            }


            return pos;
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      getX: function(el) {
         var f = function(el) {
            return util.Dom.getXY(el)[0];
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      getY: function(el) {
         var f = function(el) {
            return util.Dom.getXY(el)[1];
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      setXY: function(el, pos, noRetry) {
         var f = function(el) {
            var style_pos = this.getStyle(el, 'position');
            if (style_pos == 'static') { // default to relative
               this.setStyle(el, 'position', 'relative');
               style_pos = 'relative';
            }

            var pageXY = this.getXY(el);
            if (pageXY === false) { // has to be part of doc to have pageXY
               return false;
            }

            var delta = [ // assuming pixels; if not we will have to retry
               parseInt( this.getStyle(el, 'left'), 10 ),
               parseInt( this.getStyle(el, 'top'), 10 )
            ];

            if ( isNaN(delta[0]) ) {// in case of 'auto'
               delta[0] = (style_pos == 'relative') ? 0 : el.offsetLeft;
            }
            if ( isNaN(delta[1]) ) { // in case of 'auto'
               delta[1] = (style_pos == 'relative') ? 0 : el.offsetTop;
            }

            if (pos[0] !== null) { el.style.left = pos[0] - pageXY[0] + delta[0] + 'px'; }
            if (pos[1] !== null) { el.style.top = pos[1] - pageXY[1] + delta[1] + 'px'; }

            var newXY = this.getXY(el);

            // if retry is true, try one more time if we miss
            if (!noRetry && (newXY[0] != pos[0] || newXY[1] != pos[1]) ) {
               this.setXY(el, pos, true);
            }

         };

         util.Dom.batch(el, f, util.Dom, true);
      },

      setX: function(el, x) {
         util.Dom.setXY(el, [x, null]);
      },

      setY: function(el, y) {
         util.Dom.setXY(el, [null, y]);
      },

      getRegion: function(el) {
         var f = function(el) {
            var region = new T_demo.util.Region.getRegion(el);
            return region;
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      getClientWidth: function() {
         return util.Dom.getViewportWidth();
      },

      getClientHeight: function() {
         return util.Dom.getViewportHeight();
      },

      getElementsByClassName: function(className, tag, root) {
         var method = function(el) { return util.Dom.hasClass(el, className) };
         return util.Dom.getElementsBy(method, tag, root);
      },

      hasClass: function(el, className) {
         var re = new RegExp('(?:^|\\s+)' + className + '(?:\\s+|$)');

         var f = function(el) {
            return re.test(el['className']);
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      addClass: function(el, className) {
         var f = function(el) {
            if (this.hasClass(el, className)) { return; } // already present


            el['className'] = [el['className'], className].join(' ');
         };

         util.Dom.batch(el, f, util.Dom, true);
      },

      removeClass: function(el, className) {
         var re = new RegExp('(?:^|\\s+)' + className + '(?:\\s+|$)', 'g');

         var f = function(el) {
            if (!this.hasClass(el, className)) { return; } // not present


            var c = el['className'];
            el['className'] = c.replace(re, ' ');
            if ( this.hasClass(el, className) ) { // in case of multiple adjacent
               this.removeClass(el, className);
            }

         };

         util.Dom.batch(el, f, util.Dom, true);
      },

      replaceClass: function(el, oldClassName, newClassName) {
         if (oldClassName === newClassName) { // avoid infinite loop
            return false;
         };

         var re = new RegExp('(?:^|\\s+)' + oldClassName + '(?:\\s+|$)', 'g');

         var f = function(el) {

            if ( !this.hasClass(el, oldClassName) ) {
               this.addClass(el, newClassName); // just add it if nothing to replace
               return; // note return
            }

            el['className'] = el['className'].replace(re, ' ' + newClassName + ' ');

            if ( this.hasClass(el, oldClassName) ) { // in case of multiple adjacent
               this.replaceClass(el, oldClassName, newClassName);
            }
         };

         util.Dom.batch(el, f, util.Dom, true);
      },

      generateId: function(el, prefix) {
         prefix = prefix || 'yui-gen';
         el = el || {};

         var f = function(el) {
            if (el) {
               el = util.Dom.get(el);
            } else {
               el = {}; // just generating ID in this case
            }

            if (!el.id) {
               el.id = prefix + id_counter++;
            } // dont override existing


            return el.id;
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      isAncestor: function(haystack, needle) {
         haystack = util.Dom.get(haystack);
         if (!haystack || !needle) { return false; }

         var f = function(needle) {
            if (haystack.contains && !isSafari) { // safari "contains" is broken
               return haystack.contains(needle);
            }
            else if ( haystack.compareDocumentPosition ) {
               return !!(haystack.compareDocumentPosition(needle) & 16);
            }
            else { // loop up and test each parent
               var parent = needle.parentNode;

               while (parent) {
                  if (parent == haystack) {
                     return true;
                  }
                  else if (!parent.tagName || parent.tagName.toUpperCase() == 'HTML') {
                     return false;
                  }

                  parent = parent.parentNode;
               }
               return false;
            }
         };

         return util.Dom.batch(needle, f, util.Dom, true);
      },

      inDocument: function(el) {
         var f = function(el) {
            return this.isAncestor(document.documentElement, el);
         };

         return util.Dom.batch(el, f, util.Dom, true);
      },

      getElementsBy: function(method, tag, root) {
         tag = tag || '*';
         root = util.Dom.get(root) || document;

         var nodes = [];
         var elements = root.getElementsByTagName(tag);

         if ( !elements.length && (tag == '*' && root.all) ) {
            elements = root.all; // IE < 6
         }

         for (var i = 0, len = elements.length; i < len; ++i)
         {
            if ( method(elements[i]) ) { nodes[nodes.length] = elements[i]; }
         }


         return nodes;
      },

      batch: function(el, method, o, override) {
         var id = el;
         el = util.Dom.get(el);

         var scope = (override) ? o : window;

         if (!el || el.tagName || !el.length) { // is null or not a collection (tagName for SELECT and others that can be both an element and a collection)
            if (!el) {
               return false;
            }
            return method.call(scope, el, o);
         }

         var collection = [];

         for (var i = 0, len = el.length; i < len; ++i) {
            if (!el[i]) {
               id = id[i];
            }
            collection[collection.length] = method.call(scope, el[i], o);
         }

         return collection;
      },

      getDocumentHeight: function() {
         var scrollHeight=-1,windowHeight=-1,bodyHeight=-1;
         var marginTop = parseInt(util.Dom.getStyle(document.body, 'marginTop'), 10);
         var marginBottom = parseInt(util.Dom.getStyle(document.body, 'marginBottom'), 10);

         var mode = document.compatMode;

         if ( (mode || isIE) && !isOpera ) { // (IE, Gecko)
            switch (mode) {
               case 'CSS1Compat': // Standards mode
                  scrollHeight = ((window.innerHeight && window.scrollMaxY) ?  window.innerHeight+window.scrollMaxY : -1);
                  windowHeight = [document.documentElement.clientHeight,self.innerHeight||-1].sort(function(a, b){return(a-b);})[1];
                  bodyHeight = document.body.offsetHeight + marginTop + marginBottom;
                  break;

               default: // Quirks
                  scrollHeight = document.body.scrollHeight;
                  bodyHeight = document.body.clientHeight;
            }
         } else { // Safari & Opera
            scrollHeight = document.documentElement.scrollHeight;
            windowHeight = self.innerHeight;
            bodyHeight = document.documentElement.clientHeight;
         }

         var h = [scrollHeight,windowHeight,bodyHeight].sort(function(a, b){return(a-b);});
         return h[2];
      },

      getDocumentWidth: function() {
         var docWidth=-1,bodyWidth=-1,winWidth=-1;
         var marginRight = parseInt(util.Dom.getStyle(document.body, 'marginRight'), 10);
         var marginLeft = parseInt(util.Dom.getStyle(document.body, 'marginLeft'), 10);

         var mode = document.compatMode;

         if (mode || isIE) { // (IE, Gecko, Opera)
            switch (mode) {
               case 'CSS1Compat': // Standards mode
                  docWidth = document.documentElement.clientWidth;
                  bodyWidth = document.body.offsetWidth + marginLeft + marginRight;
                  break;

               default: // Quirks
                  bodyWidth = document.body.clientWidth;
                  docWidth = document.body.scrollWidth;
                  break;
            }
         } else { // Safari
            docWidth = document.documentElement.clientWidth;
            bodyWidth = document.body.offsetWidth + marginLeft + marginRight;
         }

         var w = Math.max(docWidth, bodyWidth);
         return w;
      },

      getViewportHeight: function() {
         var height = -1;
         var mode = document.compatMode;

         if ( (mode || isIE) && !isOpera ) {
            switch (mode) { // (IE, Gecko)
               case 'CSS1Compat': // Standards mode
                  height = document.documentElement.clientHeight;
                  break;

               default: // Quirks
                  height = document.body.clientHeight;
            }
         } else { // Safari, Opera
            height = self.innerHeight;
         }

         return height;
      },


      getViewportWidth: function() {
         var width = -1;
         var mode = document.compatMode;

         if (mode || isIE) { // (IE, Gecko, Opera)
            switch (mode) {
            case 'CSS1Compat': // Standards mode
               width = document.documentElement.clientWidth;
               break;

            default: // Quirks
               width = document.body.clientWidth;
            }
         } else { // Safari
            width = self.innerWidth;
         }
         return width;
      }
   };
}();

T_demo.util.Region = function(t, r, b, l) {

    this.top = t;

    this[1] = t;

    this.right = r;

    this.bottom = b;

    this.left = l;

    this[0] = l;
};

T_demo.util.Region.prototype.contains = function(region) {
    return ( region.left   >= this.left   &&
             region.right  <= this.right  &&
             region.top    >= this.top    &&
             region.bottom <= this.bottom    );

};

T_demo.util.Region.prototype.getArea = function() {
    return ( (this.bottom - this.top) * (this.right - this.left) );
};

T_demo.util.Region.prototype.intersect = function(region) {
    var t = Math.max( this.top,    region.top    );
    var r = Math.min( this.right,  region.right  );
    var b = Math.min( this.bottom, region.bottom );
    var l = Math.max( this.left,   region.left   );

    if (b >= t && r >= l) {
        return new T_demo.util.Region(t, r, b, l);
    } else {
        return null;
    }
};

T_demo.util.Region.prototype.union = function(region) {
    var t = Math.min( this.top,    region.top    );
    var r = Math.max( this.right,  region.right  );
    var b = Math.max( this.bottom, region.bottom );
    var l = Math.min( this.left,   region.left   );

    return new T_demo.util.Region(t, r, b, l);
};

T_demo.util.Region.prototype.toString = function() {
    return ( "Region {"    +
             "top: "       + this.top    +
             ", right: "   + this.right  +
             ", bottom: "  + this.bottom +
             ", left: "    + this.left   +
             "}" );
};

T_demo.util.Region.getRegion = function(el) {
    var p = T_demo.util.Dom.getXY(el);

    var t = p[1];
    var r = p[0] + el.offsetWidth;
    var b = p[1] + el.offsetHeight;
    var l = p[0];

    return new T_demo.util.Region(t, r, b, l);
};


T_demo.util.Point = function(x, y) {
   if (x instanceof Array) { // accept output from Dom.getXY
      y = x[1];
      x = x[0];
   }


    this.x = this.right = this.left = this[0] = x;

    this.y = this.top = this.bottom = this[1] = y;
};

T_demo.util.Point.prototype = new T_demo.util.Region();

