/**
 * @file
 * "hurricane" hurricane renderer
 */
(function($) {
  $.hurricane.hurricane = $.hurricane.raphael.extend({
    setup: function(options) {
      // TODO: Find better fix for vml rotation center.
      var center = document.addEventListener ? 64 : 59;

      var deg = 360 / options['letter-spacing'];
      var height = options['line-height'] * 64 / 100;
      var width = height * options['font-size'] / 100;
      this.lines = [];

      for (var i = 0; i < options['letter-spacing']; i++) {
        var line = this.paper.rect(64 - width / 2, 0, width, height).attr({
          'stroke': 'none',
          'fill': options['color'],
          'opacity': 0.2,
          'r': width * options['text-indent'] / 200
        });
        line.rotate(deg * i + deg / 2, center, center);
        this.lines.push(line);
      }

      this.step = 0.8 / Math.floor(options['letter-spacing'] * (options['font-weight'] / 8));
      this.op = [];
      this.timer = false;
      this.speed = 1000 / ((options['word-spacing'] / 25) * options['letter-spacing']);
      this.round = 0;
      this.stopped = true;
      this.callParent(options);
    },

    tick: function() {
      if (this.stopped && this.round === 0) {
        this.op.pop();
        this.op.unshift(0);
      } else {
        this.op.unshift(this.op.pop());
        this.round = ++this.round % this.lines.length;
      }
      var done = true;
      for (var i = 0; i < this.op.length; i++) {
        if (this.op[i] > 0) {
          done = false;
        }
        this.lines[i].animate({
          opacity: 0.2 + Math.max(this.op[i], 0)
        }, this.speed);
      }
      if (done) {
        window.clearInterval(this.timer);
        this.timer = false;
      }
    },

    start: function() {
      if ((!this.stopped) || this.timer) {
        return;
      }
      this.stopped = false;
      this.op = [];
      for (var i = this.lines.length - 1; i >= 0; i--) {
        this.op.push(1 - i * this.step);
      }
      var that = this;
      this.timer = window.setInterval(function(){
        that.tick();
      }, this.speed);
    },

    stop: function() {
      this.stopped = true;
    }
  });
}(jQuery));
