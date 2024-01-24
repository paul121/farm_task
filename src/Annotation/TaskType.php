<?php

namespace Drupal\farm_task\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the task type plugin annotation object.
 *
 * Plugin namespace: Plugin\Task\AssetType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class TaskType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The task type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
