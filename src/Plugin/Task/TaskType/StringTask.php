<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "string",
 *    label = @Translation("String"),
 *  )
 */
class StringTask extends TaskTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields['string_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'string',
      'label' => 'Value',
      'description' => 'String Field',
    ]);

    return $fields;
  }

}
