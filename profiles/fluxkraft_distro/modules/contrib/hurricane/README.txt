# Hurricane
Drupal 7 module to add CSS-styleable loading spinners.

## What does it do?
Provides easily styleable and extendable **throbbers** (the spinning AJAX
progress indicator) and optionally replaces the Drupal standard throbber.

## Installation
Copy the *[libraries]* and *hurricane* modules into a Drupal `modules` folder
and either the [Raphaël][raphael] or [Spin.js][spinjs] library into a
`libraries` folder. Hurricane consists of a core module and three extensions
which may be activated independently:

- **hurricane:** The main module, providing the hurricane jQuery plugin.
  Required by all other modules.
- **hurricane_integration:** Deeper integration of hurricane into Drupal.
  Automatically replaces Drupal Ajax throbbers with hurricane and provides theme
  settings. *Without this module, you won't see any throbbers unless you attach
  them manually, by using the `$.hurricane()` jQuery plugin in your javascript
  code!*
- **hurricane_raphael:** [Raphaël][raphael] based SVG/VML renderers. Requires
  [Raphaël][raphael] library to be installed in a libraries compliant subfolder.
- **hurricane_spinjs:** Renderer based on [Spin.js][spinjs]. Requires
  [Spin.js][spinjs] library to be installed in a libraries compliant subfolder.
- **hurricane_tests:** A test page `hurricane/test` which displays hurricane
  renderers configured in a theme.

### Libraries
Hurricane may use [Raphaël][raphael] or [Spin.js][spinjs] to draw more
sophisticated throbber animations. They are both optional, but if you want to
try out Hurricane, you want them both. For production you will use either one of
them or a custom renderer you write yourself.

Download [raphael-min.js][raphaelmin] and place it in a `raphael` subdirectory
in any of these libraries folders.

    sites/all/libraries/raphael/raphael-min.js
    sites/[mydomain.com]/libraries/raphael/raphael-min.js
    profiles/[myprofile]/libraries/raphael/raphael-min.js

There is a [raphael module][raphmod] for drupal, which has not been updated for
a long time and is currently not compatible with hurricane. I'm looking for a
way to fix this.

For using [Spin.js][spinjs], download [spin.js][spinjsmin] and place it in
a `spinjs` libraries folder.

    sites/all/libraries/spinjs/spin.js
    sites/[mydomain.com]/libraries/spinjs/spin.js
    profiles/[myprofile]/libraries/spinjs/spin.js

## Usage
Activate `hurricane`, `hurricane_integration` and `hurricane_raphael` /
`hurricane_spinjs`, navigate to your theme's settings page and configure
available renderers. After saving the available renderers setting an additional
group appears, where the appearance of the standard renderer may be configured.
Renderers may be styled independently in the CSS sheet. A CSS-Snippet is
generated on the fly in the appearance configurations *"Export"* group.

### Included renderers
Hurricane uses replacable plugins, called *renderers*, to draw the animated
throbber. `hurricane` itself provides the *Drop*, a simple pulsating CSS3 dot.
Nothing fancy.

With `hurricane_raphael` the *Hurricane* and *Twister* renderers are
enabled. Additionally it includes a new class for [Raphaël][raphael] based
renderers for you to build upon.
[Raphaël][raphael] is quite big (31Kb gzipped), and if you don't use it in your
project anyway, the throbbers alone may not justify the additional filesize.
Your choice.

`hurricane_spinjs` uses the [Spin.js][spinjs] library to provide a throbber
called *Spin.js*, similiar to the *Hurricane* included in `hurricane_raphael`.
Far more lightweight, but less fancy.

I'm planning to add more renderer animations out of the box. Suggestions are
welcome!

## Extend
To add a completely new renderer, a module has to implement
`hook_hurricane_renderers()`, which returns an array of machine readable
identifiers associated with information about the new renderer:

- **label:** A human readable label for the renderer.
- **parameters:** CSS parameters the renderer accepts, as an array with
  CSS-properties as keys and information arrays as values.
	- *label:* Human readable title, describing what the property is used for.
	- *default:* A default value for this property.
- **js/css/library:** Attach Javascript and CSS resources which should be
  loaded for this renderer. Behaves exactly like [\#attached][attached] in
  Forms. At least one javascript file, containing the renderers implementation,
  should be registered.

The javascript implementation of the renderer has to extend `$.hurricane.base`,
which defines the following interface:

- `init(element)`: The constructor. The document element the throbber should be
  rendered into is passed as argument.
- `setup()`: Called after construction. Parsed CSS-properties are passed as
  `options` argument.
- `start()`: The throbber should start to spin, jump, dance or whatever you
  have in mind.
- `stop()`: Tells the throbber to stand still again.
- `destroy()`: Called before the throbber is hidden, to do any necessary cleanup
  work.

### Parameters
These CSS-Properties and according values are available as parameters.

- `line-height`: 0 - 100
- `text-indent`: 0 - 100
- `word-spacing`: 0 - 100
- `letter-spacing`: 0 - 100
- `font-size`: 0 - 100
- `font-weight`: 100 | 200 | 300 | 400 | 500 | 600 | 700 | 800 | 900 
- `text-transform`: none | capitalize | uppercase
- `text-decoration`: none | underline | overline |  line-through | blink
- `text-align`: left | right | center | justify
- `white-space`: normal | pre | nowrap | pre-wrap | pre-line
- `font-style`: normal | italic | oblique
- `color`: *any hex color value*
- `background-color`: *any hex color value*

### Example
Implementation of `hook_hurricane_renderers()`:

    function mymodule_hurricane_renderers() {
      return array(
        'my_throbber' => array(
          'label' => t('My Throbber'),
          'js' => array(
            'path/to/mymodule/mythrobber.js' => array(),
          ),
          'parameters' => array(
            'line-height' => array('label' => t('Size'), 'default' => 50),
            'color' => array('label' => t('Color'), 'default' => '#3182c5'),
          ),
        ),
      );
    }

Javascript implementation in `mythrobber.js`. For more sophisticated examples
look at `hurricane/drop.js` or `hurricane/hurricane_raphael/hurricane.js`.

    (function($) {
      $.hurricane.mythrobber = $.hurricane.base.extend({
        init: function (element) {
          // Prepare and store the anchor element.
        },
        setup: function (options) {
          // Interpret and store options.
        },
        start: function () {
          // Dance like the world is ending!
        },
        stop: function () {
          // Stop dancing!
        },
        destroy: function () {
          // Clean up allocated objects.
        }
      });
    }(jQuery));

*The module has been developed by [Philipp Melab][pm]
and sponsored by [Zensations - web & communcations][zen].*

[spinjs]: http://fgnass.github.com/spin.js/ "Spin.js"
[spinjsmin]: http://fgnass.github.com/spin.js/dist/spin.js "Spin.js Source"
[raphael]: http://raphaeljs.com/ "Raphaël"
[raphaelmin]: http://tinyurl.com/bwp4wam "Raphaël Source"
[raphmod]: http://drupal.org/project/raphael "Raphaël module on drupal.org"
[zen]: http://www.zensations.at "Zensations - web & communications"
[pm]: http://drupal.org/user/555322 "User Profile: Philipp Melab"
[attached]: http://tinyurl.com/cxzn4cp "Drupal Form API Reference: #attached"
[libraries]: http://drupal.org/project/libraries "Libraries API"
