<?php

namespace Drupal\farm_task\Bundle;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_task\Entity\Task;

/**
 *
 */
class DocumentTask extends Task {

  public function getTargetFieldName() {
    return 'file_value';
  }

}
