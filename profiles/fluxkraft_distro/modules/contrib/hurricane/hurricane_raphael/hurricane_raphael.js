/**
 * @file
 * Base class for raphael-based renderers.
 */
(function($){
  // raphael based renderers
  $.hurricane.raphael = $.hurricane.base.extend({
    init: function (el) {
      var width = $(el).width();
      var height = $(el).height();
      var size = Math.min(width, height);
      this.paper = Raphael(el, size, size);
      $(this.paper.canvas).css('position', 'absolute');
    },
    setup: function(options) {
      this.paper.setViewBox(0, 0, 128, 128, true);
    },
    destroy: function() {
      this.paper.remove();
    }
  });
}(jQuery));
