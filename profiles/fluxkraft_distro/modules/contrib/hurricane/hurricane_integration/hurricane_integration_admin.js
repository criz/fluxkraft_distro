/**
 * @file
 * Hurricane theme administration settings javascript behaviors.
 */
(function($){
  Drupal.behaviors.hurricane_integration = {
    attach: function(context, settings) {
      $('.hurricane-preview', context).once('hurricane-configuration', function(){
        var first_refresh = true;
        $font = $('#edit-hurricane-font-family', context);
        $font.change(function(){

          var params = settings.hurricane.parameters[$(this).val()];
          $.each(Drupal.settings.hurricane.map, function(property, info) {
            if (property === 'font-family') {
              return;
            }
            $item = $('.form-item-hurricane-' + property, context);
            if (typeof params[property] === 'undefined') {
              $item.hide();
              return;
            }
            if (typeof params[property].label !== 'undefined') {
              $('label', $item).text(params[property].label);
            }
            $item.show();
            if (!first_refresh) {
              if (typeof params[property].default !== 'undefined') {
                $('input', $item).val(params[property].default).change();
              }
            }
          });
          refresh(true);
        });

        /**
         * attach sliders
         */
        $('input.slider', context).each(function(){
          $input = $(this);
          $slider = $('<div class="slider"></div>');
          var map = Drupal.settings.hurricane.map[this.name.replace('hurricane_', '')];
          if (typeof map === 'undefined') {
            return;
          }
          $input.after($slider);
          $slider.slider({
            min: 1,
            max: (typeof map === 'object') ? (map.length - 1) : 100,
            step: 1,
            value: $input.val(),
            slide: function(event, slider) {
              $(this).parent().find('input').val(slider.value);
              $(this).parent().find('input').change();
            }
          });
          $input.change(function(){
            var $s = $(this).parent().find('.slider');
            if ($s.slider('value') !== $(this).val()) {
              $s.slider('value', $(this).val());
            }
            refresh();
          });
        });

        /**
         * attach farbtastic
         */
        var $farb = $('<div class="hurricane-farbtastic"></div>').prependTo('.hurricane-properties');
        var farbtastic = $.farbtastic('.hurricane-farbtastic');

        $('input.color').each(function(){
          $(this).change(function(){
            $(this).css({
              backgroundColor: this.value,
              'color': farbtastic.RGBToHSL(farbtastic.unpack(this.value))[2] > 0.5 ? '#000' : '#fff'
            });
          });
          $(this).css({
            backgroundColor: this.value,
            'color': farbtastic.RGBToHSL(farbtastic.unpack(this.value))[2] > 0.5 ? '#000' : '#fff'
          });
        });

        $('input.color', context).blur(function(){
          $farb.hide();
        });

        $('input.color', context).focus(function(){
          $farb.show();
          $farb.css({
            left: $(this).position().left + $(this).outerWidth() + 10,
            top: $(this).position().top - $farb.outerHeight() / 2 + $(this).outerHeight() / 2
          });
          $input = $(this);
          farbtastic.linkTo(function (color) {
            $($input).css({
              backgroundColor: color,
              'color': this.RGBToHSL(this.unpack(color))[2] > 0.5 ? '#000' : '#fff'
            });
            $input.val(color);
            refresh();
          });
          $input.change(function(){
            farbtastic.setColor(this.value);
          });
          farbtastic.setColor(this.value);
        });

        /**
         * Add preview spinner.
         */
        $(this).append('<div class="preview-spinner large ajax-progress"><div class="throbber"></div></div>');
        $(this).append('<div class="preview-spinner medium ajax-progress"><div class="throbber"></div></div>');
        $(this).append('<div class="preview-spinner small ajax-progress"><div class="throbber"></div></div>');
        $('.preview-spinner .throbber', this).mouseenter(function(){
          $(this).hurricane('start');
        }).mouseleave(function(){
          $(this).hurricane('stop');
        });

        /**
         * Generate CSS string for current configuration.
         */
        var generateCSS = function () {
          var css = '.ajax-progress div.throbber {\n';
          $.each(Drupal.settings.hurricane.map, function(property, info) {
            if (!$('#edit-hurricane-' + property).is(':visible')) {
              return;
            }
            var value = $('#edit-hurricane-' + property, context).val();
            if (typeof info === 'object' && property !== 'font-family') {
              value = Drupal.settings.hurricane.map[property][value];
            }
            else if (property === 'font-family') {
              value = value + ' !important'
            }
            else if (info === true) {
              value = parseInt(value) + 'px';
            }
            if (!value) {
              return;
            }
            css += '  ' + property + ': ' + value + ';\n'
          });
          css += '}';
          return css;
        };

        /**
         * Generate info string for current configuration.
         */
        var generateInfo = function() {
          var text = '';
          $.each(Drupal.settings.hurricane.map, function(property, info) {
            var value = $('#edit-hurricane-' + property, context).val();
            if (!value) {
              return;
            }
            text += 'settings[hurricane_' + property + '] = ' + value + '\n';
          });
          return text;
        };

        /**
         * Refresh method.
         */
        var refresh_timeout = false;
        var refresh = function(immediatly) {
          var timeout = 500;
          if (immediatly) {
            timeout = 0;
          }
          if (refresh_timeout) {
            window.clearTimeout(refresh_timeout);
          }
          refresh_timeout = window.setTimeout(function(){
            $('head style#hurricane-preview-styles').remove();
            $style = $('<style>' + generateCSS() + '</style>').attr({
              type: 'text/css',
              id: 'hurricane-preview-styles'
            });
            $('head').append($style);
            $('fieldset#edit-hurricane-integration .ajax-progress .throbber').hurricane('redraw');
            $('.hurricane-css pre', context).text(generateCSS());
            $('.hurricane-info pre', context).text(generateInfo());
          }, timeout);
        };

        // initial refresh
        $font.change();
        first_refresh = false;
      });
    }
  };
}(jQuery));
