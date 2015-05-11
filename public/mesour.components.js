/**
 * Mesour Selection Component - selection.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {
    widgets: {}
} : mesour;

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) { return i; }
        }
        return -1;
    };
}

mesour._core = mesour._core ? mesour._core : function() {

    this.createWidget = function(name, instance) {
        mesour[name] = mesour.widgets[name] = instance;
        return instance;
    };

};
mesour.core = mesour.core ? mesour.core : new mesour._core();