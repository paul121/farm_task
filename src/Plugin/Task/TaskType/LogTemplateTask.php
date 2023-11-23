<?php

namespace Drupal\farm_task\Plugin\Task\TaskType;

use Drupal\entity\BundleFieldDefinition;

/**
 * @TaskType(
 *    id = "log_template",
 *    label = @Translation("Log Template"),
 *  )
 */
class LogTemplateTask extends LogTask {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $fields['template'] = BundleFieldDefinition::create('entity_reference')
      ->setLabel('Template')
      ->setDescription('Template reference')
      ->setRequired(TRUE)
      ->setSetting('target_type', 'farm_entity_template')
      ->setSetting('handler', 'default:farm_entity_template')
      ->setSetting('handler_settings', [
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => FALSE,
        'auto_create_bundle' => '',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => $options['weight']['form'] ?? 0,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'match_limit' => '10',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'entity_reference_label',
        'weight' => $options['weight']['view'] ?? 0,
        'settings' => [
          'link' => TRUE,
        ],
      ]);
    return $fields;
  }

}
