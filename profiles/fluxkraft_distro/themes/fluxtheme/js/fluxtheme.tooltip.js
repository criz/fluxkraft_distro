/*fluxtheme.tooltip js*/
/*based on http://wordpress.org/plugins/responsive-mobile-friendly-tooltip/*/

  (function ($) {
    Drupal.behaviors.tooltip = {
      attach: function (context, settings) {
        var targets = jQuery( '[rel~=tooltip]' ),
          target  = false,
          tooltip = false,
          title   = false;
        text = false;

        targets.bind( 'mouseenter', function()
        {
          target  = jQuery( this );
          text = target.next();
          tip     = text.html();
          tooltip = jQuery( '<div id="tooltip"></div>' );

          if( !tip || tip == '' )
            return false;

          target.removeAttr( 'title' );
          tooltip.css( 'opacity', 0 )
            .html( tip )
            .appendTo( 'body' );

          var init_tooltip = function()
          {
            if( jQuery( window ).width() < tooltip.outerWidth() * 1.5 )
              tooltip.css( 'max-width', jQuery( window ).width() / 2 );
            else
              tooltip.css( 'max-width', 340 );

            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
              pos_top  = target.offset().top - tooltip.outerHeight() - 20;

            if( pos_left < 0 )
            {
              pos_left = target.offset().left + target.outerWidth() / 2 - 20;
              tooltip.addClass( 'left' );
            }
            else
              tooltip.removeClass( 'left' );

            if( pos_left + tooltip.outerWidth() > jQuery( window ).width() )
            {
              pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
              tooltip.addClass( 'right' );
            }
            else
              tooltip.removeClass( 'right' );

            if( pos_top < 0 )
            {
              var pos_top  = target.offset().top + target.outerHeight();
              tooltip.addClass( 'top' );
            }
            else
              tooltip.removeClass( 'top' );

            tooltip.css( { left: pos_left, top: pos_top } )
              .animate( { top: '+=10', opacity: 1 }, 50 );
          };

          init_tooltip();
          jQuery( window ).resize( init_tooltip );

          var remove_tooltip = function()
          {
            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
            {
              jQuery( this ).remove();
            });

            target.attr( 'title', tip );
          };

          target.bind( 'mouseleave', remove_tooltip );
          tooltip.bind( 'click', remove_tooltip );
        });
      }
    };
  })(jQuery);