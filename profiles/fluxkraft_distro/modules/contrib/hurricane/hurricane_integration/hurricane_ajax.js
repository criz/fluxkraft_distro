/**
 * @file
 * Wraps Drupal.ajax.prototype.beforeSend to replace throbbers with hurricane.
 */

(function($){
  var hurricane_beforesend = Drupal.ajax.prototype.beforeSend;
  Drupal.ajax.prototype.beforeSend = function (xmlhttprequest, options) {
    hurricane_beforesend.apply(this, [xmlhttprequest, options]);
    $(this.element).parent().find('.throbber').hurricane('start');
  };
}(jQuery));
