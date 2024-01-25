<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "file",
 *    label = @Translation("File"),
 *  )
 */
class FileTask extends TaskTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields['file_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'file',
      'label' => 'File value',
      'description' => 'File field',
      'multiple' => TRUE,
    ]);

    return $fields;
  }

}
