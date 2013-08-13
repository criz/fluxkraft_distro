<?php
/**
 * @file
 * Implementation to present a Panels layout.
 */
?>
<div<?php print $attributes; ?>>
  <?php
  // Add contextual links if rendered via display suite.
  if (isset($title_suffix['contextual_links'])): ?>
    <?php print render($title_suffix['contextual_links']); ?>
  <?php endif; ?>

  <?php print $content['naked']; ?>
</div>
