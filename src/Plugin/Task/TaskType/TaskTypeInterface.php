<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for task types.
 */
interface TaskTypeInterface extends BundlePluginInterface {

  /**
   * Gets the task type label.
   *
   * @return string
   *   The task type label.
   */
  public function getLabel();

}
