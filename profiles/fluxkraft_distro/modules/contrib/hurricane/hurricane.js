/**
 * @file
 * Hurricane jquery plugin.
 */

/**
 * Hurricane jQuery Plugin.
 * Parses the elements CSS properties and invokes the appropriate rendering
 * plugins.
 *
 * @param: string action [redraw|start|stop]
 *   Optional parameter to redraw, start or stop an already existing throbber
 *   on this element.
 */
(function($) {
  // available renderers
  $.hurricane = {};
  var initializing = false;

  // Hurricane extendable base class
  var Hurricane = function(){};

  /**
   * Extend a throbber.
   * Makes "this.callParent()" available to all subclass methods.
   */
  Hurricane.extend = function(prop) {
    var parent = this.prototype;
    initializing = true;
    var prototype = new this();
    initializing = false;
    for (var name in prop) {
      if ($.isFunction(prop[name]) && $.isFunction(parent[name])) {
        prototype[name] = (function(name, fn){
            return function() {
              var tmp = this.callParent;
              this.callParent = parent[name];
              var ret = fn.apply(this, arguments);
              this.callParent = tmp;

              return ret;
            };
          }(name, prop[name]));
      }
      else {
        prototype[name] = prop[name];
      }
    }
    function Base() {
      if (!initializing && this.init) {
        this.init.apply(this, arguments);
      }
    }
    Base.prototype = prototype;
    Base.prototype.constructor = Base;
    Base.extend = arguments.callee;
    return Base;
  };

  // Empty base throbber. Interface definition.
  $.hurricane.base = Hurricane.extend({
    /**
     * Constructor.
     * @param element
     */
    init: function (element) {
      this.$el = $(element);
    },

    /**
     * Destructor.
     * Clean up anything inside the element here.
     */
    destroy: function() {
      this.$el.children().remove();
    },

    /**
     * Setup throbber according to the parameters passed.
     * Should leave throbber in "stopped" state.
     * @param options
     */
    setup: function (options) {},

    /**
     * Start throbber animation.
     * @param options
     */
    start: function (options) {},

    /**
     * Return throbber to "stopped" state.
     * @param options
     */
    stop: function (options) {}
  });

  /**
   * Retrieve a specific renderer.
   * @param font
   *   the font to search for
   * @return {*}
   *   a renderer object
   */
  $.hurricaneRenderer = function (font) {
    if (typeof $.hurricane[font] === 'undefined') {
      return $.hurricane.drop;
    }
    return $.hurricane[font];
  };

  /**
   * Adds a centered spinner to the element.
   */
  $.fn.hurricane = function(operation) {

    // Iterate over all targeted elements.
    $(this).each(function() {

      // "Redraw" action. Deletes a throbber that might already be added
      // to this element.
      if (operation === 'redraw' && this.hurricane) {
        this.hurricane.destroy();
        this.hurricane = false;
      }

      // If there is no throbber, create a new one.
      if (typeof this.hurricane === 'undefined' || this.hurricane === false) {
        $root = $(this);

        // Reset all inline style definitions, to retrieve the ones defined
        // in CSS Sheets
        $.each(Drupal.settings.hurricane.map, function(property, info){
          $root.css(property, '');
        });

        // Retrieve the renderer class based on font-family property.
        var font = $(this).css('font-family');
        var renderer = $.hurricaneRenderer(font);
        if (!renderer) {
          return;
        }
        var options = {};

        // Iterate over all readable properties and write them normalized to
        // options object.
        $.each(Drupal.settings.hurricane.map, function(property, info){
          if (property === 'font-family') {
            return;
          }
          $root.css(property, '');
          if (typeof info === 'object') {
            var value = $root.css(property);
            if (property === 'font-weight') {
              if (value === 'normal') {
                value = 400;
              }
              else if (value === 'bold') {
                value = 700;
              }
              else {
                value = parseInt(value);
              }
            }
            options[property] = $.inArray(value, info);
          }
          else if (info) {
            options[property] = parseInt($root.css(property));
          }
          else {
            options[property] = $root.css(property);
          }
        });

        // If root element is static, set positioning to relative, since
        // the throbber is positioned absolute inside.
        if ($root.css('position') === 'static') {
          $root.css('position', 'relative');
        }

        // Create new throbber.
        this.hurricane = new renderer(this);
        // Call setup hook.
        this.hurricane.setup(options);

        // Set inline style properties to "zero" values, so they don't interfere
        // with the elements display.
        $.each(Drupal.settings.hurricane.map, function(property, info){
          if (property === 'line-height' || property === 'font-size') {
            $root.css(property, '0px');
          }
          if (property === 'background-color') {
            $root.css(property, 'transparent');
          }
          else {
            $root.css(property, 'inherit');
          }
        });
      }

      // Invoke "start()" if operation "start" is called.
      if (operation === 'start') {
        this.hurricane.start();
      }

      // Invoke "stop()" if operation "stop" is called.
      if (operation === 'stop') {
        this.hurricane.stop();
      }
    });
    return this;
  };
}(jQuery));
