<?php

namespace Drupal\farm_task\Bundle;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_task\Entity\Task;

/**
 *
 */
class LogTemplateTask extends Task {

  public function getTargetFieldName() {
    return 'log_value';
  }

  public function getComponentConfiguration() {
    return [
      'type' => 'farm_template_entity_reference',
      'settings' => [
        'template' => $this->get('template')->entity->id(),
      ],
    ];
  }

}
