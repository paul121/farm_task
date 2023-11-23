<?php

namespace Drupal\farm_task\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\RevisionableEntityBundleInterface;

/**
 * Interface for task type entities.
 */
interface TaskTypeInterface extends ConfigEntityInterface, RevisionableEntityBundleInterface {}
