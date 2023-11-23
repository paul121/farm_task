<?php

namespace Drupal\farm_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TaskList extends ControllerBase {

  public function title(PlanInterface $plan = NULL, int $delta = NULL) {
    $task_list = $plan->get('task_list')->get($delta)->entity;
    return "{$plan->label()}: {$task_list->label()}";
  }

  public function access(AccountInterface $account, PlanInterface $plan = NULL, int $delta = NULL) {

    if (is_null($plan) || is_null($delta) || !$plan->hasField('task_list') || !$plan->get('task_list')->offsetExists($delta)) {
      throw new ResourceNotFoundException();
    }

    return $plan->access('view', $account, TRUE);
  }

  public function taskList(PlanInterface $plan = NULL, int $delta = NULL) {

    $task_list = $plan->get('task_list')->get($delta);
    $build['task_list'] = $this->entityTypeManager()->getViewBuilder('task_list')->viewFieldItem($task_list);

    $build['task_list']['#cache']['max-age'] = 0;
    $build['#cache']['max-age'] = 0;

    return $build;
  }

}
