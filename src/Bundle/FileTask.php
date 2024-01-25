<?php

namespace Drupal\farm_task\Bundle;

use Drupal\farm_task\Entity\Task;

/**
 * File task.
 */
class FileTask extends Task {

  public function getTargetFieldName(): string {
    return 'file_value';
  }

}
