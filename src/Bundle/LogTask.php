<?php

namespace Drupal\farm_task\Bundle;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_task\Entity\Task;

/**
 * Log task.
 */
class LogTask extends Task {

  /**
   * {@inheritdoc}
   */
  public function getComponentConfiguration(): array {
    return [
      'type' => 'inline_entity_form_complex',
      'settings' => [
        'form_mode' => 'default',
        'revision' => TRUE,
        'allow_new' => TRUE,
        'allow_existing' => TRUE,
        'allow_duplicate' => FALSE,
      ],
    ];
  }

}
