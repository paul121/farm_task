<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

/**
 * @TaskType(
 *    id = "asset",
 *    label = @Translation("Asset"),
 *  )
 */
class AssetTask extends TaskTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    $fields['asset_value'] = $this->farmFieldFactory->bundleFieldDefinition([
      'type' => 'entity_reference',
      'target_type' => 'asset',
      'label' => 'Value',
      'description' => 'Asset Field',
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
