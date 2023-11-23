<?php

namespace Drupal\farm_task\Form;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Session\AccountInterface;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 *
 */
class TaskListTasks extends FormBase {

  public function title(PlanInterface $plan = NULL, int $delta = NULL) {
    $task_list = $plan->get('task_list')->get($delta)->entity;
    return "{$plan->label()}: {$task_list->label()}";
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_task_list_tasks';
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account, PlanInterface $plan = NULL, int $delta = NULL) {

    if (is_null($plan) || is_null($delta) || !$plan->hasField('task_list') || !$plan->get('task_list')->offsetExists($delta)) {
      throw new ResourceNotFoundException();
    }

    return $plan->access('view', $account, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, PlanInterface $plan = NULL, int $delta = NULL) {

    $form_state->set('plan', $plan);
    $form_state->set('delta', $delta);

    $task_list = $plan->get('task_list')->get($delta)->entity;

    $form['test'] = ['#markup' => $task_list->label()];

    $form['tasks'] = [];
    /** @var \Drupal\farm_task\Entity\TaskInterface $task */
    foreach ($task_list->get('task')->referencedEntities() as $id => $task) {
      $form['tasks'][$id] = ['#parents' => ['tasks', $id]];
      $task_subform = SubformState::createForSubform($form['tasks'][$id], $form, $form_state);
      $form_display = EntityFormDisplay::collectRenderDisplay($task, NULL, FALSE);
      $field_id = $task->getTargetFieldName();
      $component_config = $task->getComponentConfiguration();
      $form_display->setComponent($field_id, $component_config);
      $form_display->buildForm($task, $form['tasks'][$id], $task_subform);
      $task->buildTaskForm($form['tasks'][$id], $task_subform);

      #$form['tasks'][$id][$field_id]['#type'] = 'fieldset';
      $form['tasks'][$id][$field_id]['#title'] = $task->label();
      $form['tasks'][$id][$field_id]['#description'] = $task->get('description')->value;


      $form['tasks'][$id][$field_id]['widget']['#title'] = $task->label();
      $form['tasks'][$id][$field_id]['widget']['#field_title'] = $task->label();
      $form['tasks'][$id][$field_id]['widget']['#description'] = $task->get('description')->value;

      $field_name = $task->get($field_id)->getFieldDefinition()->getFieldStorageDefinition()->getMainPropertyName();

      foreach ($form['tasks'][$id][$field_id]['widget'] as $sub_name => &$sub_value) {

        if (is_array($sub_value) && isset($sub_value['#title'])) {
          $sub_value['#title'] = $task->label();
          $sub_value['#description'] = $task->get('description')->value;
        }

        if (is_numeric($sub_name) && isset($sub_value[$field_name]['#title'])) {
          $sub_value[$field_name]['#title'] = $task->label();
          $sub_value[$field_name]['#description'] = $task->get('description')->value;
        }

      }
    }

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => 'Submit',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $plan = $form_state->get('plan');
    $delta = $form_state->get('delta');

    $task_list = $plan->get('task_list')->get($delta)->entity;

    foreach ($task_list->get('task')->referencedEntities() as $id => $task) {
      if ($form_state->hasValue(['tasks', $id])) {
        $form_display = EntityFormDisplay::collectRenderDisplay($task, NULL, FALSE);
        $field_id = $task->getTargetFieldName();
        $component_config = $task->getComponentConfiguration();
        $form_display->setComponent($field_id, $component_config);
        $extracted = $form_display->extractFormValues($task, $form['tasks'][$id], $form_state);
        $task->set('completed', TRUE);
        $task->save();
      }
    }
  }

}
