<?php
/**
 * @file
 * Twig Filter for markdown.
 */

use \Michelf\MarkdownExtra;

$filter = new Twig_SimpleFilter('markdown', function ($string) {
  return MarkdownExtra::defaultTransform($string);
});
