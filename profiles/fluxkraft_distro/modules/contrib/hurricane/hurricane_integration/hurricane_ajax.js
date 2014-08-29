/**
 * @file
 * Wraps Drupal.ajax.prototype.beforeSend to replace throbbers with hurricane.
 */

(function($){
  // Initialize hurricane on the newly created throbber element right after
  // its been attached by Drupal.ajax.beforeSend.
  // Attaches the class ".form-ajax-progress" to the .form-item wrapper
  // element.
  var hurricane_beforesend = Drupal.ajax.prototype.beforeSend;
  Drupal.ajax.prototype.beforeSend = function (xmlhttprequest, options) {
    hurricane_beforesend.apply(this, [xmlhttprequest, options]);
    var $wrapper = $(this.element).parents('.form-item').first();
    $wrapper.addClass('form-ajax-progress');
    $wrapper.find('.throbber').hurricane('start');
  };

  // Remove ".form-ajax-progress" from the wrapping elements
  // on success or error.
  var hurricane_success = Drupal.ajax.prototype.success;
  Drupal.ajax.prototype.success = function(response, status) {
    var $wrapper = $(this.element).parents('.form-item').first();
    $wrapper.removeClass('form-ajax-progress');
    hurricane_success.apply(this, [response, status]);
  };

  var hurricane_error = Drupal.ajax.prototype.error;
  Drupal.ajax.prototype.error = function(response, uri) {
    var $wrapper = $(this.element).parents('.form-item').first();
    $wrapper.removeClass('form-ajax-progress');
    hurricane_error.apply(this, [response, uri]);
  };
}(jQuery));
