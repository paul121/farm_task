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
 * Task list form.
 */
class TaskListForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_task_list_form';
  }

  /**
   * Determines whether a user has access to edit a task list.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account.
   * @param \Drupal\plan\Entity\PlanInterface|null $plan
   *   (optional) The plan object. Defaults to NULL.
   *
   * @return bool
   *   TRUE if the user has access to edit the task list, FALSE otherwise.
   */
  public function access(AccountInterface $account, PlanInterface $plan = NULL) {
    if (!$plan->hasField('task_list')) {
      throw new ResourceNotFoundException();
    }
    return $plan->get('task_list')->access('edit', $account, TRUE);
  }

  /**
   * Title callback.
   *
   * @param \Drupal\plan\Entity\PlanInterface|null $plan
   *   (optional) The plan object for which to generate the title. Defaults to NULL.
   * @param int|null $delta
   *   (optional) The delta value for this particular title. Defaults to NULL.
   *
   * @return string
   *   The generated title for the specified plan and delta.
   */
  public function title(PlanInterface $plan = NULL, int $delta = NULL) {
    return "{$plan->label()}: Task lists";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, PlanInterface $plan = NULL, int $delta = NULL) {

    $form_state->set('plan', $plan);
    $form['plan'] = ['#parents' => []];
    $subform = SubformState::createForSubform($form['plan'], $form, $form_state);
    $form_display = EntityFormDisplay::collectRenderDisplay($plan, NULL, FALSE);
    $form_display->setComponent('task_list', [
      'type' => 'inline_entity_form_complex',
      'settings' => [
        'form_mode' => 'default',
        'revision' => TRUE,
        'allow_new' => TRUE,
        'allow_existing' => FALSE,
        'allow_duplicate' => TRUE,
      ],
      'weight' => '-5',
    ]);
    $form_display->buildForm($plan, $form['plan'], $subform);

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var PlanInterface $plan */
    $plan = $form_state->get('plan');
    $form_display = EntityFormDisplay::collectRenderDisplay($plan, NULL, FALSE);
    $form_display->setComponent('task_list', [
      'type' => 'inline_entity_form_complex',
      'settings' => [
        'form_mode' => 'default',
        'revision' => TRUE,
        'allow_new' => TRUE,
        'allow_existing' => FALSE,
        'allow_duplicate' => TRUE,
      ],
      'weight' => '-5',
    ]);
    $form_display->extractFormValues($plan, $form['plan'], $form_state);
    $plan->save();

    $this->messenger()->addStatus($this->t('Updated task lists.'));
  }

}
