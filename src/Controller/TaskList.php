<?php

namespace Drupal\farm_task\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class TaskList
 *
 */
class TaskList extends ControllerBase {

  /**
   * Title callback.
   *
   * @param PlanInterface|null $plan
   *   (optional) The plan entity object. Defaults to NULL.
   * @param int|null $delta
   *   (optional) The index of the task list entity in the plan's task list
   *   field. Defaults to NULL.
   *
   * @return string
   *   The title for the plan.
   */
  public function title(PlanInterface $plan = NULL, int $delta = NULL) {
    $label = "Task lists";
    if ($delta !== NULL) {
      $label = $plan->get('task_list')->get($delta)->entity->label();
    }
    return "{$plan->label()}: $label";
  }

  /**
   * Access callback.
   *
   * @param AccountInterface $account
   *   The account object to check access for.
   * @param PlanInterface|null $plan
   *   (optional) The plan object to check access against. Defaults to NULL.
   * @param int|null $delta
   *   (optional) The delta value to check access against. Defaults to NULL.
   *
   * @return bool
   *   TRUE if the account has access to view the plan, otherwise FALSE.
   *
   * @throws ResourceNotFoundException
   *   If the delta is not NULL or delta does not exist in the plan's task list.
   * @throws ResourceNotFoundException
   *   If the plan is NULL or does not have the 'task_list' field.
   */
  public function access(AccountInterface $account, PlanInterface $plan = NULL, int $delta = NULL) {

    if (is_null($plan) || !$plan->hasField('task_list')) {
      throw new ResourceNotFoundException();
    }

    if (!is_null($delta) && !$plan->get('task_list')->offsetExists($delta)) {
      throw new ResourceNotFoundException();
    }

    return $plan->access('view', $account, TRUE);
  }

  /**
   * Returns the task list for a given plan.
   *
   * @param \Drupal\plan\Entity\PlanInterface|null $plan
   *   The plan for which to retrieve the task list.
   * @param int|null $delta
   *   (optional) The delta value for the task list. Defaults to NULL.
   *
   * @return array
   *   An array containing the rendered task list.
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function taskList(PlanInterface $plan = NULL, int $delta = NULL) {

    if (is_null($delta)) {
      $build['task_list'] = $plan->get('task_list')->view([
        'label' => 'hidden',
        'type' => 'entity_reference_revisions_task_list',
        'settings' => [
          'view_mode' => 'default',
          'link' => FALSE,
        ],
      ]);
    }
    else {
      $task_list = $plan->get('task_list')->get($delta);
      $build['task_list'] = $this->entityTypeManager()->getViewBuilder('task_list')->viewFieldItem($task_list);
    }

    $build['task_list']['#cache']['max-age'] = 0;
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
