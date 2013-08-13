/**
 * @file
 * Replace autocomplete-textfield throbbers with hurricane.
 */

(function($){
  /**
   * Wrap Drupal.jsAC.prototype.setStatus to properly active autocompletion throbber.
   */
  var hurricane_setStatus = Drupal.jsAC.prototype.setStatus;
  Drupal.jsAC.prototype.setStatus = function(status) {
    hurricane_setStatus.apply(this, arguments);
    switch (status) {
      case 'begin':
        $(this.input).parent().find('.throbber').hurricane('start');
        break;

      case 'cancel':
      case 'error':
      case 'found':
        $(this.input).parent().find('.throbber').hurricane('stop');
        break;

    }
  };

  /**
   * Retreive an elements property as float value.
   * @param el
   * @param prop
   * @return {Number}
   */
  function pixels(el, prop) {
    var value = parseFloat($(el).css(prop));
    if (isNaN(value)) {
      value = 0;
    }
    return value;
  }

  /**
   * Attach throbbers to autocomplete textfields.
   */
  Drupal.behaviors.hurricane_autocomplete = {
    attach: function(context, settings) {
      $(document).ready(function() {
        // TODO: find a better solution than polling for visible autocomplete fields
        var count_fields = $('input.form-autocomplete', context).length;
        var check = window.setInterval(function() {
          $('input.form-autocomplete:visible', context).once('hurricane-autocomplete', function() {
            if ($(this).parent().css('position') == 'static') {
              $(this).parent().css('position', 'relative');
            }
            var size = $(this).height();
            var pos = $(this).position();

            var top = pos.top + pixels(this, 'margin-top') + pixels(this, 'border-top-width') + pixels(this, 'padding-top');
            var left = pos.left + pixels(this, 'margin-left') + $(this).outerWidth() - $(this).height() - pixels(this, 'border-right-width') - pixels(this, 'padding-right');

            $wrapper = $('<div class="hurricane-autocomplete ajax-progress"><div class="throbber"></div></div>');
            $wrapper.css({
              height: size,
              width: size,
              top: top,
              left: left
            });

            $throbber = $('.throbber', $wrapper);
            $throbber.css({
              width: size,
              height: size
            });
            $(this).before($wrapper);
            $throbber.hurricane();
            if (--count_fields === 0) {
              window.clearInterval(check);
            }
          });
        }, 500);
      });
    }
  };
}(jQuery));
