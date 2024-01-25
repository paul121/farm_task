<?php

namespace Drupal\farm_task\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Interface for Task entities.
 */
interface TaskInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, EntityOwnerInterface {

  /**
   * Function that returns if a task is completed.
   *
   * @return bool
   *   Indicates if the task is completed.
   */
  public function completed(): bool;

  /**
   * Function that returns the target field name for use in forms.
   *
   * @return string
   *   The ID of the target field.
   */
  public function getTargetFieldName(): string;

  /**
   * Function that returns the component configuration for use in forms.
   *
   * @return array
   *   The form component configuration.
   */
  public function getComponentConfiguration(): array;

  /**
   * Function to build the task form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function buildTaskForm(array &$form, FormStateInterface $form_state);
}
