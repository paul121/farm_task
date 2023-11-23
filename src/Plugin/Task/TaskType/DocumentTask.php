<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "document",
 *    label = @Translation("Document"),
 *  )
 */
class DocumentTask extends TaskTypeBase {

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

    $fields['image_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'image',
      'label' => 'Image value',
      'description' => 'Image field',
      'multiple' => TRUE,
    ]);

    return $fields;
  }

}
