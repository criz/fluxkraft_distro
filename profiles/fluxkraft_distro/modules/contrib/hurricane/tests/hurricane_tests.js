/**
 * @file
 * Start/Stop behavior for the test-throbber.
 */
(function($){
  Drupal.behaviors.hurricane_tests = {
    attach: function (context, settings) {
      $('.hurricane-test-throbber .throbber').hurricane().mouseenter(function(){
        $(this).hurricane('start');
      }).mouseleave(function(){
        $(this).hurricane('stop');
      });
    }
  };
}(jQuery));
