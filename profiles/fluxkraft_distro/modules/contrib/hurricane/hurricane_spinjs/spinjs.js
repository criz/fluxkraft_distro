/**
 * @file
 * Spin.js hurricane renderer.
 */
(function($){
  $.hurricane.spinjs = $.hurricane.base.extend({
    setup: function (options) {
      var radius = this.$el.width() / 2;
      var length = radius * (options['line-height'] / 100);
      var thickness = length * options['font-size'] / 100;
      this.speed = options['word-spacing'] / 25;
      this.opts = {
        lines: options['letter-spacing'],
        length: length - thickness,
        width: thickness,
        radius: radius * (1 - options['line-height'] / 100),
        corners: options['text-indent'] / 100,
        rotate: 0,
        color: options['color'],
        speed: 0,
        trail: options['font-weight'] * 10,
        shadow: options['text-decoration'] > 1,
        hwaccel: options['text-decoration'] > 2,
        className: 'spinner',
        zIndex: 0,
        top: 'auto',
        left: 'auto'
      };
      this.spinner = new Spinner(this.opts).spin(this.$el[0]);
    },

    destroy: function() {
      this.spinner.stop();
    },

    start: function() {
      this.spinner.stop();
      var o = $.extend({}, this.opts, {speed: this.speed});
      this.spinner = new Spinner(o).spin(this.$el[0]);
    },

    stop: function() {
      this.spinner.stop();
      this.spinner = new Spinner(this.opts).spin(this.$el[0]);
    }
  });
}(jQuery));
