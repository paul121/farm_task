<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "boolean",
 *    label = @Translation("Boolean"),
 *  )
 */
class BooleanTask extends TaskTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields['boolean_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'boolean',
      'label' => 'Value',
      'description' => 'Boolean Field',
    ]);

    return $fields;
  }

}
