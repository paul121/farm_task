<?php

namespace Drupal\farm_task\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_task\Entity\Task;
use Drupal\plan\Entity\Plan;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TaskList extends ControllerBase {

  public function title(PlanInterface $plan = NULL, int $delta = NULL) {
    $label = "Task lists";
    if ($delta !== NULL) {
      $label = $plan->get('task_list')->get($delta)->entity->label();
    }
    return "{$plan->label()}: $label";
  }

  public function access(AccountInterface $account, PlanInterface $plan = NULL, int $delta = NULL) {

    if (is_null($plan) || !$plan->hasField('task_list')) {
      throw new ResourceNotFoundException();
    }

    if (!is_null($delta) && !$plan->get('task_list')->offsetExists($delta)) {
      throw new ResourceNotFoundException();
    }

    return $plan->access('view', $account, TRUE);
  }

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

  public function export(PlanInterface $plan = NULL, int $delta = NULL) {

    /** @var \Symfony\Component\Serializer\Serializer $serializer */
    $serializer = \Drupal::service('serializer');

//    $input = [
//      'name' => ['Imported list'],
//      'task' => [
//        [
//          'type' => 'boolean',
//          'name' => ['Test input'],
//          'description' => ['Test description'],
//          'boolean_value' => [TRUE],
//          'completed' => [FALSE],
//        ],
//        [
//          'type' => 'log',
//          'name' => ['Test log'],
//          'description' => ['Test log description'],
//          'log_value' => [],
//        ]
//      ]
//    ];
//    $tasks = array_map(function ($input_task) use ($serializer) {
//      return $serializer->deserialize(Json::encode($input_task), Task::class, 'json');
//    }, $input['task']);
//    $out = $serializer->deserialize(Json::encode($input),\Drupal\farm_task\Entity\TaskList::class, 'json');
//    $default = $out->get('task')->referencedEntities();
//    $out->set('task', $tasks);
//
//    $other_plan = Plan::load(1);
//    $other_plan->get('task_list')->removeItem(1);
//    $other_plan->get('task_list')->appendItem($out);
//    $other_plan->save();

    // Build data to export.
    $export = [];

    // First export the task list entity.
    $format = 'json';
    $task_list = $plan->get('task_list')->get($delta)->entity;
    $export['task_list'] = $serializer->normalize($task_list, $format, ['plugin_id' => 'entity']);

    // Then export the tasks.
    $tasks = $task_list->get('task')->referencedEntities();
    $export['tasks'] = $serializer->normalize($tasks, $format, ['plugin_id' => 'entity']);

    $response = new Response(Json::encode($export));
    $response->headers->set('Content-Type', "text/$format");
    return $response;
  }

}
