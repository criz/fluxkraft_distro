<?php

/**
 * @file
 * Template for Rules configurations rendered in the fluxkraft-rules UI.
 *
 * Available variables:
 * - $content: An array of comment items. Use render($content) to print them all, or
 *   print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $title: The (sanitized) entity label.
 * - $url: Direct url of the current entity if specified.
 * - $page: Flag for the full page state.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. By default the following classes are available, where
 *   the parts enclosed by {} are replaced by the appropriate values:
 *   - entity-{ENTITY_TYPE}
 *   - {ENTITY_TYPE}-{BUNDLE}
 *
 * Other variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * @see fluxkraft_rules_template_preprocess_entity()
 */
?>

<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>>
      <?php if ($url): ?>
        <a href="<?php print $url; ?>"><?php print $title; ?></a>
      <?php else: ?>
        <?php print $title; ?>
      <?php endif; ?>
    </h2>
  <?php endif; ?>

  <?php
  foreach ($tags as $tag) { ?>
    <span<?php print $tag_attributes; ?>><?php print $tag; ?></span>
  <?php } ?>

  <div class="fluxkraft-rules-activation-status"><?php print $activation_status; ?></div>
  <div class="fluxkraft-rules-author"><?php print $author; ?></div>

  <?php
  foreach ($operations as $op) { ?>
    <span<?php print $operation_attributes; ?>><?php print $op; ?></span>
  <?php } ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      print render($content);
    ?>
  </div>
</div>