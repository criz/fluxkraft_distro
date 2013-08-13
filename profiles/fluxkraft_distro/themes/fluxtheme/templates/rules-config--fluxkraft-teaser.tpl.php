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

<div class="fluxkraft-rules-overview--element--header">
  <div class="fluxkraft-rules-overview--element--header--info l-fluxkraft-rules-overview--element--main">
    <?php if (!$page): ?>
      <h2 class="title">
        <?php if ($url): ?>
          <a href="<?php print $url; ?>"><?php print $title; ?></a>
        <?php else: ?>
          <?php print $title; ?>
        <?php endif; ?>
      </h2>
      <?php /*<a id="js-rules-element-toggle" class="show-more icon-chevron-sign-down"><span>Show more...</span></a> */ ?>
    <?php endif; ?>
    <div class="fluxkraft-rules-tags">
      <?php foreach ($tags as $tag) { ?>
        <span class="tag"><i class="icon icon-tag"></i><?php print $tag; ?></span>
      <?php } ?>
    </div>
  </div>
  <div class="fluxkraft-rules-overview--element--header--status l-fluxkraft-rules-overview--element--aside">
    <?php print $activation_status; ?>
  </div>
</div>
<?php /*
<div class="fluxkraft-rules-overview--element--header-more">
  <div class="fluxkraft-rules-overview--element--header--info l-fluxkraft-rules-overview--element--main">
    <span class="label">Author:</span> <?php print $author; ?><br />
  </div>
  <div class="fluxkraft-rules-overview--element--header--status l-fluxkraft-rules-overview--element--aside">
    <span class="label">Status:</span> <?php print $status; ?>
  </div>
</div>
 */ ?>
<div class="fluxkraft-rules-overview--element--content">
  <div class="fluxkraft-rules-overview--element--content--info l-fluxkraft-rules-overview--element--main">
    <?php if (!empty($content['events'])): ?>
    <div class="fluxkraft-rules-events">
      <span class="fluxkraft-rules-element-type">When</span>
      <?php print render($content['events']); ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($content['conditions'])): ?>
    <div class="fluxkraft-rules-conditions">
      <span class="fluxkraft-rules-element-type">Only when</span>
      <?php print render($content['conditions']); ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($content['actions'])): ?>
    <div class="fluxkraft-rules-arrow-sign icon-chevron-right">&nbsp;</div>
    <div class="fluxkraft-rules-actions">
      <span class="fluxkraft-rules-element-type">Then</span>
      <?php print render($content['actions']); ?>
    </div>
    <?php endif; ?>

  </div>
  <div class="fluxkraft-rules-overview--element--content--operations l-fluxkraft-rules-overview--element--aside">
    <?php foreach ($operations as $op) { ?>
      <span<?php print $operation_attributes; ?>><?php print $op; ?></span>
    <?php } ?>
  </div>
</div>