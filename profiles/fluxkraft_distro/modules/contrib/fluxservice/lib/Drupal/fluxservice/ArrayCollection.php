<?php
/**
 * @file
 * Contains ArrayCollection.
 */

namespace Drupal\fluxservice;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Provides an array collection with merge functionality.
 */
class ArrayCollection extends DoctrineArrayCollection {

  /**
   * Merge in another array.
   *
   * @param array $array
   *   The array to merge.
   * @param bool $overwrite
   *   (optional) Whether to overwrite existing values in case of overlapping
   *   keys. Defaults to TRUE.
   */
  public function mergeArray(array $array, $overwrite = TRUE) {
    foreach ($array as $key => $value) {
      if ($overwrite || !$this->containsKey($key)) {
        $this->set($key, $value);
      }
    }
  }

  /**
   * Merge in another collection.
   *
   * @param Collection $collection
   *   The collection to merge.
   * @param bool $overwrite
   *   (optional) Whether to overwrite existing values in case of overlapping
   *   keys. Defaults to TRUE.
   */
  public function merge(Collection $collection, $overwrite = TRUE) {
    foreach ($collection as $key => $value) {
      if ($overwrite || !$this->containsKey($key)) {
        $this->set($key, $value);
      }
    }
  }

}