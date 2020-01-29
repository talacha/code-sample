/**
 * Custom logic related to the layout and grid.
 */
(function gridBehaviors(Drupal, $) {
  'use strict';

  Drupal.setupStickyHeader('.site__header');

  $('nav.toolbar-bar').click(function toolbarClick() {
    Drupal.setupStickyHeader('.site__header');
  });
})(Drupal, jQuery);
