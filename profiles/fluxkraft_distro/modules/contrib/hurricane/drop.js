/**
 * @file
 * Provides Hurricane default renderer "drop".
 */
(function($){
  $.hurricane.drop = $.hurricane.base.extend({
    setup: function(options) {
      this.$dot = $('<div></div>').css({
        position: 'absolute',
        top: '0px',
        left: '0px',
        width: this.$el.width(),
        height: this.$el.height()
      });
      this.$el.append(this.$dot);
      this.stopped = true;
      this.speed = 15000 / options['word-spacing'];
      this.active  = this.rgb2hex(options['color']);
      this.inactive  = this.rgb2hex(options['background-color']);
      var radius = this.$dot.width() / 2;
      var speed = this.speed / 1000;
      var css = {
        'background-color': this.inactive
      };
      $.each(['', '-webkit-', '-moz-', '-ms-', '-o-'], function(index, prefix){
        css[prefix + 'border-radius'] = radius + 'px';
        css[prefix + 'transition'] = 'background-color ' + speed + 's ease';
      });
      this.$dot.css(css);
    },

    rgb2hex: function (rgb) {
      var match = rgb.match(/^rgb[a?]\((\d+),\s*(\d+),\s*(\d+)\)$/);
      if (!match) {
        return rgb;
      }
      function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
      }
      return "#" + hex(match[1]) + hex(match[2]) + hex(match[3]);
    },

    pulseUp: function () {
      if (this.stopped) {
        return;
      }
      var that = this;
      this.$dot.css({
        'background-color': this.active
      });
      window.setTimeout(function(){
        that.pulseDown();
      }, this.speed);
    },

    pulseDown: function () {
      var that = this;
      this.$dot.css({
        'background-color': this.inactive
      });
      window.setTimeout(function(){
        that.pulseUp();
      }, this.speed);
    },

    start: function () {
      if (this.stopped) {
        this.stopped = false;
        this.pulseUp();
      }
    },

    stop: function () {
      this.stopped = true;
    }
  });
}(jQuery));
