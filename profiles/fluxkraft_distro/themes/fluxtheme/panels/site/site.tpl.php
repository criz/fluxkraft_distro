<?php
/**
 * @file
 * Adaptivetheme implementation to present a Panels layout.
 */
?>
<?php if ($attributes): ?>
<div<?php print $attributes; ?>>
<?php endif; ?>
<?php if ($content['header']): ?>
  <header role="banner">
    <div class="l-header">
      <?php print $content['header']; ?>
    </div>
  </header>
<?php endif; ?>

<div class="l-main-content" role="main">
  <a id="main-content-anchor"></a>
  <?php print $content['main']; ?>
</div>
<footer role="contentinfo" class="footer">
  <div class="l-footer--columns footer--columns">
    <div class="l-footer-columns">
      <?php if ($content['footer_col1']): ?>
        <div class="l-footer-columns__left">
          <?php print $content['footer_col1']; ?>
        </div>
      <?php endif; ?>
      <?php if ($content['footer_col2']): ?>
        <div class="l-footer-columns__middle_left">
          <?php print $content['footer_col2']; ?>
        </div>
      <?php endif; ?>
       <?php if ($content['footer_col3']): ?>
        <div class="l-footer-columns__middle_right">
          <?php print $content['footer_col3']; ?>
        </div>
      <?php endif; ?>
      <?php if ($content['footer_col4']): ?>
        <div class="l-footer-columns__right">
          <?php print $content['footer_col4']; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="l-footer--bottom footer--bottom">
    <?php print $content['bottom']; ?>
  </div>
</footer>

<?php if ($attributes): ?>
<div>
<?php endif; ?>