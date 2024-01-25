<?php

namespace Drupal\farm_task\Bundle;

use Drupal\farm_task\Entity\Task;

/**
 * Asset task.
 */
class AssetTask extends Task {

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
