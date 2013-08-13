(function ($) {
  Drupal.behaviors.togg = {
    attach: function (context, settings) {
      $("div.fluxkraft-rules-overview--element--header-more").addClass('hidden');
      $('a#js-rules-element-toggle', context).each(function() {
        $(this).click(function() {
          $("div.fluxkraft-rules-overview--element--header-more").toggleClass('hidden');
          $('a#js-rules-element-toggle').toggleClass('icon-chevron-sign-down');
          $('a#js-rules-element-toggle').toggleClass('icon-chevron-sign-up');
          return false;
        });
      });
    }
  };
})(jQuery);
