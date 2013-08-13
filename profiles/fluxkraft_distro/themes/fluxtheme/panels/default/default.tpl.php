<?php
/**
 * @file
 * Adaptivetheme implementation to present a Panels layout.
 */
?>
<div<?php print $attributes; ?>>
  <?php if ($content['header']): ?>
  <div class="l-content-header-container">
    <div class="l-container-inner">
      <?php print $content['header']; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="l-content-container">
    <?php if ($content['sidebar-first']): ?>
    <div class="l-sidebar-first-container sidebar-first">
      <div class="content-wrapper">
        <?php print $content['sidebar-first']; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="l-main-content">
      <?php print $content['main']; ?>
    </div>

    <?php if ($content['sidebar-second']): ?>
    <div class="l-sidebar-second-container sidebar-second">
      <div class="content-wrapper">
        <?php print $content['sidebar-second']; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <?php if ($content['footer']): ?>
  <div class="l-content-footer-container">
    <div class="l-container-inner">
      <?php print $content['footer']; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
