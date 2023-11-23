<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "log",
 *    label = @Translation("Log"),
 *  )
 */
class LogTask extends TaskTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields['log_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'entity_reference',
      'target_type' => 'log',
      'label' => 'Value',
      'description' => 'Log Field',
      'multiple' => TRUE,
      'form_display_options' => [
        'type' => 'inline_entity_form_complex',
        'settings' => [
          'form_mode' => 'default',
          'revision' => TRUE,
          'allow_new' => TRUE,
          'allow_existing' => TRUE,
          'allow_duplicate' => FALSE,
        ],
      ],
    ]);

    return $fields;
  }

}
