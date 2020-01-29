<?php
/**
 * @file
 * Twig Function for partitioning arrays.
 */

$function = new Twig_SimpleFunction('partition', function ($items, $num_partitions = 1, $direction = 'column') {
  $items_length = count($items);
  $min_items_per_partition = floor($items_length / $num_partitions);
  $remainder = $items_length % $num_partitions;
  $partitions = [];
  if ($direction === 'column') {
    $mark = 0;
    for ($i = 0; $i < $num_partitions; $i++) {
      $num_items = ($i < $remainder) ? $min_items_per_partition + 1 : $min_items_per_partition;
      $partitions[$i] = array_slice($items, $mark, $num_items);
      $mark += $num_items;
    }
  }
  if ($direction === 'row') {
    $current_col = 1;
    foreach ($items as $item) {
      if ($current_col > $num_partitions) {
        $current_col = 1;
      }
      $partitions[$current_col][] = $item;
      $current_col += 1;
    }
  }

  return $partitions;
});
